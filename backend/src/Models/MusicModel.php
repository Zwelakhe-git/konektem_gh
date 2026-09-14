<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\Database;
use Konektem\Models\MainPageContentModel;
use Konektem\Utils\Log;
use PDO;

Log::init();

class MusicModel extends Database {
    private $mainpagemodel;
    private const TRACK_COLUMNS = [
        'id',
        'title',
        ''
    ];
    public function __construct(){
        parent::__construct();
        $this->mainpagemodel = new MainPageContentModel();
    }
    public function getAllMusic() {
        try{
            $query = "
                SELECT m.*, a.name as artist_name, i.url as image_url,
                CASE
                    WHEN m.id IN (SELECT music FROM mainpagecontent) THEN 'mainpage'
                    ELSE 'no_pos'
                END position
                FROM music m 
                LEFT JOIN artists a ON m.artist_id = a.id 
                LEFT JOIN images i ON m.image_id = i.id
                ORDER BY order_no ASC
            ";
            $stmt = $this->pdo->query($query);
            //Log::info("query executed without errors");
            return $stmt->fetchAll();
        } catch(PDOException $e){
            Log::error("MusicModel - getAllMusic - {$e->getMessage()}");
            return [];
        }
    }
    
    public function getMusicByOwner($owner){
        $owner = $owner ?? $_SESSION['user']['name'];
        try{
            $sql = "
                SELECT m.*, a.name as artist_name, i.url as image_url,
                CASE
                    WHEN m.id IN (SELECT music FROM mainpagecontent) THEN 'mainpage'
                    ELSE 'no_pos'
                END AS position
                FROM music m 
                LEFT JOIN artists a ON m.artist_id = a.id 
                LEFT JOIN images i ON m.image_id = i.id
                WHERE m.owner = ?
                ORDER BY order_no ASC
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$owner]);
            return $stmt->fetchAll();
        } catch(Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function createMusic($data) {
        try{
            $this->pdo->beginTransaction();
            $columns = ['title', 'artist_id', 'image_id', 'url', 'mime_type', 'genre', 'owner'];
            if(isset($data['album_id']) && !empty($data['album_id'])){
                $columns[] = 'album_id';
            }
            if(isset($data['public'])){
                $columns[] = 'public';
            }
            $values = array_map(fn($col) => $data[$col], $columns);/*[
                $data['title'],
                $data['artist_id'],
                $data['image_id'],
                $data['url'],
                $data['mime_type'],
                $data['genre'],
                $data['owner']
            ];*/
            
            $params = str_repeat('?,', count($columns) - 1) . '?';
            $stmt = $this->pdo->prepare("
                INSERT INTO music (". implode(',', $columns) .") 
                VALUES ($params)
            ");
            $result = $stmt->execute($values);
            $id = $this->pdo->lastInsertId();
            if($result){
                $stmt = $this->pdo->prepare("
                    UPDATE music SET order_no = ? WHERE id = ?
                ");
                //Log::info($id);
                $stmt->execute([$data['order_no'] ?: $id, $id]);
                // change position to publish
                if(isset($data['position']) && $data['position'] === 'mainpage'){
                    if(count($this->mainpagemodel->getItemsByType('music')) >= 5){
                        Log::warn("attempt to publish music by " . $_SESSION['user']['name'] . " failed. limit reached");
                    } else {
                        if(!$this->mainpagemodel->addItem('music', $id)){
                            Log::warn("attempt to publish music by " . $_SESSION['user']['name'] . " failed. server error");
                        }
                    }
                    
                }
            }
            $this->pdo->commit();
            return [
                'success' => true,
                'message' => 'Track successfully created'
            ];
            
        } catch(\PDOException $e){
            $this->pdo->rollBack();
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }

    }
    
    public function getAllArtists() {
        $stmt = $this->pdo->query("SELECT * FROM artists ORDER BY name");
        return $stmt->fetchAll();
    }
    
    public function createArtist($name) {
        $stmt = $this->pdo->prepare("INSERT INTO artists (name) VALUES (?)");
        $stmt->execute([$name]);
        return $this->pdo->lastInsertId();
    }

    public function getTrackById($id){
        try {
            $stmt = $this->pdo->prepare("SELECT m.*, a.name as artist_name, im.url as image_url,
            CASE
                    WHEN m.id IN (SELECT music FROM mainpagecontent) THEN 'mainpage'
                    ELSE 'no_pos'
                END position
            FROM music m
            LEFT JOIN artists a ON a.id = m.artist_id
            LEFT JOIN images im ON im.id = m.image_id
            WHERE m.id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        }
    }

    public function deleteMusic($id){
        $stmt = $this->pdo->prepare("
            DELETE FROM music WHERE id = ? AND owner = ?
        ");

        return $stmt->execute([$id, $_SESSION['user']['name']]);
    }
    
    public function updateMusic($id, $data){
        try{
            $columns = ['title', 'artist_id', 'image_id', 'url', 'mime_type', 'genre'];
            if(isset($data['album_id']) && !empty($data['album_id'])){
                $columns[] = 'album_id';
            }
            //Log::info("track update: " . print_r($data, true));
            $values = array_map(fn($col) => $data[$col], $columns);
            $params = implode(',', array_map(fn($col) => "$col=?", $columns));
            $msg = "music model update: $id";
            $stmt = $this->pdo->prepare("UPDATE music SET $params WHERE id = ? AND owner = ?");

            $values = [...$values, $id, $data['owner']];
            
            $cur_pos = $data['old_position'];
            $msg .= " init_position: " . $cur_pos;
            $column = null;
            if($cur_pos === 'mainpage'){
                $column = 'music';
            }
            $result = $stmt->execute($values);
            Log::info("$msg, $column");

            if($column && (!isset($data['position']) || $data['old_position'] !== $data['position'])){
                //Log::info("taking item to main page at $column");
                if(!$this->mainpagemodel->deleteItem($column, $id)){
                    $msg .= ", pos_update success: false";
                }
            } else {
                //Log::info("item did not go to main page");
            }
            Log::info("MusicModel: music update successful - " . $result . " by " . $_SESSION['user']['name']);
            if($result){
                $stmt = $this->pdo->prepare("
                        UPDATE music SET order_no = ? WHERE id = ?
                    ");
                $stmt->execute([$data['order_no'] ?? $id, $id]);
                if(isset($data['position']) && $data['position'] === 'mainpage'){
                    if($this->mainpagemodel->addItem('music', $id)){
                        $msg = "Music model - music $id successfully set to MPC";
                    } else {
                        $msg = "Music model - music $id failed to set to MPC";
                    }
                    Log::info($msg);
                }
            }
            return [
                'success' => true,
                'message' => 'Track updated successfully'
            ];
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }

    public function updateTrackStats($trackId, $type, $increment = 1){
        $allowedTypes = ['likes', 'downloads', 'shares', 'plays'];
        
        if (!in_array($type, $allowedTypes, true)) {
            return ['success' => false, 'message' => 'Invalid stat type'];
        }
        
        try {
            // Сначала проверяем, существует ли трек
            $stmt = $this->pdo->prepare("SELECT id, $type FROM music WHERE id = ?");
            $stmt->execute([$trackId]);
            $track = $stmt->fetch();
            
            if (!$track) {
                //Log::warning("Track with ID $trackId not found");
                return [
                    'success' => false, 
                    'message' => "Track with ID $trackId not found"
                ];
            }
            
            // Проверяем, что значение в колонке - число
            $currentValue = (int) $track[$type];
            //Log::info("Current $type for track $trackId: $currentValue");
            
            // Выполняем обновление
            $sql = "UPDATE music SET `$type` = `$type` + :increment WHERE id = :trackId";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':increment' => $increment,
                ':trackId' => $trackId
            ]);
            
            // Проверяем, что обновление затронуло строку
            if ($stmt->rowCount() === 0) {
                //Log::warning("No rows updated for track $trackId");
                return [
                    'success' => false,
                    'message' => "No rows updated"
                ];
            }
            
            // Получаем новое значение
            $stmt = $this->pdo->prepare("SELECT $type FROM music WHERE id = ?");
            $stmt->execute([$trackId]);
            $currValue = (int) $stmt->fetchColumn();
            
            //Log::info("Track $trackId stats $type updated from $currentValue to $currValue");
            
            return [
                'success' => true,
                'message' => 'Stats updated',
                $type => $currValue
            ];
            
        } catch (\PDOException $e) {
            Log::error("Error updating track stats: " . $e->getMessage());
            Log::error("SQL State: " . ($e->errorInfo[0] ?? 'N/A'));
            Log::error("Error Code: " . ($e->errorInfo[1] ?? 'N/A'));
            Log::error("Error Message: " . ($e->errorInfo[2] ?? 'N/A'));
            
            return [
                'success' => false, 
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }

    private function flushLikesToDB(){}

    private function getCachedTracks(){}

    private function getCachedTrackById($id){}

}
?>