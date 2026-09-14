<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\ServiceOrderModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;

Log::init();
class ServiceOrderController {
    private $model;
    
    public function __construct() {
        $this->model = new ServiceOrderModel();
    }
    public function index(){
        $orders = $this->model->getAllOrders($_SESSION['name']);
        require_once ADMIN_DIR . '/views/service-orders/list.php';
    }

    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return $this->model->getServiceById();
            }
            return $this->model->getAllOrders();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function createOrder() {
        
        $input = $_POST ?? json_decode(file_get_contents('php://input'), true);
        
        // Валидация обязательных полей
        $requiredFields = ['service_id', 'full_name', 'email', 'phone'];
        foreach ($requiredFields as $field) {
            if (empty($input[$field])) {
                echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                return;
            }
        }
        
        // Валидация email
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            return;
        }
        
        try {
            $orderData = [
                'service_id' => $input['service_id'],
                'full_name' => $input['full_name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'message' => $input['message'] ?? ''
            ];
            
            if ($this->model->createOrder($orderData)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Заказ услуги успешно создан! Мы свяжемся с вами в ближайшее время.',
                    'order_id' => $this->model->pdo->lastInsertId()
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create order']);
            }
            
        } catch (Exception $e) {
            log::error('Service order creation error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Internal server error']);
        }
    }
}
?>