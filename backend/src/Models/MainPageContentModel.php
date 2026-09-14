<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';
use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();

class MainPageContentModel extends Database {
    public function __construct(){
        parent::__construct();
    }
    public function getAllItems(){
        $stmt = $this->pdo->query("SELECT * FROM mainpagecontent");
        return $stmt->fetchAll();
    }
    
    public function getItemsByType($type){
        try{
            $stmt = $this->pdo->prepare("SELECT DISTINCT ? FROM mainpagecontent WHERE $type IS NOT NULL");
            $stmt->execute([
                $type
            ]);
            return $stmt->fetchAll();
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function itemExists($type, $id){
        try{
            $query = "SELECT $type FROM mainpagecontent WHERE $type = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);
            $result = $stmt->fetchAll();
            Log::info("MPCModel - $query");
            Log::info("MPCModel - $type - $id already exists: " . count($result));
            return !empty($result);
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }
    public function addItem($type, $id){
        try{
            // empty an existing slot
            $positions = [
                'mpnews_slide' => 'newsSlide',
                'mpnews_fade' => 'fadeNews',
                'newsSlide' => 'newsSlide',
                'fadeNews' => 'fadeNews',
                'music' => 'music',
                'events' => 'events',
                'interviews' => 'interviews'
            ];
            $limits = [
                'mpnews_slide' => 25,
                'mpnews_fade' => 25,
                'newsSlide' => 25,
                'fadeNews' => 25,
                'music' => 5,
                'events' => 25,
                'interviews' => 25
            ];
            if(!isset($positions[$type])){
                Log::warn("cannot remove $type from main page");
                return;
            }
            $position = $positions[$type];
            if($this->itemExists($position, $id)){
                Log::error("MP content " . $type . " already inserted");
                return false;
            }

            $stmt = $this->pdo->query("SELECT COUNT(`$position`) FROM mainpagecontent WHERE `$position` IS NOT NULL");
            if($limits[$type] >= 0 && $stmt->fetchColumn() >= $limits[$type]){
                return false;
            }
            
            //Log::info("inserting " . $type . " into MPC");
            // first slide into an empty slot
            $query = "UPDATE mainpagecontent SET `$position` = ? WHERE $position IS NULL LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute([$id]);
            
            //Log::info($stmt->rowCount());
            if($stmt->rowCount() > 0){
                return true;
            }
            
            // make a new record if there isnt an empty slot
            $query = "INSERT INTO mainpagecontent ($position) VALUES (?)";
            $stmt = $this->pdo->prepare($query);
        	return $stmt->execute([$id]);
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }
    
    public function deleteItem($type, $id){
        try{
            // empty an existing slot
            $positions = [
                'mpnews_slide' => 'newsSlide',
                'mpnews_fade' => 'fadeNews',
                'newsSlide' => 'newsSlide',
                'fadeNews' => 'fadeNews',
                'music' => 'music',
                'events' => 'events',
                'interviews' => 'interviews'
            ];
            if(!isset($positions[$type])){
                Log::warn("cannot remove $type from main page");
                return;
            }

            $position = $positions[$type];
            Log::info("item to delete $type");
            $query = "UPDATE mainpagecontent SET `$position` = NULL WHERE `$position` = ?";
            $stmt = $this->pdo->prepare($query);
            //Log::info("removed $id from mainpagecontent at position $position");
            return $stmt->execute([$id]);
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }
}

?>