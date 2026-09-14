<?php 
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();
class UserModel extends Database{
    public function __construct(){
        parent::__construct();
    }

    public function getAllUsers(){
        try {
            $sql = "SELECT id, name, email, is_blocked, role, avatar_url FROM users";
            $stmt = $this->pdo->query($sql);
            $users = $stmt->fetchAll();
            foreach($users as $user){
                $stmt = $this->pdo->prepare("SELECT  plan, status, started_at, expires_at, auto_renew FROM subscriptions WHERE user_id = ?");
                $stmt->execute([$user['id']]);
                $subscriptions = $stmt->fetchAll();
                if(!empty($subscriptions)){
                    $user['subscriptions'] = $subscriptions;
                }
            }
            return $users;
        } catch(\PDOException $e){
            Log::error("PDOEexception: {$e->getMessage()}");
            return [];
        }
        return [];
    }
    
    public function createUser($data){
        try{
            $sql = 'INSERT INTO users (email, password_hash, name, role) VALUES (?,?,?,?)';
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute($data);

            if($res){
                $id = $this->pdo->lastInsertId();
                $cachePayload = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'avatar_url' => ''
                ];

                if(isset($data['avatar_url'])){
                    $this->updateUserAvatar(['avatar_url' => $data['avatar_url'], 'user_id' => $id]);
                    $cachePayload['avatar_url'] = $data['avatar_url'];
                }
                
                // Cache user data in Redis with 1 hour TTL
                $this->setCacheData("user:{$id}", $cachePayload);
            }
            
            return [
                'success' => $res ? true : false,
                'user_id' => $res ? $this->pdo->lastInsertId() : -1
            ];
        } catch(PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ['success' => false];
        } catch(Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ['success' => false];
        }
    }

    public function deleteUser($id){
        try{
            $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ? LIMIT 1');
            $res = $stmt->execute([$id]);
            
            if($res){
                $this->invalidateCache("user:{$id}");
            }
            
            return $res ? true : false;
        } catch(PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        } catch(Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }

    public function updateUserProfile($username, $currentPassword, $newName, $newEmail, $newAvatar, $newPassword) {
        try {
            $this->pdo->beginTransaction();

            // First, verify current password
            $stmt = $this->pdo->prepare("SELECT id, password_hash, email, google_id FROM users WHERE name = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if (!$user) {
                return ['success' => false, 'message' => 'User not found'];
            }

            if($user['google_id']){
                return ['success' => false, 'message' => "Can't update google account"];
            }

            if (!password_verify($currentPassword, $user['password_hash'])) {
                return ['success' => false, 'message' => 'Current password is incorrect'];
            }

            // Check if new username or email already exists (if changed)
            if ($newName !== $username) {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE name = ? AND id != ?");
                $stmt->execute([$newName, $user['id']]);
                if ($stmt->fetchColumn() > 0) {
                    return ['success' => false, 'message' => 'Username already taken'];
                }
            }

            if ($newEmail && $newEmail !== $user['email']) {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$newEmail, $user['id']]);
                if ($stmt->fetchColumn() > 0) {
                    return ['success' => false, 'message' => 'Email already in use'];
                }
            }

            // Prepare update data
            $updateFields = ['name = ?', 'email = ?'];
            $updateParams = [$newName, $newEmail];

            // Add avatar if provided
            if ($newAvatar !== '') {
                $updateFields[] = 'avatar_url = ?';
                $updateParams[] = $newAvatar;
            }

            // Update password if provided
            if ($newPassword) {
                $updateFields[] = 'password_hash = ?';
                $updateParams[] = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            // Add ID parameter
            $updateParams[] = $user['id'];

            // Execute update
            $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($updateParams);

            

            $this->pdo->commit();
            
            // Invalidate cache
            $this->invalidateCache("user:{$user['id']}");

            return [
                'success' => true,
                'message' => 'Profile updated successfully',
                'name' => $newName,
                'email' => $newEmail,
                'avatar_url' => $newAvatar
            ];

        } catch (\PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ['success' => false, 'message' => 'Server Error'];
        } catch(\Exception $e){
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ['success' => false, 'message' => 'Server Error'];
        }
    }
    
    public function updateUserAvatar($data){
        try{
            $sql = "UPDATE users SET avatar_url = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);

            if($stmt->execute([$data['avatar_url'], $data['user_id']])){
                $this->invalidateCache("user:{$data['user_id']}");
                return ["success" => true, "message" => "profile updated successfully"];
            }
            return ["success" => false, "message" => "failed to update profile"];
        } catch (Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ["success" => false, "message" => "server error"];
        }
    }
    public function updateLoginTime($name, $email){
        try{
            $sql = 'UPDATE users SET last_login = CURRENT_TIMESTAMP() WHERE name = ? AND email = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$name, $email]);
        } catch(Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }
    

    /**
     * gives the id of a user, registered as an author.
     * @param name - username
     * @param email - user's email
     * @return (id, name, email, avatar_url, role)
     */
    public function getUserDetails($name, $email, $id=null) {
        $user = null;
        try {
            if($this->redisClient){
                if($id){
                    $key = "user:$id";
                    $data = $this->redisClient->get($key);
                    if($data){
                        $data = json_decode($data, true);
                        Log::info("using cached user data");
                    }
                } else {
                    Log::info("searching all user keys");
                    $keys = $this->redisClient->keys("user:*");
                    if(count($keys) > 0){
                        for($i = 0; $i < count($keys); $i++){
                            $data = $this->redisClient->get($keys[$i]);
                            $data = json_decode($data, true);
                            if($data['email'] === $email){
                                $cached = $data;
                                $this->redisClient->expire($keys[$i], 3600);
                                break;
                            }
                        }
                    }
                }
            }
            if(!$user){
                $sql = "SELECT id, name, email, avatar_url, role FROM users WHERE ";
                $sql .= $id ? "id = ?" : "name = ? AND email = ? LIMIT 1";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($id ? [$id] : [$name, $email]);
                $user = $stmt->fetch();

                if(!$user || empty($user)) return [];
                
                $sql = "SELECT s.*, CASE 
                    WHEN expire_at IS NULL THEN 'active'
                    WHEN expire_at > NOW() THEN 'active' 
                    ELSE 'expired' 
                END AS status
                FROM subscriptions s
                LEFT JOIN subscription_plans sp ON s.plan_id = sp.id
                WHERE s.user_id = ? AND expire_at > NOW() ORDER BY created_at DESC LIMIT 1";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$user['id']]);

                //assign the latest
                $subscription = $stmt->fetch() ?: [];
                $user['subscription'] = $subscription;
                $cacheKey = "user:{$user['id']}";
                Log::info("caching user: $cacheKey");
                $this->setCacheData($cacheKey, $user);
            }

            //$stmt = $this->pdo->query("SELECT * FROM notifications WHERE user_id = {$user['id']}");
            //$notifications = $stmt->fetchAll();

            //$user['notifications'] = $notifications;
            // Cache the user data for 1 hour
            
            return $user;

        } catch (\PDOException $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }

    /**
     * gives the id of a user, registered as an author.
     * @param name - username
     * @param email - user's email
     * @return -1 if the user doesnt have the author role, or if the user doesnt exist, else the user id
     */
    public function getAuthorId($name, $email){
        
        try{
            $sql = 'SELECT id FROM users WHERE name = ? AND email = ? AND role = "author"';
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$name, $email]);
            if(!$res){
                return -1;
            }
            return $stmt->fetchColumn();
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return -1;
        }
    }

    /**
     * Приватный метод для безопасного получения данных из Redis
     * Не выбрасывает исключения, логирует ошибки
     * @return array|null Декодированные данные или null
     */
    private function getCachedData($key) {
        if (!isset($this->redisClient)) {
            return null;
        }
        
        try {
            $cached = $this->redisClient->get($key);
            return $cached !== false ? json_decode($cached, true) : null;
        } catch (\RedisException $e) {
            Log::error("Redis read error for key {$key}: {$e->getMessage()}");
            return null;
        } catch (\Exception $e) {
            Log::error("Cache read error: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Приватный метод для безопасной инвалидации кеша
     * Не выбрасывает исключения, логирует ошибки
     */
    private function invalidateCache($key) {
        if (!isset($this->redisClient)) {
            return false;
        }
        
        try {
            $this->redisClient->del($key);
            return true;
        } catch (\RedisException $e) {
            Log::error("Redis delete error for key {$key}: {$e->getMessage()}");
            return false;
        } catch (\Exception $e) {
            Log::error("Cache invalidation error: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Получить пользователя по email
     * @param email - email пользователя
     * @return array|null - данные пользователя или null
     */
    public function getUserByEmail($email) {
        try {
            $sql = "SELECT id, name, email, avatar_url, role, is_blocked, google_id FROM users WHERE email = ? LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            return $user ?: null;
        } catch(\PDOException $e) {
            Log::error("Error getting user by email: " . $e->getMessage());
            return null;
        } catch(\Exception $e) {
            Log::error("Error getting user by email: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Создать пользователя с помощью Google Auth
     * @param data - массив с email, name, avatar_url, google_id
     * @return array - результат создания ['success' => bool, 'user_id' => int]
     */
    public function createGoogleUser($data) {
        try {
            // Проверяем что пользователь с таким email уже не существует
            $existing = $this->getUserByEmail($data['email']);
            if ($existing) {
                return ['success' => false, 'user_id' => $existing['id'], 'message' => 'User already exists'];
            }

            $sql = 'INSERT INTO users (email, name, avatar_url, google_id, role, is_blocked) 
                    VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $this->pdo->prepare($sql);
            
            $result = $stmt->execute([
                $data['email'],
                $data['name'],
                $data['avatar_url'] ?? '',
                $data['google_id'] ?? null,
                'reader', // Default role for new Google users
                0 // Not blocked by default
            ]);

            if ($result) {
                $userId = $this->pdo->lastInsertId();
                
                // Сохраняем данные в кэш Redis
                $cachePayload = [
                    'id' => $userId,
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'avatar_url' => $data['avatar_url'] ?? '',
                    'role' => 'reader',
                    'auth_provider' => 'google'
                ];

                $this->setCacheData("user:{$userId}", $cachePayload, 3600);
                
                Log::info("Google user created: {$data['email']}, ID: {$userId}");

                return [
                    'success' => true,
                    'user_id' => $userId
                ];
            }

            Log::error("Failed to create Google user");
            return ['success' => false, 'user_id' => -1];

        } catch(\PDOException $e) {
            Log::error("PDOException creating Google user: " . $e->getMessage());
            return ['success' => false, 'user_id' => -1];
        } catch(\Exception $e) {
            Log::error("Exception creating Google user: " . $e->getMessage());
            return ['success' => false, 'user_id' => -1];
        }
    }

    /**
     * Обновить google_id для пользователя
     * @param user_id - ID пользователя
     * @param google_id - Google ID
     * @return bool
     */
    public function updateGoogleId($user_id, $google_id) {
        try {
            $sql = "UPDATE users SET google_id = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$google_id, $user_id]);

            if ($result) {
                $this->invalidateCache("user:{$user_id}");
                Log::info("Google ID updated for user: {$user_id}");
            }

            return $result;
        } catch(\PDOException $e) {
            Log::error("Error updating Google ID: " . $e->getMessage());
            return false;
        } catch(\Exception $e) {
            Log::error("Error updating Google ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Обновить время последнего входа
     * @param name - имя пользователя
     * @param email - email пользователя
     */
    public function updateLastLogin($name, $email) {
        try {
            $sql = 'UPDATE users SET last_login = CURRENT_TIMESTAMP() WHERE name = ? AND email = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$name, $email]);
            Log::info("Last login updated for: {$email}");
        } catch(\Throwable $e) {
            Log::error("Error updating last login: " . $e->getMessage());
        }
    }

    /**
     * Публичный метод для сохранения данных в Redis
     * Используется для сохранения данных пользователя после аутентификации
     */
    public function setCacheData($key, $data, $ttl = 3600) {
        if (!isset($this->redisClient)) {
            return false;
        }
        
        try {
            $this->redisClient->set($key, json_encode($data, JSON_UNESCAPED_UNICODE));
            $this->redisClient->expire($key, $ttl);
            return true;
        } catch (\RedisException $e) {
            Log::error("Redis write error for key {$key}: {$e->getMessage()}");
            return false;
        } catch (\Exception $e) {
            Log::error("Cache write error: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Публичный метод для получения данных из Redis
     */
    public function getCacheData($key) {
        if (!isset($this->redisClient)) {
            return null;
        }
        
        try {
            $cached = $this->redisClient->get($key);
            return $cached !== false ? json_decode($cached, true) : null;
        } catch (\RedisException $e) {
            Log::error("Redis read error for key {$key}: {$e->getMessage()}");
            return null;
        } catch (\Exception $e) {
            Log::error("Cache read error: {$e->getMessage()}");
            return null;
        }
    }

    public function subscribeToAuthor($authorId, $userId){
        try {
            if($this->redisClient){
                if(!$this->redisClient->sismember("author:$authorId:followers", $userId)){
                    $this->redisClient->sadd("author:$authorId:followers", $userId);
                }
                return [
                    'success' => true,
                    'subscribers_count' => $this->redisClient->scard("author:$authorId:followers")
                ];
            }
            $stmt = $this->pdo->prepare("INSERT INTO author_followers (follower_id, author_id) VALUES (?,?)");
            $stmt->execute([$userId, $authorId]);
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM author_followers WHERE author_id = ?");
            $stmt->execute([$authorId]);
            $subCount = $stmt->fetchColumn();

            if($this->redisClient){
                $this->redisClient->sadd("author:$authorId:followers", $userId);
            }
            return [
                'success' => true,
                'subscribers_count' => $subCount
            ];
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }

    private function flushSubscriptionsToDb($authorId, $userId){}

    public function userIsFollowingAuthor($authorId, $userId){
        try {
            $cached = null;
            if($this->redisClient){
                $cached = $this->redisClient->sismember("author:$authorId:followers", $userId);
            }
            if($cached !== null){
                return $cached;
            }
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM author_followers WHERE author_id = ? AND follower_id = ?");
            $stmt->execute([$authorId, $userId]);

            if($stmt->fetchColum() > 0){
                if($this->redisClient){
                    $this->redisClient->sadd("author:$authorId:followers", $userId);
                }
                return true;
            }
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return false;
        }
    }

    public function openSubscription($plan, $userId, $subId, $paymentMethod=null){
        try {
            $columns = ['user_id', 'plan', 'subscription_id'];
            $values = [$userId, $plan, $subId];
            if($paymentMethod){
                $columns[] = 'payment_method';
                $values[] = $paymentMethod;
            }
            $placeholders = str_repeat("?,", count($columns) - 1) . '?';
            $sql = "INSERT INTO subscriptions (". implode(",", $columns) .") VALUES ($placeholders)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
            
            $id = $this->pdo->lastInsertId();
            $stmt = $this->pdo->prepare("SELECT * FROM subscriptions WHERE id = ?");
            $stmt->execute([$id]);
            $subscription =  $stmt->fetch();
            $redisPayload = [];
            //$this->setCacheData("subscription:$id", $redisPayload);
            return [
                'started_at' => $subscription['started_at'], 
                'expires_at' => $subscription['expires_at'],
                'subscription_id' => $id
            ];
        } catch(\PDOException $e){
            Log::error("error opening subscription for user $userId: {$e->getMessage()} on line {$e->getLine()}");
            return [];
        } catch(\Exception $e){
            Log::error("error opening subscription for user $userId: {$e->getMessage()} on line {$e->getLine()}");
            return [];
        }
    }

    public function makePayment($userId, $subscriptionId, $status, $amount, $gateway){
        try {
            $stmt = $this->pdo->prepare("INSERT INTO payments (user_id, subscription_id, amount, status, payment_gateway) VALUES (?,?,?,?,?)");
            $stmt->execute([$userId, $subscriptionId, $amount, $status, $gateway]);
            $stmt = $this->pdo->prepare("UPDATE users SET role = 'author' WHERE id = ?");
            $stmt->execute([$userId]);
            return true;
        } catch(\PDOException $e) {
            Log::error("error making payment: {$e->getMessage()}");
            return false;
        } catch(\Exception $e){
            Log::error("error making payment: {$e->getMessage()}");
            return false;
        }
    }

    public function subscriptionPaid($subId, $userId){
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM payments WHERE subscription_id = ? AND user_id = ?");
            $stmt->execute([$subId, $userId]);
            return $stmt->fetchColumn() > 0;
        } catch(\Exception $e){
            Log::error("error checking subscription payment status: {$e->getMessage()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch(\PDOException $e){
            Log::error("error checking subscription payment status: {$e->getMessage()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }

    public function resetPassword($email, $newPassword){
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
            $stmt->execute([$newPassword, $email]);
            return [
                'success' => true,
                'message' => 'Password reset successful'
            ];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }

    public function getLiveStreamAccountInfo($username){
        try {
            $columns = ['v.id', 'v.name', 'v.password_hash', 'sak.access_key', 'sak.date_end AS key_exp'];
            $sql = "SELECT " . implode(',', $columns) . " FROM viewers v LEFT JOIN streamAccesskeys sak ON sak.id = v.access_key_id WHERE v.name = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username]);
            return $stmt->fetch();
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
        return [];
    }
} 
?>