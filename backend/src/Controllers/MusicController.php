<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\MusicModel;
use Konektem\Models\ImageModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;
Log::init();

class MusicController {
    private $model;
    private $imageModel;
    private $utils;
    private $log;
    
    public function __construct() {
        $this->model = new MusicModel();
        $this->imageModel = new ImageModel();
        $this->utils = new Utils();
    }
    
    
    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return $this->model->getTrackById($params['id']);
            } elseif(isset($params['user_name']) && !empty($params['user_name'])){
                return $this->model->getMusicByOwner($params['user_name']);
            }
            return $this->model->getAllMusic();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }

    private function deleteUploadedFiles(array $files){
        if(empty($files)) return;
        try {
            foreach($files as $file){
                if(isset($file['type']) && $file['type'] === 'image'){
                    $this->imageModel->deleteImage(null, $file['url']);
                }
                $this->utils->deleteFile($file['url']);
            }
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }

    public function create() {
        $artists = $this->model->getAllArtists();
        $uploadedFiles = [];
        try {
            $trackImageId = null;
            Log::info(print_r($_POST, true));
            
            // Загрузка обложки трека
            if (!empty($_FILES['track_image']['name'])) {
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['track_image']);
                $trackImageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $uploadedFiles[] = [
                    'url' => $imageInfo['filepath'],
                    'type' => 'image'
                ];
            }
            
            if(empty($_FILES['audio_file']['name'])){
                $this->deleteUploadedFiles($uploadedFiles);
                return [
                    'success' => false,
                    'message' => 'Audio file not uploaded'
                ];
            }
            // Загрузка аудио файла
            $audioInfo = $this->utils->upload(UPLOAD_DIR . '/music', AUDIOTYPES, $_FILES['audio_file']);
            $uploadedFiles[] = [
                'url' => $audioInfo['filepath'],
                'type' => 'file'
            ];
            
            // Если выбран новый артист, создаем его
            $artistId = $_POST['artist_name'];
            if ($_POST['artist_name'] == 'new' && !empty($_POST['new_artist_name'])) {
                $artistId = $this->model->createArtist($_POST['new_artist_name']);
            }
            
            $data = [
                'title' => $_POST['title'],
                'artist_id' => $artistId,
                'image_id' => $trackImageId,
                'url' => $audioInfo['filepath'],
                'mime_type' => $audioInfo['mime_type'],
                'order_no' => $_POST['order_no'] ?? null,
                'genre' => $_POST['genre'],
                'owner' => $_SESSION['user']['name'],
                'public' => 0
            ];
            if(isset($_POST['public'])){
                $data['public'] = $_POST['public'];
            }
            if(isset($_POST["position"])){
                $data['position'] = $_POST['position'];
            }
            $result = $this->model->createMusic($data);
            return $result;
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            $this->deleteUploadedFiles($uploadedFiles);
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
        //Log::info("updating track: " . print_r($body, true));
        $id = $body['id'];
        $track = $this->model->getTrackById($id);
        $artists = $this->model->getAllArtists();
        $newCover = false;
        $newTrack = false;
        $uploadedFiles = [];
        try {
            //Log::info("music controller - music " . $id . " edit initiated");
            $trackImageId = $this->imageModel->getImageByFilename($track['image_url'])['id'];
            $trackInfo = [
                'filepath' => $track['url'],
                'mime_type' => $track['mime_type']
            ];
            
            // Загрузка новой обложки
            if (!empty($_FILES['track_image']['name'])) {
                $newCover = true;
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['track_image']);
                $trackImageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $uploadedFiles[] = [
                    'url' => $imageInfo['filepath'],
                    'type' => 'image'
                ];
            }

            if(!empty($_FILES['audio_file']['name'])){
                $trackInfo = $this->utils->upload(UPLOAD_DIR . '/music', AUDIOTYPES, $_FILES['audio_file']);
                $newTrack = true;
                $uploadedFiles[] = [
                    'url' => $trackInfo['filepath'],
                    'type' => 'file'
                ];
            }
            
            // Если выбран новый артист, создаем его
            $artistId = $_POST['artist_id'];
            if ($_POST['artist_id'] == 'new' && !empty($_POST['new_artist_name'])) {
                $artistId = $this->model->createArtist($_POST['new_artist_name']);
            }
            
            $data = [
                'title' => $_POST['title'],
                'artist_id' => $artistId,
                'image_id' => $trackImageId,
                'url' => $trackInfo['filepath'],
                'mime_type' => $trackInfo['mime_type'],
                'order_no' => $_POST['order_no'],
                'genre' => $_POST['genre'],
                'owner' => $_SESSION['user']['name'],
                'old_position' => $track['position'],
                'public' => $track['public']
            ];
            if(isset($_POST["position"])){
                $data['position'] = $_POST['position'];
            }
            if(isset($_POST['public'])){
                $data['public'] = $_POST['public'];
            }
            $result = $this->model->updateMusic($id, $data);
            if ($result['success']) {
                if($newCover){
                    $this->imageModel->deleteImage(null, $track['image_url']);
                    $this->utils->deleteFile($track['image_url']);
                }
                if($newTrack){
                    $this->utils->deleteFile($track['url']);
                }
            } else {
                $this->deleteUploadedFiles($uploadedFiles);
            }
            return $result;
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            $this->deleteUploadedFiles($uploadedFiles);
            return [
                'success' => true,
                'message' => 'Server error'
            ];
        }
    }
    
    public function delete($params=[]) {
        $body = json_decode(file_get_contents('php://input'), true);
        if((empty($body) || !isset($body['id']) || empty($body['id'])) && (!isset($params['id']) || empty($params['id']))){
            return [
                'successs' => false,
                'message' => 'Missing parameters'
            ];
        }
        $id = $body['id'] ?? $params['id'];
        try {
            $track = $this->model->getTrackById($id);
            
            if ($this->model->deleteMusic($id)) {
                if ($track['image_url']) {
                    $imageId = $this->imageModel->getImageByFilename($track['image_url']);
                    $this->imageModel->deleteImage(null, $track['image_url']);
                    $this->utils->deleteFile($track['image_url']);
                }
                $this->utils->deleteFile($track['url']);
                (new \Konektem\Models\MainPageContentModel())->deleteItem('music', $track['id']);
                return [
                    'success' => true,
                    'message' => 'Track deleted successfully'
                ];
            }
            return [
                'success' => false,
                'message' => 'Failed to delete track'
            ];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    // Метод для быстрого добавления артиста через AJAX
    public function addArtist() {
        if ($_POST && !empty($_POST['artist_name'])) {
            $artistId = $this->model->createArtist($_POST['artist_name']);
            return ['success' => true, 'artist_id' => $artistId, 'artist_name' => $_POST['artist_name']];
        }
        return ['success' => false];
    }
}
?>