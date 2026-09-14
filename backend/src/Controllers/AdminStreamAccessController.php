<?php
namespace Konektem\Controllers;

require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\OrderModel;
use Konektem\Utils\Log;

class AdminStreamAccessController {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new OrderModel();
    }
    
    /**
     * Выдать доступ к трансляции
     * POST /api/admin/stream/grant-access
     * Body: { "user_id": 2, "product_id": 10, "days": 30 }
     */
    public function grantAccess() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return [
                    'success' => false,
                    'message' => 'Invalid request method'
                ];
            }
            
            // Проверка авторизации админа
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                return [
                    'success' => false,
                    'message' => 'Unauthorized'
                ];
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                $input = $_POST;
            }
            
            $userId = $input['user_id'] ?? null;
            $productId = $input['product_id'] ?? null;
            $days = $input['days'] ?? 30;
            $adminId = $_SESSION['user_id'];
            
            if (!$userId || !$productId) {
                return [
                    'success' => false,
                    'message' => 'Missing required parameters: user_id and product_id'
                ];
            }
            
            if ($days < 1 || $days > 365) {
                return [
                    'success' => false,
                    'message' => 'Invalid days value. Must be between 1 and 365'
                ];
            }
            
            $result = $this->orderModel->grantAdminStreamAccess($userId, $productId, $adminId, $days);
            return $result;
            
        } catch (\Exception $e) {
            Log::error("Error in grantAccess: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Проверить доступ к трансляции
     * GET /api/stream/check-access?user_id=2&product_id=10
     */
    public function checkAccess() {
        try {
            $userId = $_GET['user_id'] ?? null;
            $productId = $_GET['product_id'] ?? null;
            
            if (!$userId || !$productId) {
                return [
                    'success' => false,
                    'message' => 'Missing required parameters'
                ];
            }
            
            $result = $this->orderModel->checkStreamAccess($userId, $productId);
            return $result;
            
        } catch (\Exception $e) {
            Log::error("Error in checkAccess: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    /**
     * Отозвать доступ к трансляции
     * POST /api/admin/stream/revoke-access
     * Body: { "user_id": 2, "product_id": 10 }
     */
    public function revokeAccess() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return [
                    'success' => false,
                    'message' => 'Invalid request method'
                ];
            }
            
            // Проверка авторизации админа
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                return [
                    'success' => false,
                    'message' => 'Unauthorized'
                ];
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                $input = $_POST;
            }
            
            $userId = $input['user_id'] ?? null;
            $productId = $input['product_id'] ?? null;
            $adminId = $_SESSION['user_id'];
            
            if (!$userId || !$productId) {
                return [
                    'success' => false,
                    'message' => 'Missing required parameters'
                ];
            }
            
            $result = $this->orderModel->revokeStreamAccess($userId, $productId, $adminId);
            return $result;
            
        } catch (\Exception $e) {
            Log::error("Error in revokeAccess: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    /**
     * Получить список пользователей с доступом к трансляции
     */
    public function getUsersWithAccess($productId = null) {
        try {
            if (!$productId && isset($_GET['product_id'])) {
                $productId = $_GET['product_id'];
            }
            
            if (!$productId) {
                return [
                    'success' => false,
                    'message' => 'Product ID is required'
                ];
            }
            
            $stmt = $this->orderModel->pdo->prepare("
                SELECT 
                    u.id,
                    u.email,
                    u.name,
                    u.first_name,
                    u.last_name,
                    sa.access_token,
                    sa.expires_at,
                    sa.is_active,
                    sa.created_at as access_granted_at,
                    o.order_number,
                    o.payment_date
                FROM stream_access sa
                JOIN order_items oi ON sa.order_item_id = oi.id
                JOIN orders o ON oi.order_id = o.id
                JOIN users u ON sa.user_id = u.id
                WHERE oi.product_id = ?
                ORDER BY sa.created_at DESC
            ");
            $stmt->execute([$productId]);
            $users = $stmt->fetchAll();
            
            return [
                'success' => true,
                'data' => $users,
                'count' => count($users)
            ];
            
        } catch (\Exception $e) {
            Log::error("Error getting users with access: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
}
?>