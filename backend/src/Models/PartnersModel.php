<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();
class PartnersModel extends Database {
    public function __construct(){
        parent::__construct();
    }

    public function getAllPartners(){
        try{
            $stmt = $this->pdo->query("SELECT p.*, im.url AS image_url FROM partners p LEFT JOIN images im ON im.id = p.image_id");
            return $stmt->fetchAll();
        } catch(PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function createPartner($data){
        try{
            $stmt = $this->pdo->prepare("INSERT INTO partners (name, image_id) VALUES (?,?)");
            $stmt->execute([
                $data['partner_name'],
                $data['image_id']
            ]);
            return [
                'success' => true,
                'message' => 'Partner successfully added'
            ];
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }

    public function deletePartner($id){
        try{

            $stmt = $this->pdo->prepare("DELETE FROM partners WHERE id = ?");
            return $stmt->execute([$id]);

        } catch(PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }

    public function getPartnerById($id){
        try{
            $stmt = $this->pdo->prepare("SELECT p.*, im.url AS image_url FROM partners p LEFT JOIN images im ON im.id = p.image_id WHERE p.id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch(PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }

    public function editPartner($id, $data){
        try{
            $stmt = $this->pdo->prepare("UPDATE partners SET name = ?, image_id = ? WHERE id = ?");
            $stmt->execute([
                $data['name'],
                $data['image_id'],
                $id
            ]);
            return [
                'success' => true,
                'message' => 'Partner successfully updated'
            ];
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
}
?>