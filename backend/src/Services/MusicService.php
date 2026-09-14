<?php
namespace Konektem\Services;
require_once __DIR__ . '/../../config/config.php';

use Konektem\Utils\Log;

Log::init();
class MusicService{
    private $model;
    private $tableName;
    private $userPayload;
    private $cb;
    private $trackId;
    private const REDIS_TRACK_PLAYS_KEY_PREFIX = "plays:track:";

    public function __construct(){
        $this->model = new \Konektem\Models\MusicModel();
        $this->tableName = 'music';
        $this->userPayload = [];
        $this->cb = null;
        $this->trackId = null;
    }

    public function playTrack($req, $res){
        try {
            $this->userPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();
            if(empty($this->userPayload)){
                return $res->status(401)->json([
                    'success' => false,
                    'message' => 'Failed to authenticate'
                ]);
            }
            $this->trackId = $_POST['id'] ?? $req->body->id;
            if(!$this->trackId){
                return $res->status(400)->json([
                    'success' => false,
                    'message' => 'Missing track id'
                ]);
            }
            $track = $this->model->getTrackById($this->trackId);
            if(!$track){
                return $res->status(404)->json([
                    'success' => false,
                    'message' => 'No track with id ' . $this->trackId
                ]);
            }
            $fileUrl = $track['url'] ?? $track['location'];
            if(preg_match('/^http[s]?/', $fileUrl)){
                return $res->status(200)->json([
                    'success' => true,
                    'data' => [
                        'file_url' => $fileUrl
                    ]
                ]);
            }
            $fileUrl = (new \Konektem\Utils\Utils())->normalizeUrl($fileUrl);
            if(!file_exists(HTDOCS . $fileUrl)){
                return $res->status(404)->json([
                    'success' => false,
                    'message' => 'File not found'
                ]);
            }
            $this->cb = fn($id, $incr=1) => $this->model->updateTrackStats($id, 'plays', $incr);
            return $res->status(200)->json([
                'success' => true,
                'data' => [
                    'file_url' => $fileUrl
                ]
            ]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} at {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    private function statsUpdateCB($cb, $incr=1){
        return $cb($this->trackId, $incr);
    }

    private function flushStatsToDb(){
        try {
            if(!$this->cb){
                Log::warn("uninitialized callback in music service");
                return [
                    'succes' => false
                ];
            }
            if(!this->mode->redisClient){
                Log::warn("redis connection not established");
                return [
                    'success' => false,
                    'message' => "redis connection not established"
                ];
            }
            $incr = $this->model->redisClient->get(self::REDIS_TRACK_PLAYS_KEY_PREFIX . $this->trackId);
            $this->statsUpdateCB($this->cb, $incr);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }

    private function cachePlayStats(){
        try {
            if($this->model && $this->model->redisClient){
                $this->model->redisClient->incr(self::REDIS_TRACK_PLAYS_KEY_PREFIX . $this->trackId);
                $curValue = $this->model->redisClient->get(self::REDIS_TRACK_PLAYS_KEY_PREFIX . $this->trackId);

                if($curValue >= 1000){
                    if($this->flushStatsToDb())
                    $this->model->redisClient->del(self::REDIS_TRACK_PLAYS_KEY_PREFIX . $this->trackId);
                }
                return [
                    'success' => true,
                    'plays' => $curValue
                ];
            }
        } catch(\Exception $e){
            return [
                'success' => false,
                'reason' => 'Server error'
            ];
        }
    }
}
?>