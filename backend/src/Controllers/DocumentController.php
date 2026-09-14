<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\DocumentModel;
use Konektem\Utils\Log;

Log::init();
class DocumentController {
    private $model;
    private $log;
    
    public function __construct() {
        $this->model = new DocumentModel();
        Log::info("DocumentController constructor called");
    }
    
    public function saveDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed'];
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            $data = $_POST;
        }
        
        // Валидация
        if (empty($data['title']) || empty($data['content'])) {
            return ['error' => 'Title and content are required'];
        }
        
        // Генерация slug если не предоставлен
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        // Добавляем author_id из сессии
        session_start();
        if (isset($_SESSION['user_id'])) {
            $data['author_id'] = $_SESSION['user_id'];
        }
        
        $result = $this->model->saveDocument($data);
        return $result;
    }
    
    public function get($params=[]) {
        if (!isset($params['id'])) {
            return ['error' => 'Document ID is required'];
        }
        
        $document = $this->model->getDocument($params['id']);
        return $document;
    }
    
    public function getAllDocuments() {
        $status = $_GET['status'] ?? null;
        $type = $_GET['type'] ?? null;
        
        $documents = $this->model->getAllDocuments($status, $type);
        return $documents;
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
        
        $result = $this->model->deleteDocument($id);
        return $result;
    }
    
    public function uploadImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Method not allowed'];
        }
        
        if (!isset($_FILES['image'])) {
            return ['error' => 'No file uploaded'];
        }
        
        $documentId = $_POST['document_id'] ?? null;
        $result = $this->model->uploadImage($_FILES['image'], $documentId);
        return $result;
    }
    
    private function generateSlug($title) {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        $slug = preg_replace('/-+/', '-', $slug);
        
        return $slug;
    }
}
?>