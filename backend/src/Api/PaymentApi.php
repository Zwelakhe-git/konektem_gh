<?php
namespace Konektem\Api;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Utils\Log;
use Konektem\Models\OrderModel;
use Dotenv\Dotenv;
use Stripe\Stripe;

$dotenv = Dotenv::createImmutable(BASE_DIR, '.env');
$dotenv->load();

Log::init();

class PaymentApi {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new OrderModel();
    }
    
    // =============== STRIPE ===============
    public function handleStripePaymentIntent($req, $res) {
        try {
            $data = (array)$req->body ?? $_POST;
            Stripe::setApiKey($_ENV['STRIPE_SECRET']);
            $amount = intval($data['amount']);
            $currency = $data['currency'] ?? 'usd';
            $email = $data['email'] ?? '';

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'receipt_email' => $email,
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'user_id' => $data['user_id'] ?? null,
                    'product_id' => $data['product_id'] ?? null,
                    'order_id' => $data['order_id'] ?? null
                ]
            ]);

            return $res->status(200)->json([
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id
            ]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error',
                'error' => 'Server error'
            ]);
        }
    }

    // =============== PAYPAL ===============
    public function handlePayPalCreateOrder($req, $res) {
        try {
            $data = (array)$req->body ?? $_POST;
            $paypal_client_id = $_ENV['PAYPAL_CLIENT_ID']; 
            $paypal_secret = $_ENV['PAYPAL_SECRET'];
            
            $amount = $data['amount'] ?? '1.80';
            $currency = $data['currency'] ?? 'USD';
            
            $access_token = $this->getPayPalAccessToken($paypal_client_id, $paypal_secret);
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://api.sandbox.paypal.com/v2/checkout/orders',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $access_token,
                    'Content-Type: application/json',
                ],
                CURLOPT_POSTFIELDS => json_encode([
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => $amount
                        ],
                        'custom_id' => json_encode([
                            'user_id' => $data['user_id'] ?? null,
                            'product_id' => $data['product_id'] ?? null,
                            'order_id' => $data['order_id'] ?? null
                        ])
                    ]]
                ])
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            $order_data = json_decode($response, true);
            
            if (isset($order_data['id'])) {
                return $res->status(200)->json(['id' => $order_data['id']]);
            } else {
                Log::error('PayPal order creation failed: ' . print_r($order_data, true));
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'PayPal order creation failed'
                ]);
            }
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error',
                'error' => 'Server error'
            ]);
        }
    }

    public function handlePayPalCaptureOrder($req, $res){
        try {
            $data = (array)$req->body ?? $_POST;
            $paypal_client_id = $_ENV['PAYPAL_CLIENT_ID']; 
            $paypal_secret = $_ENV['PAYPAL_SECRET'];

            $orderID = $data['orderID'] ?? '';
            $access_token = $this->getPayPalAccessToken($paypal_client_id, $paypal_secret);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => "https://api.sandbox.paypal.com/v2/checkout/orders/{$orderID}/capture",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $access_token,
                    'Content-Type: application/json',
                ]
            ]);

            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response, true);

            // Если платеж успешен, обновляем статус заказа
            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                $this->handleSuccessfulPayment($data);
            }

            return $res->status(200)->json($response);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error',
                'error' => 'Server error'
            ]);
        }
    }

    private function getPayPalAccessToken($client_id, $secret) {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://api.sandbox.paypal.com/v1/oauth2/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Basic ' . base64_encode("{$client_id}:{$secret}"),
                    'Content-Type: application/x-www-form-urlencoded',
                ],
                CURLOPT_POSTFIELDS => 'grant_type=client_credentials'
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            $token_data = json_decode($response, true);
            return $token_data['access_token'] ?? '';
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        }
    }

    // =============== MONCASH ===============
    public function handleMonCashPayment($req, $res) {
        try {
            
            $data = (array)$req->body ?? $_POST;
            
            // Валидация данных
            if (empty($data['amount']) || empty($data['user_id'])) {
                return $res->status(400)->json([
                    'success' => false,
                    'message' => 'Missing required fields: amount, product_id, user_id'
                ]);
            }
            
            $moncash_client_id = $_ENV['MONCASH_CLIENT_ID'];
            $moncash_secret = $_ENV['MONCASH_SECRET'];
            
            if (!$moncash_client_id || !$moncash_secret) {
                Log::error("Missing MonCash credentials");
                return $res->status(500)->json([
                    'success' => false,
                    'message' => 'Payment service not configured'
                ]);
            }
            
            $amount = floatval($data['amount']);
            $userId = intval($data['user_id']);
            $productId = null;
            $email = $data['email'] ?? '';
            $phone = $data['phone'] ?? '';
            $orderResult = null;
            
            if(isset($data['order_id']) || isset($data['order_number'])){
                $order = $this->orderModel->getOrderById($data['order_id']);

                //Log::info(print_r($order, true));
                if(!$order){
                    return $res->status(404)->json([
                        'success' => false,
                        'message' => "Order not found"
                    ]);
                } else if($order['order_status'] === 'processing' || $order['order_status'] !== 'pending'){
                    return $res->status(200)->json([
                        'success' => false,
                        'message' => "Failed to handle payment on order. Order status: {$order['order_status']}"
                    ]);
                }
                $this->orderModel->updatePaymentMethod($data['order_id'], 'moncash');

                $orderResult = [
                    'success' => true,
                    'order_id' => $order['id'],
                    'order_number' => $order['order_number']
                ];
            } else {
                if(!isset($data['product_id']) || empty($data['product_id'])){
                    return $res->status(400)->json([
                        'success' => false,
                        'message' => 'Missing product_id'
                    ]);
                }
                $productId = intval($data['product_id']);
                $stmt = $this->orderModel->pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$productId]);
                $product = $stmt->fetch() ?: [];
                if(empty($product)){
                    return $res->status(404)->json([
                        'success' => false,
                        'message' => 'Product not found'
                    ]);
                }
                $amount = $product['price'] ?: $amount;
                // Создаем заказ со статусом pending
                $orderResult = $this->createPendingOrder($userId, $productId, $amount);
            }
            
            if (!$orderResult['success']) {
                return $res->status(500)->json([
                    'success' => false,
                    'message' => 'Failed to create order: ' . ($orderResult['message'] ?? 'Unknown error')
                ]);
            }
            
            $orderId = $orderResult['order_id'];
            $orderNumber = $orderResult['order_number'];

            $auth = new \Konektem\Auth\Auth();
            $finalToken = $auth->createJWTToken([
                'amount' => $amount,
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'email' => $email
            ]);
            $this->orderModel->updateOrderPaymentToken($orderId, $finalToken);
            
            // Получение токена MonCash
            $token_response = $this->getMonCashToken($moncash_client_id, $moncash_secret);
            $access_token = $token_response['access_token'] ?? '';

            if (!$access_token) {
                Log::error('MonCash authentication failed');
                return $res->status(500)->json([
                    'success' => false,
                    'message' => 'Payment service authentication failed'
                ]);
            }

            // Создание платежа в MonCash
            $payment_data = [
                'amount' => $amount,
                'orderId' => $orderNumber
            ];

            $payment_response = $this->createMonCashPayment($access_token, $payment_data);

            if (isset($payment_response['payment_token']['token'])) {
                $paymentToken = $payment_response['payment_token']['token'];
                $paymentUrl = $this->getMonCashPaymentUrl($paymentToken);
                
                // Сохраняем информацию о транзакции
                $this->saveTransaction([
                    'order_id' => $orderId,
                    'transaction_id' => substr($paymentToken, 0, 254),
                    'amount' => $amount,
                    'payment_system' => 'moncash',
                    'status' => 'pending',
                    'payment_details' => [
                        'token' => $paymentToken,
                        'user_id' => $userId,
                        'product_id' => $productId ?? '',
                        'email' => $email,
                        'phone' => $phone
                    ]
                ]);
                
                return $res->status(200)->json([
                    'success' => true,
                    'payment_url' => $paymentUrl,
                    'order_id' => $orderId,
                    'order_number' => $orderNumber,
                    'payment_token' => $paymentToken
                ]);
            } else {
                Log::error('MonCash payment creation failed: ' . print_r($payment_response, true));
                return $res->status(500)->json([
                    'success' => false,
                    'message' => 'Payment creation failed'
                ]);
            }
        } catch(\Exception $e){
            Log::error("MonCash payment error: {$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    /**
     * Получение токена MonCash
     */
    private function getMonCashToken($client_id, $secret) {
        try {
            $host = $_ENV['MONCASH_LIVE_HOST_REST_API'] ?? 'moncashbutton.digicelgroup.com/Api';
            
            $ch = curl_init();
            $url = sprintf(
                'https://%s:%s@%s/oauth/token',
                urlencode($client_id),
                urlencode($secret),
                $host
            );
            
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded',
                ],
                CURLOPT_POSTFIELDS => 'scope=read,write&grant_type=client_credentials',
                CURLOPT_SSL_VERIFYPEER => false, // В продакшене должно быть true
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                Log::error("MonCash cURL error: " . $error);
                return null;
            }

            $result = json_decode($response, true);
            
            if ($httpCode !== 200) {
                Log::error("MonCash token error: HTTP {$httpCode}, response: " . $response);
                return null;
            }

            return $result;
        } catch(\Exception $e){
            Log::error("MonCash token error: {$e->getMessage()} line {$e->getLine()}");
            return null;
        }
    }

    /**
     * Создание платежа в MonCash
     */
    private function createMonCashPayment($access_token, $payment_data) {
        try {
            $host = $_ENV['MONCASH_LIVE_HOST_REST_API'] ?? 'moncashbutton.digicelgroup.com/Api';
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => "https://{$host}/v1/CreatePayment",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Authorization: Bearer ' . $access_token,
                    'Content-Type: application/json',
                ],
                CURLOPT_POSTFIELDS => json_encode([
                    'amount' => $payment_data['amount'],
                    'orderId' => $payment_data['orderId']
                ]),
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                Log::error("MonCash create payment cURL error: " . $error);
                return null;
            }

            $result = json_decode($response, true);
            
            if ($httpCode !== 200 && $httpCode !== 202) {
                Log::error("MonCash create payment error: HTTP {$httpCode}, response: " . $response);
                return null;
            }

            return $result;
        } catch(\Exception $e){
            Log::error("MonCash create payment error: {$e->getMessage()} line {$e->getLine()}");
            return null;
        }
    }

    /**
     * Получение URL для редиректа на страницу оплаты MonCash
     */
    private function getMonCashPaymentUrl($token) {
        $gatewayBase = $_ENV['MONCASH_LIVE_GATEWAY_BASE'] ?? 'https://moncashbutton.digicelgroup.com/Moncash-middleware';
        return $gatewayBase . '/Payment/Redirect/?token=' . $token;
    }

    /**
     * Вебхук для обработки статуса платежа MonCash
     */
    public function moncashWebhook($req, $res) {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                Log::error("MonCash webhook: Invalid input");
                return $res->status(400)->json(['success' => false, 'message' => 'Invalid input']);
            }
            
            Log::info("MonCash webhook received: " . print_r($input, true));
            
            // MonCash webhook sends transactionId (the actual transaction ID from MonCash)
            // NOT the payment_token we created earlier
            $transactionId = $input['transactionId'] ?? $input['transaction_id'] ?? null;
            $orderId = $input['orderId'] ?? $input['order_id'] ?? null;
            
            if (!$transactionId) {
                Log::error("MonCash webhook: Missing transactionId");
                return $res->status(400)->json(['success' => false, 'message' => 'Missing transactionId']);
            }
            
            // Get transaction details from MonCash
            $transactionDetails = $this->getMonCashTransactionDetails($transactionId);
            /*$transactionDetails = [
                'payment' => $input['payment']
            ];*/
            
            if (!$transactionDetails) {
                Log::error("MonCash webhook: Failed to get transaction details");
                return $res->status(500)->json(['success' => false, 'message' => 'Failed to get transaction details']);
            }
            
            // The transaction details contain the orderId we sent
            $moncashOrderId = $transactionDetails['payment']['orderId'] ?? $orderId;
            //$moncashOrderId = $orderId;
            
            // Find the transaction by orderId (since we don't have the payment_token anymore)
            // Or use the transactionId if we stored it
            $transaction = $this->getTransactionByOrderId($moncashOrderId);
            
            if (!$transaction) {
                Log::error("MonCash webhook: Transaction not found for orderId: {$moncashOrderId}");
                return $res->status(404)->json(['success' => false, 'message' => 'Transaction not found']);
            }
            Log::info("transaction: " . print_r($transaction, true));
            
            // Update the transaction with the real transaction ID from MonCash
            $this->updateTransactionId($transaction['id'], $transactionId);
            
            // Update payment status
            $paymentStatus = $transactionDetails['payment']['message'] ?? '';
            $isSuccessful = strtolower($paymentStatus) === 'successful';
            
            if ($isSuccessful) {
                $this->handleSuccessfulMonCashPayment($transactionDetails, $transaction);
            } else {
                $this->handleFailedMonCashPayment($transactionDetails, $transaction);
            }
            
            return $res->status(200)->json(['success' => true]);
            
        } catch(\Exception $e) {
            Log::error("MonCash webhook error: {$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    /**
     * Получение деталей транзакции MonCash
     */
    private function getMonCashTransactionDetails($transactionId) {
        try {
            $moncash_client_id = $_ENV['MONCASH_CLIENT_ID'];
            $moncash_secret = $_ENV['MONCASH_SECRET'];
            
            // Получаем новый токен для запроса деталей
            $token_response = $this->getMonCashToken($moncash_client_id, $moncash_secret);
            $access_token = $token_response['access_token'] ?? '';
            
            if (!$access_token) {
                Log::error("Failed to get MonCash token for transaction details");
                return null;
            }
            
            $host = $_ENV['MONCASH_LIVE_HOST_REST_API'] ?? 'moncashbutton.digicelgroup.com/Api';
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => "https://{$host}/v1/RetrieveTransactionPayment",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Authorization: Bearer ' . $access_token,
                    'Content-Type: application/json',
                ],
                CURLOPT_POSTFIELDS => json_encode(['transactionId' => $transactionId]),
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::error("MonCash get transaction error: HTTP {$httpCode}, response: " . $response);
                return null;
            }

            return json_decode($response, true);
        } catch(\Exception $e) {
            Log::error("MonCash get transaction error: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Обработка успешного платежа MonCash
     */
    private function handleSuccessfulMonCashPayment($transactionDetails, $transaction) {
        try {
            Log::info("handling successful moncash payment");
            $transactionId = $transactionDetails['payment']['transaction_id'] ?? null;
            $amount = $transactionDetails['payment']['cost'] ?? 0;
            $payer = $transactionDetails['payment']['payer'] ?? null;
            
            // Находим заказ по orderId или transactionId
            if (isset($transaction['order_id'])) {
                $order = $this->orderModel->getOrderById($transaction['order_id']);
            } else {
                $order = $this->getOrderByTransactionId($transactionId);
                Log::info("order: " . print_r($order, true));
            }
            
            if (!$order) {
                Log::error("Order not found for MonCash payment: orderId={$transaction['order_id']}, transactionId={$transactionId}");
                return false;
            }
            
            // Обновляем статус заказа
            $this->orderModel->updatePaymentStatus($order['id'], 'paid');
            $this->orderModel->updateOrderStatus($order['id'], 'confirmed', 'Payment confirmed via MonCash');
            
            // Обновляем транзакцию
            $this->updateTransactionStatus($transactionId, 'completed');
            
            // Отправляем email подтверждения заказа
            $this->orderModel->sendOrderConfirmationEmail($order['id']);
            
            // Получаем позиции заказа и выдаем доступ к трансляциям
            $orderItems = $this->getOrderItems($order['id']);
            foreach ($orderItems as $item) {
                switch($item['product_type']){
                    case "premium_subscription":
                        return (new \Konektem\Models\AdminModel())->subscribeUserToPremium($order['user_id']);
                        break;
                    case "stream":
                        $accessResult = $this->orderModel->grantStreamAccess(
                            $order['user_id'], 
                            $item['id'] // Передаем ID позиции заказа (order_item_id)
                        );
                        
                        if ($accessResult['success']) {
                            Log::info("Stream access granted for user {$order['user_id']}, product {$item['product_id']}");
                        } else {
                            Log::error("Failed to grant stream access: " . ($accessResult['message'] ?? 'Unknown error'));
                        }
                        break;
                    default:
                        break;
                }
            }
            
            Log::info("MonCash payment successful: order_id={$order['id']}, transaction_id={$transactionId}");
            return true;
            
        } catch(\Exception $e) {
            Log::error("Error handling successful MonCash payment: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Обработка неудачного платежа MonCash
     */
    private function handleFailedMonCashPayment($transactionDetails, $transaction) {
        try {
            Log::info("handling failed moncash payment");
            $transactionId = $transactionDetails['payment']['transaction_id'] ?? null;
            
            if ($transaction['order_id']) {
                $order = $this->getOrderByNumber($transaction['order_id']);
            } else {
                $order = $this->getOrderByTransactionId($transactionId);
            }
            
            if ($order) {
                $this->orderModel->updatePaymentStatus($order['id'], 'failed');
                $this->orderModel->updateOrderStatus($order['id'], 'cancelled', 'Payment failed');
            }
            
            if ($transactionId) {
                $this->updateTransactionStatus($transactionId, 'failed');
            }
            
            Log::warning("MonCash payment failed: order_id=" . ($order['id'] ?? 'unknown') . ", transaction_id={$transactionId}");
            return true;
            
        } catch(\Exception $e) {
            Log::error("Error handling failed MonCash payment: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Получение транзакции по ID заказа
     */
    private function getTransactionByOrderId($orderId) {
        try {
            Log::info("searching for transaction by order id");
            $stmt = $this->orderModel->pdo->prepare("
                SELECT * FROM transactions 
                WHERE order_id = ?
                ORDER BY id DESC LIMIT 1
            ");
            $stmt->execute([$orderId]);
            return $stmt->fetch();
        } catch(\Exception $e) {
            Log::error("Error getting transaction by order: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Обновление транзакции с реальным transaction_id от MonCash
     */
    private function updateTransactionId($transactionDbId, $realTransactionId) {
        try {
            $stmt = $this->orderModel->pdo->prepare("
                UPDATE transactions 
                SET transaction_id = ?
                WHERE id = ?
            ");
            return $stmt->execute([$realTransactionId, $transactionDbId]);
        } catch(\Exception $e) {
            Log::error("Error updating transaction ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Создание заказа со статусом pending
     */
    private function createPendingOrder($userId, $productId, $amount) {
        try {
            $orderNumber = 'ORD_' . date('YmdHis') . '_' . rand(1000, 9999);
            if(!$this->orderModel->pdo->inTransaction()){
                $this->orderModel->pdo->beginTransaction();
            }
            $stmt = $this->orderModel->pdo->prepare("
                INSERT INTO orders (
                    user_id, 
                    order_number, 
                    total_amount, 
                    order_status, 
                    payment_status, 
                    payment_method,
                    order_date
                ) VALUES (?, ?, ?, 'pending', 'pending', 'moncash', NOW())
            ");
            $stmt->execute([$userId, $orderNumber, $amount]);
            
            $orderId = $this->orderModel->pdo->lastInsertId();
            
            // Создаем позицию заказа
            $stmt = $this->orderModel->pdo->prepare("
                INSERT INTO order_items (
                    order_id, 
                    product_id, 
                    quantity, 
                    price_at_time, 
                    discount, 
                    subtotal
                ) VALUES (?, ?, 1, ?, 0.00, ?)
            ");
            $stmt->execute([$orderId, $productId, $amount, $amount]);
            $this->orderModel->pdo->commit();
            return [
                'success' => true,
                'order_id' => $orderId,
                'order_number' => $orderNumber
            ];
            
        } catch(\Exception $e) {
            $this->orderModel->pdo->rollBack();
            Log::error("Error creating pending order: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "Server error"
            ];
        }
    }

    /**
     * Сохранение транзакции
     */
    private function saveTransaction($data) {
        try {
            $stmt = $this->orderModel->pdo->prepare("
                INSERT INTO transactions (
                    order_id,
                    transaction_id,
                    amount,
                    status,
                    payment_system,
                    payment_details,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $data['order_id'],
                $data['transaction_id'],
                $data['amount'],
                $data['status'] ?? 'pending',
                $data['payment_system'],
                json_encode($data['payment_details'] ?? []),
            ]);
            
            return $this->orderModel->pdo->lastInsertId();
            
        } catch(\Exception $e) {
            Log::error("Error saving transaction: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Обновление статуса транзакции
     */
    private function updateTransactionStatus($transactionId, $status) {
        try {
            $stmt = $this->orderModel->pdo->prepare("
                UPDATE transactions 
                SET status = ?
                WHERE transaction_id = ?
            ");
            return $stmt->execute([$status, $transactionId]);
        } catch(\Exception $e) {
            Log::error("Error updating transaction status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Получение заказа по номеру
     */
    private function getOrderByNumber($orderNumber) {
        try {
            Log::info("searching for order by order number: $orderNumber");
            $stmt = $this->orderModel->pdo->prepare("SELECT * FROM orders WHERE order_number = ?");
            $stmt->execute([$orderNumber]);
            return $stmt->fetch();
        } catch(\Exception $e) {
            Log::error("Error getting order by number: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Получение заказа по ID транзакции
     */
    private function getOrderByTransactionId($transactionId) {
        try {
            Log::info("searching for order by transaction id: $transactionId");
            $stmt = $this->orderModel->pdo->prepare("
                SELECT o.* 
                FROM orders o
                JOIN transactions t ON o.id = t.order_id
                WHERE t.transaction_id = ?
            ");
            $stmt->execute([$transactionId]);
            return $stmt->fetch();
        } catch(\Exception $e) {
            Log::error("Error getting order by transaction: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Получение позиций заказа
     */
    private function getOrderItems($orderId) {
        try {
            $stmt = $this->orderModel->pdo->prepare("
                SELECT 
                    oi.*,
                    p.product_type,
                    p.name as product_name
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = ?
            ");
            $stmt->execute([$orderId]);
            return $stmt->fetchAll();
        } catch(\Exception $e) {
            Log::error("Error getting order items: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Обработка успешного платежа (общий метод)
     */
    private function handleSuccessfulPayment($data) {
        try {
            $orderId = $data['order_id'] ?? null;
            if ($orderId) {
                $this->orderModel->updatePaymentStatus($orderId, 'paid');
                $this->orderModel->updateOrderStatus($orderId, 'confirmed', 'Payment confirmed');
            }
        } catch(\Exception $e) {
            Log::error("Error handling successful payment: " . $e->getMessage());
        }
    }

    // =============== MONCASH CALLBACK ===============
    public function moncashCallback($req, $res) {
        try {
            $status = $_GET['status'] ?? null;
            $token = $_GET['token'] ?? null;
            
            Log::info("MonCash callback: status={$status}, token={$token}");
            
            if ($status === 'cancel' || $status === 'cancelled') {
                // Пользователь отменил оплату
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'Payment cancelled',
                    'redirect' => '/payment/moncash?status=cancelled'
                ]);
            }
            
            // Успешная оплата - проверяем статус
            if ($token) {
                $transactionDetails = $this->getMonCashTransactionDetails($token);
                
                if ($transactionDetails && isset($transactionDetails['payment']['message']) 
                    && strtolower($transactionDetails['payment']['message']) === 'successful') {
                    
                    return $res->status(200)->json([
                        'success' => true,
                        'message' => 'Payment successful',
                        'redirect' => '/payment/moncash?status=success'
                    ]);
                }
            }
            
            return $res->status(200)->json([
                'success' => false,
                'message' => 'Payment verification failed',
                'redirect' => '/payment/failed'
            ]);
            
        } catch(\Exception $e) {
            Log::error("MonCash callback error: " . $e->getMessage());
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    // =============== DEVELOPMENT: TESTING ===========
        /**
     * Send a mock MonCash webhook response for testing
     * 
     * @param string $orderId The order ID to send in the webhook
     * @param bool $success Whether the payment should be successful or failed
     * @param string|null $customTransactionId Optional custom transaction ID
     * @return array Response from the webhook
     */
    public function sendMockMonCashWebhook($orderId, $amount, $success = true) {
        try {
            // Generate a random transaction ID if not provided
            $transactionId = $this->generateMonCashTransactionId();
            
            // Build the mock webhook payload
            $payload = [
                'transactionId' => $transactionId,
                'orderId' => $orderId,
                'payment' => [
                    'transaction_id' => $transactionId,
                    'cost' => $amount, // You might want to pass this as a parameter
                    'message' => $success ? 'successful' : 'failed',
                    'payer' => [
                        'phone' => '50912345678',
                        'email' => 'test@example.com',
                        'name' => 'Test User'
                    ],
                    'status' => $success ? 'COMPLETED' : 'FAILED'
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            // If you want to add more realistic MonCash response fields
            if ($success) {
                $payload['payment']['transaction_reference'] = 'REF_' . strtoupper(uniqid());
                $payload['payment']['payment_method'] = 'MonCash';
            }
            
            Log::info("Sending mock MonCash webhook: " . print_r($payload, true));
            
            // Get the webhook URL from environment or config
            $webhookUrl = $_ENV['MONCASH_WEBHOOK_URL'] ?? "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/api/payment/moncash/webhook";
            
            // Send the webhook
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $webhookUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    // Optional: Add a secret key for webhook authentication
                    'X-Webhook-Secret: ' . ($_ENV['WEBHOOK_SECRET'] ?? 'test-secret-key')
                ],
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_TIMEOUT => 30,
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                Log::error("Mock webhook cURL error: " . $error);
                return [
                    'success' => false,
                    'message' => 'Failed to send mock webhook: ' . $error,
                    'payload' => $payload
                ];
            }
            
            Log::info("Mock webhook response: HTTP {$httpCode}, response: " . $response);
            
            return [
                'success' => true,
                'http_code' => $httpCode,
                'response' => json_decode($response, true),
                'payload' => $payload,
                'transaction_id' => $transactionId
            ];
            
        } catch(\Exception $e) {
            Log::error("Error sending mock MonCash webhook: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate a realistic MonCash transaction ID
     * 
     * @return string Random transaction ID
     */
    private function generateMonCashTransactionId() {
        // MonCash transaction IDs typically look like: TXN_XXXXXXXXXX
        $prefix = 'TXN_';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 10;
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $prefix . $randomString . date('Ymd');
    }

    /**
     * Batch send mock webhooks for multiple orders
     * 
     * @param array $orderIds Array of order IDs
     * @param bool $success Whether all payments should be successful
     * @return array Results for each order
     */
    public function sendBatchMockMonCashWebhooks($orderIds, $success = true) {
        $results = [];
        
        foreach ($orderIds as $orderId) {
            $result = $this->sendMockMonCashWebhook($orderId, $success);
            $results[$orderId] = $result;
            
            // Add a small delay to simulate real webhook behavior
            usleep(500000); // 0.5 second delay
        }
        
        return $results;
    }
}
?>