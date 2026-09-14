<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\PartnersModel;
use Konektem\Models\ImageModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;

Log::init();
class PartnersController {
    private $model;
    private $imageModel;
    private $utils;
    
    public function __construct() {
        $this->model = new PartnersModel();
        $this->imageModel = new ImageModel();
        $this->utils = new Utils();
    }
    
    public function index() {
        $partners = $this->model->getAllPartners();
    }

    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return $this->model->getPartnerById();
            }
            return $this->model->getAllPartners();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function create() {
        try {
            $imageId = null;
            
            // Загрузка логотипа
            if (!empty($_FILES['partner_image']['name'])) {
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['partner_image']);
                $imageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
            }
            
            $data = [
                'partner_name' => $_POST['partner_name'],
                'image_id' => $imageId
            ];
            $result = $this->model->createPartner($data);
            
            return $result;
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
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
        $partner = $this->model->getPartnerById($id);
        
        if (!$partner) {
            return [
                'success' => false,
                'message' => 'Item not found'
            ];
        }
        
        try {
            $imageId = $partner['image_id'];
            
            // Удаление текущего изображения
            if (isset($_POST['remove_image']) && $_POST['remove_image']) {
                if ($imageId) {
                    $this->imageModel->deleteImage($imageId);
                }
                $imageId = null;
            }
            
            // Загрузка нового изображения
            if (!empty($_FILES['partner_image']['name'])) {
                // Удаляем старое изображение если есть
                if ($imageId) {
                    $this->imageModel->deleteImage($imageId);
                    $this->utils->deleteFile($partner['image_location']);
                }
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['partner_image']);
                $imageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
            }
            
            $data = [
                'name' => $_POST['name'],
                'image_id' => $imageId
            ];
            $result = $this->model->editPartner($id, $data);
            return $result;
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function delete($params = []) {
        $body = json_decode(file_get_contents('php://input'), true);
        if((empty($body) || !isset($body['id']) || empty($body['id'])) && (!isset($params['id']) || empty($params['id']))){
            return [
                'successs' => false,
                'message' => 'Missing parameters'
            ];
        }
        $id = $body['id'] ?? $params['id'];
        $partner = $this->model->getPartnerById($id);
        $image_id = $partner['image_id'];
        $result = $this->model->deletePartner($id);
        if ($result) {
            $this->imageModel->deleteImage($image_id);
            return [
            'success' => true,
            'message' => 'Partner deleted successfully'
        ];
        }
        return [
            'success' => false,
            'message' => 'Failed to delete item'
        ];
    }
}
?>