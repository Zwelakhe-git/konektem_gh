<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Utils\Log;
use Konektem\Models\Database;

Log::init();
class ImageModel extends Database {
    public function __construct(){
        parent::__construct();
    }
    
    public function createImage($location, $mime_type) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO images (url, mime_type) 
                VALUES (?, ?)
            ");
            $stmt->execute([$location, $mime_type]);
            return $this->pdo->lastInsertId();
        } catch(\Exception $e){
            Log::error($e->getMessage() . " in file " . $e->getFile() . " line " . $e->getLine());
            return -1;
        }
    }
    
    public function getImageById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM images WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function deleteImage($id, $url=null) {
        try{
            $sql = "DELETE FROM images WHERE";
            $sql .= $url ? " url = ?" : " id = ?";
            $stmt = $this->pdo->prepare($sql);
        	return $stmt->execute([$url ?? $id]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }
    
    // НОВЫЕ МЕТОДЫ ДЛЯ API
    
    /**
     * Получить все изображения с пагинацией
     */
    public function getAllImages($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $stmt = $this->pdo->prepare("
            SELECT * FROM images 
            ORDER BY id DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Получить общее количество изображений
     */
    public function getTotalImages() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM images");
        return $stmt->fetch()['total'];
    }
    
    /**
     * Найти изображение по имени файла
     */
    public function getImageByFilename($filename) {
        $stmt = $this->pdo->prepare("SELECT * FROM images WHERE url = ?");
        $stmt->execute([$filename]);
        return $stmt->fetch();
    }
    
    /**
     * Получить изображения по массиву ID
     */
    public function getImagesByIds($ids) {
        if (empty($ids)) {
            return [];
        }
        
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $this->pdo->prepare("
            SELECT * FROM images 
            WHERE id IN ($placeholders) 
            ORDER BY FIELD(id, " . $placeholders . ")
        ");
        
        // Дважды передаем IDs для ORDER BY FIELD
        $params = array_merge($ids, $ids);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Обновить информацию об изображении
     */
    public function updateImage($id, $data) {
        $allowedFields = ['url', 'mime_type', 'alt_text', 'title', 'description'];
        $updates = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updates[] = "$key = ?";
                $params[] = $value;
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE images SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    /**
     * Поиск изображений по тексту
     */
    public function searchImages($search, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $searchTerm = "%$search%";
        
        $stmt = $this->pdo->prepare("
            SELECT * FROM images 
            WHERE url LIKE ? 
            OR mime_type LIKE ?
            ORDER BY id DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(2, $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->bindValue(4, $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>