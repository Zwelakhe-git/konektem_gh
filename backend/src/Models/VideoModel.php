<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';
use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();
class VideoModel extends Database {
    private const VIDEO_COLUMNS = [
        ''
    ];
    public function __construct(){
        parent::__construct();
    }
    
    public function getAllVideos() {
        $stmt = $this->pdo->query("
            SELECT v.*, i.url as image_url 
            FROM videos v 
            LEFT JOIN images i ON v.image_id = i.id 
            ORDER BY v.id DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function getVideoById($id) {
        $stmt = $this->pdo->prepare("
            SELECT v.*, i.url as image_url 
            FROM videos v 
            LEFT JOIN images i ON v.image_id = i.id 
            WHERE v.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function createVideo($image_id, $title, $url, $mime_type) {
        try{
            $stmt = $this->pdo->prepare("
                INSERT INTO videos (image_id, title, url, mime_type) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $image_id,
                $title,
                $url,
                $mime_type
            ]);
            return $this->pdo->lastInsertId();
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return -1;
        }
    }
    
    public function updateVideo($id, $image_id, $title, $url, $mime_type) {
        $stmt = $this->pdo->prepare("
            UPDATE videos 
            SET image_id = ?, title = ?, url = ?, mime_type = ? 
            WHERE id = ?
        ");
        return $stmt->execute([
            $image_id,
            $title,
            $url,
            $mime_type,
            $id
        ]);
    }
    
    public function deleteVideo($id) {
        $stmt = $this->pdo->prepare("DELETE FROM videos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>