<?php
require_once __DIR__ . '/path.php';
require_once __DIR__ . '/../controllers.php';

use Konektem\Utils\Log;

$createOrderToken = function($req, $res) {
    try {
        $itemType = $req->body->type;
        $itemId = $req->body->id;
        $payload = [];
        //\Konektem\Utils\Log::info("$itemType, $itemId");

        switch($itemType){
            case "event":
                $event = (new \Konektem\Controllers\EventController())->get(['id' => $itemId]);
                $payload = [
                    "id" => $event['id'],
                    "title" => $event['title'],
                    "event_date" => $event['event_date'],
                    "description" => $event['description'],
                    "price" => $event['price'],
                    "location" => $event['location'],
                    "image_url" => $event['image_url'],
                    "product_type" => 'event'
                ];
                break;
            case "stream":
                $stream = (new \Konektem\Controllers\LiveStreamController())->get(['id' => $itemId]);
                $payload = [
                    "id" => $stream['id'],
                    "name" => $stream['name'],
                    "description" => $stream['description'],
                    "price" => $stream['price'],
                    "is_active" => $stream['is_active'],
                    "product_type" => 'stream'
                ];
                break;
            case "service":
                $service = (new \Konektem\Controllers\ServiceController())->get(['id' => $itemId]);
                $payload = [
                    "id" => $service['id'],
                    "name" => $service['name'],
                    "price" => $service['price'],
                    "image_url" => $service['image_url'],
                    "product_type" => 'service'
                ];
                break;
            default:
                return $res->status(400)->json([
                    'success' => false,
                    'message' => 'Item not sold'
                ]);
        }
        $token = (new \Konektem\Auth\Auth())->createJWTToken($payload);
        if(!$token){
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Failed to create token'
            ]);
        }
        return $res->status(200)->json([
            'success' =>  true,
            'token' => $token
        ]);
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        return $res->status(500)->json([
            'success' => false,
            'message' => 'Server error'
        ]);
    }
};

$prepareOrderForPayment = function($req, $res){
    try {
        $auth = new \Konektem\Auth\Auth();
        $userPayload = ($auth)->getAuthTokenPayload();
        if(empty($userPayload)){
            return $res->status(401)->json([
                'success' => false,
                'message' => 'Login required'
            ]);
        }
        $data = (array)$req->body ?: $_POST;
        $orderToken = $data['order_token'];

        
        $orderTokenPayload = json_decode(base64_decode($orderToken), true);;
        Log::info($orderToken . print_r($orderTokenPayload, true));
        // expecting an array
        $orderData = [
            'user_id' => $userPayload['id']
        ];
        $orderDetailsText = "";
        
        $orderData['items'] = array_map(function($item) use (&$orderDetailsText){
            $itemType = $item['item_type'];
            $controller = null;
            switch($itemType){
                case "event":
                    $controller = new \Konektem\Controllers\EventController();
                    break;
                default:
                    return null;
            }
            
            $orderedItem = $controller->get(['id' => $item['item_id']]);
            $product_id = $orderedItem['product_id'];
            $orderDetailsText .= "$itemType ({$item['quantity']}) \${$orderedItem['price']}\n";
            return [
                'product_id' => $product_id,
                'price_at_time' => $orderedItem['price'] ?? 0,
                'quantity' => $item['quantity']
            ];
        }, $orderTokenPayload['items']);
        $orderData['total_amount'] = array_reduce($orderData['items'], fn($carry, $item) => $carry + intval($item['price_at_time']), 0);

        //Log::info("order data: " . print_r($orderData, true));
        $model = new \Konektem\Models\OrderModel();
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

        $email = new \Konektem\Models\EmailModel();
        $toEmail = $userPayload['email'];
        $toName = $userPayload['name'];
        $orderDetailsText = nl2br($orderDetailsText);
        $bodyHtml = "<div>
        <h1>Dear {$userPayload['name']}</h1>
            <p>Order successfully created: </p>
            Order #: <h3>{$result['order_number']}</h3>
            <div>
                <p>Order details:</p>
                $orderDetailsText
            </div>
            <div style=\"order: 1px dashed gray;\"></div>
            <h3>Total: \${$orderData['total_amount']}</h3>
            <p>status: pending</p>
            <p>Please proceed to <a href=\"{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/checkout?token=$finalToken\">payment</a> to complete order</p>
        </div>
        ";
        $subject = "Order creation";
        $emailResult = $email->prepare($subject, $toEmail, $toName, $bodyHtml)->send();

        if($emailResult['status'] === 'success'){
            Log::info("email sent successfully");
        }
        return $res->status(200)->json([
            'success' => true,
            'redirect_url' => "/checkout?token=$finalToken"
        ]);
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        return $res->status(500)->json([
            'success' => false,
            'message' => 'Server error'
        ]);
    }
};

$store = function($req, $res) use ($view) {
    try {
        $context = [
            'styles' => [
                BASE_URL . '/static/css/order-form.css'
            ],
            'scripts' => [
                //getScript(BASE_URL . '/static/js/')
            ],
            'template' => 'order-form.php',
            'page' => ''
        ];
        return $res->send($view->render('Public/layout', $context));
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        $res->redirect('/error/500');
    }
};

$storeRoutes = [
    path_('/counter', 'get', $store, 'store-counter'),
    path_('/api/create-order-token', 'post', $createOrderToken, 'order-token-create'),
    path_('/api/create-order', 'post', $prepareOrderForPayment, 'order-create')
];
?>