<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\ImageModel;
use Konektem\Models\ServicesModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;

Log::init();
class ServiceController {
    private $model;
    private $imageModel;
    private $utils;
    
    public function __construct() {
        $this->model = new ServicesModel();
        $this->imageModel = new ImageModel();
        $this->utils = new Utils();
    }
    
    public function index() {
        $services = $this->model->getAllServices($owner = $_SESSION['name']);
    }

    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return $this->model->getServiceById($params['id']);
            } elseif(isset($params['user_name']) && !empty($params['user_name'])){
                return $this->model->getServicesByOwner($params['user_name']);
            }
            return $this->model->getAllServices();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function create() {
        $uploadedFiles = [];
        try {
            $serviceImageId = null;
            if (!empty($_FILES['service_image']['name'])) {
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['service_image']);
                $serviceImageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $uploadedFiles = [
                    'url' => $imageInfo['filePath'],
                    'type' => 'image',
                ];
            }
            
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'serviceImg' => $serviceImageId,
                'owner' => $_SESSION['user']['name'],
                'price' => $_POST['price'] ?? '0'
            ];
            $result = $this->model->createService($data);
            return $result;
        } catch (\Exception $e) {
            if(!empty($uploadedFiles)){
                foreach($uploadedFiles as $file){
                    if(isset($file['type']) && $file['type'] === 'image'){
                        $this->imageModel->deleteImage(null, $file['url']);
                    }
                    $this->utils->deleteFile($file['url']);
                }
            }
            Log::error($e->getMessage());
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
                    'successs' => false,
                    'message' => 'Missing parameters'
                ];
            }
            $id = $body['id'];
            $service = $this->model->getServiceById($id);
            if(!$service){
                return [
                    'successs' => false,
                    'message' => 'Service not found'
                ];
            }
            $serviceImageId = $service['serviceImg'];
            $newImage = false;
            
            if (!empty($_FILES['service_image']['name'])) {
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['service_image']);
                $serviceImageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $newImage = true;
            }
            
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'serviceImg' => $serviceImageId,
                'owner' => $_SESSION['user']['name']
            ];
            $result = this->model->updateService($id, $data);
            if ($result['success']) {
                if($newImage){
                    $this->utils->deleteFile($service['image_url']);
                }
            }
            return $result;
        } catch (\Exception $e) {
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
            $service = $this->model->getServiceById($id);
            if(!$service){
                return [
                    'successs' => false,
                    'message' => 'Service not found'
                ];
            }
            
            if ($this->model->deleteService($id, $_SESSION['user']['name'])) {
                if ($service['serviceImg']) {
                    $this->imageModel->deleteImage($service['serviceImg']);
                    $this->utils->deleteFile($service['image_url']);
                }
                return [
                    'success' => true
                ];
                //header('Location: ?action=services&success=1');
            }
        } catch(\Exception $e){
            Log::error($e->getMesssage());
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