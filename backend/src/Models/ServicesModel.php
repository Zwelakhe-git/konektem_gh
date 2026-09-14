<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';
use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();
class ServicesModel extends Database {
    private const SERVICES_COLUMNS = [
        'id',
        'product_id',
        'name',
        'description',
        'price',
        'serviceImg',
        'owner',
        'orders',
        'requirements',
        'estimated_days'
    ];
    public function __construct(){
        parent::__construct();
    }
    public function getAllServices() {
        $sql = "
            SELECT s.*, i.url as image_url 
            FROM services s 
            LEFT JOIN images i ON s.serviceImg = i.id
            ORDER BY s.id DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getServicesByOwner($owner) {
        $sql = "
            SELECT s.*, i.url as image_url 
            FROM services s 
            LEFT JOIN images i ON s.serviceImg = i.id
            WHERE s.owner = '" . $owner . "'
            ORDER BY s.id DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getServiceById($id) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, i.url as image_url 
            FROM services s 
            LEFT JOIN images i ON s.serviceImg = i.id 
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function createService($data) {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("INSERT INTO products (product_type, name, description, price) VALUES (?,?,?,?)");
            $stmt->execute(['service', "service provision: {$data['name']}", $data['description'], $data['price']]);
            $productId = $this->pdo->lastInsertId();
            $stmt = $this->pdo->prepare("
                INSERT INTO services (name, description, serviceImg, price, product_id, owner) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['serviceImg'],
                $data['price'],
                $productId,
                $data['owner']
            ]);
            $this->pdo->commit();
            return [
                'success' => true,
                'message' => 'Service successfully created'
            ];
    }
    
    public function updateService($id, $data) {
            $stmt = $this->pdo->prepare("
                UPDATE services 
                SET name = ?, description = ?, serviceImg = ? 
                WHERE id = ? AND owner = ?
            ");
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['serviceImg'],
                $id,
                $data['owner']
            ]);
            return [
                'success' => true,
                'message' => 'Service successfully edited'
            ];
    }
    
    public function deleteService($id, $owner) {
        try {
            $service = $this->getServiceById($id);
            $stmt = $this->pdo->prepare("DELETE FROM services WHERE id = ? AND owner = ?");
            $svDelResult = $stmt->execute([$id, $owner]);
            $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ? AND product_type = 'service'");
            $pdDelResult = $stmt->execute([$service['product_id']]);
            return $svDelResult && $pdDelResult;
        } catch(\Exception $e){
            Log::error("");
            return false;
        }
    }

    public function updateServiceStats($serviceId, $type, $increment = 1) {
        $allowedTypes = ['orders' => 'orders', 'cancellations' => 'cancellations'];
    
        if (!isset($allowedTypes[$type])) {
            return ['success' => false, 'message' => 'Invalid stat type'];
        }

        $column = $allowedTypes[$type];
        
        try {
            $sql = "UPDATE services SET `$column` = `$column` + ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$increment, $serviceId]);

            $sql = "SELECT `$column` FROM services WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$serviceId]);
            $currVal = $stmt->fetchColumn();
            
            return ['success' => true, 'message' => 'Stats updated', $type => $currVal];
        } catch (\PDOException $e) {
            Log::error("Error updating service stats: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }
}
?>