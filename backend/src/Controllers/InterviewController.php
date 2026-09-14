<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\InterviewsModel;
use Konektem\Models\ImageModel;
use Konektem\Models\VideoModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;


class InterviewController {
    private $model;
    private $imageModel;
    private $videoModel;
    private $utils;
    private $log;
    
    public function __construct() {
        $this->model = new InterviewsModel();
        $this->imageModel = new ImageModel();
        $this->videoModel = new VideoModel();
        $this->utils = new Utils();
        $this->log = new Log();
    }
    
    public function index() {
        $interviews = $this->model->getAllInterviews();
        require_once ADMIN_DIR . '/views/interview/list.php';
    }

    public function get($params = []){
        try {
            if(isset($params['id'])){
                $interview = $this->model->getInterviewById($params['id']);
                return $interview;
            } elseif(isset($params['title_hash'])){
                $interview = $this->model->getInterviewByTitleHash($params['title_hash']);
                return $interview;
            } elseif(isset($params['user_id']) && !empty($params['user_id'])){
                return $this->model->getInterviewsByUser($params['user_id']);
            }
            $interviews = $this->model->getAllInterviews();
            return $interviews;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        }
    }
    
    public function create() {
        $uploadedFiles = [];
        try {
            $imageId = null;
            $video_info = null;
            $videoId = null;
            
            // Upload interview image
            if (!empty($_FILES['interview_image']['name'])) {
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['interview_image']);
                $imageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $uploadedFiles[] = ['type' => 'image', 'url' => $imageInfo['filepath']];
            }
            if(!empty($_FILES['interview_video']['name'])){
                $video_info = $this->utils->upload(VIDEOS_PATH, VIDEOTYPES, $_FILES['interview_video']);
                $videoId = $this->videoModel->createVideo($imageId, $_POST['title'], $video_info['filepath'], $video_info['mime_type']);
                $uploadedFiles[] = ['url' => $imageInfo['filepath']];
            }
            
            
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'image_id' => $imageId,
                'guest_name' => $_POST['guest_name'],
                'guest_title' => $_POST['guest_title'],
                'interview_date' => $_POST['interview_date'],
            ];
            if($videoId){
                $data['video_id'] = $videoId;
            }
            if(isset($_POST["position"])){
                $data['position'] = $_POST['position'];
            }
            
            $result = $this->model->createInterview($data);
            if(!$result['success']){
                $this->deleteUploadedFiles($uploadedFiles);
            }
            return $result;
        } catch (\Exception $e) {
            if(!empty($uploadedFiles)){
                $this->deleteUploadedFiles($uploadedFiles);
            }
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch (\Throwable $e) {
            if(!empty($uploadedFiles)){
                $this->deleteUploadedFiles($uploadedFiles);
            }
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
        $interview = $this->model->getInterviewById($id);
        $uploadedFiles = [];
        try {
            $imageId = $interview['image_id'];
            $videoId = $interview['video_id'];

            $newImage = false;
            $newVideo = false;
            
            // Upload new interview image
            if (!empty($_FILES['interview_image']['name'])) {
                $newImage = true;
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['interview_image']);
                $imageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $uploadedFiles[] = ['type' => 'image', 'url' => $imageInfo['filepath']];
            }
            
            if(!empty($_FILES['interview_video']['name'])){
                $videoInfo = $this->utils->upload(VIDEOS_PATH, VIDEOTYPES, $_FILES['interview_video']);
                $newVideo = true;
                $videoId = $this->videoModel->createVideo($imageId, $_POST['title'], $videoInfo['filepath'], $videoInfo['mime_type']);
                $uploadedFiles[] = ['type' => 'image', 'url' => $videoInfo['filepath']];
            }                
            
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'image_id' => $imageId,
                'guest_name' => $_POST['guest_name'],
                'guest_title' => $_POST['guest_title'],
                'interview_date' => $_POST['interview_date'],
                'video_id' => $videoId
            ];
            
            $result = $this->model->updateInterview($id, $data);
            if ($result['success']) {
                if($newImage){
                    $this->imageModel->deleteImage(null, $interview['image_url']);
                    $this->utils->deleteFile($interview['image_url']);
                }
                if($newVideo){
                    $this->utils->deleteFile($interview['video_url']);
                }
            } else {
                $this->deleteUploadedFiles($uploadedFiles);
            }
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
            $interview = $this->model->getInterviewById($id);
            
            
            
            if ($this->model->deleteInterview($id)) {
                // Delete associated image
                if ($interview['image_url']) {
                    $this->imageModel->deleteImage(null, $interview['image_url']);
                    $this->utils->deleteFile($interview['image_url']);
                }
                if($interview['video_url']){
                    $this->utils->deleteFile($interview['video_url']);
                }
                // if($interview['position'] === 'mainpage'){
                //     (new \Konektem\Models\MainPageContentModel())->deleteItem('interviews', $id);
                // }
                return [
                    'success' => true,
                    'message' => 'Interview deleted successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to delete interview'
                ];
            }
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }

    private function deleteUploadedFiles(array $files){
        if(empty($files)) return;
        foreach($files as $file){
            if(isset($file['type']) && $file['type'] === 'image'){
                $this->imageModel->deleteImage(null, $file['url']);
            }
            $this->utils->deleteFile($file['url']);
        }
    }
}
?>