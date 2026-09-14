<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\VideoModel;
use Konektem\Models\ImageModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;

Log::init();

class VideoController {
    private $model;
    private $imageModel;
    
    public function __construct() {
        $this->model = new VideoModel();
        $this->imageModel = new ImageModel();
    }
    
    public function index() {
        $videos = $this->model->getAllVideos();
    }
    
    public function create() {
        try {
            // Загрузка обложки видео
            $coverImageId = null;
            if (!empty($_FILES['cover_image']['name'])) {
                $coverInfo = $this->utils->upload(IMAGES_PATH, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], $_FILES['cover_image']);
                $coverImageId = $this->imageModel->createImage($coverInfo['filepath'], $coverInfo['mime_type']);
            }
            
            // Загрузка видео файла
            $videoInfo = $this->utils->upload(VIDEOS_PATH, ['video/mp4', 'video/avi', 'video/mkv'],$_FILES['video_file']);
            
            $data = [
                'vidImg' => $coverImageId,
                'vidTitle' => $_POST['vidTitle'],
                'location' => $videoInfo['filepath'],
                'mime_type' => $videoInfo['mime_type']
            ];
            $result = $this->model->createVideo($data);
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
        $id = $body['id'] ?? $params['id'];
        $video = $this->model->getVideoById($id);
        
        try {
            $coverImageId = $video['vidImg'];
            
            if (!empty($_FILES['cover_image']['name'])) {
                $coverInfo = $this->utils->upload(IMAGES_PATH,['image/jpeg', 'image/png', 'image/gif'], $_FILES['cover_image']);
                $coverImageId = $this->imageModel->createImage($coverInfo['filepath'], $coverInfo['mime_type']);
            }
            
            $data = [
                'vidImg' => $coverImageId,
                'vidTitle' => $_POST['vidTitle'],
                'location' => $video['location'],
                'mime_type' => $video['mime_type']
            ];
            $result = $this->model->updateVideo($id, $data);
            return $result;
        } catch (\Exception $e) {
            $error = $e->getMessage();
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
            $video = $this->model->getVideoById($id);
            
            // Удаляем связанные изображения
            if ($video['vidImg']) {
                $this->imageModel->deleteImage($video['vidImg']);
            }
            
            // Удаляем видео файл
            if (file_exists($video['location'])) {
                unlink($video['location']);
            }
            $result = $this->model->deleteVideo($id);
            return $result;
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