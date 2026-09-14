<?php
namespace Konektem\Controllers;

require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\OrderModel;
use Konektem\Models\EmailModel;
use Konektem\Utils\Log;

class OrderController {
    private $model;
    private $log;
    
    public function __construct() {
        $this->model = new OrderModel();
        $this->log = new Log();
    }
    
    public function index() {
        $orders = $this->model->getAllOrders();
        return $orders;
    }

    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return $this->model->getOrderById($params['id']);
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
            
            if (!$input) {
                return [
                    'success' => false,
                    'message' => 'Invalid input data'
                ];
            }
            
            try {
                // Создаем или получаем пользователя
                $userData = [
                    'email' => $input['email'],
                    'first_name' => $input['first_name'] ?? '',
                    'last_name' => $input['last_name'] ?? '',
                    'phone' => $input['phone'] ?? null
                ];
                
                $userId = $this->model->createOrGetUser($userData);
                
                if (!$userId) {
                    return [
                        'success' => false,
                        'message' => 'Failed to create/get user'
                    ];
                }
                
                // Получаем детали продукта
                $productDetails = $this->model->getProductDetails($input['product_id']);
                if (!$productDetails) {
                    return [
                        'success' => false,
                        'message' => 'Product not found'
                    ];
                }
                
                // Создаем заказ
                $orderData = [
                    'user_id' => $userId,
                    'total_amount' => $input['total_amount'],
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                    'items' => [
                        [
                            'product_id' => $input['product_id'],
                            'quantity' => $input['quantity'] ?? 1,
                            'price_at_time' => $productDetails['product']['price'],
                            'discount' => $input['discount'] ?? 0
                        ]
                    ]
                ];
                
                $result = $this->model->createOrder($orderData);
                
                if ($result['success']) {
                    // Создаем транзакцию, если есть transaction_id
                    if (isset($input['transaction_id'])) {
                        $this->model->createTransaction([
                            'order_id' => $result['order_id'],
                            'transaction_id' => $input['transaction_id'],
                            'amount' => $input['total_amount'],
                            'payment_system' => $input['payment_system'] ?? 'manual',
                            'payment_details' => $input['payment_details'] ?? []
                        ]);
                    }
                    
                    // Если это услуга, отправляем email
                    if ($productDetails['product']['product_type'] === 'service') {
                        $this->sendServiceEmail(
                            $input['message'] ?? '',
                            $input['first_name'] . ' ' . $input['last_name'],
                            $input['email'],
                            $productDetails['product']['name']
                        );
                    }
                    
                    // Если это трансляция, генерируем доступ
                    if ($productDetails['product']['product_type'] === 'stream') {
                        $this->model->grantStreamAccess($userId, $result['order_id']);
                    }
                    
                    return [
                        'success' => true,
                        'order_id' => $result['order_id'],
                        'order_number' => $result['order_number'],
                        'message' => 'Order created successfully'
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => $result['message'] ?? 'Failed to create order'
                    ];
                }
            } catch(\Exception $e) {
                Log::error("Error creating order: " . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage()
                ];
            }
        }
        
        return [
            'success' => false,
            'message' => 'Invalid request method'
        ];
    }

    private function sendServiceEmail($message, $fromName, $fromEmail, $serviceName) {
        try {
            $email = new Email($fromEmail, $fromEmail);
            $email->prepare(
                "New Service Order: {$serviceName}",
                SERVICE_MANAGER_EMAIL,
                'MediaHub Service Manager',
                $this->getServiceEmailBody($message, $fromName, $fromEmail, $serviceName)
            );
            $email->send();
        } catch(\Exception $e) {
            Log::error("Error sending service email: " . $e->getMessage());
        }
    }

    private function getServiceEmailBody($message, $fromName, $fromEmail, $serviceName) {
        return "
        <h2>New Service Order</h2>
        <p><strong>Service:</strong> {$serviceName}</p>
        <p><strong>Customer:</strong> {$fromName}</p>
        <p><strong>Email:</strong> {$fromEmail}</p>
        <p><strong>Message:</strong></p>
        <p>{$message}</p>
        ";
    }
    
    public function updatePaymentStatus() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return [
                    'success' => false,
                    'message' => 'Invalid request method'
                ];
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['order_id']) || !isset($input['status'])) {
                return [
                    'success' => false,
                    'message' => 'Missing required parameters'
                ];
            }
            
            $orderId = $input['order_id'];
            $status = $input['status'];
            
            $result = $this->model->updatePaymentStatus($orderId, $status);
            
            if ($result) {
                // Если платеж успешен, обновляем транзакцию
                if ($status === 'paid' && isset($input['transaction_id'])) {
                    $this->model->updateTransactionStatus($input['transaction_id'], 'completed');
                }
                
                return [
                    'success' => true,
                    'message' => 'Payment status updated successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update payment status'
                ];
            }
        } catch(\Exception $e) {
            Log::error("Error updating payment status: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server Error'
            ];
        }
    }

    public function updateOrderStatus() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return [
                    'success' => false,
                    'message' => 'Invalid request method'
                ];
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['order_id']) || !isset($input['status'])) {
                return [
                    'success' => false,
                    'message' => 'Missing required parameters'
                ];
            }
            
            $result = $this->model->updateOrderStatus(
                $input['order_id'],
                $input['status'],
                $input['comment'] ?? null
            );
            
            return [
                'success' => $result,
                'message' => $result ? 'Order status updated' : 'Failed to update order status'
            ];
        } catch(\Exception $e) {
            Log::error("Error updating order status: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server Error'
            ];
        }
    }
}
?>