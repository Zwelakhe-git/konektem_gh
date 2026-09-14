<?php
namespace Konektem\Auth;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Utils\Log;
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_DIR, '.env');
$dotenv->load();

Log::init();
class Auth {
    private $sessionStarted = false;

    public function __construct(){
    }
    
    /**
     * Гарантированно запускает сессию, если она еще не запущена
     */
    private function ensureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            $this->sessionStarted = true;
        }
    }
    
    // Check if user is logged in
    public function isLoggedIn($role = 'guest') {
        $this->ensureSession();
        $result = (isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION['user']['role'] === $role && $_SESSION['session_end'] > time());
        //if($role === 'admin' && $result && $_SESSION['user']['role'] !== 'admin'){ $result = false; }
        return $result;
    }
    
    // Login user
    public function login($user, $token=null) {
        try {
            $this->ensureSession();
            $now = time();
            
            // Очищаем существующую сессию перед созданием новой
            session_regenerate_id(true);
            
            $_SESSION['user'] = $user;
            $_SESSION['name'] = $user['name']; // for supporting old versions while full changes havent been made
            $_SESSION['login_time'] = $now;
            $_SESSION['session_start'] = $now;
            $_SESSION['session_end'] = $now + 3600;
            $_SESSION['session_id'] = session_id();

            if(!$token){
                $token = $this->createJWTToken($user);
            }
            if($token) { $_SESSION['token'] = $token; }
            return true;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }
    
    // Logout user
    public function logout() {
        $this->ensureSession();
        
        // Полная очистка сессии
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        Log::info(' - Session destroyed');
        //Log::info(' - Session destroyed');
    }
    
    // Get current user
    public function getCurrentUser() {
        $this->ensureSession();
        return $_SESSION['user'] ?? null;
    }
    
    // Require login - redirect if not logged in
    public function requireLogin($redirectTo = '/konektem/auth/login') {
        if(preg_match('/admin/', $_GET['route'] ?? '')){
            $redirectTo = BASE_URL . '/auth/admin/logout';
            $role = 'admin';
        } else {
            $redirectTo = BASE_URL . '/auth/logout';
            $role = 'guest';
        }
        if (!$this->isLoggedIn($role)) {
        
            header("Location: $redirectTo");
            exit;
        }
    }
    
    // Check session timeout (optional security feature)
    public function checkSessionTimeout($timeoutMinutes = 60) {
        $this->ensureSession();
        
        if (isset($_SESSION['session_start'])) {
            $elapsedTime = time() - $_SESSION['session_start'];
            if ($elapsedTime > ($timeoutMinutes * 60)) {
                $this->logout();
                Log::info('Session timeout after ' . $elapsedTime . ' seconds');
                return false;
            } else {
                // Update login time on activity
                $now = time();
                $_SESSION['session_start'] = $now;
                return true;
            }
        }
        return false;
    }

    public function createJWTToken($payload){
        try {
            $secret = $_ENV['JWT_SECRET'];
            $payload['exp'] = time() + 86400;
            $token = JWT::encode($payload, $secret, 'HS256');
            return $token;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        }
    }

    public function decodeJWT(string $token){
        try {
            $parts = explode('.', $token);
            if(count($parts) !== 3){
                throw new \Exception("Invalid JWT token: $token");
            }
            $payload = json_decode(base64_decode($parts[1]), true);

            return $payload;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        }
    }

    public function getAuthTokenPayload(): array{
        try {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';

            if(!$authHeader){
                Log::warn('Authorization header not set');
                return [];
            }
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                $jwt = $matches[1];
            } else {
                Log::warn("Invalid auth token");
                return [];
            }

            $secretKey = $_ENV['JWT_SECRET'];

             // 2. Верифицируем токен
            $payload = $this->decodeJWT($jwt);//JWT::decode($jwt, new Key($secretKey, 'HS256'));
            if(isset($payload['exp']) && $payload['exp'] < time()){
                Log::warn("JWT token expired");
                return [];
            }
            
            return (array)$payload;
        } catch(\Exception $e){
            Log::error("Error validating auth token: {$e->getMessage()}");
            return [];
        }
        return [];
    }
    
}