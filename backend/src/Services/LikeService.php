<?php
namespace Konektem\Services;
require_once __DIR__ . '/../../config/config.php';

use Konektem\Utils\Log;

Log::init();

class LikeService{
    private $model;
    private $itemName;
    private $itemId;
    private $userPayload;
    private $cb;
    private $dbTableName;
    private const REDIS_SHARES_KEY_PREFIX = "shares:";

    public function __construct(){
        $this->model = null;
        $this->itemName = '';
        $this->itemId = null;
        $this->userPayload = [];
        $this->cb = null;
        $this->dbTableName = '';
    }

    public function like($req, $res){
        try {
            $this->userPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();
            if(empty($this->userPayload)){
                return $res->status(401)->json([
                    'success' => false,
                    'message' => 'Failed to authenticate'
                ]);
            }
            $this->itemName = $_POST['item'] ?? $req->body->item;
            $this->itemId = $_POST['id'] ?? $req->body->id;

            if(!$this->itemName || !$this->itemId){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Missing params'
                ]);
            }

            switch($this->itemName){
                case "track":
                    $this->model = new \Konektem\Models\MusicModel();
                    $this->cb = fn($id, $incr) => $this->model->updateTrackStats($id, 'likes', $incr);
                    break;
                case "album":
                    $this->model = new \Konektem\Models\AlbumModel();
                    $this->cb = fn($id, $incr) => $this->model->updateAlbumStats($id, 'likes', $incr);
                    break;
                case "article":
                    $this->model = new \Konektem\Models\NewsModel();
                    $this->cb = fn($id, $incr) => $this->model->updateArticleStats($id, 'likes', $incr);
                    break;
                case "interview":
                    $this->model = new \Konektem\Models\InterviewsModel();
                    $this->cb = fn($id, $incr) => $this->model->updateInterviewStats($id, 'likes', $incr);
                    break;
                case "event":
                    $this->model = new \Konektem\Models\EventsModel();
                    $this->cb = fn($id, $incr) => $this->model->updateEventStats($id, 'likes', $incr);
                    break;
                default:
                    return $res->status(200)->json([
                        'success' => false,
                        'message' => "{$this->itemName} cannot be liked"
                    ]);
            }
            $result = $this->cacheLikeStats();
            if(!$result['success'] && $this->cb){
                $result = $this->statsUpdateCB($this->cb);//return $this->directRecordDownloadStatsToDb($this->statsUpdateCB);
            }
            // Log::info("sharing {$this->itemName} {$this->itemId}: $fileName, $fileUrl, {$result['success']}");
            return $result['success'] ?
            $res->status(200)->json([
                'success' => true,
                'data' => [
                    'likes' => $result['likes']
                ]
            ]) :
            $res->status(200)->json($result);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    private function cacheLikeStats(){
        try {
            if($this->model && $this->model->redisClient){
                $this->model->redisClient->incr(self::REDIS_LIKES_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");
                $curValue = $this->model->redisClient->get(self::REDIS_LIKES_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");

                if($curValue % 1000 === 0){
                    $this->flushShareStatsToDb();
                    //$this->model->redisClient->del(self::REDIS_LIKES_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");
                }
                return [
                    'success' => true,
                    'likes' => $curValue
                ];
            }
            Log::warn("failed to cache likes: no connection to redis server");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }

    private function statsUpdateCB($cb, $incr=1){
        $result = $cb($this->itemId, $incr);
        return $result;
    }

    private function flushLikeStatsToDB(){
        try {
            if($this->model && $this->model->redisClient){
                $incr = $this->model->redisClient->get(self::REDIS_SHARES_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");
                $stmt = $this->model->pdo->prepare("UPDATE {$this->dbTableName} SET downloads = ? WHERE id = ?");
                return $stmt->execute([$incr, $this->itemId]);
            } else {
                Log::warn("Failed to flush downloads to DB. no connection to redis server");
            }
            return false;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
        return false;
    }

    private function directRecordLikeStatsToDB(){
        try {
            return $statsUpdateCB($this->cb);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
}
?>