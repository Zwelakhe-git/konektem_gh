<?php
namespace Konektem\Api;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use Konektem\Models\AdminModel;
use Konektem\Utils\Log;

$dotenv = Dotenv::createImmutable(BASE_DIR, '.env');
$dotenv->load();
Log::init();

class ApiParent{
    public function allow($key){
        try {
            return (new AdminModel())->acceptAPIKey($apiKey);
        } catch(\Exception $e){
            return false;
        }
    }

    public function getAuthTokenPayload(): array{
        try {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';

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
            $payload = (new \Konektem\Auth\Auth())->decodeJWT($jwt);//JWT::decode($jwt, new Key($secretKey, 'HS256'));
            
            return (array)$payload;
        } catch(\Exception $e){
            Log::error("Error validating auth token: {$e->getMessage()}");
            return [];
        }
        return [];
    }
}
?>