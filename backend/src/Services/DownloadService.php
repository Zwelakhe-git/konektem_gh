<?php
namespace Konektem\Services;
require_once __DIR__ . '/../../config/config.php';
use Konektem\Utils\Log;

Log::init();
class DownloadService{
    private $model;
    private $itemName;
    private $itemId;
    private $userPayload;
    private $cb;
    private $dbTableName;
    private const REDIS_DOWNLOADS_KEY_PREFIX = "downloads:";
    private const REDIS_USER_DOWNLOADS_KEY_PREFIX = "user:";

    public function __construct(){
        $this->model = null;
        $this->userPayload = [];
        $this->itemName = '';
        $this->itemId = null;
        $this->cb = null;
        $this->dbTableName = '';
    }
    public function download($req, $res){
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
            $fileName = '';
            $fileUrl = '';

            switch($this->itemName){
                case "track":
                    $this->model = new \Konektem\Models\MusicModel();
                    $track = $this->model->getTrackById($this->itemId);
                    if(!$track){
                        //Log::error("download track not found");
                        return $res->status(200)->json([
                            'success' => false,
                            'message' => 'Track not found'
                        ]);
                    }
                    $this->dbTableName = 'music';
                    $fileName = $track['title'];
                    $fileUrl = $track['url'];
                    $this->cb = fn($id, $incr) => $this->model->updateTrackStats($id, 'downloads', $incr);
                    break;
                case "album":
                    $this->model = new \Konektem\Models\AlbumModel();
                    $downloader = new \Konektem\Utils\ZipDownloader();
                    $album = $this->model->getAlbumById($this->itemId);
                    if(!$album){
                        Log::info($this->itemId);
                        return $res->status(404)->json([
                            'success' => false,
                            'message' => 'Album not found'
                        ]);
                    }
                    Log::info("creating zip");
                    $result = $downloader->downloadAlbumZip($this->itemId, $album['tracks'], $album['name']);
                    if(!$result['success']){
                        Log::warn("failed to created album zip file");
                        return $res->status(200)->json([
                            'success' => false,
                            'message' => 'Failed to create file'
                        ]);
                    } else {
                        Log::info("album zip file successfully created");
                    }
                    $this->dbTableName = 'albums';
                    $fileName = $result['file_name'];
                    $fileUrl = $result['file_url'];
                    $this->cb = fn($id, $incr) => $this->model->updateAlbumStats($id, 'downloads', $incr);
                    break;
                case "book":
                    $this->model = new \Konektem\Models\BooksModel();
                    $book = $this->model->getBookById($this->itemId);
                    if(!$book){
                        return $res->status(204)->json([
                            'success' => false,
                            'message' => 'Book not found'
                        ]);
                    }
                    $this->dbTableName = 'books';
                    $fileName = $book['title'];
                    $fileUrl = $book['pdfUrl'];
                    $this->cb = fn($id, $incr) => $this->model->updateBookStats($id, 'downloads', $incr);
                    break;
                default:
                    return $res->status(400)->json([
                        'success' => false,
                        'message' => 'Item "' . $this->itemName . '" not available for download' 
                    ]);
                    break;
            }
            Log::info("looking for file ");
            if(!$fileUrl || !file_exists(HTDOCS . $fileUrl)){
                return $res->status(404)->json([
                    'success' => false,
                    'message' => 'File not found'
                ]);
            }
            $fileUrl = (new \Konektem\Utils\Utils())->normalizeUrl($fileUrl);
            $filePath = realpath(HTDOCS . $fileUrl);
            $result = $this->cacheDownloadStats();
            if(!$result['success'] && $this->cb){
                $result = $this->statsUpdateCB($this->cb);//return $this->directRecordDownloadStatsToDb($this->statsUpdateCB);
            } else {
                //Log::info("downloads for {$this->itemName} {$this->itemId} cached successfully");
            }
            // Log::info("downloading {$this->itemName} {$this->itemId}: $fileName, $fileUrl, {$result['success']}");
            //$res->download($filePath, $fileName);
            return $result['success'] ?
            $res->status(200)->json([
                'success' => true,
                'data' => [
                    'file_name' => $fileName,
                    'file_url' => $fileUrl,
                    'downloads' => $result['downloads']
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

    private function statsUpdateCB($cb, $incr=1){
        $result = $cb($this->itemId, $incr);
        return $result;
    }

    private function directRecordDownloadStatsToDb($cb, $incr=1){
        try {
            return $cb($this->itemId, $incr);
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

    private function cacheDownloadStats(){
        try {
            if($this->model && $this->model->redisClient){
                $this->model->redisClient->incr(self::REDIS_DOWNLOADS_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");
                $curValue = $this->model->redisClient->get(self::REDIS_DOWNLOADS_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");

                if($curValue % 1000 === 0){
                    $this->flushDownloadStatsToDb();
                }
                return [
                    'success' => true,
                    'downloads' => $curValue
                ];
            }
            Log::warn("failed to cache download: no connection to redis server");
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

    private function flushDownloadStatsToDb(): bool{
        try {
            if($this->model && $this->model->redisClient){
                $incr = $this->model->redisClient->get(self::REDIS_DOWNLOADS_KEY_PREFIX . "{$this->itemName}:{$this->itemId}");
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
}
?>