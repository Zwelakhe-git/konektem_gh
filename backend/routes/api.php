<?php
require_once __DIR__ . '/path.php';
require_once __DIR__ . '/../controllers.php';
require_once 'payment.php';

use Konektem\Utils\Log;
Log::init();

$adminProfileApi = function($req, $res){
    try {
        preg_match('/api\/(.*)\/(.*)/', $_GET['route'], $matches);
        $item = count($matches) > 2 ? $matches[1] : 'dashboard';
        //Log::info($item . " " . $req->params->action . " " . $matches[2]);
        $action = $matches[2];//$req->params->action;
        $controller = null;
        $auth = new \Konektem\Auth\Auth();
        $tokenPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();

        if(empty($tokenPayload)){
            return $res->status(401)->json([
                'success' => false,
                'message' => 'Login required'
            ]);
        }
        if(!$auth->isLoggedIn($tokenPayload['role'])){
            return $res->status(401)->json([
                'success' => false,
                'message' => 'Session expired'
            ]);
        }
        
        if($tokenPayload['id'] !== $_SESSION['user']['id']){
            return $res->status(401)->json([
                'success' => false,
                'message' => 'Invalid token'
            ]);
        }
        switch($item){
            case "news":
                $controller = new \Konektem\Controllers\ArticleController();
                break;
            case "music":
                $controller = new \Konektem\Controllers\MusicController();
                break;
            case "albums":
                $controller = new \Konektem\Controllers\AlbumController();
                break;
            case "events":
                $controller = new \Konektem\Controllers\EventController();
                break;
            case "books":
                $controller = new \Konektem\Controllers\BooksController();
                break;
            case "services":
                $controller = new \Konektem\Controllers\ServiceController();
                break;
            case "interviews":
                $controller = new \Konektem\Controllers\InterviewController();
                break;
            case "partners":
                $controller = new \Konektem\Controllers\PartnersController();
                break;
            case "stream":
                $controller = new \Konektem\Controllers\LiveStreamController();
                break;
            case "settings":
                $controller = new \Konektem\Controllers\SiteSettingController();
                break;
            default:
                return $res->status(400)->json([
                    'success' => false,
                    'message' => "no action '$action' for $item"
                ]);
        }
        if(($_SERVER['REQUEST_METHOD'] === 'POST' && ($action !== 'create' && $action !== 'edit')) ||
           //($_SERVER['REQUEST_METHOD'] === 'PUT' && $action !== 'edit') ||
           ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action !== 'delete')){
            Log::info("method: {$_SERVER['REQUEST_METHOD']}, action: {$action}");
            return $res->status(400)->json([
                'success' => false,
                'message' => 'Invalid action for request method'
            ]);
        }
        $result = $controller->{"{$action}"}();
        return $res->status(200)->json($result);
        
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

$uploadApi = function($req, $res){
	try{
        $api = new \Konektem\Api\UploadApi();
        if(isset($req->params->source) && $req->params->source === 'local'){
            return $api->saveFile($req, $res);
        }
        return $api->uploadToImgbb($req, $res);
    } catch(\Exception $e){
    	Log::error($e->getMessage());
        return $res->status(500)->json([
        	'success' => false,
            'message' => 'Server error'
        ]);
    } catch(\Throwable $e){
    	Log::error($e->getMessage());
        return $res->status(500)->json([
        	'success' => false,
            'message' => 'Server error'
        ]);
    }
};

$deleteUploadApi = function($req, $res){
	try {
        $api = new \Konektem\Api\UploadApi();
    	$fileType = $req->params->type;
        if(isset($req->params->source) && $req->params->source === 'local'){
            return $api->deleteFile($req, $res);
        }
        return $api->deleteFromImgbb($req, $res);
    } catch(\Exception $e){
    	Log::error($e->getMessage());
        return $res->status(500)->json([
        	'success' => false,
            'message' => 'Server error'
        ]);
    }
};

$liveStreamApi = function($req, $res){
    try {
        $api = new \Konektem\Api\LiveStreamApi();

        switch($req->params->action){
            case 'get-key':
                return $api->getStreamKey($req, $res);
                break;
            case 'watch':
                return $api->getStreamUrl($req, $res);
                break;
            case 'login':
                return $api->login($req, $res);
            case 'exit-stream':
                return $api->exitStream($req, $res);
            case 'generate-key':
                return $api->generateKey($req, $res);
            default:
                return $res->status(400)->json([
                    'success' => false,
                    'message' => 'Unknown action'
                ]);
        }
    } catch(\Exception $e){
        Log::error($e->getMessage());
        return $res->status(500)->json([
        	'success' => false,
            'message' => 'Server error'
        ]);
    }
};

$paymentApi = function($req, $res){
    try {
        $headers = $req->headers;
        /*if($headers['Content-Type'] === 'application/json'){
            $input = $req->body;
        } else {
            $input = $_POST;
        }*/
        $input = $req->body ?? $_POST;
        $action = $_POST['action'] ?? $req->body->action;
        $api = new \Konektem\Api\PaymentApi();
        switch ($action) {
            case 'create_payment_intent':
                return $api->handleStripePaymentIntent($req, $res);
                break;
            case 'paypal_create_order':
                return $api->handlePayPalCreateOrder($req, $res);
                break;
            case 'paypal_capture_order':
                return $api->handlePayPalCaptureOrder($req, $res);
                break;
            case 'moncash_create_payment':
                Log::info("creating moncash payment");
                return $api->handleMonCashPayment($req, $res);
                break;
            default:
                return $res->status(400)->json([
                    'success' => false,
                    'message' => 'Unknown action'
                ]);
        }
    } catch(\Exception $e){
        Log::error($e->getMessage());
        return $res->status(500)->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
};

$mainPageControl = function($req, $res){
    try {
        $tokenPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();
        if(empty($tokenPayload)){
            return $res->status(401)->json([
                'success'=> false,
                'message' => 'Login required'
            ]);
        }
        if($tokenPayload['role'] !== 'admin'){
            return $res->status(403)->json([
                'success' => false,
                'message' => 'Insufficient priviledges to edit the main page content'
            ]);
        }
        $body = (array)$req->body ?? json_decode(file_get_contents('php://input'), true) ?? $_POST;
        //preg_match('/mainpage\/(.*)\/(.*)/', $_GET['route'], $matches);
        //Log::info(print_r($body, true));
        $ids = $body['ids'];
        $position = $req->params->position;//$body['position'];
        $action = $req->params->action;
        Log::info("executing $action for " . print_r($ids, true) . " at position $position");
        if(!in_array($action, ['add', 'remove'])){
            return $res->status(400)->json([
                'success' => false,
                'message' => 'Unknown action'
            ]);
        }
        
        $model = new \Konektem\Models\MainPageContentModel();
        
        $addedIds = array_map(function($id) use ($position, $action, $model){
            if($action === 'add'){
                return $model->addItem($position, $id);
            } else {
                return $model->deleteItem($position, $id);
            }
        }, $ids);
        $successCount = count($addedIds);
        $totalCount = count($ids);
        return $res->json([
            'success' => true,
            'message' => "Main page content updated successfully ($successCount/$totalCount)"
        ]);
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        return $res->status(500)->json([
            'success' => false,
            'message' => 'Server error'
        ]);
    }
};

$paymentWebhook = function($req, $res){
    try {
        Log::info("webhook response from {$req->params->platform}");
        Log::info(print_r((array)$req->body ?? $_POST, true));

        $api = new \Konektem\Api\PaymentApi();
        switch($req->params->platform){
            case "moncash":
                return $api->moncashWebhook($req, $res);
            default:
                break;
        }
    } catch(\Exception $e){
        Log::error($e->getMessage());
    }
};

$testPaymentWebhook = function($req, $res){
    try {
        $api = new \Konektem\Api\PaymentApi();
        $orderId = $req->query->order_id;
        $model = new \Konektem\Models\OrderModel();
        $order = $model->getOrderById($orderId);
        $result = $api->sendMockMonCashWebhook($orderId, $order['total_amount']);

        return $res->status(200)->json($result);
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        
    }
};

$premiumSubscription = function($req, $res){
    /*$auth = new \Konektem\Auth\Auth();
    $userPayload = $auth->getAuthTokenPayload();
    if(empty($userPayload)){
        return $res->status(401)->json([
            'success' => false,
            'message' => 'login required'
        ]);
    }
    if($userPayload['role'] === 'admin'){
        return $res->status(403)->json([
            'success' => false,
            'message' => "Admin account not allowed"
        ]);
    }
    $body = (array)$req->body ?? $_POST ?? json_decode(file_get_contents("php:://input"), true);
    if(empty($body)){
        return $res->status(403)->json([
            'success' => false,
            'message' => 'Empty request body'
        ]);
    }

    $model = new \Konektem\Models\OrderModel();
    $pdo = $model->getPdo();
    $stmt = $pdo->query("SELECT * FROM subscription_plans");
    $plan = $stmt->fetch() ?: [];
    if(empty($plan)){
        return $res->status(404)->json([
            'success' => false,
            'message' => 'Plan not found'
        ]);
    }
    try {
        $orderData = [
            'user_id' => $userPayload['id']
        ];
        $orderData['items'] = [
            [
                'product_id' => $plan['product_id'],
                'quantity' => 1,
                'price_at_time' => $plan['price']
            ]
        ];
        $orderData['total_amount'] = $plan['price'];
        $result = $model->createOrder($orderData);
        if(!$result['success']){
            return $res->status(200)->json([
                'success' => false,
                'message' => 'Failed to create order'
            ]);
        }
        
        //Log::info("Order created: " . print_r($result, true));
        
        $finalToken = $auth->createJWTToken([
            'amount' => $orderData['total_amount'],
            'order_id' => $result['order_id'],
            'order_number' => $result['order_number'],
            'email' => $userPayload['email']
        ]);
        $model->updateOrderPaymentToken($result['order_id'], $finalToken);

        return $res->status(200)->json([
            'success' => true,
            'redirect_url' => "/checkout?token=$finalToken"
        ]);
    } catch(\Exception $e){
        
    }*/
    Log::info("premium subscription payload: " . print_r((array)$req->body ?? $_POST, true));
};

$ordersApi = function($req, $res){
    try {
        $auth = new \Konektem\Auth\Auth();
        $userPayload = $auth->getAuthTokenPayload();
        if(empty($userPayload)){
            return $res->status(401)->json([
                'success' => false,
                'message' => 'Login required'
            ]);
        }
        $controller = new \Konektem\Controllers\OrderController();
        $uid = $userPayload['id'];
        $orders = $controller->get(['user_id' => $uid]);

        if(isset($req->params->id)){
            $oid = $req->params->id;
            $order = array_values(array_filter($orders, fn($order) => $order['id'] == $oid));
            if(empty($order)){
                return $res->json([
                    'success' => false,
                    'message' => 'order not found'
                ]);
            }
            //Log::info(print_r($order, true));
            $order = $order[0];
            $action = $req->params->action;
            if($action === 'pay'){
                return $res->json([
                    'success' => true,
                    'redirect_url' => "/checkout/?token={$order['payment_token']}"
                ]);
            } else if($action === 'cancel'){
                $orderModel = new \Konektem\Models\OrderModel();
                $stmt = $orderModel->pdo->query("SELECT id FROM transactions WHERE order_id = $oid");
                $tid = $stmt->fetchColumn();
                $orderModel->updateOrderStatus($oid, 'cancelled');
                $orderModel->updatePaymentStatus($oid, 'failed');
                $orderModel->updateTransactionStatus($tid, 'failed');

                return $res->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully'
                ]);
            } else {
                return $res->status(403)->json([
                    'success' => false,
                    'message' => 'Unknown action'
                ]);
            }
        }
        return $res->json([
            'success' => true,
            'data' => [
                'orders' => $orders
            ]
        ]);
    } catch(\Exception $e){
        Log::error("");
        return $res->status(500)->json([
            'success' => false,
            'message' => 'Server error'
        ]);
    }
};

