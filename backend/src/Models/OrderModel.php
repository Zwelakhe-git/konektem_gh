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
            SELECT 
                o.*
            FROM orders o 
            ORDER BY o.order_date DESC
        ");
        $oiStmt = $this->pdo->prepare("SELECT
            oi.quantity,
            oi.price_at_time,
            oi.subtotal,
            p.name as product_name,
            p.product_type 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $orders = $stmt->fetchAll();
        $finalOrders = array_map(function($order) use ($oiStmt){
            $oiStmt->execute([$order['id']]);
            $order['items'] = $oiStmt->fetchAll();
            return $order;
        }, $orders);
        return $finalOrders;
    }

    public function getOrdersByUser($userId) {
        /*[
            'items' => [
                'qty' => 1,
                'price_at_time' => 2,
                'subtotal' => 20,
                'product_name' => '',
                'product_type' => 'type'
            ]
        ];*/
        $stmt = $this->pdo->prepare("
            SELECT 
                o.*
            FROM orders o 
            WHERE o.user_id = ?
            ORDER BY o.order_date DESC
        ");
        $oiStmt = $this->pdo->prepare("SELECT
            oi.quantity,
            oi.price_at_time,
            oi.subtotal,
            oi.product_id,
            p.name as product_name,
            p.product_type 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll();
        $finalOrders = array_map(function($order) use ($oiStmt){
            $oiStmt->execute([$order['id']]);
            $order['items'] = $oiStmt->fetchAll();
            foreach($order['items'] as &$item){
                $productDetails = $this->getProductDetails($item['product_id']);
                $item['image_url'] = $productDetails['details']['image_url'] ?: '';
            }
            return $order;
        }, $orders);
        return $finalOrders;
    }

    public function getOrderById($orderId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                o.*
            FROM orders o 
            WHERE o.id = ?
            ORDER BY o.order_date DESC
        ");
        $oiStmt = $this->pdo->prepare("SELECT
            oi.quantity,
            oi.price_at_time,
            oi.subtotal,
            oi.product_id,
            p.name as product_name,
            p.product_type 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch() ?: [];

        if(empty($order)){
            return [];
        }
        $oiStmt->execute([$order['id']]);
        $order['items'] = $oiStmt->fetchAll();
        foreach($order['items'] as &$item){
            $productDetails = $this->getProductDetails($item['product_id']);
            $item['image_url'] = $productDetails['details']['image_url'] ?: '';
        }
        //Log::info(print_r($order, true));
        return $order;
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT id, email, first_name, last_name, phone FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function createOrGetUser($data) {
        try {
            // Проверяем существующего пользователя
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$data['email']]);
            $user = $stmt->fetch();
            
            if ($user) {
                return $user['id'];
            }
            
            // Создаем нового пользователя (гостевого)
            $stmt = $this->pdo->prepare("
                INSERT INTO users (email, first_name, last_name, phone, is_guest, created_at) 
                VALUES (?, ?, ?, ?, 1, NOW())
            ");
            $stmt->execute([
                $data['email'],
                $data['first_name'] ?? '',
                $data['last_name'] ?? '',
                $data['phone'] ?? null
            ]);
            
            return $this->pdo->lastInsertId();
        } catch(\PDOException $e) {
            Log::error("Error creating/getting user: " . $e->getMessage());
            return false;
        }
    }
    
    public function createOrder($orderData) {
        try {
            $this->pdo->beginTransaction();
            
            // Создаем заказ
            $orderNumber = 'ORD-' . strtoupper(uniqid());
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (
                    user_id, 
                    order_number, 
                    total_amount, 
                    order_status, 
                    payment_status, 
                    order_date,
                    ip_address,
                    user_agent
                ) VALUES (?, ?, ?, 'pending', 'pending', NOW(), ?, ?)
            ");
            
            $stmt->execute([
                $orderData['user_id'],
                $orderNumber,
                $orderData['total_amount'],
                $orderData['ip_address'] ?? null,
                $orderData['user_agent'] ?? null
            ]);
            
            $orderId = $this->pdo->lastInsertId();
            
            // Создаем позиции заказа
            foreach ($orderData['items'] as $item) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO order_items (
                        order_id, 
                        product_id, 
                        quantity, 
                        price_at_time, 
                        discount, 
                        subtotal
                    ) VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                $subtotal = $item['price_at_time'] * $item['quantity'] - ($item['discount'] ?? 0);
                $stmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'] ?? 1,
                    $item['price_at_time'],
                    $item['discount'] ?? 0,
                    $subtotal
                ]);
            }
            
            $this->pdo->commit();
            
            return [
                'success' => true,
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'message' => 'Order created successfully'
            ];
        } catch(\PDOException $e) {
            $this->pdo->rollBack();
            Log::error("Error creating order: {$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ];
        }
    }

    public function updatePaymentMethod($orderId, $method){
        try {
            $stmt = $this->pdo->prepare("UPDATE orders SET payment_method = ? WHERE id = ?");
            return $stmt->execute([$method, $orderId]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }

    public function updateOrderStatus($orderId, $status, $comment = null) {
        try {
            // Получаем текущий статус для истории
            $stmt = $this->pdo->prepare("SELECT order_status FROM orders WHERE id = ?");
            $stmt->execute([$orderId]);
            $oldStatus = $stmt->fetchColumn();
            
            // Обновляем статус заказа
            $stmt = $this->pdo->prepare("UPDATE orders SET order_status = ?, updated_at = NOW() WHERE id = ?");
            $result = $stmt->execute([$status, $orderId]);
            
            if ($result && $oldStatus !== $status) {
                // Сохраняем в историю
                $stmt = $this->pdo->prepare("
                    INSERT INTO order_status_history (order_id, old_status, new_status, comment, created_at) 
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$orderId, $oldStatus, $status, $comment]);
            }
            
            return $result;
        } catch(\Exception $e) {
            Log::error("Error updating order status: " . $e->getMessage());
            return false;
        }
    }
    
    public function updatePaymentStatus($orderId, $status) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE orders 
                SET payment_status = ?, payment_date = NOW(), updated_at = NOW() 
                WHERE id = ?
            ");
            return $stmt->execute([$status, $orderId]);
        } catch(\Exception $e) {
            Log::error("Error updating payment status: " . $e->getMessage());
            return false;
        }
    }

    public function createTransaction($transactionData) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO transactions (
                    order_id,
                    transaction_id,
                    amount,
                    currency,
                    status,
                    payment_system,
                    payment_details,
                    created_at
                ) VALUES (?, ?, ?, ?, 'pending', ?, ?, NOW())
            ");
            
            return $stmt->execute([
                $transactionData['order_id'],
                $transactionData['transaction_id'],
                $transactionData['amount'],
                $transactionData['currency'] ?? 'USD',
                $transactionData['payment_system'],
                json_encode($transactionData['payment_details'] ?? [])
            ]);
        } catch(\Exception $e) {
            Log::error("Error creating transaction: " . $e->getMessage());
            return false;
        }
    }

    public function updateTransactionStatus($transactionId, $status) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE transactions 
                SET status = ?, updated_at = NOW() 
                WHERE transaction_id = ?
            ");
            return $stmt->execute([$status, $transactionId]);
        } catch(\Exception $e) {
            Log::error("Error updating transaction status: " . $e->getMessage());
            return false;
        }
    }

    public function getProductDetails($productId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch();
            
            if (!$product) {
                return null;
            }
            
            // Получаем детали в зависимости от типа
            $details = [
                'image_url' => ''
            ];
            switch ($product['product_type']) {
                case 'event':
                    $stmt = $this->pdo->prepare("SELECT e.*, i.url AS image_url FROM events e JOIN images i ON e.image_id = i.id WHERE e.product_id = ?");
                    $stmt->execute([$productId]);
                    $details = [...$details, ...$stmt->fetch()];
                    break;
                case 'service':
                    $stmt = $this->pdo->prepare("SELECT s.*, i.url AS image_url FROM services s JOIN images i ON s.serviceImg = i.id WHERE s.product_id = ?");
                    $stmt->execute([$productId]);
                    $details = [...$details, ...$stmt->fetch()];
                    break;
                case 'stream':
                    $stmt = $this->pdo->prepare("SELECT * FROM stream_details WHERE product_id = ?");
                    $stmt->execute([$productId]);
                    $details = [...$details, ...$stmt->fetch()];
                    $details['image_url'] = $details['cover_image'];
                    break;
                case 'premium_subscription':
                    $stmt = $this->pdo->prepare("SELECT * FROM subscription_plans WHERE product_id = ?");
                    $stmt->execute([$productId]);
                    $details = [...$details, ...$stmt->fetch()];
                    break;
                default:
                    Log::warn("cant fetch product details of p_type: {$product['product_type']}");
            }
            if(!isset($details['image_url'])){
                Log::warn("product {$product['product_type']} has no image");
            }
            return [
                'product' => $product,
                'details' => $details
            ];
        } catch(\Exception $e) {
            Log::error("Error getting product details: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Выдать временный доступ к трансляции для админа
     * 
     * @param int $userId ID пользователя, которому даем доступ
     * @param int $productId ID продукта (трансляции)
     * @param int $adminId ID администратора
     * @param int $days Количество дней доступа
     * @param float $amount Сумма (опционально)
     * @return array Результат операции
     */
    public function grantAdminStreamAccess($userId, $productId, $adminId, $days = 30, $amount = 10.00) {
        try {
            // Проверяем существование пользователя
            $userExists = $this->checkUserExists($userId);
            if (!$userExists) {
                Log::error("User not found: ID $userId");
                return [
                    'success' => false,
                    'message' => 'User not found'
                ];
            }
            
            // Проверяем существование администратора
            $adminExists = $this->checkUserExists($adminId);
            if (!$adminExists) {
                Log::error("Admin not found: ID $adminId");
                return [
                    'success' => false,
                    'message' => 'Admin not found'
                ];
            }
            
            // Проверяем существование продукта
            $product = $this->getProductDetails($productId);
            if (!$product) {
                Log::error("Stream product not found: ID $productId");
                return [
                    'success' => false,
                    'message' => 'Stream product not found'
                ];
            }
            
            if ($product['product']['product_type'] !== 'stream') {
                Log::error("Product is not a stream: ID $productId");
                return [
                    'success' => false,
                    'message' => 'Product is not a stream'
                ];
            }
            
            // Проверяем, нет ли уже активного доступа
            $existingAccess = $this->checkExistingAccess($userId, $productId);
            if ($existingAccess) {
                // Обновляем существующий доступ
                return $this->renewStreamAccess($existingAccess['id'], $userId, $days, $adminId);
            }
            
            // Создаем новый доступ
            return $this->createNewStreamAccess($userId, $productId, $adminId, $days, $amount);
            
        } catch (\Exception $e) {
            Log::error("Error in grantAdminStreamAccess: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Проверка существования пользователя
     */
    private function checkUserExists($userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchColumn() > 0;
        } catch (\Exception $e) {
            Log::error("Error checking user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Проверка существующего доступа
     */
    private function checkExistingAccess($userId, $productId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT sa.id, sa.expires_at, sa.access_token
                FROM stream_access sa
                JOIN order_items oi ON sa.order_item_id = oi.id
                JOIN orders o ON oi.order_id = o.id
                WHERE sa.user_id = ? 
                  AND oi.product_id = ? 
                  AND sa.is_active = 1
                  AND o.payment_status = 'paid'
                  AND (sa.expires_at IS NULL OR sa.expires_at > NOW())
                ORDER BY sa.created_at DESC
                LIMIT 1
            ");
            $stmt->execute([$userId, $productId]);
            return $stmt->fetch();
        } catch (\Exception $e) {
            Log::error("Error checking existing access: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Обновление существующего доступа
     */
    private function renewStreamAccess($accessId, $userId, $days, $adminId) {
        try {
            $newExpiresAt = date('Y-m-d H:i:s', strtotime("+{$days} days"));
            
            $stmt = $this->pdo->prepare("
                UPDATE stream_access 
                SET expires_at = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$newExpiresAt, $accessId]);
            
            // Логируем действие
            $this->logAdminAction($adminId, 'renew_stream_access', [
                'user_id' => $userId,
                'access_id' => $accessId,
                'days' => $days,
                'new_expires_at' => $newExpiresAt
            ]);
            
            return [
                'success' => true,
                'message' => 'Access renewed successfully',
                'action' => 'renewed',
                'access_id' => $accessId,
                'expires_at' => $newExpiresAt,
                'access_token' => null // Возвращаем токен, если нужно
            ];
            
        } catch (\Exception $e) {
            Log::error("Error renewing access: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to renew access: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Создание нового доступа к трансляции
     */
    private function createNewStreamAccess($userId, $productId, $adminId, $days, $amount) {
        try {
            $this->pdo->beginTransaction();
            
            // Получаем цену продукта
            $stmt = $this->pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$productId]);
            $productPrice = $stmt->fetchColumn();
            
            $price = $productPrice ?: $amount;
            
            // Создаем заказ
            $orderNumber = 'ADMIN_' . date('YmdHis') . '_' . rand(1000, 9999);
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (
                    user_id, 
                    order_number, 
                    total_amount, 
                    order_status, 
                    payment_status, 
                    payment_method,
                    order_date,
                    payment_date,
                    created_at,
                    updated_at
                ) VALUES (?, ?, ?, 'completed', 'paid', 'admin_grant', NOW(), NOW(), NOW(), NOW())
            ");
            $stmt->execute([$userId, $orderNumber, $price]);
            $orderId = $this->pdo->lastInsertId();
            
            // Создаем позицию заказа
            $stmt = $this->pdo->prepare("
                INSERT INTO order_items (
                    order_id, 
                    product_id, 
                    quantity, 
                    price_at_time, 
                    discount, 
                    subtotal,
                    created_at
                ) VALUES (?, ?, 1, ?, 0.00, ?, NOW())
            ");
            $stmt->execute([$orderId, $productId, $price, $price]);
            $orderItemId = $this->pdo->lastInsertId();
            
            // Создаем доступ к трансляции
            $accessToken = 'ACCESS_' . md5($userId . $productId . time() . rand(1000, 9999));
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$days} days"));
            
            $stmt = $this->pdo->prepare("
                INSERT INTO stream_access (
                    user_id, 
                    order_item_id, 
                    access_token, 
                    expires_at, 
                    is_active, 
                    created_at,
                    updated_at
                ) VALUES (?, ?, ?, ?, 1, NOW(), NOW())
            ");
            $stmt->execute([$userId, $orderItemId, $accessToken, $expiresAt]);
            $accessId = $this->pdo->lastInsertId();
            
            $this->pdo->commit();
            
            // Логируем действие
            $this->logAdminAction($adminId, 'grant_stream_access', [
                'user_id' => $userId,
                'product_id' => $productId,
                'order_id' => $orderId,
                'access_id' => $accessId,
                'days' => $days,
                'expires_at' => $expiresAt
            ]);
            
            // Если есть Redis, кешируем доступ
            if ($this->redisClient) {
                $this->cacheStreamAccess($userId, $productId, $accessToken, $expiresAt);
            }
            
            return [
                'success' => true,
                'message' => 'Access granted successfully',
                'action' => 'created',
                'order_id' => $orderId,
                'access_id' => $accessId,
                'access_token' => $accessToken,
                'expires_at' => $expiresAt,
                'days' => $days
            ];
            
        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            Log::error("Error creating new stream access: " . $e->getMessage() . " file {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Failed to grant access: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Кеширование доступа в Redis
     */
    private function cacheStreamAccess($userId, $productId, $accessToken, $expiresAt) {
        try {
            if (!$this->redisClient) {
                return false;
            }
            
            $key = "stream_access:{$userId}";
            $data = [
                'user_id' => $userId,
                'product_id' => $productId,
                'access_token' => $accessToken,
                'expires_at' => $expiresAt,
                'is_active' => 1
            ];
            
            $ttl = strtotime($expiresAt) - time();
            if ($ttl > 0) {
                $this->redisClient->set($key, json_encode($data));
                $this->redisClient->expire($key, $ttl);
                Log::info("Stream access cached in Redis for user {$userId}");
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error("Error caching stream access: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Логирование действия администратора
     */
    private function logAdminAction($adminId, $action, $data) {
        try {
            // Проверяем существование таблицы admin_logs
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM information_schema.tables 
                WHERE table_schema = DATABASE() AND table_name = 'admin_logs'
            ");
            $stmt->execute();
            $tableExists = $stmt->fetchColumn() > 0;
            
            if (!$tableExists) {
                // Создаем таблицу, если её нет
                $this->createAdminLogsTable();
            }
            
            $stmt = $this->pdo->prepare("
                INSERT INTO admin_logs (admin_id, action, data, created_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->execute([$adminId, $action, json_encode($data, JSON_UNESCAPED_UNICODE)]);
            
        } catch (\Exception $e) {
            // Просто логируем ошибку, не прерывая основную операцию
            Log::error("Error logging admin action: " . $e->getMessage());
        }
    }
    
    /**
     * Создание таблицы admin_logs если её нет
     */
    private function createAdminLogsTable() {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS `admin_logs` (
                    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
                    `admin_id` bigint UNSIGNED NOT NULL,
                    `action` varchar(100) NOT NULL,
                    `data` json DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `admin_id` (`admin_id`),
                    KEY `action` (`action`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            $this->pdo->exec($sql);
            Log::info("Admin logs table created");
        } catch (\Exception $e) {
            Log::error("Error creating admin_logs table: " . $e->getMessage());
        }
    }
    
    /**
     * Проверка доступа к трансляции
     */
    public function checkStreamAccess($userId, $productId) {
        try {
            // Сначала проверяем Redis
            if ($this->redisClient) {
                $key = "stream_access:{$userId}:{$productId}";
                $cached = $this->redisClient->get($key);
                if ($cached) {
                    $data = json_decode($cached, true);
                    if ($data && $data['is_active'] && strtotime($data['expires_at']) > time()) {
                        return [
                            'has_access' => true,
                            'access_token' => $data['access_token'],
                            'expires_at' => $data['expires_at'],
                            'source' => 'cache'
                        ];
                    }
                }
            }
            
            // Проверяем в базе данных
            $stmt = $this->pdo->prepare("
                SELECT 
                    sa.id,
                    sa.access_token,
                    sa.expires_at,
                    sa.is_active,
                    oi.product_id,
                    o.order_status,
                    o.payment_status
                FROM stream_access sa
                JOIN order_items oi ON sa.order_item_id = oi.id
                JOIN orders o ON oi.order_id = o.id
                WHERE sa.user_id = ? 
                  AND oi.product_id = ?
                  AND sa.is_active = 1
                  AND o.payment_status = 'paid'
                  AND o.order_status != 'cancelled'
                  AND (sa.expires_at IS NULL OR sa.expires_at > NOW())
                ORDER BY sa.created_at DESC
                LIMIT 1
            ");
            $stmt->execute([$userId, $productId]);
            $result = $stmt->fetch();
            
            if ($result) {
                // Обновляем кеш
                if ($this->redisClient) {
                    $this->cacheStreamAccess($userId, $productId, $result['access_token'], $result['expires_at']);
                }
                
                return [
                    'has_access' => true,
                    'access_token' => $result['access_token'],
                    'expires_at' => $result['expires_at'],
                    'source' => 'database'
                ];
            }
            
            return [
                'has_access' => false,
                'message' => 'No active access found'
            ];
            
        } catch (\Exception $e) {
            Log::error("Error checking stream access: " . $e->getMessage());
            return [
                'has_access' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    /**
     * Отзыв доступа к трансляции
     */
    public function revokeStreamAccess($userId, $productId, $adminId) {
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("
                UPDATE stream_access sa
                JOIN order_items oi ON sa.order_item_id = oi.id
                SET sa.is_active = 0, sa.updated_at = NOW()
                WHERE sa.user_id = ? AND oi.product_id = ? AND sa.is_active = 1
            ");
            $stmt->execute([$userId, $productId]);
            
            $affectedRows = $stmt->rowCount();
            
            if ($affectedRows > 0) {
                // Удаляем из Redis
                if ($this->redisClient) {
                    $key = "stream_access:{$userId}:{$productId}";
                    $this->redisClient->del($key);
                }
                
                $this->pdo->commit();
                
                // Логируем
                $this->logAdminAction($adminId, 'revoke_stream_access', [
                    'user_id' => $userId,
                    'product_id' => $productId
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Access revoked successfully'
                ];
            }
            
            $this->pdo->rollBack();
            return [
                'success' => false,
                'message' => 'No active access found to revoke'
            ];
            
        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            Log::error("Error revoking access: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to revoke access: ' . $e->getMessage()
            ];
        }
    }

    // new methods for granting stream access to user and send an email
    /**
     * Выдать доступ к трансляции после успешной оплаты
     * 
     * @param int $userId ID пользователя
     * @param int $orderItemId ID позиции заказа
     * @return array Результат операции
     */
    public function grantStreamAccess($userId, $orderItemId) {
        try {
            // Проверяем, существует ли уже доступ для этой позиции заказа
            $stmt = $this->pdo->prepare("
                SELECT id, access_token, expires_at 
                FROM stream_access
                WHERE order_item_id = ? AND user_id = ? AND is_active = 1
            ");
            $stmt->execute([$orderItemId, $userId]);
            $existing = $stmt->fetch();
            
            if ($existing) {
                // Если доступ уже есть, обновляем срок
                $newExpiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
                $stmt = $this->pdo->prepare("
                    UPDATE stream_access 
                    SET expires_at = ?
                    WHERE id = ?
                ");
                $stmt->execute([$newExpiresAt, $existing['id']]);
                
                return [
                    'success' => true,
                    'access_token' => $existing['access_token'],
                    'expires_at' => $newExpiresAt,
                    'action' => 'renewed'
                ];
            }
            
            // Создаем новый доступ
            $accessToken = 'STREAM_' . md5($userId . $orderItemId . time() . rand(1000, 9999));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            $stmt = $this->pdo->prepare("
                INSERT INTO stream_access (
                    user_id, 
                    order_item_id, 
                    access_token, 
                    expires_at, 
                    is_active, 
                    created_at
                ) VALUES (?, ?, ?, ?, 1, NOW())
            ");
            $stmt->execute([$userId, $orderItemId, $accessToken, $expiresAt]);
            $accessId = $this->pdo->lastInsertId();
            
            // Кешируем доступ в Redis
            if ($this->redisClient) {
                $this->cacheStreamAccess($userId, $orderItemId, $accessToken, $expiresAt);
            }
            
            // Получаем информацию о пользователе и продукте для email
            $userInfo = $this->getUserInfo($userId);
            $productInfo = $this->getProductInfoByOrderItem($orderItemId);
            
            // Отправляем email с токеном доступа
            if ($userInfo && $productInfo) {
                $this->sendStreamAccessEmail($userInfo, $productInfo, $accessToken, $expiresAt);
            }
            
            Log::info("Stream access granted: user_id={$userId}, order_item_id={$orderItemId}, access_id={$accessId}");
            
            return [
                'success' => true,
                'access_token' => $accessToken,
                'expires_at' => $expiresAt,
                'access_id' => $accessId,
                'action' => 'created'
            ];
            
        } catch (\Exception $e) {
            Log::error("Error granting stream access: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to grant stream access: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Получение информации о пользователе
     */
    private function getUserInfo($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, email, name 
                FROM users 
                WHERE id = ?
            ");
            $stmt->execute([$userId]);
            return $stmt->fetch();
        } catch (\Exception $e) {
            Log::error("Error getting user info: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Получение информации о продукте по позиции заказа
     */
    private function getProductInfoByOrderItem($orderItemId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    p.id as product_id,
                    p.name as product_name,
                    p.product_type,
                    sd.stream_url,
                    sd.start_time,
                    sd.end_time,
                    oi.order_id
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                LEFT JOIN stream_details sd ON p.id = sd.product_id
                WHERE oi.id = ?
            ");
            $stmt->execute([$orderItemId]);
            return $stmt->fetch();
        } catch (\Exception $e) {
            Log::error("Error getting product info: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Отправка email с токеном доступа к трансляции
     */
    private function sendStreamAccessEmail($userInfo, $productInfo, $accessToken, $expiresAt) {
        try {
            $email = new \Konektem\Models\EmailModel();
            
            $subject = "Votre accès à la diffusion en direct - " . $productInfo['product_name'];
            
            $toEmail = $userInfo['email'];
            $toName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
            if (empty(trim($toName))) {
                $toName = $userInfo['name'] ?? $userInfo['email'];
            }
            
            // Генерируем URL для доступа к трансляции
            $streamUrl = $this->generateStreamAccessUrl($accessToken, $productInfo);
            
            // HTML тело письма
            $bodyHtml = $this->getStreamAccessEmailHtml($toName, $productInfo, $accessToken, $expiresAt, $streamUrl);
            
            // Текстовое тело письма
            $bodyText = $this->getStreamAccessEmailText($toName, $productInfo, $accessToken, $expiresAt, $streamUrl);
            
            $result = $email->prepare($subject, $toEmail, $toName, $bodyHtml, $bodyText)->send();
            
            if ($result['status'] === 'success') {
                Log::info("Stream access email sent to: {$toEmail}");
            } else {
                Log::error("Failed to send stream access email: " . ($result['error'] ?? 'Unknown error'));
            }
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error("Error sending stream access email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Генерация URL для доступа к трансляции
     */
    private function generateStreamAccessUrl($accessToken, $productInfo) {
        $baseUrl = $_ENV['ANT_MEDIA_BASE_URL'] ?? 'https://try.antmedia.io/zwelakhemzwet/play.html';
        $streamKey = $productInfo['stream_url'] ?? '';
        
        if (empty($streamKey)) {
            // Если нет stream_url, используем product_id
            $streamKey = 'stream_' . $productInfo['product_id'];
        }
        
        // Добавляем токен доступа как параметр
        $url = $baseUrl . (strpos($baseUrl, '?') === false ? '?' : '&') . 'id=' . urlencode($streamKey);
        $url .= '&token=' . urlencode($accessToken);
        
        return $url;
    }

    /**
     * HTML шаблон для email с доступом к трансляции
     */
    private function getStreamAccessEmailHtml($userName, $productInfo, $accessToken, $expiresAt, $streamUrl) {
        $formattedDate = date('d.m.Y H:i', strtotime($expiresAt));
        $productName = htmlspecialchars($productInfo['product_name']);
        $startTime = isset($productInfo['start_time']) ? date('d.m.Y H:i', strtotime($productInfo['start_time'])) : 'Non spécifiée';
        // paste stream payment email
        
        ob_start();
        require_once TEMPLATES_DIR . '/Components/StreamPaymentEmail.php';
        $emailBody = ob_get_clean();
        return $emailBody;
    }

    /**
     * Текстовый вариант письма для доступа к трансляции
     */
    private function getStreamAccessEmailText($userName, $productInfo, $accessToken, $expiresAt, $streamUrl) {
        $formattedDate = date('d.m.Y H:i', strtotime($expiresAt));
        $productName = $productInfo['product_name'];
        $startTime = isset($productInfo['start_time']) ? date('d.m.Y H:i', strtotime($productInfo['start_time'])) : 'Non spécifiée';
        
        return "
Bonjour {$userName},

Votre accès à la diffusion en direct a été activé avec succès !

📺 Diffusion : {$productName}
🕐 Date et heure : {$startTime}
⏳ Validité : Jusqu'au {$formattedDate}

🔑 Votre token d'accès personnel :
{$accessToken}

🎥 Lien d'accès :
{$streamUrl}

Ce token est unique et vous permet d'accéder à la diffusion. Ne le partagez pas avec d'autres personnes.

---
© " . date('Y') . " Konektem - Tous droits réservés
Cet email a été envoyé automatiquement. Merci de ne pas y répondre.
        ";
    }

    /**
     * Отправка email при создании заказа
     */
    public function sendOrderConfirmationEmail($orderId) {
        try {
            Log::info("sending order confirmation email");
            // Получаем информацию о заказе
            $orderInfo = $this->getOrderWithDetails($orderId);
            if (!$orderInfo) {
                Log::error("Order not found for email: {$orderId}");
                return false;
            }
            
            $userInfo = $this->getUserInfo($orderInfo['user_id']);
            if (!$userInfo) {
                Log::error("User not found for order: {$orderId}");
                return false;
            }
            
            $email = new EmailModel();
            
            $subject = "📋 Confirmation de votre commande #" . $orderInfo['order_number'];
            $toEmail = $userInfo['email'];
            $toName = ($userInfo['name'] ?? $userInfo['first_name']) . ' ' . ($userInfo['last_name'] ?? '');
            if (empty(trim($toName))) {
                $toName = $userInfo['name'] ?? $userInfo['email'];
            }
            
            $bodyHtml = $this->getOrderConfirmationHtml($toName, $orderInfo);
            $bodyText = $this->getOrderConfirmationText($toName, $orderInfo);
            
            $result = $email->prepare($subject, $toEmail, $toName, $bodyHtml, $bodyText)->send();
            
            if ($result['status'] === 'success') {
                Log::info("Order confirmation email sent to: {$toEmail}");
            } else {
                Log::error("Failed to send order confirmation email: " . ($result['error'] ?? 'Unknown error'));
            }
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error("Error sending order confirmation email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Получение заказа с деталями для email
     */
    private function getOrderWithDetails($orderId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    o.*,
                    GROUP_CONCAT(
                        CONCAT(p.name, ' x', oi.quantity, ' = ', oi.subtotal, ' ', o.currency) 
                        SEPARATOR '\n'
                    ) as items_list,
                    COUNT(oi.id) as items_count
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE o.id = ?
                GROUP BY o.id
            ");
            $stmt->execute([$orderId]);
            return $stmt->fetch();
        } catch (\Exception $e) {
            Log::error("Error getting order with details: " . $e->getMessage());
            return null;
        }
    }

    public function updateOrderPaymentToken($orderId, $token){
        try {
            $stmt = $this->pdo->prepare("UPDATE orders SET payment_token = ? WHERE id = ?");
            return $stmt->execute([$token, $orderId]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }

    /**
     * HTML шаблон для подтверждения заказа
     */
    private function getOrderConfirmationHtml($userName, $orderInfo) {
        $orderNumber = htmlspecialchars($orderInfo['order_number']);
        $totalAmount = number_format($orderInfo['total_amount'], 2);
        $currency = $orderInfo['currency'] ?? 'USD';
        $orderDate = date('d.m.Y H:i', strtotime($orderInfo['order_date']));
        
        ob_start();
        ///var/www/html/konektem/frontend/src/Components/OrderConfirmationHtml.php
        require_once realpath(TEMPLATES_DIR . '/../Components/OrderConfirmationHtml.php');
        return ob_get_clean();
    }

    /**
     * Текстовый вариант подтверждения заказа
     */
    private function getOrderConfirmationText($userName, $orderInfo) {
        $orderNumber = $orderInfo['order_number'];
        $totalAmount = number_format($orderInfo['total_amount'], 2);
        $currency = $orderInfo['currency'] ?? 'USD';
        $orderDate = date('d.m.Y H:i', strtotime($orderInfo['order_date']));
        
        return "
Bonjour {$userName},

Votre commande a été confirmée avec succès !

📅 Date : {$orderDate}
🔢 Numéro de commande : #{$orderNumber}
📦 Articles : {$orderInfo['items_count']}

Total : {$totalAmount} {$currency}

---
© " . date('Y') . " Konektem - Tous droits réservés
        ";
    }
}
?>