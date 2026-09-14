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

            $streamId = $_POST['id'] ?? $req->body->id ?? null;
            $userId = $this->userPayload['user_id'] ?? null;

            if(!$streamId || !$userId){
                return $res->status(400)->json([
                    'success' => false,
                    'message' => 'Missing stream id or user id'
                ]);
            }

            // Проверяем доступ к трансляции
            $access = $this->model->getStreamAccess($userId, $streamId);
            if (!$access || $access['payment_status'] !== 'paid') {
                return $res->status(403)->json([
                    'success' => false,
                    'message' => 'No access to this stream'
                ]);
            }

            $stream = $this->model->getStreamById($streamId);
            if(!$stream){
                return $res->status(404)->json([
                    'success' => false,
                    'message' => 'Stream not found'
                ]);
            }

            // Проверяем время трансляции
            $streamStart = new \DateTime($stream['start_time']);
            $streamEnd = $stream['end_time'] ? new \DateTime($stream['end_time']) : null;
            $now = new \DateTime();

            if ($now < $streamStart) {
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Stream not started yet'
                ]);
            }

            if ($streamEnd && $now > $streamEnd) {
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Stream has ended'
                ]);
            }

            // Здесь вы можете добавить логику для генерации временного ключа для медиа-сервера
            $streamKey = $stream['stream_key'] ?? $stream['stream_url'];
            
            // Записываем пользователя как активного зрителя
            $this->recordUserToViewers($userId, $streamId);

            return $res->status(200)->json([
                'success' => true,
                'data' => [
                    'stream_key' => $streamKey,
                    'access_token' => $access['access_token'],
                    'expires_at' => $access['expires_at']
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

    public function generateKey($req, $res){
        try {
            $auth = new \Konektem\Auth\Auth();
            $this->userPayload = $auth->getAuthTokenPayload();
            
            if(empty($this->userPayload) || $this->userPayload['role'] !== 'admin'){
                return $res->status(401)->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ]);
            }
            
            $streamKey = $this->model->generateStreamKey();
            
            return $res->status(200)->json([
                'success' => true,
                'stream_key' => $streamKey
            ]);
        } catch(\Exception $e) {
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
            //$username = $_POST['login'] ?? $req->body->login;
            $userId = $this->userPayload['id'];
            $accessToken = $_POST['accessToken'] ?? $req->body->accessKey;
            //$password = $_POST['password'] ?? $req->body->password;

            $stream = $this->model->getStreamById($streamId);
            if(!$stream){
                return $res->status(404)->json([
                    'success' => false,
                    'message' => 'Stream not found'
                ]);
            }
            $streamKey = $stream['stream_key'];
            $streamDate = new \DateTime($stream['start_time']);
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

            if($this->userInStreamViewers($userId, $streamKey)){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Already watching'
                ]);
            }
            $access = $this->model->getStreamAccess($userId, $streamId);
            if (!$access || $access['payment_status'] !== 'paid') {
                return $res->status(403)->json([
                    'success' => false,
                    'message' => 'No access to this stream'
                ]);
            }

            $dotenv = \Dotenv\Dotenv::createImmutable(BASE_DIR, '.env');
            $dotenv->load();

            if(!$stream['stream_url'] && (!isset($_ENV['ANT_MEDIA_BASE_URL']) || empty($_ENV['ANT_MEDIA_BASE_URL']))){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Missing stream url'
                ]);
            }
            $this->recordUserToViewers($userId, $streamKey);
            $queryParams = http_build_query([ 'id' => $streamKey ]);
            $token = (new \Konektem\Auth\Auth())->createJWTToken([
                //'username' => $username,
                'accessToken' => $accessToken,
                'streamId' => $streamId,
                'exp' => time() + 86000
            ]);
            return $res->status(200)->json([
                'success' => true,
                'data' => [
                    'stream_url' => $stream['stream_url'] ?: "{$_ENV['ANT_MEDIA_BASE_URL']}?$queryParams",
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
            //$username = $_POST['login'] ?? $req->body->login;
            $userId = $this->userPayload['id'];
            $streamId = $_POST['streamId'] ?? $req->body->streamId;
            $accessToken = $_POST['accessToken'] ?? $req->body->accessToken;
            //$password = $_POST['password'] ?? $req->body->password;
            
            $access = $this->model->getStreamAccess($userId, $streamId);
            if (!$access || $access['payment_status'] !== 'paid') {
                return $res->status(403)->json([
                    'success' => false,
                    'message' => 'No access to this stream'
                ]);
            }
            if($accessToken !== $access['access_token']){
                return $res->status(402)->json([
                    'success' => false,
                    'message' => 'Invalid access token'
                ]);
            }

            if((new \DateTime($access['expires_at'])) < (new \DateTime())){
                return $res->status(402)->json([
                    'success' => false,
                    'message' => 'Access key expired'
                ]);
            }

            return $res->status(200)->json([
                'success' => true,
                'data' => [
                    'access_token' => $accessToken
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
            $userId = $this->userPayload['id'];
            $accessToken = $_POST['accessToken'] ?? $req->body->accessToken;
            $token = $_POST['token'] ?? $req->body->token;

            $tokenPayload = $auth->decodeJWT($token);
            if(empty($tokenPayload)){
                return $res->status(402)->json([
                    'success' => false,
                    'message' => 'Invalid stream token'
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
                $filtered = array_map(fn($id) => $id !== $userId, $viewers);

                if(empty($filtered)){
                    $stmt = $this->model->pdo->prepare("DELETE FROM livestream_viewers WHERE stream_id = ?");
                    $stmt->execute([$tokenPayload['streamId']]);
                } else {
                    $stmt = $this->model->pdo->prepare("UPDATE livestream_viewers SET user_ids  = ? WHERE stream_id = ?");
                    $stmt->execute([implode(',', $filtered), $tokenPayload['streamId']]);
                }
            } else {
                $this->model->redisClient->srem("stream:$streamKey:viewers", $userId);
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
            //Log::info("recording user to lv: $uid");
            $sql = "SELECT lv.user_ids, lv.stream_id FROM livestream_viewers lv LEFT JOIN stream_details sd ON lv.stream_id = sd.product_id WHERE sd.stream_key = ?";
            $stmt = $this->model->pdo->prepare($sql);
            $stmt->execute([$streamKey]);
            $result = $stmt->fetch();

            if(empty($result)){
                $sql = "SELECT product_id FROM stream_details WHERE stream_key = ?";
                $stmt = $this->model->pdo->prepare($sql);
                $stmt->execute([$streamKey]);
                $streamId = $stmt->fetchColumn();
                $sql = "INSERT INTO livestream_viewers (user_ids, stream_id) VALUES (?,?)";
                $stmt = $this->model->pdo->prepare($sql);
                return $stmt->execute([$uid, $streamId]);
            }
            $userIds = explode(',', $result['user_ids']);
            $userIds[] = $uid;
            //Log::info("user ids" . print_r($userIds, true));
            $streamId = $result['stream_id'];
            $sql = "UPDATE livestream_viewers SET user_ids = ? WHERE stream_id = ?";
            $stmt = $this->model->pdo->prepare($sql);
            return $stmt->execute([implode(',', $userIds), $streamId]);

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
                $sql = "SELECT user_ids FROM livestream_viewers lv LEFT JOIN stream_details sd ON lv.stream_id = sd.product_id WHERE sd.stream_key = ?";
                $stmt = $this->model->pdo->prepare($sql);
                $stmt->execute([$streamKey]);
                $userIds = explode(',', $stmt->fetchColumn());
                //Log::info("user ids: " . print_r($userIds, true) . ", uid $uid");
                //Log::info("uid found: " . in_array($uid, $userIds));
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
            $redisKey = "stream_access:$uid";
            $result = [];
            if($this->model->redisClient){
                $viewer = $this->model->redisClient->get($redisKey);
                if($viewer){
                    $viewer = json_decode($viewer, true);
                    if(!$viewer['access_token'] == $accessKey){
                        $result['expires_at'] = $viewer['expires_at'];
                    }
                    $this->model->redisClient->expire($redisKey, 3600);
                }
            }
            if(empty($result)){
                $stmt = $this->model->pdo->prepare("SELECT expires_at 
                    FROM stream_access 
                    WHERE user_id = ? AND access_token = ?");
                $stmt->execute([$uid, $accessKey]);
                $result = $stmt->fetch() ?: [];
            }
            if(!empty($result)){
                $this->model->redisClient->set($redisKey, json_encode([
                    'expires_at' => $result['expires_at'],
                    'access_token' => $accessKey
                ], JSON_UNESCAPED_UNICODE));
                if((new \DateTime($result['expires_at'])) < (new \DateTime())){
                    return [
                        'success' => false,
                        'reason' => 'Key expired',
                        'message' => 'Token expired'
                    ];
                }
                return [
                    'success' => true,
                    'message' => 'Access granted'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No access to stream or invalid access token'
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