$apiRoutes = [
    ...$paymentRoutes,
    path_('/(albums|music|news|events|interviews|stream|orders|partners|books|services|settings)/(create|edit)', 'post', $adminProfileApi, 'admin_profile_create'),
    path_('/(albums|music|news|events|interviews|stream|orders|partners|books|services|settings)/delete', 'delete', $adminProfileApi, 'admin_profile_delete'),

    path_('/livestream/:action', 'post', $liveStreamApi, 'stream_api'),
    path_('/upload/:type/:source', 'post', $uploadApi, 'upload'),
    path_('/upload/:source', 'post', $uploadApi, 'upload'),
    path_('/delete-upload/:type/:source', 'get', $deleteUploadApi, 'upload'),
    path_('/payment', 'post', $paymentApi, 'payment_api'),
    path_('/mainpage/:position/:action', 'post', $mainPageControl, 'main-page-control'),
    path_('/payment/:platform/webhook', 'post', $paymentWebhook, 'payment_webhook'),
    path_('/payment/:platform/webhook', 'put', $paymentWebhook, 'payment_webhook'),
    //path_('/payment/:platform/webhook', 'get', $paymentWebhook, 'payment_webhook'),

    // testing
    path_('/test.payment/:platform/webhook', 'get', $testPaymentWebhook, 'payment_webhook_test'),
    path_('/subscription/premium', 'post', $premiumSubscription, 'psub'),
    path_('/orders', 'get', $ordersApi, 'orders-api'),
    path_('/orders/:id/:action', 'post', $ordersApi, 'orders-api')
];
?>