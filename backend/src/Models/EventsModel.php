<?php
namespace Konektem\Models;
use Konektem\Models\Database;
use Konektem\Models\MainPageContentModel;
use Konektem\Utils\Log;

Log::init();
class EventsModel extends Database {
    private $mainpagemodel;
    private const EVENT_COLUMNS = [
        'id',
        'product_id',
        'title',
        'event_date',
        'description',
        'price',
        'location',
        'owner',
        'likes',
        'shares',
        'public'
    ];
    public function __construct(){
        parent::__construct();
        $this->mainpagemodel = new MainPageContentModel();
    }
    
    public function getAllEvents() {
        try{
            $query = "
                SELECT ". implode(',', array_map(fn($column) => 'e.' . $column, self::EVENT_COLUMNS)) .", i.url as image_url ,
                CASE
                    WHEN e.id IN (SELECT events FROM mainpagecontent) THEN 'mainpage'
                    ELSE 'no_pos'
                END AS position
                FROM events e 
                LEFT JOIN images i ON e.image_id = i.id
                ORDER BY e.event_date DESC
            ";
            $stmt = $this->pdo->query($query);
            
            return $stmt->fetchAll();
        } catch(Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ['error' => $e->getMessage()];
        }
    }
    public function getEventsByOwner($owner) {
        try{
            $sql = "
                SELECT ". implode(',', array_map(fn($column) => 'e.' . $column, self::EVENT_COLUMNS)) .", i.url as image_url,
                CASE
                    WHEN e.id IN (SELECT events FROM mainpagecontent) THEN 'mainpage'
                    ELSE 'no_pos'
                END position
                FROM events e 
                LEFT JOIN images i ON e.image_id = i.id
                WHERE e.owner = ?
                ORDER BY e.event_date DESC
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$owner]);
            $events = $stmt->fetchAll();
            return $events;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        }
    }
    
    public function getEventById($id) {
        try{
            $stmt = $this->pdo->prepare("
                SELECT ". implode(',', array_map(fn($column) => 'e.' . $column, self::EVENT_COLUMNS)) .", i.url as image_url,
                CASE
                    WHEN e.id IN (SELECT events FROM mainpagecontent) THEN 'mainpage'
                    ELSE 'no_pos'
                END position
                FROM events e 
                LEFT JOIN images i ON e.image_id = i.id 
                WHERE e.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        }
    }
    
    public function createEvent($data) {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO products (product_type, name, description, price) VALUES (?,?,?,?)");
            $stmt->execute(['event', "event ticket: {$data['title']}", $data['description'], $data['price']]);
            
            $productId = $this->pdo->lastInsertId();
            $cols = ["title", "event_date", "description", "location", "price", "image_id", "owner", "public"];
            $values = array_map(fn($col) => $data[$col], $cols);
            $cols[] = "product_id";
            $values[] = $productId;
            $params = str_repeat("?,", count($cols) - 1) . "?";
            $stmt = $this->pdo->prepare("
                INSERT INTO events (". implode(",", $cols) .") 
                VALUES ($params)
            ");
            $result = $stmt->execute($values);
            if($result && isset($data['position']) && $data['position'] === 'mainpage'){
                $this->mainpagemodel->addItem('events', $this->pdo->lastInsertId());
            }

            $this->pdo->commit();
            return [
                'success' => true,
                'message' => 'Event successfully created'
            ];
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function updateEvent($id, $data) {
        try{
            //Log::info("updating event");
            $this->pdo->beginTransaction();
            $cur_pos = $data['old_position'];
            $column = null;
            if($cur_pos === 'mainpage'){
                $column = 'events';
            }
            $stmt = $this->pdo->prepare("
                UPDATE events 
                SET title = ?, event_date = ?, description = ?, location = ?, price = ?, image_id = ?, public = ? 
                WHERE id = ?
            ");
            $result = $stmt->execute([
                $data['title'],
                $data['event_date'],
                $data['description'],
                $data['location'],
                $data['price'],
                $data['image_id'],
                $data['public'],
                $id
            ]);
            
            if($column)$this->mainpagemodel->deleteItem($column, $id);
            if($result && isset($data['position']) && ($data['position'] === 'mainpage')){
                $this->mainpagemodel->addItem('events', $id);
            }
            $this->pdo->commit();
            return [
                'success' => true,
                'message' => 'Event updated successfully'
            ];
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            $this->pdo->rollBack();
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function deleteEvent($id) {
        try {
            $this->pdo->beginTransaction();
            if($this->mainpagemodel->itemExists('events', $id)){
                $this->mainpagemodel->deleteItem('events', $id);
            }
            $event = $this->getEventById($id);
            $estmt = $this->pdo->prepare("DELETE FROM events WHERE id = ? AND owner = ?");
            $pstmt = $this->pdo->prepare("DELETE FROM products WHERE product_type = 'event' AND id = ?");
            $estmt->execute([$id, $_SESSION['user']['name']]);
            $pstmt->execute([$event['product_id']]);
            $this->pdo->commit();
            return true;
        } catch (\Exception $e){
            $this->pdo->rollBack();
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }

    public function updateEventStats($eventId, $type, $increment = 1) {
        $allowedTypes = ['likes', 'shares'];
        if (!in_array($type, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid stat type'];
        }
        
        try {
            $sql = "UPDATE events SET $type = $type + ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$increment, $eventId]);

            $stmt = $this->pdo->prepare("SELECT $type FROM events WHERE id = ?");
            $stmt->execute([$eventId]);
            $currVal = $stmt->fetchColumn();
            return ['success' => true, 'message' => 'Stats updated', $type => $currVal];
        } catch (\PDOException $e) {
            Log::error("Error updating event stats: " . $e->getMessage());
            return ['success' => false, 'message' => 'Server error'];
        } catch(\Exception $e){
            Log::error("Error updating event stats: " . $e->getMessage());
            return ['success' => false, 'message' => 'Server error'];
        }
    }

    private function flushLikesToDb(){
        try {} catch(\RedisException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }

    private function flushSharesToDb(){
        try {} catch(\RedisException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }

    private function cacheLikes(){
        try {} catch(\RedisException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }

    private function cacheShares(){
        try {} catch(\RedisException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }
}
?>