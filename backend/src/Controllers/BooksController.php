<?php
namespace Konektem\Controllers;

require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\BooksModel;
use Konektem\Models\ImageModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;

Log::init();
class BooksController {
    private $model;
    private $imageModel;
    private $utils;
    
    public function __construct() {
        $this->model = new BooksModel();
        $this->imageModel = new ImageModel();
        $this->utils = new Utils();
    }
    
    public function index() {
        $books = $this->model->getAllBooks();
        return [
            'books' => $books
        ];
    }

    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return $this->model->getBookById($params['id']);
            } elseif(isset($params['user_name']) && !empty($params['user_name'])){
                return $this->model->getBooksByOwner($params['user_name']);
            }
            return $this->model->getAllBooks();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }

    public function getBooksByOwner(){
        $books = $this->model->getBooksByOwner($_SESSION['user']['name']);
        return [
            'books' => $books
        ];
    }

    public function getBooksByAuthor($name){
        $books = $this->model->getBooksByAuthor($name);
        return [
            'books' => $books
        ];
    }
    
    public function create() {
        $uploadedFiles = [];
        try {
            $bookImageId = null;
            $fileInfo = null;
            
            //Log::info(print_r($_FILES, true));
            if (!empty($_FILES['book_image']['name'])) {
                //Log::info("uploading book cover image: {$_FILES['book_image']['name']}");
                $imageInfo = $this->utils->upload(IMAGES_PATH, IMAGETYPES, $_FILES['book_image']);
                $bookImageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                $uploadedFiles[] = [
                    'url' => $imageInfo['filepath'],
                    'type' => 'image'
                ];
            }
            if(!empty($_FILES['book_file']['name'])){
                // upload pdf file
                $fileInfo = $this->utils->upload(UPLOAD_DIR . '/documents', ['application/pdf'],$_FILES['book_file']);
                $uploadedFiles[] = [
                    'url' => $fileInfo['filepath'],
                    'type' => 'image'
                ];
            }
            //Log::info("Book create: file_url: " . $fileInfo['filepath']);
            
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'cover_image' => $bookImageId,
                'description' => $_POST['description'],
                'release_date' => $_POST['release_date'],
                'genre' => $_POST['book-genre'] ?? $_POST['new-book-genre'],
                'pdfUrl' => $fileInfo['filepath'] ?? null,
                'public' => 0,
                'owner' =>  $_SESSION['user']['name']
            ];
            if(isset($_POST['public'])){
                $data['public'] = $_POST['public'];
            }
            $result = $this->model->createBook($data);
            return $result;
            
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            if(!empty($uploadedFiles)){
                foreach($uploadedFiles as $file){
                    if($file['type'] === 'image'){
                        $this->imageModel->deleteImage(null, $file['url']);
                    }
                    $this->utils->deleteFile($file['url']);
                }
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
            $book = $this->model->getBookById($id);

            if(!$book){
                return [
                    'book' => null,
                    'message' => 'no such book'
                ];
            }

            //Log::info(print_r($body, true));
            
            try {
                $bookImageId = $book['cover_image'];
                $image = $this->imageModel->getImageById($bookImageId);
                $fileUrl = $book['pdfUrl'];
                $remove_image = false;
                $newPdf = false;
                
                if (!empty($_FILES['book_image']['name'])) {
                    $imageInfo = $this->utils->upload(UPLOAD_DIR . '/images', IMAGETYPES, $_FILES['book_image']);
                    $bookImageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                    $image && $this->utils->deleteFile($image['url']);
                    $remove_image = true;
                }
                
                if(!empty($_FILES['book_file']['name'])){
                    // upload pdf file
                    $fileUrl && $this->utils->deleteFile($fileUrl);
                    $fileInfo = $this->utils->upload(UPLOAD_DIR . '/documents', ['application/pdf'], $_FILES['book_file']);
                    $fileUrl = $fileInfo['filepath'];
                    $newPdf = true;
                }
                
                $data = [
                    'title' => $body['title'],
                    'author' => $body['author'],
                    'bookImg' => $bookImageId,
                    'description' => $body['description'],
                    'release_date' => $body['release_date'],
                    'genre' => $body['book-genre'] ?? $body['new-book-genre'],
                    'pdfUrl' => $fileUrl,
                    'owner' => $_SESSION['user']['name'],
                    'public' => $book['public']
                ];
                if(isset($body['public'])){
                    $data['public'] = $body['public'];
                }
                $result = $this->model->updateBook($id, $data);
                if ($result['success']) {
                    $remove_image && $this->imageModel->deleteImage($book['cover_image']);
                    $newPdf && $this->utils->deleteFile($book['pdfUrl']);
                }
                return $result;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Server error'
                ];
            }
    }
    
    public function delete($params = []) {
        try {
            $body = json_decode(file_get_contents('php://input'), true);
            if((empty($body) || !isset($body['id']) || empty($body['id'])) && (!isset($params['id']) || empty($params['id']))){
                return [
                    'successs' => false,
                    'message' => 'Missing parameters'
                ];
            }
            $id = $body['id'] ?? $params['id'];
            $book = $this->model->getBookById($id);

            Log::info("book id: $id");

            if(!$book){
                return [
                    'success' => false,
                    'message' => 'Book not found'
                ];
            }
                    
            if ($this->model->deleteBook($id)) {
                $image = $this->imageModel->getImageById($book['cover_image']);
                $book['cover_image'] && $this->imageModel->deleteImage($book['cover_image']);
                $book['pdfUrl'] && $this->utils->deleteFile($book['pdfUrl']);
                $image && $this->utils->deleteFile($image['url']);

                return [
                    'success' => true,
                    'message' => 'Book successfully deleted'
                ];
            }
            return [
                'success' => false,
                'message' => 'failed to delete book'
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