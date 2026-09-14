<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();
class OrderModel extends Database {
    public function __construct(){
        parent::__construct();
    }
    
    public function getAllOrders() {
        $stmt = $this->pdo->query("
            SELECT o.*, c.first_name, c.last_name, c.email 
            FROM orders o 
            JOIN clients c ON o.client_id = c.id
            ORDER BY o.order_date DESC
        ");
        return $stmt->fetchAll();
    }
    public function createClient($data) {
        try {
            $cached = [];//$this->getCachedClients();
            if($cached){
                $existing = array_filter($cached, fn($client) => $client['email'] === $data['email']);
                if($existing){
                    return $existing[0]['id'];
                }
            }
            $stmt = $this->pdo->prepare("SELECT id FROM clients WHERE email = ?");
            $stmt->execute([$data['email']]);
            $id = $stmt->fetchColumn();
            if($id){
                $this->cacheClient($data);
                return $id;
            }
            $stmt = $this->pdo->prepare("
                INSERT INTO clients (first_name, last_name, email, phone) 
                VALUES (?, ?, ?, ?)
            ");
            $payload = [
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['phone']
            ];
            $stmt->execute($payload);
            $id = $this->pdo->lastInsertId();
            //$this->cacheClient($id, $payload);
            return $id;
        } catch(\PDOException $e) {
            // Если email уже существует, возвращаем существующий ID
            if ($e->getCode() == 23000) {
                $stmt = $this->pdo->prepare("SELECT id FROM clients WHERE email = ?");
                $stmt->execute([$data['email']]);
                $client = $stmt->fetch();
                return $client ? $client['id'] : false;
            }
            return -1;
        }
    }

    private function cacheClient($id, $payload){
        try {
            foreach($data as $key => $val){
                $this->redisClient->hset("client:$id", $key, $val);
            }
        } catch(\Exception $e){}
    }

    private function getCachedClients(){
        try{
            $keys = $this->redisClient->keys("client:*");
            if(!empty($keys)){
                $cached = [];
                foreach($keys as $key){
                    $cached[] = $this->redisClient->hgetall($key);
                }
                return $cached;
            }
            return null;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        }
    }
    
    public function createOrder($orderData) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (client_id, total_amount, transaction_id, item_name, item_id, order_date) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $orderData['client_id'],
                $orderData['total_amount'],
                $orderData['transaction_id'],
                $orderData['item_name'],
                $orderData['item_id'],
                date('Y-m-d H:i:s')
            ]);
            $id = $this->pdo->lastInsertId();
            return [
                'success' => true,
                'message' => 'Order successfully created'
            ];
        } catch(\PDOException $e) {
            Log::error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
        return $stmt->execute([$status, $orderId]);
    }
    
    public function updatePaymentStatus($orderId, $status) {
        try{
            $stmt = $this->pdo->prepare("
                UPDATE orders SET payment_status = ?, payment_date = NOW() 
                WHERE id = ?
            ");
            return $stmt->execute([$status, $orderId]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }
}
?>