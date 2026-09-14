<?php
require_once 'path.php';
require_once __DIR__ . '/../controllers.php';

use Konektem\Utils\Log;
Log::init();
$userApi = function($req, $res){
    try {
        preg_match('/api\/data\/(.*)/', $_GET['route'], $matches);
        
        $tokenPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();
        if(empty($tokenPayload)){
            return $res->status(401)->json([
                'success' => false,
                'message' => 'Login required'
            ]);
        }
        $item = $matches[1];
        $controller = null;

        //Log::info("looking for $item by {$tokenPayload['name']}");
        switch($item){
            case "events":
                $controller = new \Konektem\Controllers\EventController();
                break;
            case "music":
                $controller = new \Konektem\Controllers\MusicController();
                break;
            case "books":
                $controller = new \Konektem\Controllers\BooksController();
                break;
            default:
                return $res->status(400)->json([
                    'success' => false,
                    'message' => "$item not found"
                ]);
        }
        $data = $controller->get(['user_name' => $tokenPayload['name']]);
        Log::info("found " . count($data));
        if(empty($data)){
            return $res->status(404)->json([
                'success' => false,
                'message' => "no $item for '{$tokenPayload['name']}'"
            ]);
        }
        return $res->status(200)->json([
            'success' => true,
            'data' => $data
        ]);
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
};

$profileUpdate = function($req, $res){
    try {
        $controller = new \Konektem\Controllers\UserController();
        return $controller->updateUserProfile($req, $res);
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
};

$publishContetnt = function($req, $res){
    try {
        $service = new \Konektem\Services\PostingService();
        return $service->publish($req, $res);
    } catch(\Exception $e){
        Log::error($e);
        return $res->status(500)->json([
            'success' => false,
            'message' => 'Server error'
        ]);
    }
};

$userRoutes = [
    /* profile */
    path_('/me', 'get', $profile, 'profile-post'),
    path_('/me/(albums|music|events|books|stream|orders)', 'get', $profile, 'profile-post'),
    path_('/me/(albums|music|events|books|stream)/create', 'get', $profile, 'profile-post'),
    path_('/me/(albums|music|events|books|stream|orders)/:id/(edit|preview)', 'get', $profile, 'profile-post'),
    path_('/me/:item/:id/download', 'get', $download, 'download-url'),
    path_('/api/data/(music|events|books)', 'get', $userApi, 'user-data'),
    path_('/api/profile-update', 'post', $profileUpdate, 'profile-update'),
    path_('/api/publish', 'post', $publishContetnt, 'publishing_endpoint'),
];
?>