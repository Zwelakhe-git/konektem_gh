<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\Database;
use Konektem\Models\UserModel;
use Konektem\Models\MainPageContentModel;
use Konektem\Utils\Log;
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_DIR, '.env');
$dotenv->load();

Log::init();
class AdminModel extends Database {
    private $mainpagemodel;

    // payload for redis
    private $userPayload = [
        'id', 'name', 'avatar_url', 'email', 'role', 'google_id', 'password_hash'
    ];
    private $userModel;
    public function __construct(){
        parent::__construct();
        $this->mainpagemodel = new MainPageContentModel();
        $this->userModel = new UserModel();
    }

    private function getRedisKey(): string{
        return "user:";
    }

    public function grantLogin($data){
        try{
            $user = null;
            if($this->redisClient){
                $keys = $this->redisClient->keys("user:*");
                
                for($i = 0; $i < count($keys); $i++){
                    $cached = $this->redisClient->get($keys[$i]);
                    $cached = json_decode($cached, true);
                    if($cached['email'] === $data['email']){
                        $session = $this->redisClient->get("session:user:{$cached['id']}");
                        if($session){
                            $session = json_decode($session, true);
                            $tokenPayload = array_filter($session, fn($k) => $k !== 'orders', ARRAY_FILTER_USE_KEY);

                            $secret = $_ENV['JWT_SECRET'];
                            $token = JWT::encode($tokenPayload, $secret, 'HS256');
                            return [
                                "success" => true, 
                                "user" => $session, 
                                "token" => $token
                            ];
                        }
                        break;
                    }
                }
            }

            if(!$user){
                $stmt = $this->pdo->prepare("SELECT id, name, last_name, email, avatar_url, password_hash, role FROM users WHERE email = ? LIMIT 1");

                $result = $stmt->execute([$data['email']]);
                $user = $stmt->fetch();
            }


            /*if($result == false){
                return ["access" => "denied", "success" => false, "message" => "invalid login or password"];
            }*/
            if(!$user) return ['access' => "denied", "success" => false, 'message' => 'unregistered'];

            if(password_verify($data['password'], $user['password_hash'])){
                $userDetails = $this->userModel->getUserDetails(null, null, $user['id']);
                $orders = (new \Konektem\Models\OrderModel())->getOrdersByUser($user['id']);
                $hasSubscription = !empty($userDetails['subscription']);
                $userPayload = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'last_name' => $user['last_name'],
                    'full_name' => $user['name'] . ($user['last_name'] ? " {$user['last_name']}" : ''),
                    'avatar_url' => $user['avatar_url'],
                    'role' => $user['role'],
                    'email' => $user['email'],
                    'subscription' => $hasSubscription ? json_encode($userDetails['subscription']) : '{}',
                ];
                $tokenPayload = [
                    ...$userPayload,
                    'exp' => time() + 86400
                ];
                $userPayload['orders'] = $orders;
                $secret = $_ENV['JWT_SECRET'];
                $token = JWT::encode($tokenPayload, $secret, 'HS256');

                if($this->redisClient){
                    $this->redisClient->set("session:user:{$user['id']}", json_encode($userPayload, JSON_UNESCAPED_UNICODE));
                    $this->redisClient->expire("session:user:{$user['id']}", 86400);
                }
                return [
                    'access' => 'granted', 
                    "success" => true, 
                    "user" => $userPayload, 
                    "token" => $token
                ];
            }

            return ['access' => 'denied', "success" => false, 'message' => 'wrong_password'];
        } catch (\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ["access" => "denied", "success" => false, "message" => "Server Error "];
        } catch (\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ["access" => "denied", "success" => false, "message" => "Server Error "];
        }
    }

    public function registerUser($data){
        try{
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE name = ? OR email = ?");
            $stmt->execute([$data['name'], $data['email']]);
            $user_count = $stmt->fetchColumn();
            
            if ($user_count > 0) {                
                // Проверяем что именно существует
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE name = ?");
                $stmt->execute([$data['name']]);
                if ($stmt->fetchColumn() > 0) {
                    return ['response' => 'fail', "message" => "Username already exists"];
                } else {
                    return ['response' => 'fail', "message" => "Email already exists"];
                }
            }

            $stmt = $this->pdo->query("SELECT MAX(id) FROM users");
            $maxId = $stmt->fetchColumn();
            $stmt = $this->pdo->prepare("INSERT INTO users (id, name, password_hash, email)
            VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([
                (int)$maxId + 1,
                $data['name'],
                $data['password_hash'],
                $data['email']
            ]);

            if($result){
                return ['response' => 'success', 'message' => 'registration successful'];
            }
            return ['response' => 'fail', 'message' => 'server error'];

        } catch(PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ['response' => 'fail', 'message' => 'Server error'];
        }
    }
    
    public function monitorSubscription($username = ADMIN_NAME){
        try{
            // get user info about subscription. return if still active
            $user_info = $this->userModel->getUserDetails($username);
            if(!(isset($user_info['premium_subscription_status']) && $user_info['premium_subscription_status'] === 'expired')){
                return;
            }
            
            // get all ids of items owner by username
            $sql = "SELECT id FROM Music WHERE owner = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username]);
            
            $result = $stmt->fetchAll();
            if(!empty($result)){
                foreach($result as $row){
                    // check which of the owned items are published, and remove pulbished if found
                    $this->mainpagemodel->deleteItem('music', $row['id']);
                }
            }
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }
    public function subscribeUserToPremium($user_id){
        try{
            // get user id, fail if account does not exist
            $this->pdo->beginTransaction();
            
            $sql = "SELECT CASE
                WHEN expire_at IS NULL THEN 'active'
                WHEN expire_at > NOW() THEN 'active' 
                ELSE 'expired' 
            END AS status FROM subscriptions WHERE user_id = ? ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$user_id]);
            $result = $stmt->fetch();
                        
            if($result === false || $result['status'] != 'active'){
                $sql = "SELECT sp.id FROM subscription_plans sp LEFT JOIN products p ON sp.product_id = p.id WHERE p.product_type = 'premium_subscription'";
                $stmt = $this->pdo->query($sql);
                $planId = $stmt->fetch() ?: -1;

                if($planId < 0){
                    return ['success' => false, 'message' => 'Invalid plan id'];
                }
                $sql = "INSERT INTO subscriptions (user_id, plan_id) VALUES (?)";
                $stmt = $this->pdo->prepare($sql);
                $result = $stmt->execute([$user_id, $planId]);
                
                $this->pdo->commit();
                $userInfo = (new UserModel())->getUserDetails(null, null, $user_id);
                $this->sendPremiumSubscriptionEmail($userInfo);
                return ['success' => true, 'message' => "user $user_id now has premium access"];
            }
            $this->pdo->rollback();
            return ["success" => false, "message" => "subscription active"];
            
        } catch(\PDOException $e){
            $this->pdo->rollback();
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ["success" => false, "message" => "Server error"];
        }
    }

    private function sendPremiumSubscriptionEmail($userInfo){
        try {
            $toEmail = $userInfo['email'];
            $toName = $userInfo['name'] ?? substr($userInfo['email'], 0, strpos($userInfo['email'], '@'));
            $email = new \Konektem\Models\EmailModel();

            $bodyHtml = file_get_contents(TEMPLATES_DIR . '/../Components/PremiumSubscriptionEmailBody.php');
            $subject = "Konektem Premium Subscription";
            $result = $email->prepare($subject, $toEmail, $toName, $bodyHtml, null)->send();
            if ($result['status'] === 'success') {
                Log::info("Premium subscription email sent to: {$toEmail}");
            } else {
                Log::error("Failed to send Premium subscription email: " . ($result['error'] ?? 'Unknown error'));
            }
        } catch(\Exception $e){}
    }
    
    
    public function recordMediaActivity($activity, $item_id, $itemname, $user){
        $tables = [
            'interviews' => 'interview',
            'actuality' => 'news',
            'music' => 'music',
            'events' => 'events',
            'books' => 'books'
        ];
        try{
            $user_info = $this->userModel->getUserDetails($user);
            // check if users exists and whether they logged in with google or any other third party
            $activityTblname = null;
            
            $stmt = $this->pdo->prepare('SELECT password_hash FROM users WHERE name = ? LIMIT 1');
            $stmt->execute([$user]);
            $data = $stmt->fetch();
            
            //$activityTblname = $user . '_activities';
            if($activity == 'like'){
                $sql = 'SELECT COUNT(*) FROM UserActivityLog WHERE activity_name = ? AND item_id = ? AND username = ?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$activity, $item_id, $user]);
                $count = $stmt->fetchColumn();

                if($count > 0){
                    return ['success' => false, 'message' => 'user activity repeat'];
                }
            }
            $this->pdo->beginTransaction();
            
            if(!array_key_exists($itemname, $tables)){
                $this->pdo->rollback();
                return ['success' => false, 'message' => 'unrecognized media item' . $itemname];
            }
            if($activity != 'plays'){
                $sql = "INSERT INTO UserActivityLog (activity_name, item_name, item_id, user_id, username) VALUES (?,?,?,?,?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$activity, $tables[$itemname] ,$item_id, $user_info['id'], $user]);
            }
            
            $column = '';
            switch($activity){
                case "like":
                    $column = 'likes';
                    break;
                case "download":
                    $column = 'downloads';
                    break;
                case "plays":
                    $column = 'plays';
                    break;
                case "share":
                    $column = 'shares';
                    break;
                default:
                    throw new Exception("Unknown activity: " . $activity);
            }
            
            $table = $tables[$itemname];
            $sql = "UPDATE $table SET $column = $column + 1 WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$item_id]);

            $sql = "SELECT $column FROM $table WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$item_id]);

            $count = $stmt->fetchColumn();

            $this->pdo->commit();

            $response = [
                "success" => true,
                "response" => [
                "action" => $activity,
                "track_id" => $item_id,
                "count" => $count,
            ]];
            return $response;

        } catch(PDOException $e){
            if($this->pdo->inTransaction()){
                $this->pdo->rollback();
            }

            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ['success' => false, 'message' => 'server error:' . $e->getMessage()];
        } catch(Exception $e){
            if($this->pdo->inTransaction()){
                $this->pdo->rollback();
            }

            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function grantStream($login, $access_key, $password){
        try{
            $query = "SELECT password_hash as ph,
                        CASE 
                            WHEN vw.name IS NULL THEN 'unregistered'
                            WHEN ak.access_key IS NULL THEN 'wrong_key' 
                            WHEN ak.date_end < CURRENT_TIMESTAMP THEN 'expired'
                            ELSE 'valid'
                        END as status
                    FROM viewers vw
                    LEFT JOIN streamAccesskeys ak ON vw.access_key_id = ak.id
                    WHERE vw.name = ? AND ak.access_key = ?";
            
            $stmt = $this->pdo->prepare($query);
            
            if($stmt->execute([$login, $access_key])){
                $row = $stmt->fetch();
                switch($row['status']){
                    case 'valid':
                        if(password_verify($password, $row['ph'])){
                            return ["access" => "granted"];
                        } else {
                            return ["access" => "denied", "message" => "incorrect password"];
                        }
                        
                    case 'unregistered': return ["access" => "denied", "message" => "unsubscribed account"];
                    case 'wrong_key': return ["access" => "denied", "message" => "wrong access key"];
                    case 'expired': return ["access" => "denied", "message" => "access key expired"];
                    default: return ["access" => "denied", "message" => "invalid password"];
                }
            }
            
            return ["access" => "denied", "message" => "account not found:"];
            
        } catch(Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ["access" => "denied", "message" => "Database error: "];
        }
    }

    public function registerViewer($login, $password, $access_key) {
        
        try {
            $this->pdo->beginTransaction();
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare("INSERT INTO streamAccesskeys (access_key) VALUES (?)");
            $stmt->execute([$access_key]);
            $accessKeyId = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("INSERT INTO viewers (name, access_key_id, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$login, $accessKeyId, $password_hash]);
            
            if($this->pdo->inTransaction()){
                $this->pdo->commit();
            }
            $stmt = $this->pdo->prepare("SELECT date_end FROM streamAccesskeys WHERE id = ?");
            $stmt->execute([$accessKeyId]);
            return ["success" => true, "expiration_date" => $stmt->fetchColumn()];
            
        } catch (PDOException $e) {
            if($this->pdo->inTransaction()){
                $this->pdo->rollBack();
            }
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ["success" => false, "message" => "Registration failed: " . $e];
        }
    }
    
    public function login($name, $password){
        try {
            $columns = ['id', 'name', 'email', 'avatar_url', 'role'];
            $sql = "SELECT " . implode(',', $columns) . ", password_hash FROM users WHERE name = ? AND role = 'admin'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$name]);
            $user = $stmt->fetch();

            if(empty($user)){
                return [
                    'success' => false,
                    'message' => 'Not admin account'
                ];
            }
            if(!password_verify($password, $user['password_hash'])){
                return [
                    'success' => false,
                    'message' => 'Incorrect password'
                ];
            }
            $userPayload = [
                'id' => $user['id'],
                'name' => $user['name'],
                'avatar_url' => $user['avatar_url'],
                'role' => $user['role'],
                'email' => $user['email']
            ];
            $tokenPayload = [
                ...$userPayload,
                'exp' => time() + 86400
            ];
            $secret = $_ENV['JWT_SECRET'];
            $token = JWT::encode($tokenPayload, $secret, 'HS256');
            return [
                "success" => true, 
                "user" => $userPayload, 
                "token" => $token
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
        }
        
    }

    /**
     * generates a unique api key and stores the hash in the database,
     * @return array containing the original api key
     */
    public function createAPIKey(){
        try{
            $user = (new \Konektem\Auth\Auth())->getAuthTokenPayload();
            if(!$user){
                return [
                    'success' => false,
                    'message' => 'user not registered'
                ];
            }

            $userId = $user['id'];
            $key = preg_replace('/\W/', '_', uniqid('konektem_', true));
            $hash = md5($key);
            $permissions = "read,write,delete,insert,update,create";
            $sql = 'INSERT INTO api_keys (owner_id, api_key, permissions) VALUES (?,?,?)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $hash, $permissions]);

            return [
                'success' => true,
                'api_key' => $key
            ];
        } catch(\PDOException $e){
            Log::error("AdminModel - createAPIKey - {$e->getMessage()}");
            return [
                'success' => false,
                'message' => 'Server Error'
            ];
        }
    }
    
    public function acceptAPIKey($apiKey){
        try{
            //Log::info("calling AdminModel acceptAPIKey");
            $sql = 'SELECT * FROM api_keys WHERE api_key = ? AND status = "active"';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([md5($apiKey)]);
            $res = $stmt->fetch();

            //Log::info("request completed without errors ");
            return !empty($res);
        } catch(\PDOException $e){
            Log::error("AdminModel - acceptAPIKey - {$e->getMessage()}");
            return false;
        } catch(\Exception $e){
            Log::error("AdminModel - acceptAPIKey - {$e->getMessage()}");
            return false;
        }
    }
    public function blockUser($name, $email, $id=null){}

    public function cacheUser(array $payload){
        if($this->redisClient){
            $key = $this->getRedisKey();
            $this->redisClient->set($key, json_encode($payload, JSON_UNESCAPED_UNICODE));
        }
    }

    public function getCachedUser(array $params){
        if($this->redisClient){
            $cached = null;
            if(isset($params['id'])){
                $key = "user:{$params['id']}";
                $cached = $this->redisClient->get($key);
            } else if(isset($params['email'])){
                
            }
        }
    }
}

?>