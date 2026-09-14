<?php
namespace Konektem\Api;

require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Utils\Log;

Log::init();

class LivestreamApi{
    private $userPayload;
    private $model;

    public function __construct(){
        $this->userPayload = [];
        $this->model = new \Konektem\Models\LiveStreamModel();
    }
    public function getStreamKey($req, $res){
        try {
            $this->userPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();

            if(empty($this->userPayload)){
                return $res->status(401)->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ]);
            }
            $streamId = $_POST['id'] ?? $req->body->id;
            $username = $_POST['login'] ?? $req->body->login;

            if(!$streamId){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Missing stream id'
                ]);
            }
            if(empty((new \Konektem\Models\UserModel())->getLiveStreamAccountInfo($username))){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Not subscribed to livestreams'
                ]);
            }
            $stream = $this->model->getStreamById($streamId);
            if(!$stream){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Stream not found'
                ]);
            }
            $streamDate = new \Datetime($stream['stream_date']);
            $today = new \Datetime();

            if($streamDate < $today){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Stream already passed'
                ]);
            } elseif($streamDate > $today){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Stream not started'
                ]);
            }

            return $res->staus(200)->json([
                'success' => false,
                'data' => [
                    'stream_key' => $stream['stream_key']
                ]
            ]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    public function getStreamUrl($req, $res){
        try {
            $this->userPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();

            if(empty($this->userPayload)){
                return $res->status(401)->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ]);
            }

            $streamId = $_POST['streamId'] ?? $req->body->streamId;
            $username = $_POST['login'] ?? $req->body->login;
            $accessKey = $_POST['accessKey'] ?? $req->body->accessKey;
            //$password = $_POST['password'] ?? $req->body->password;

            $userInfo = (new \Konektem\Models\UserModel())->getLiveStreamAccountInfo($username);
            if(empty($userInfo)){
                return $res->status(402)->json([
                    'success' => false,
                    'message' => 'Not subscribed to livestreams'
                ]);
            }
            $stream = $this->model->getStreamById($streamId);
            if(!$stream){
                return $res->status(404)->json([
                    'success' => false,
                    'message' => 'Stream not found'
                ]);
            }
            $streamKey = $stream['stream_key'];
            $streamDate = new \DateTime($stream['stream_date']);
            $today = new \DateTime();
            $diff = $streamDate->diff($today);


            if($streamDate->format('Y-m-d') !== $today->format('Y-m-d')){
                if($streamDate < $today){
                    return $res->status(200)->json([
                        'success' => false,
                        'message' => 'Stream already passed'
                    ]);
                } elseif($streamDate > $today){
                    return $res->status(200)->json([
                        'success' => false,
                        'message' => 'Stream not started'
                    ]);
                }
            }

            if($this->userInStreamViewers($userInfo['id'], $streamKey)){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Already watching'
                ]);
            }
            $grantAccess = $this->grantAccessToStream($userInfo['id'], $accessKey);
            if(!$grantAccess['success']){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => $grantAccess['reason']
                ]);
            }

            $dotenv = \Dotenv\Dotenv::createImmutable(BASE_DIR, '.env');
            $dotenv->load();

            if(!isset($_ENV['ANT_MEDIA_BASE_URL']) || empty($_ENV['ANT_MEDIA_BASE_URL'])){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Missing stream url'
                ]);
            }
            $this->recordUserToViewers($userInfo['id'], $streamKey);
            $queryParams = http_build_query([ 'id' => $streamKey ]);
            $token = (new \Konektem\Auth\Auth())->createJWTToken([
                'username' => $username,
                'accessKey' => $accessKey,
                'streamId' => $streamId,
                'exp' => time() + 86000
            ]);
            return $res->status(200)->json([
                'success' => true,
                'data' => [
                    'stream_url' => $_ENV['ANT_MEDIA_BASE_URL'] . "?$queryParams",
                    'token' => $token
                ]
            ]);
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

    public function login($req, $res){
        try {
            $this->userPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();
            if(empty($this->userPayload)){
                return $res->status(401)->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ]);
            }
            $username = $_POST['login'] ?? $req->body->login;
            $accessKey = $_POST['accessKey'] ?? $req->body->accessKey;
            $password = $_POST['password'] ?? $req->body->password;
            
            $userInfo = (new \Konektem\Models\UserModel())->getLiveStreamAccountInfo($username);
            if(empty($userInfo)){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Not subscribed to livestreams'
                ]);
            }
            if($accessKey !== $userInfo['access_key']){
                return $res->status(402)->json([
                    'success' => false,
                    'message' => 'Invalid access key'
                ]);
            }

            if(!password_verify($password, $userInfo['password_hash'])){
                return $res->status(402)->json([
                    'success' => false,
                    'message' => 'Incorrect password'
                ]);
            }

            if((new \DateTime($userInfo['key_exp'])) < (new \DateTime())){
                return $res->status(402)->json([
                    'success' => false,
                    'message' => 'Access key expired'
                ]);
            }

            return $res->status(200)->json([
                'success' => true,
                'data' => [
                    'name' => $username,
                    'access_key' => $accessKey
                ]
            ]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    public function exitStream($req, $res){
        try {
            $auth = new \Konektem\Auth\Auth();
            $this->userPayload = ($auth)->getAuthTokenPayload();
            if(empty($this->userPayload)){
                return $res->status(401)->json([
                    'success' => false,
                    'message' => 'Authentication is required'
                ]);
            }
            $username = $_POST['username'] ?? $req->body->username;
            $accessKey = $_POST['accessKey'] ?? $req->body->accessKey;
            $token = $_POST['token'] ?? $req->body->token;

            $tokenPayload = $auth->decodeJWT($token);
            if(empty($tokenPayload)){
                return $res->status(402)->json([
                    'success' => false,
                    'message' => 'Invalid stream token'
                ]);
            }

            $userInfo = (new \Konektem\Models\UserModel())->getLiveStreamAccountInfo($tokenPayload['username']);
            if(empty($userInfo)){
                return $res->status(402)->json([
                    'success' => false,
                    'message' => 'Not subscribed to livestreams'
                ]);
            }
            $stream = $this->model->getStreamById($tokenPayload['streamId']);
            if(!$stream){
                return $res->status(404)->json([
                    'success' => false,
                    'message' => 'Stream not found'
                ]);
            }
            $streamKey = $stream['stream_key'];

            if(!$this->model->redisClient){
                $stmt = $this->model->pdo->prepare("SELECT user_ids FROM livestream_viewers WHERE stream_id = ?");
                $stmt->execute([$tokenPayload['streamId']]);
                $viewers = explode(',', $stmt->fetchColumn());
                $filtered = array_map(fn($id) => $id !== $userInfo['id'], $viewers);

                if(empty($filtered)){
                    $stmt = $this->model->pdo->prepare("DELETE FROM livestream_viewers WHERE stream_id = ?");
                    $stmt->execute([$tokenPayload['streamId']]);
                } else {
                    $stmt = $this->model->pdo->prepare("UPDATE livestream_viewers SET user_ids  = ? WHERE stream_id = ?");
                    $stmt->execute([implode(',', $viewers), $tokenPayload['streamId']]);
                }
            } else {
                $this->model->redisClient->srem("stream:$streamKey:viewers", $userInfo['id']);
            }

            return $res->status(200)->json([
                'success' => true
            ]);
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

    private function directRecordUserToViewersInBD($uid, $streamKey){
        try {
            
            $sql = "SELECT lv.user_ids, lv.stream_id FROM livestream_viewers lv LEFT JOIN livestream ls ON lv.stream_id = ls.id WHERE ls.stream_key = ?";
            $stmt = $this->model->pdo->prepare($sql);
            $stmt->execute([$streamKey]);
            $result = $stmt->fetch();

            if(empty($result)){
                $sql = "SELECT id FROM livestream WHERE stream_key = ?";
                $stmt = $this->model->pdo->prepare($sql);
                $stmt->execute([$streamKey]);
                $streamId = $stmt->fetchColumn();
                $sql = "INSERT INTO livestream_viewers (user_ids, stream_id) VALUES (?,?)";
                $stmt = $this->model->pdo->prepare($sql);
                return $stmt->execute([$uid, $streamId]);
            }
            $userIds = $result['user_ids'];
            $streamId = $result['stream_id'];
            $sql = "UPDATE livestream_viewers SET user_ids = CONCAT(user_ids, ',?') WHERE stream_id = ?";
            $stmt = $this->model->pdo->prepare($sql);
            return $stmt->execute([$uid, $streamId]);

        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
        return false;
    }

    private function recordUserToViewers($uid, $streamKey){
        try {
            if(!$this->model->redisClient){
                return $this->directRecordUserToViewersInBD($uid, $streamKey);
            }
            $this->model->redisClient->sadd("stream:$streamKey:viewers", $uid);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }

    private function userInStreamViewers($uid, $streamKey): bool{
        try {
            if(!$this->model->redisClient){
                $sql = "SELECT user_ids FROM livestream_viewers lv LEFT JOIN livestream ls ON lv.stream_id = ls.id WHERE ls.stream_key = ?";
                // $sql = "SELECT 1 FROM livestream_viewers lv LEFT JOIN livestream ls ON lv.stream_id = ls.id WHERE lv.user_id = ? AND ls.stream_key = ?";
                $stmt = $this->model->pdo->prepare($sql);
                $stmt->execute([$streamKey]);
                $userIds = explode(',', $stmt->fetchColumn());
                return !empty($userIds) && in_array($uid, $userIds);
            }
            //$this->model->redisClient->srem("stream:$streamKey:viewers", $uid);
            return (bool)$this->model->redisClient->sismember("stream:$streamKey:viewers", $uid);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
        return false;
    }

    private function grantAccessToStream($uid, $accessKey): array{
        try {
            if(!$this->model->redisClient){}
            $stmt = $this->model->pdo->prepare("SELECT v.password_hash, sak.date_end FROM viewers v LEFT JOIN streamAccesskeys sak ON v.access_key_id = sak.id WHERE v.id = ? AND sak.access_key = ?");
            $stmt->execute([$uid, $accessKey]);
            $result = $stmt->fetch();
            if(!empty($result)){
                if((new \DateTime($result['date_end'])) < (new \DateTime())){
                    return [
                        'success' => false,
                        'reason' => 'Key expired'
                    ];
                }
                return [
                    'success' => true
                ];
            }
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
        return [
            'success' => false,
            'reason' => 'Server error'
        ];
    }
}
?>