<?php
namespace Konektem\Api;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Utils\Log;
use Konektem\Models\UserModel;
use Konektem\Models\AdminModel;
use Konektem\Auth\Auth;

Log::init();
class AuthApi{
    public function getGoogleAuthUrl($req, $res) {
        try {
            // Check if Google auth config exists - try multiple paths
            $googleConfigPaths = [
                __DIR__ . '/../../config/google-auth.php',
            ];

            $googleConfigLoaded = false;
            foreach ($googleConfigPaths as $googleConfigPath) {
                if (file_exists($googleConfigPath)) {
                    require_once $googleConfigPath;
                    $googleConfigLoaded = true;
                    break;
                }
            }

            if (!$googleConfigLoaded) {
                Log::error("Google auth config file not found. Checked paths: " . implode(", ", $googleConfigPaths));
                return $res->json([
                    'success' => false,
                    'message' => 'Google authentication not configured - config file missing'
                ]);
            }

            // Validate that required constants are defined
            if (!defined('GOOGLE_CLIENT_ID') || empty(GOOGLE_CLIENT_ID) || GOOGLE_CLIENT_ID === 'your-google-client-id.apps.googleusercontent.com') {
                return $res->status(500)->json([
                    'success' => false,
                    'message' => 'Google OAuth configuration missing or not set'
                ]);
            }

            if (!defined('GOOGLE_REDIRECT_URI') || empty(GOOGLE_REDIRECT_URI)) {
                return $res->status(500)->json([
                    'success' => false,
                    'message' => 'Google redirect URI not configured'
                ]);
            }

            $params = [
                'client_id' => GOOGLE_CLIENT_ID,
                'redirect_uri' => GOOGLE_REDIRECT_URI,
                'response_type' => 'code',
                'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
                'access_type' => 'online',
                'prompt' => 'consent'
            ];

            $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

            return $res->status(200)->json([
                'success' => true,
                'auth_url' => $authUrl
            ]);
        } catch(\Exception $e){
            Log::error();
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        } catch(\Throwable $e){
            Log::error();
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
        
    }

    /**
     * Обработчик callback от Google OAuth
     * Получает код авторизации, обменивает его на access_token,
     * извлекает информацию о пользователе и создает сессию
     */
    public function handleGoogleAuthCallback($req, $res) {
        try {
            // Загружаем конфиг Google
            $googleConfigPaths = [
                __DIR__ . '/../../config/google-auth.php',
            ];

            $googleConfigLoaded = false;
            foreach ($googleConfigPaths as $googleConfigPath) {
                if (file_exists($googleConfigPath)) {
                    require_once $googleConfigPath;
                    $googleConfigLoaded = true;
                    break;
                }
            }
            $redirectUrl = "/konektem/auth/login";

            if (!$googleConfigLoaded) {
                Log::error("Google auth config file not found");
                header("Location: " . $redirectUrl . "?" . http_build_query([
                    'google_error' => 1,
                    'message' => 'Server error'
                ]));
                exit;
            }

            // Проверяем наличие кода авторизации
            $code = $_GET['code'] ?? null;
            if (!$code) {
                Log::error("Google auth code not received");
                header("Location: " . $redirectUrl . "?" . http_build_query([
                    'google_error' => 1,
                    'message' => 'Server error'
                ]));
                exit;
            }

            // Обмениваем код на access_token
            $tokenData = $this->exchangeCodeForToken($code);
            if (!$tokenData) {
                Log::error("Failed to exchange code for token");
                header("Location: " . $redirectUrl . "?" . http_build_query([
                    'google_error' => 1,
                    'message' => 'Server error'
                ]));
                exit;
            }

            // Получаем информацию о пользователе
            $userInfo = $this->getUserInfoFromGoogle($tokenData['access_token']);
            if (!$userInfo) {
                Log::error("Failed to get user info from Google");
                header("Location: " . $redirectUrl . "?" . http_build_query([
                    'google_error' => 1,
                    'message' => 'Server error'
                ]));
                exit;
            }

            // Ищем или создаем пользователя в БД
            $userModel = new UserModel();
            $orderModel = new \Konektem\Models\OrderModel();
            $user = $userModel->getUserByEmail($userInfo['email']);
            

            if (!$user) {
                // Создаем нового пользователя
                $createResult = $userModel->createGoogleUser([
                    'email' => $userInfo['email'],
                    'name' => $userInfo['name'],
                    'avatar_url' => $userInfo['picture'] ?? '',
                    'google_id' => $userInfo['id']
                ]);

                if (!$createResult['success']) {
                    Log::error("Failed to create user in database");
                    header("Location: " . $redirectUrl . "?" . http_build_query([
                        'google_error' => 1,
                        'message' => 'Server error'
                    ]));
                    exit;
                }

                $user = $userModel->getUserDetails(null, null, $createResult['user_id']);
            } else {
                // Обновляем google_id если необходимо
                if (empty($user['google_id'])) {
                    $userModel->updateGoogleId($user['id'], $userInfo['id']);
                }
                // Обновляем информацию при каждом входе
                $userModel->updateLastLogin($user['name'], $user['email']);
                $user = $userModel->getUserDetails(null, null, $user['id']);
            }
            $user['orders'] = $orderModel->getOrdersByUser($user['id']);

            // Сохраняем данные в Redis
            $this->saveUserToRedis($user);

            // Создаем сессию через Auth класс
            $auth = new Auth();
            $sessionUser = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'avatar_url' => $user['avatar_url'],
                'role' => $user['role'],
                'subscription' => $user['subscription'],
                //'orders' => $user['orders'],
                'auth_provider' => 'google'
            ];
            $sessionUser['subscription'] = json_encode($sessionUser['subscription']);
            $token = $auth->createJWTToken($sessionUser);
            $auth->login($sessionUser, $token);

            //Log::info("User {$user['email']} authenticated via Google");
            
            header("Location: " . $redirectUrl . "?" . http_build_query([
                'google_success' => 1,
                'token' => $token
            ]
            ));
            exit;

        } catch(\Exception $e) {
            Log::error("Google auth callback error: " . $e->getMessage());
            header("Location: " . $redirectUrl . "?" . http_build_query([
                'google_error' => 1,
                'message' => 'Server error'
            ]));
            exit;
        } catch(\Throwable $e) {
            Log::error("Google auth callback error: " . $e->getMessage());
            header("Location: " . $redirectUrl . "?" . http_build_query([
                'google_error' => 1,
                'message' => 'Server error'
            ]));
            exit;
        }
    }

