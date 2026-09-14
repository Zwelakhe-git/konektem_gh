<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\LiveStreamModel;
use Konektem\Utils\Log;

$dotenv = \Dotenv\Dotenv::createImmutable(BASE_DIR, '.env');
$dotenv->load();

class LiveStreamController {
    private $model;
    private $log;
    
    public function __construct() {
        $this->model = new LiveStreamModel();
        $this->log = new Log();
    }
    
    public function index() {
        $streams = $this->model->getAllStreams();
        return $streams;
    }

    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return $this->model->getStreamById($params['id']);
            } elseif(isset($params['user_id']) && !empty($params['user_id'])){
                return $this->model->getStreamsByUser($params['user_id']);
            }
            return $this->model->getAllStreams();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function create() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                $input = $_POST;
            }
            $coverImage = null;
            if(isset($_FILES['cover_image']) && !empty($_FILES['cover_image']['name'])){
                $coverImage = (new \Konektem\Utils\Utils())->upload(UPLOAD_DIR . '/images', IMAGETYPES, $_FILES['cover_image']);

            }
            $stream_key = $input['stream_key'] ?: $this->model->generateStreamKey();
            $stream_url = $input['stream_url'] ?: $_ENV['ANT_MEDIA_BASE_URL'] . '?id=' . $stream_key;
            $data = [
                'stream_title' => $input['stream_title'] ?? '',
                'description' => $input['description'] ?? '',
                'price' => $input['price'] ?? 0,
                'start_time' => $input['start_time'] ?? date('Y-m-d H:i:s'),
                'end_time' => $input['end_time'] ?? null,
                'stream_url' => $stream_url ?? null,
                'max_viewers' => $input['max_viewers'] ?? null,
                'stream_key' => $stream_key
            ];
            if($coverImage){
                $data['cover_image'] = $coverImage['filepath'];
            }
            
            $result = $this->model->createStream($data);
            return $result;
        } catch(\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function edit() {
        try {    
            $body = $_POST ?: json_decode(file_get_contents('php://input'), true);
            if(empty($body) || !isset($body['id']) || empty($body['id'])){
                return [
                    'success' => false,
                    'message' => 'Missing parameters'
                ];
            }
            
            $id = $body['id'];
            
            $coverImage = null;
            if (isset($_FILES['cover_image']) && !empty($_FILES['cover_image']['tmp_name'])) {
                $coverImage = (new \Konektem\Utils\Utils())->upload(UPLOAD_DIR . '/images', IMAGETYPES, $_FILES['cover_image']);
            }
            $data = [
                'stream_title' => $body['stream_title'] ?? '',
                'description' => $body['description'] ?? '',
                'price' => $body['price'] ?? 0,
                'start_time' => $body['start_time'] ?? date('Y-m-d H:i:s'),
                'end_time' => $body['end_time'] ?? null,
                'stream_url' => $body['stream_url'] ?? null,
                'max_viewers' => $body['max_viewers'] ?? null
            ];
            if (isset($body['remove_cover_image']) && $body['remove_cover_image']) {
                $data['remove_cover_image'] = $body['remove_cover_image'];
            }
            if($coverImage){
                $data['cover_image'] = $coverImage['filepath'];
            }
            
            $result = $this->model->updateStream($id, $data);
            return $result;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function delete($params=[]) {
        try {
            $body = json_decode(file_get_contents('php://input'), true);
            if((empty($body) || !isset($body['id']) || empty($body['id'])) && (!isset($params['id']) || empty($params['id']))){
                return [
                    'success' => false,
                    'message' => 'Missing parameters'
                ];
            }
            $id = $body['id'] ?? $params['id'];
            $result = $this->model->deleteStream($id);
            return $result;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }

    public function checkAccess($params=[]) {
        try {
            if (!isset($params['user_id']) || !isset($params['stream_id'])) {
                return [
                    'success' => false,
                    'message' => 'Missing parameters'
                ];
            }
            
            $access = $this->model->getStreamAccess($params['user_id'], $params['stream_id']);
            
            if (!$access || $access['payment_status'] !== 'paid' || $access['order_status'] === 'cancelled') {
                return [
                    'success' => false,
                    'has_access' => false,
                    'message' => 'No access to this stream'
                ];
            }
            
            // Проверяем срок действия
            if ($access['expires_at'] && new \DateTime($access['expires_at']) < new \DateTime()) {
                return [
                    'success' => false,
                    'has_access' => false,
                    'message' => 'Access expired'
                ];
            }
            
            return [
                'success' => true,
                'has_access' => true,
                'access_token' => $access['access_token'],
                'expires_at' => $access['expires_at']
            ];
        } catch(\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
}
?>