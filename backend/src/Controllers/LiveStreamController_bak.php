<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\LiveStreamModel;
use Konektem\Utils\Log;


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
        $data = [
            'stream_date' => $_POST['stream_date'],
            'stream_title' => $_POST['stream_title'],
            'stream_key' => $this->model->generateStreamKey()
        ];
        $result = $this->model->createStream($data);
        return $result;
    }
    
    public function edit() {
        try {    
            $body = $_POST ?: json_decode(file_get_contents('php://input'), true);
            if(empty($body) || !isset($body['id']) || empty($body['id'])){
                return [
                    'successs' => false,
                    'message' => 'Missing parameters'
                ];
            }
            $id = $body['id'];
            $data = [
                'stream_date' => $_POST['stream_date'],
                'stream_title' => $_POST['stream_title'],
                'stream_key' => $_POST['stream_key']
            ];
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
                    'successs' => false,
                    'message' => 'Missing parameters'
                ];
            }
            $id = $body['id'] ?? $params['id'];
            $result = $this->model->deleteStream($id);
            if ($result) {

            }
            return $result;
        } catch(\Ecxeption $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
        
    }
}
?>