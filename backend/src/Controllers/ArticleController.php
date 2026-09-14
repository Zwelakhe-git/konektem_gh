<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';
use Konektem\Models\NewsModel;
use Konektem\Models\ImageModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;

Log::init();

class ArticleController {
    private $model;
    private $imageModel;
    private $utils;
    private $userPayload;
    
    public function __construct() {
        $this->model = new NewsModel();
        $this->imageModel = new ImageModel();
        $this->utils = new Utils();
    }
    
    public function index() {
        $articles = $this->model->getAllArticles();
    }

    public function get($params=[]){
        try {
            if(isset($params['title_hash']) && !empty($params['title_hash'])){
                $article = $this->model->getArticleByTitleHash($params['title_hash']);
                return $article;
            } elseif(isset($params['id']) && !empty($params['id'])){
                $article = $this->model->getArticleById($params['id']);
                return $article;
            } elseif(isset($params['user_id']) && !empty($params['user_id'])){
                return $this->model->getArticlesByUser($params['user_id']);
            }
            $articles = $this->model->getAllArticles();
            return $articles;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function create() {
        $uploadedFiles = [];
        try {
            $newsImageId = null;
            
            // Загрузка изображения новости
            if (!empty($_FILES['news_image']['name'])) {
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['news_image']);
                $newsImageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $uploadedFiles[] = [
                    'type' => 'image',
                    'url' => $imageInfo['filepath']
                ];
            }
            if($this->model->getArticleByTitleHash(md5($_POST['title']))){
                return [
                    'success' => false,
                    'message' => 'Article with the same title already exists'
                ];
            }
            
            $data = [
                'image_id' => $newsImageId,
                'title' => $_POST['title'],
                'created_at' => $_POST['created_at'],
                'headline' => $_POST['headline'],
                'content' => $_POST['content'],
                'category' => $_POST['category'],
                'publish' => $_POST['publish'] ?? false,
                'author_id' => $_SESSION['user']['id']
            ];
            if(isset($_POST["position"])){
                $data['position'] = $_POST['position'];
            }
            $result = $this->model->createArticle($data);
            if ($result['success'] && $data['publish']) {
                // SEND notifications
                //$this->utils->sendOSPushNotification('new article', $data['title'], BASE_URL . '/actuality/' . md5($data['title']));
            } else { $this->removeUploadedFiles($uploadedFiles); }
            return $result;
        } catch (\Exception $e) {
            $this->removeUploadedFiles($uploadedFiles);
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch (\Throwable $e) {
            $this->removeUploadedFiles($uploadedFiles);
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
        
    }

    private  function removeUploadedFiles(array $files){
        if(empty($files)) return;
        foreach($files as $file){
            if(isset($file['type']) && $file['type'] === 'image'){
                $this->imageModel->deleteImage(null, $file['url']);
            }
            $this->utils->deleteFile($file['url']);
        }
    }
    
    public function edit() {
        $uploadedFiles = [];
        try {
            $body = $_POST ?: json_decode(file_get_contents('php://input'), true);
            if(empty($body) || !isset($body['id']) || empty($body['id'])){
                return [
                    'successs' => false,
                    'message' => 'Missing parameters'
                ];
            }
            $id = $body['id'];
            $article = $this->model->getArticleById($id);
            $newsImageId = $article['image_id'];
            $newCover = false;
            
            // Загрузка нового изображения
            if (!empty($_FILES['news_image']['name'])) {
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['news_image']);
                $newsImageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $uploadedFiles[] = [
                    'type' => 'image',
                    'url' => $imageInfo['filepath']
                ];
                $newCover = true;
            }
            
            $data = [
                'image_id' => $newsImageId,
                'title' => $_POST['title'],
                'headline' => $_POST['headline'],
                'content' => $_POST['content'],
                'category' => $_POST['category'],
                'old_position' => $article['position'],
                'publish' => (bool)$_POST['publish'] ?? false,
            ];
            if(isset($_POST["position"])){
                $data['new_position'] = $_POST['position'];
            }
            $result = $this->model->updateArticle($id, $data);
            if ($result) {
                if(!$article['published_at'] && $data['publish']){
                    $this->utils->sendOSPushNotification('New article', $data['title'], BASE_URL . '/actuality/' . md5($data['title']));
                }
                if($newCover){
                    $this->imageModel->deleteImage(null, $article['image_url']);
                    $this->utils->deleteFile($article['image_url']);
                }
            }
            return $result;
        } catch (\Exception $e) {
            $this->removeUploadedFiles($uploadedFiles);
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch (\Throwable $e) {
            $this->removeUploadedFiles($uploadedFiles);
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
            $article = $this->model->getArticleById($id);
        
            if($article['published_at']){
                Log::warn("not allowed to delete a published article");
            }
            // Удаляем связанные изображения
            if ($article['image_id']) {
                $this->imageModel->deleteImage($article['image_id']);
                $this->utils->deleteFile($article['image_url']);
            }
            $result = $this->model->deleteArticle($id);
            (new \Konektem\Models\MainPageContentModel())->deleteItem($article['position'], $id);
            return [
                'success' => true,
                'message' => 'article successfully deleted'
            ];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    // Метод для предпросмотра новости
    public function preview($id) {
        $articles = $this->model->getArticleById($id);
        try{
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
        
    }
}
?>