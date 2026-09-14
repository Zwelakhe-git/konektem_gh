<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';
use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();
class ServiceOrderModel extends Database {
    public function __construct(){
        parent::__construct();
        Log::info("ServiceOrderModel constructor called");
    }
    
    public function createOrder($orderData) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO service_orders (service_id, full_name, email, phone, message) 
                VALUES (?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $orderData['service_id'],
                $orderData['full_name'],
                $orderData['email'],
                $orderData['phone'],
                $orderData['message'] ?? ''
            ]);
        } catch(PDOException $e) {
            Log::error("Service order creation error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllOrders() {
        $stmt = $this->pdo->query("
            SELECT od.*, s.name as service_name 
            FROM orders od
            JOIN Services s ON s.id = od.item_id
            WHERE item_name = 'service' 
            ORDER BY od.order_date DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function getServiceById($id) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, i.location as image_location 
            FROM services s 
            LEFT JOIN Images i ON s.serviceImg = i.id 
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->pdo->prepare("
            UPDATE service_orders 
            SET status = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$status, $orderId]);
    }
}
?>