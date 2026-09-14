<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\UserModel;
use Konektem\Models\ImageModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;

Log::init();
class UserController{
    private $model;
    private $imagemodel;
    private $utils;
    
   public function __construct(){
       $this->model = new UserModel();
       $this->imagemodel = new ImageModel();
       $this->utils = new Utils();
   }
    
   public function updateUserProfile($req, $res) {
        $uploadedFiles = [];
        try {

            $auth = new \Konektem\Auth\Auth();
            $tokenPayload = $auth->getAuthTokenPayload();
            if(empty($tokenPayload)){
                return $res->status(401)->json([
                    'success' => false,
                    'message' => 'Login required'
                ]);
            }
            // Get JSON input
            $input = (array)$req->body ?? $_POST ?? json_decode(file_get_contents('php://input'), true);

            if (!$input || empty($input['current_password'])) {
                return ['response' => 'fail', 'message' => 'Missing required fields'];
            }

            //Log::info(print_r($_FILES, true));

            if(!empty($_FILES['avatar_image']['name'])){
                //Log::info("avatar image uploaded");
                $fileInfo = $this->utils->upload(UPLOAD_DIR . '/images', IMAGETYPES, $_FILES['avatar_image']);
                
                $uploadedFiles[] = $fileInfo['filepath'];
                $input['avatar_url'] = $fileInfo['filepath'];
            }

            $username = $tokenPayload['name'];
            $currentPassword = $input['current_password'];
            $newName = $input['name'] ?? $username;
            $newEmail = $input['email'] ?? '';
            $newAvatar = $input['avatar_url'] ?? '';
            $newPassword = $input['new_password'] ?? '';

            // Update profile via model
            $result = $this->model->updateUserProfile(
                $username,
                $currentPassword,
                $newName,
                $newEmail,
                $newAvatar,
                $newPassword
            );

            // Update session if username changed
            if ($result['success']) {
                $user = $this->model->getUserDetails($result['name'], $result['email']);
                if(!$user || empty($user)){
                    return $res->json([
                        'success' => false,
                        'message' => 'Failed to save updates'
                    ]);
                }
                if($tokenPayload['avatar_url'] && !empty($_FILES['avatar_image']['name'])){
                    $this->utils->deleteFile($tokenPayload['avatar_url']);
                }
                $dotenv = Dotenv::createImmutable(BASE_DIR, '.env');
                $dotenv->load();
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

                $auth->login($user, $token);
                
                return $res->json([
                    'success' => true,
                    'message' => 'profile updated successfully',
                    'token' => $token
                ]);
            }

            return $res->json($result);
        } catch(\Exception $e){
            if(!empty($uploadedFiles)){
                foreach($uploadedFiles as $url){
                    $this->utils->deleteFile($url);
                }
            }
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    public function upadateAvatar(){
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if(!$input){
                return ["success" => false, "message" => "failed to read data"];
            }
            
            return $this->model->updateUserAvatar($input);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }
    
    public function getUserProfile($username = null) {
        if (isset($_SESSION['name']) || $username) {
            log::info('admin controller: getUserProfile');
            $userDetails = $this->model->getUserDetails($username ?? $_SESSION['name']);
            return [
                'response' => 'success',
                'name' => $userDetails['name'],
                'email' => $userDetails['email'],
                'avatar_url' => $userDetails['avatar_url'],
                'premiumnSubscription' => $userDetails['premium_subscription_status']
            ];
        }
        log::info('admin controller: getUserProfile failed, ' . $username ?? $_SESSION['name']);
        return ['response' => 'fail', 'name' => 'guest', 'email' => '', 'avatar_url' => ''];
    }
}
?>