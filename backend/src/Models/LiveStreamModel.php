<?php
namespace Konektem\Models;
use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();
class LiveStreamModel extends Database {
    public function __construct(){
        parent::__construct();
    }
    
    public function getAllStreams() {
        $stmt = $this->pdo->query("
            SELECT 
                p.*,
                sd.stream_url,
                sd.start_time,
                sd.end_time,
                sd.max_viewers
            FROM products p
            JOIN stream_details sd ON p.id = sd.product_id
            WHERE p.product_type = 'stream' AND p.is_active = 1
            ORDER BY sd.start_time DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function getStreamById($id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                p.*,
                sd.stream_url,
                sd.stream_key,
                sd.start_time,
                sd.end_time,
                sd.max_viewers
            FROM products p
            JOIN stream_details sd ON p.id = sd.product_id
            WHERE p.id = ? AND p.product_type = 'stream'
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getStreamByProductId($productId) {
        return $this->getStreamById($productId);
    }
    
    public function getStreamsByUser($userId) {
        // Получаем трансляции, на которые у пользователя есть доступ
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT
                p.*,
                sd.stream_url,
                sd.start_time,
                sd.end_time,
                sd.max_viewers,
                sa.access_token,
                sa.expires_at,
                sa.is_active as has_access
            FROM products p
            JOIN stream_details sd ON p.id = sd.product_id
            JOIN order_items oi ON oi.product_id = p.id
            JOIN orders o ON o.id = oi.order_id
            LEFT JOIN stream_access sa ON sa.order_item_id = oi.id AND sa.user_id = ?
            WHERE o.user_id = ? 
                AND o.payment_status = 'paid'
                AND p.product_type = 'stream'
                AND p.is_active = 1
            ORDER BY sd.start_time DESC
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll();
    }
    
    public function createStream($data) {
        try {
            $this->pdo->beginTransaction();
            
            // Сначала создаем продукт
            $stmt = $this->pdo->prepare("
                INSERT INTO products (name, description, price, product_type, is_active, created_at) 
                VALUES ('stream access', ?, ?, 'stream', 1, NOW())
            ");
            $stmt->execute([
                $data['description'] ?? '',
                $data['price'] ?? 0
            ]);
            
            $productId = $this->pdo->lastInsertId();
            
            // Создаем детали трансляции
            $columns = [ 
                    'stream_url', 
                    'start_time',
                    'stream_key'
                    ];
            if(isset($data['end_time']) && !empty($data['end_time'])){
                $columns['end_time'] = $data['end_time'];
            }
            if($data['max_viewers']){
                $columns['max_viewers'] = $data['max_viewers'];
            }
            
            $values = [$productId, ...array_map(fn($col) => $data[$col] ?? null, $columns)];
            $params = str_repeat('?,', count($columns)) . '?';
            $stmt = $this->pdo->prepare("
                INSERT INTO stream_details (product_id,". implode(',', $columns) .") VALUES ($params)
            ");
            
            $streamKey = $data['stream_key'] ?? $this->generateStreamKey();
            $stmt->execute($values);
            
            $this->pdo->commit();
            
            return [
                'success' => true,
                'product_id' => $productId,
                'stream_key' => $streamKey,
                'message' => 'Stream created successfully'
            ];
        } catch(\Exception $e) {
            $this->pdo->rollBack();
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }
    
    public function updateStream($id, $data) {
        try {
            $this->pdo->beginTransaction();
            // Обновляем продукт
            Log::info($id);
            $stmt = $this->pdo->prepare("
                UPDATE products 
                SET name = ?, description = ?, price = ?, updated_at = NOW()
                WHERE id = ? AND product_type = 'stream'
            ");
            $stmt->execute([
                $data['stream_title'],
                $data['description'] ?? '',
                $data['price'] ?? 0.0,
                $id
            ]);
            
            
            $columns = [ 
                    'stream_url', 
                    'start_time', 
                    ];
            if(isset($data['end_time']) && !empty($data['end_time'])){
                $columns['end_time'] = $data['end_time'];
            }
            if($data['max_viewers']){
                $columns['max_viewers'] = $data['max_viewers'];
            }
            
            $values = [...array_map(fn($col) => $data[$col] ?? null, $columns), (int)$id];
            
            $stmt = $this->pdo->prepare("
                UPDATE stream_details 
                SET ". implode(',', array_map(fn($col) => $col . '=?', $columns)) ." 
                WHERE product_id = ?
            ");
            $stmt->execute($values);
            if (isset($data['remove_cover_image']) && $data['remove_cover_image']) {
                $stmt = $this->pdo->prepare("UPDATE products SET cover_image = NULL WHERE id = ?");
                $stmt->execute([$id]);
            }

            if (isset($data['cover_image']) && $data['cover_image']) {
                //$uploadResult = $this->uploadCoverImage($data['cover_image'], $id);
                $stmt = $this->pdo->prepare("UPDATE products SET cover_image = ? WHERE id = ?");
                $stmt->execute([$data['cover_image'], $id]);
            }
            $this->pdo->commit();
            
            return [
                'success' => true,
                'message' => 'Stream successfully updated'
            ];
        } catch(\Exception $e) {
            $this->pdo->rollBack();
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function deleteStream($id) {
        try {
            // Каскадное удаление сработает благодаря FOREIGN KEY
            $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ? AND product_type = 'stream'");
            $stmt->execute([$id]);

            $stmt = $this->pdo->prepare("DELETE FROM stream_details WHERE product_id = ?");
            Log::info("deleting stream $id");
            $stmt->execute([$id]);
            return [
                'success' => true,
                'message' => 'Stream deleted successfully'
            ];
        } catch(\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Stream deleted successfully'
            ];
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Stream deleted successfully'
            ];
        }
    }
    
    public function generateStreamKey() {
        return bin2hex(random_bytes(10));
    }

    public function getStreamAccess($userId, $streamId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                sa.*,
                o.payment_status,
                o.order_status
            FROM stream_access sa
            JOIN order_items oi ON sa.order_item_id = oi.id
            JOIN orders o ON oi.order_id = o.id
            WHERE sa.user_id = ? AND oi.product_id = ?
            ORDER BY sa.created_at DESC
            LIMIT 1
        ");
        $stmt->execute([$userId, $streamId]);
        return $stmt->fetch();
    }

    public function grantStreamAccess($userId, $orderItemId) {
        try {
            $accessToken = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));
            
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
            
            $result = $stmt->execute([$userId, $orderItemId, $accessToken, $expiresAt]);
            
            if ($result) {
                return [
                    'success' => true,
                    'access_token' => $accessToken,
                    'expires_at' => $expiresAt
                ];
            }
            
            return ['success' => false];
        } catch(\Exception $e) {
            Log::error("Error granting stream access: " . $e->getMessage());
            return ['success' => false];
        }
    }

    public function revokeStreamAccess($accessToken) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE stream_access 
                SET is_active = 0, updated_at = NOW()
                WHERE access_token = ?
            ");
            return $stmt->execute([$accessToken]);
        } catch(\Exception $e) {
            Log::error("Error revoking stream access: " . $e->getMessage());
            return false;
        }
    }

    public function getActiveViewers($streamId) {
        try {
            if ($this->redisClient) {
                $key = "stream:{$streamId}:viewers";
                return $this->redisClient->scard($key);
            }
            
            // Fallback to database
            $stmt = $this->pdo->prepare("
                SELECT user_ids FROM livestream_viewers WHERE stream_id = ?
            ");
            $stmt->execute([$streamId]);
            $userIds = $stmt->fetchColumn();
            return count(explode(',', $userIds));
        } catch(\Exception $e) {
            Log::error("Error getting active viewers: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Проверка доступа к трансляции с использованием OrderModel
     */
    public function checkUserAccess($userId, $streamId) {
        try {
            $orderModel = new OrderModel();
            $access = $orderModel->checkStreamAccess($userId, $streamId);
            return $access;
        } catch (\Exception $e) {
            Log::error("Error in checkUserAccess: " . $e->getMessage());
            return [
                'has_access' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    /**
     * Получение ключа трансляции для пользователя
     */
    public function getStreamKeyForUser($userId, $streamId) {
        try {
            // Проверяем доступ
            $access = $this->checkUserAccess($userId, $streamId);
            if (!$access['has_access']) {
                return [
                    'success' => false,
                    'message' => 'Access denied'
                ];
            }
            
            // Получаем информацию о трансляции
            $stream = $this->getStreamById($streamId);
            if (!$stream) {
                return [
                    'success' => false,
                    'message' => 'Stream not found'
                ];
            }
            
            // Генерируем временный ключ для OBS
            $tempKey = $this->generateStreamKey();
            
            // Здесь можно сохранить временный ключ в Redis для OBS
            if ($this->redisClient) {
                $this->redisClient->setex(
                    "stream:{$streamId}:temp_key:{$userId}",
                    3600, // 1 час
                    $tempKey
                );
            }
            
            return [
                'success' => true,
                'stream_key' => $tempKey,
                'stream_url' => $stream['stream_url'],
                'expires_in' => 3600
            ];
            
        } catch (\Exception $e) {
            Log::error("Error getting stream key: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
}
?>