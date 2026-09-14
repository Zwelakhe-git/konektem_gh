<?php
namespace Konektem\Controllers;

require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\OrderModel;
use Konektem\Models\EmailModel;
use Konektem\Utils\Log;


class OrderController {
    private $log;
    public function __construct() {
        $this->model = new OrderModel();
        $this->log = new Log();
    }
    
    public function index() {
        $orders = $this->model->getAllOrders();
    }

    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return [];
            } elseif(isset($params['user_id']) && !empty($params['user_id'])){
                return $this->model->getOrdersByUser($params['user_id']);
            }
            return $this->model->getAllOrders();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function createOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Создаем клиента
            $clientData = [
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'phone' => $input['phone'] ?? null
            ];
            
            $clientId = $this->model->createClient($clientData);
            
            if ($clientId) {
                // Создаем заказ
                $orderData = [
                    'client_id' => $clientId,
                    'item_name' => $input['item_name'],
                    'item_id' => $input['item_id'],
                    'total_amount' => $input['total_amount'],
                    'transaction_id' => uniqid('ORDER_')
                ];
                $orderId = $this->model->createOrder($orderData);
                
                if ($orderId) {
                    if($input['item_name'] == 'service'){
                        $this->sendEmail($input['message'], $input['full_name'], $input['email']);
                    }
                    return [
                        'success' => true,
                        'order_id' => $orderId,
                        'message' => 'Order created successfully'
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Failed to create order'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to register client'
                ];
            }
        }
    }

    private function sendEmail($message, $from_name, $from_email){
        $email = new Email($from_email, $from_email);
        $email->prepare(
            "Service order",
            SERVICE_MANAGER_EMAIL,
            'MediaHub Service Manager',
            "<p>" . $message . "</p>"
        );
        $email->send();
    }

    private function emailBodyForServices(){
        return "
        ";
    }
    public function updatePaymentStatus() {
        try{
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $orderId = $input['order_id'];
                $status = $input['status'];
                
                $result = $this->model->updatePaymentStatus($orderId, $status);
                
                if ($result) {
                    return [
                        'success' => true,
                        'message' => 'Order status updated successfully'
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Failed to update order status'
                    ];
                }
            }
        } catch(Exception $e){
            $this->log->error($e->getMessage());
            return ['success' => false, 'message' => 'Server Error'];
        }
    }
}
?>