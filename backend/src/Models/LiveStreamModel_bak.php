<?php
namespace Konektem\Models;
use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();
class LiveStreamModel extends Database {
    public function __construct(){
        parent::__construct();
    }
    
    public function getAllStreams() {
        $stmt = $this->pdo->query("SELECT * FROM livestream ORDER BY stream_date DESC");
        return $stmt->fetchAll();
    }
    
    public function getStreamById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM livestream WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getStreamsByUser($userId){}
    
    public function createStream($data) {
        
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO livestream (stream_date, stream_title, stream_key) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([
                $data['stream_date'],
                $data['stream_title'],
                $data['stream_key']
            ]);
            return [
                'success' => true,
                'message' => 'Stream created successfully'
            ];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function updateStream($id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE livestream 
                SET stream_date = ?, stream_title = ?, stream_key = ? 
                WHERE id = ?
            ");
            $stmt->execute([
                $data['stream_date'],
                $data['stream_title'],
                $data['stream_key'],
                $id
            ]);
            return [
                'success' => true,
                'message' => 'stream successfully updated'
            ];
        } catch(\Exception $e){
            Log::error();
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function deleteStream($id) {
        $stmt = $this->pdo->prepare("DELETE FROM livestream WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function generateStreamKey() {
        return bin2hex(random_bytes(10));
    }
}
?>