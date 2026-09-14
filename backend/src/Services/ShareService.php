<?php
namespace Konektem\Services;
require_once __DIR__ . '/../../config/config.php';

use Konektem\Utils\Log;
use Konektem\Utils\Utils;
Log::init();

class ShareService{
    private $model;
    private $itemName;
    private $shareData;
    private $itemId;
    private $userPayload;
    private $cb;
    private $dbTableName;
    private const REDIS_SHARES_KEY_PREFIX = "shares:";

    public function __construct(){
        $this->model = null;
        $this->itemName = '';
        $this->shareData = [];
        $this->itemId = null;
        $this->userPayload = [];
        $this->cb = null;
        $this->dbTableName = '';
    }

    public function share($req, $res){
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
                Log::error("{$this->itemName}:{$this->itemId}");
                return $res->status(400)->json([
                    'success' => false,
                    'message' => 'Missing params'
                ]);
            }

            switch($this->itemName){
                case "track":
                    $this->model = new \Konektem\Models\MusicModel();
                    $this->cb = fn($id, $incr) => $this->model->updateTrackStats($id, 'shares', $incr);
                    $this->dbTableName = 'music';
                    $track = $this->model->getTrackById($this->itemId);
                    if(!$track){
                        return $res->status(404)->json([
                            'success' => false,
                            'message' => 'Track not found'
                        ]);
                    }
                    $this->shareData = [
                        'url' => "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}{$track['url']}",
                        'title' => $track['title'],
                        'text' => "Track on konektem"
                    ];
                    break;
                case "album":
                    $this->model = new \Konektem\Models\AlbumModel();
                    $this->cb = fn($id, $incr) => $this->model->updateAlbumStats($id, 'shares', $incr);
                    $this->dbTableName = 'albums';
                    $album = $this->model->getAlbumById($this->itemId);
                    if(!$album){
                        return $res->status(404)->json([
                            'success' => false,
                            'message' => 'Album not found'
                        ]);
                    }
                    $this->shareData = [
                        'url' => "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/konektem/album/{$this->itemId}",
                        'title' => $album['name'],
                        'text' => "Album on konektem"
                    ];
                    break;
                case "article":
                    $this->model = new \Konektem\Models\NewsModel();
                    $this->cb = fn($id, $incr) => $this->model->updateArticleStats($id, 'shares', $incr);
                    $this->dbTableName = 'news';
                    $article = $this->model->getArticleById($this->itemId);
                    if(!$article){
                        Log::info($this->itemId);
                        return $res->status(404)->json([
                            'success' => false,
                            'message' => 'Article not found'
                        ]);
                    }
                    $this->shareData = [
                        'url' => "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/konektem/actuality/{$article['title_hash']}",
                        'title' => $article['title'],
                        'text' => "Article on konektem"
                    ];
                    break;
                case "interview":
                    $this->model = new \Konektem\Models\InterviewsModel();
                    $this->cb = fn($id, $incr) => $this->model->updateInterviewStats($id, 'shares', $incr);
                    $this->dbTableName = 'interviews';
                    $interview = $this->model->getInterviewById($this->itemId);
                    if(!$interview){
                        return $res->status(404)->json([
                            'success' => false,
                            'message' => 'Article not found'
                        ]);
                    }
                    $this->shareData = [
                        'url' => "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/konektem/interviews/id/{$this->itemId}",
                        'title' => $interview['title'],
                        'text' => "Interview on konektem"
                    ];
                    break;
                case "event":
                    $this->model = new \Konektem\Models\EventsModel();
                    $this->cb = fn($id, $incr) => $this->model->updateEventStats($id, 'shares', $incr);
                    $this->dbTableName = 'events';
                    $event = $this->model->getEventById($this->itemId);
                    if(!$event){
                        return $res->status(404)->json([
                            'success' => false,
                            'message' => 'Event not found'
                        ]);
                    }
                    $this->shareData = [
                        'url' => "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/konektem/events/id/{$this->itemId}",
                        'title' => $event['title'],
                        'text' => "Event on konektem"
                    ];
                    break;
                default:
                Log::warn("{$this->itemName} cannot be shared");
                    return $res->status(400)->json([
                        'success' => false,
                        'message' => "{$this->itemName} cannot be shared"
                    ]);
            }
            $result = $this->cacheShareStats();
            if(!$result['success'] && $this->cb){
                $result = $this->statsUpdateCB($this->cb);//return $this->directRecordDownloadStatsToDb($this->statsUpdateCB);
            } else {
                //Log::info("shares for {$this->itemName} {$this->itemId} cached successfully");
            }
            // Log::info("sharing {$this->itemName} {$this->itemId}: $fileName, $fileUrl, {$result['success']}");
            $this->shareData['url'] = (new Utils())->normalizeUrl($this->shareData['url']);
            return $result['success'] ?
            $res->status(200)->json([
                'success' => true,
                'data' => [
                    'shares' => $result['shares'],
                    ...$this->shareData
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

    private function cacheShareStats(){
        try {
            if($this->model && $this->model->redisClient){
                $this->model->redisClient->incr(self::REDIS_SHARES_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");
                $curValue = $this->model->redisClient->get(self::REDIS_SHARES_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");

                if($curValue % 1000 === 0){
                    $this->flushShareStatsToDb();
                    //$this->model->redisClient->del(self::REDIS_SHARES_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");
                }
                return [
                    'success' => true,
                    'shares' => $curValue
                ];
            }
            Log::warn("failed to cache share: no connection to redis server");
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

    private function flushShareStatsToDB(){
        try {
            if($this->model && $this->model->redisClient){
                $incr = $this->model->redisClient->get(self::REDIS_SHARES_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");
                $stmt = $this->model->pdo->prepare("UPDATE {$this->dbTableName} SET shares = ? WHERE id = ?");
                return $stmt->execute([$incr, $this->itemId]);
                //$this->statsUpdateCB($this->cb, $incr);
            } else {
                Log::warn("Failed to flush shares to DB. no connection to redis server");
            }
            return false;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
        return false;
    }

    private function directRecordShareStatsToDB(){
        try {
            return $cb($itemId, 'shares', $incr);
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