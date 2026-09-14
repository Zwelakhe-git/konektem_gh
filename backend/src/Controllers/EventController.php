<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\EventsModel;
use Konektem\Models\ImageModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;

Log::init();
class EventController {
    private $model;
    private $imageModel;
    private $utils;
    
    public function __construct() {
        $this->model = new EventsModel();
        $this->imageModel = new ImageModel();
        $this->utils = new Utils();
    }
    
    public function index() {
        $events = $this->model->getEventsByOwner($_SESSION['name']);
        //require_once ADMIN_DIR . '/views/events/list.php';
    }
    
    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return $this->model->getEventById($params['id']);
            } elseif(isset($params['user_name']) && !empty($params['user_name'])){
                return $this->model->getEventsByOwner($params['user_name']);
            }
            return $this->model->getAllEvents();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }

    public function create() {
        $uploadedFiles = [];
        try {
            $imageId = null;
            if (!empty($_FILES['event_image']['name'])) {
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['event_image']);
                $imageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $uploadedFiles[] = ['type' => 'image', 'url' => $imageInfo['filepath']];
            }
            
            $data = [
                'title' => $_POST['title'],
                'event_date' => $_POST['event_date'],
                'description' => $_POST['description'],
                'location' => $_POST['location'],
                'price' => $_POST['price'],
                'image_id' => $imageId,
                'public' => 0,
                'owner' => $_SESSION['user']['name']
            ];
            if(isset($_POST['public'])){
                $data['public'] = $_POST['public'];
            }
            if(isset($_POST['position'])){
                $data['position'] = $_POST['position'];
            }
            $result = $this->model->createEvent($data);
            return $result;
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            foreach($uploadedFiles as $file){
                if(isset($file['type']) && $file['type'] === 'image'){
                    $this->imageModel->deleteImage(null, $file['url']);
                }
                $this->utils->deleteFile($file['url']);
            }
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch (\Throwable $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            foreach($uploadedFiles as $file){
                if(isset($file['type']) && $file['type'] === 'image'){
                    $this->imageModel->deleteImage(null, $file['url']);
                }
                $this->utils->deleteFile($file['url']);
            }
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function edit() {
        $body = $_POST ?: json_decode(file_get_contents('php://input'), true);
        if(empty($body) || !isset($body['id']) || empty($body['id'])){
            return [
                'successs' => false,
                'message' => 'Missing parameters'
            ];
        }
        $id = $body['id'];
        $event = $this->model->getEventById($id);
        $uploadedFiles = [];
        try {
            $eventImageId = $this->imageModel->getImageByFilename($event['image_url'])['id'];
            $newImage = false;
            
            if (!empty($_FILES['event_image']['name'])) {
                $newImage = true;
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['event_image']);
                $eventImageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $uploadedFiles[] = ['type' => 'image', 'url' => $imageInfo['filepath']];
            }
            
            $data = [
                'title' => $_POST['title'],
                'event_date' => $_POST['event_date'],
                'description' => $_POST['description'],
                'location' => $_POST['location'],
                'price' => $_POST['price'],
                'image_id' => $eventImageId,
                'old_position' => $event['position'],
                'public' => $event['public']
            ];
            if(isset($_POST["position"])){
                //Log::info("event position update");
                $data['position'] = $_POST['position'];
            }
            if(isset($_POST['public'])){
                $data['public'] = $_POST['public'];
            }
            $result = $this->model->updateEvent($id, $data);
            if ($result['success']) {
                if($newImage){
                    $this->imageModel->deleteImage(null, $event['image_url']);
                    $this->utils->deleteFile($event['image_url']);
                }
            }
            return $result;
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            foreach($uploadedFiles as $file){
                if(isset($file['type']) && $file['type'] === 'image'){
                    $this->imageModel->deleteImage(null, $file['url']);
                }
                $this->utils->deleteFile($file['url']);
            }
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch (\Throwable $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            foreach($uploadedFiles as $file){
                if(isset($file['type']) && $file['type'] === 'image'){
                    $this->imageModel->deleteImage(null, $file['url']);
                }
                $this->utils->deleteFile($file['url']);
            }
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function delete($params=[]) {
        try {
            $body = $_POST ?: json_decode(file_get_contents('php://input'), true);
            //Log::info($_SERVER['REQUEST_METHOD']);
            if((empty($body) || !isset($body['id']) || empty($body['id'])) && (!isset($params['id']) || empty($params['id']))){
                return [
                    'successs' => false,
                    'message' => 'Missing parameters'
                ];
            }
            $id = $body['id'] ?? $params['id'];
            $event = $this->model->getEventById($id);
            
            if ($this->model->deleteEvent($id)) {
                if ($event['image_url']) {
                    $this->imageModel->deleteImage(null, $event['image_url']);
                    $this->utils->deleteFile($event['image_url']);
                }
                if($event['position'] === 'mainpage'){
                    (new \Konektem\Models\MainPageContentModel())->deleteItem('events', $id);
                }
                return [
                    'success' => true,
                    'message' => 'Event deleted successfully'
                ];
            }
            return [
                'success' => false,
                'message' => 'Failed to delete image'
            ];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
}
?>