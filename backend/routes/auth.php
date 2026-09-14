<?php
require_once __DIR__ . '/path.php';
require_once __DIR__ . '/../controllers.php';

use Konektem\Utils\Log;
Log::init();

/** authentication */
$authenticate = function ($req, $res){
    // Запускаем сессию ДО создания контроллера
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    //Log::info(' - Authenticate called, session_id: ' . session_id());
    
    try{
        $controller = new \Konektem\Controllers\AdminController();
        
        $result = [];
        if(preg_match('/register/', $_GET['route'] ?? '')){
            $result = $controller->register();
        } else {
            $result = $controller->login($_POST['role'] ?? $req->body->role ?? 'guest');
        }
        
        //Log::error(' - Result: ' . print_r($result, true));
        
        $res->status(201)->json($result);
    } catch(\Throwable $e){
        Log::error('Authentication Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        $res->json(['success' => false, 'message' => 'Server Error']);
    }
};

$login = function ($req, $res) use ($view) {
    $auth = new \Konektem\Auth\Auth();
    if(preg_match('/admin/', $_GET['route'])){
        Log::info("admin login");
        if($auth->isLoggedIn('admin')){
            return $res->redirect(BASE_URL . '/admin/dashboard');
        }
        return $res->send($view->render('Admin/login'));
    } else {
        Log::info("user login");
        if($auth->isLoggedIn() && !isset($_GET['google_success']) && !isset($_GET['google_error'])){
            return $res->redirect(BASE_URL . '/user/me');
        }
        return $res->send($view->render('Public/register'));
    }
};
$googleAuth = function($req, $res){
    $api = new \Konektem\Api\AuthApi();
    return $api->getGoogleAuthUrl($req, $res);
};

$googleAuthCallback = function($req, $res){
    $api = new \Konektem\Api\AuthApi();
    return $api->handleGoogleAuthCallback($req, $res);
};

$logout = function ($req, $res) use ($view) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    (new \Konektem\Controllers\AdminController())->logout();
    if(preg_match('/admin/', $_GET['route'])){
        return $res->redirect(BASE_URL . '/auth/admin/login');
    } else {
        return $res->redirect(BASE_URL . '/auth/login');
    }
};

$authRoutes = [
    path_('/(login|register)', 'get', $login, 'login'),
    path_('/admin/login', 'get', $login, 'admin_login'),
    path_('/google', 'get', $googleAuth, 'google-login'),
    path_('/google/callback', 'get',$googleAuthCallback, 'google-authenticate'),
    path_('/logout', 'get', $logout, 'logout'),
    path_('/admin/logout', 'get', $logout, 'admin_login'),
    path_('/(login|register)', 'post', $authenticate, 'auth'),
    path_('/admin/login', 'post', $authenticate, 'auth'),
];
?>