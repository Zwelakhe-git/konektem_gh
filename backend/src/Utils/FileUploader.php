<?php
namespace Konektem\Utils;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Exception;
use Konektem\Utils\Log;


Log::init();
class FileUploader {
    // use the default MEDIA_ROOT instead
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;
    
    public function __construct($uploadDir = UPLOAD_DIR, $allowedTypes = [], $maxSize = 50000000) {
        $this->uploadDir = $uploadDir;
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize;
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
            Log::info("Created upload dir");
        }
    }
    
    public function upload($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Log::error("File upload error: " . print_r($file, true));
            throw new Exception('File upload error');
        }
        
        if ($file['size'] > $this->maxSize) {
            Log::error('File is too big: ' . $file['size']);
            throw new Exception('File is too big: ' . $file['size']);
        }
        
        $fileType = mime_content_type($file['tmp_name']);
        if (!empty($this->allowedTypes) && !in_array($fileType, $this->allowedTypes)) {
            Log::error('File type not allowed: ' . $file['name'] . ' - ' . $fileType);
            throw new Exception('File type not allowed: ' . $file['name'] . ' - ' . $fileType);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $extension;
        $filePath = $this->uploadDir . "/$fileName";
        $fileUrl = $filePath;
        
        $parts = explode(HTDOCS, $filePath, 2);
        if(count($parts) > 1){
            $fileUrl = $parts[1];
            // Результат: /konektem/uploads/images/6a380c25cc5bb.png
        }
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // consider filepath = MEDIA_URL . $filename
            $fileUrl = (new \Konektem\Utils\Utils())->normalizeUrl($fileUrl);
            return [
                'filename' => $fileName,
                'filepath' => $fileUrl,
                'mime_type' => $fileType,
                'size' => $file['size']
            ];
        }
        Log::error('Не удалось сохранить файл');
        throw new Exception('Не удалось сохранить файл');
    }
    
    public function delete_file($path, $path_type="url"){
        /*
        	deletes a file from the server.
            path - the file's path. if its a url, then normalise. It should be relative to the server base dir.
            		If it's a full path, then do nothing.
            path_type - values [url, path]. indicates the type of the file path.
        */
        if($path_type === 'url' || $path[0] === '/'){
            if(!defined('HTDOCS')){
                // reject if htdocs dir is not defined. for security it should be defined in the config file.
                Log::error(' Failed to delete file from url. HTDOCS not defined');
                return false;
            }
            $path = HTDOCS . $path;
        }
        if(file_exists($path)){
            unlink($path);
            Log::info(' File (' . basename($path) . ') successfully deleted');
            return true;
        } else {
            Log::error(' Failed to delete file (' . basename($path) . '). File Not Found in ' . $path);
            return false;
        }
    }
}
?>