    /**
     * Обменивает код авторизации на access_token
     */
    private function exchangeCodeForToken($code) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'client_id' => GOOGLE_CLIENT_ID,
                'client_secret' => GOOGLE_CLIENT_SECRET,
                'code' => $code,
                'redirect_uri' => GOOGLE_REDIRECT_URI,
                'grant_type' => 'authorization_code'
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::error("Google token exchange failed with HTTP code: " . $httpCode);
                Log::error("Response: " . $response);
                return null;
            }

            $tokenData = json_decode($response, true);
            return $tokenData;

        } catch(\Exception $e) {
            Log::error("Error exchanging code for token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Получает информацию о пользователе от Google
     */
    private function getUserInfoFromGoogle($accessToken) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v2/userinfo');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::error("Failed to get user info from Google with HTTP code: " . $httpCode);
                return null;
            }

            $userInfo = json_decode($response, true);
            return $userInfo;

        } catch(\Exception $e) {
            Log::error("Error getting user info from Google: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Сохраняет информацию о пользователе в Redis
     */
    private function saveUserToRedis($user) {
        try {
            $redisKey = "user:{$user['id']}";
            $userData = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'avatar_url' => $user['avatar_url'],
                'role' => $user['role'],
                'auth_provider' => 'google'
            ];

            $userModel = new UserModel();
            $userModel->setCacheData($redisKey, $userData, 3600); // TTL 1 hour

            Log::info("User {$user['id']} data saved to Redis");

        } catch(\Exception $e) {
            Log::error("Error saving user to Redis: " . $e->getMessage());
        }
    }

    public function login($req, $res){
        try{
            $auth = new Auth();
            $result = [];
            if ($auth->isLoggedIn()){
                $auth->checkSessionTimeout();

                return $res->status(200)->json($auth->isLoggedIn() ?
                    ['success' => true, 'message' => $_SESSION['user']['name'] ] :
                    ['success' => false, 'message' => 'session timeout' ]
                );
            } elseif($_SESSION['REQUEST_METHOD'] === 'POST'){
                $data = $_POST ?? json_decode(file_get_contents('php://input'), true);
                $response = $this->model->grantLogin($_POST);
                if($response["success"]){
                    
                    $auth->login($response["user"]);
                    $user = [
                        'name' => $_SESSION['user']['name'],
                    ];
                    return $res->status(200)->json(["success" => true, "message" => "login successful", 'user' => $user]);
                } else {
                    return $res->status(401)->json(["success" => false,"message" => $response['message']]);
                }

            } else {
                return $res->status(403)->json(["success" => false,'message' => 'missing parameters']);
            }
            return $result;
        } catch(\Exception $e){
            Log::error("AdminController - userLogin - {$e->getMessage()}");
            return $res->status(403)->json(["success" => false, 'message' => "Server Error"]);
        }
    }

    public function logout(){
        (new Auth())->logout();
    }
}
?>