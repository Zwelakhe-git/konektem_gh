<?php
namespace Konektem\Api;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\AdminModel;
use Konektem\Auth;
use Konektem\Utils\Log;
use Konektem\Models\UserModel;
use Konektem\Api\ApiParent;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Log::init();


class UserApi extends ApiParent{
    /** for POST requests */
    public function saveProfile(){
        
    }

    public function getAllUsers($req, $res){
        $model = new UserModel();
        $users = $model->getAllUsers();
        return $res->status(200)->json([
            'success' => true,
            'count' => count($users),
            'users' => $users
        ]);
    }
    
    public function updateProfile(){}
    public function sendEmail(){}

    public function subscribeToAuthor($req, $res){
        try {
            $payload = $this->getAuthTokenPayload();
            if(empty($payload)){
                return $res->status()->json([
                    'success' => false,
                    'message' => 'Failed to authenticate'
                ]);
            }
            //Log::info("user {$payload['userId']} wants to subscribe to author {$req->params->id}");
            $userId = $payload['userId'];
            $authorId = $req->params->id;
            $model = new UserModel();
            $result = $model->subscribeToAuthor($authorId, $userId);
            $statusCode = $result['success'] ? 200 : 500;
            
            return $res->status($statusCode)->json($result);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    public function isFollowingAuthor($req, $res){
        try {
            $payload = $this->getAuthTokenPayload();
            if(empty($payload)){
                return $res->status(500)->json([
                    'success' => false,
                    'message' => 'Failed to authenticate'
                ]);
            }
            $userId = $payload['userId'];
            $authorId = $req->params->id;
            $model = new UserModel();
            $result = $model->userIsFollowingAuthor($authorId, $userId);
            return $res->status(200)->json(['is_following' => $result]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'is_following' => false
            ]);
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'is_following' => false
            ]);
        }
    }

    public function resetPassword($req, $res){
        try {
            $model = new UserModel();
            if(isset($req->params->confirm)){
                Log::info("password reset confirm");
                $recoveryCode = $req->body->recovery_code;
                $email = $req->body->email;
                $newPassword = $req->body->newPassword;

                $code = $model->redisClient->get("user:$email:password_recovery_code");
                if(!$code){
                    return $res->status(200)->json([
                        'success' => false,
                        'message' => 'password reset request not sent'
                    ]);
                }
                if($code === $recoveryCode){
                    $result = $model->resetPassword($email, password_hash($newPassword, PASSWORD_DEFAULT));
                    if($result['success']){
                        $model->redisClient->del("user:$email:password_recovery_code");
                    }
                    return $res->status(200)->json([
                        'success' => $result['success'],
                        'message' => $result['success'] ? 'Password reset successfully' : $result['message']
                    ]);
                }
                return $res->status(200)->json([
                    'success' => true,
                    'message' => 'Incorrect recovery password'
                ]);
            }
            Log::info("password reset");
            $emailModel = new \Thunderpc\Vkurse\Models\EmailModel();
            $email = $req->body->email;

            $user = $model->getUserByEmail($email);

            if(!$user){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'User with the given email not found'
                ]);
            }
            if($user['google_id']){
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Cant reset password if logged in with google'
                ]);
            }
            $userName = $req->body->full_name ?? $user['full_name'];
            $recoveryCode = uniqid();

            $emailModel->setSubject("Восстанавление пароля");
            $emailModel->setBody("<p>Введите этот код в поле восстанавления: <b>$recoveryCode</b></p>");
            $emailModel->setRecipientAddress($email);
            $emailModel->setRecipientName($userName);

            Log::info("sending email");
            $result = $emailModel->sendMail();
            if($result['success']){
                $model->redisClient->set("user:$email:password_recovery_code", $recoveryCode);
                return $res->status(200)->json([
                    'success' => true,
                    'message' => 'Email with recovery code sent'
                ]);
            }
            return $res->status(200)->json([
                'success' => false,
                'message' => 'Failed to send email. Please retry later'
            ]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(503)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        } catch(\RedisException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(503)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(503)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

}