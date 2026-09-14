<?php
namespace Konektem\Api;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Utils\Log;
use Konektem\Models\UserModel;
use Konektem\Models\AdminModel;
use Dotenv\Dotenv;
use Stripe\Stripe;

use DGCGroup\MonCashPHPSDK\Credentials;
use DGCGroup\MonCashPHPSDK\Configuration;
use DGCGroup\MonCashPHPSDK\PaymentMaker;
use DGCGroup\MonCashPHPSDK\Order;
use DGCGroup\MonCashPHPSDK\TransactionCaller;
use DGCGroup\MonCashPHPSDK\TransactionDetails;
use DGCGroup\MonCashPHPSDK\TransactionPayment;

use function curl_init;
use function curl_setopt_array;
use function curl_close;
use function curl_exec;

$dotenv = Dotenv::createImmutable(BASE_DIR, '.env');
$dotenv->load();

Log::init();
class PaymentApi{
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
                'error' => 'Server error' // keeping the error key to support older versions of the app
            ]);
        }
    }

    // =============== PAYPAL ===============
    public function handlePayPalCreateOrder($req, $res) {
        try {
            $data = (array)$req->body ?? $_POST;
            $paypal_client_id = $_ENV['PAYPAL_CLIENT_ID']; $paypal_secret = $_ENV['PAYPAL_SECRET'];
            
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
                        ]
                    ]]
                ])
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            $order_data = json_decode($response, true);
            
            if (isset($order_data['id'])) {
                return $res->status(200)->json(['id' => $order_data['id']]);
            } else {
                Log::error('PayPal order creation failed');
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
            $paypal_client_id = $_ENV['PAYPAL_CLIENT_ID']; $paypal_secret = $_ENV['PAYPAL_SECRET'];

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
            $moncash_client_id = $_ENV['MONCASH_CLIENT_ID']; $moncash_secret = $_ENV['MONCASH_SECRET'];
            if(!$moncash_client_id || !$moncash_secret){
                Log::warn("missing moncash client id and secret");
                return $res->status(200)->json([
                    'success' => false,
                    'messsage' => 'Server error'
                ]);
            }
            //Log::info("handling moncash payment. data:");
            //Log::info(print_r($data, true));
            $amount = floatval($data['amount'] ?? 1.80);
            $phone = preg_replace('/\D/', '', $data['phone'] ?? '');
            $email = $data['email'] ?? '';
            $f = $data['f'] ?? 'event'; // Get the service type from request
            $id = $data['id'] ?? '10'; // Get the ID from request
            $orderid = $data['orderid'] ?? '15'; // Get the order ID from request

            $orderId = uniqid('ORDER_', true);

            // Получение токена MonCash
            $token_response = $this->getMonCashToken($moncash_client_id, $moncash_secret);
            $access_token = $token_response['access_token'] ?? '';

            if (!$access_token) {
                //throw new \Exception('MonCash authentication failed');
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'MonCash authentication failed'
                ]);
            }

            // Создание платежа with correct return URLs
            $payment_data = [
                'amount' => $amount,
                'orderId' => $orderId,
                'redirect_url' => 'https://konektem.net/konektem/api/payment/moncash/webhook',
                'cancel_url' => 'https://konektem.net/konektem/payment/moncash/callback/?status=cancel',
                'webhook_url' => 'https://konektem.net/konektem/api/payment/moncash/webhook'
            ];

            $payment_response = $this->createMonCashPayment($access_token, $payment_data);

            if (isset($payment_response['payment_url'])) {
                return $res->status(200)->json([
                    'success' => true,
                    'payment_url' => $payment_response['payment_url']
                ]);
            } else {
                Log::error('MonCash payment creation failed');
                return $res->status(200)->json([
                    'success' => false,
                    'message' => 'MonCash payment creation failed'
                ]);
            }
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server error',
                'error' => 'Server error'
            ]);
        }
    }

    private function getMonCashToken($client_id, $secret) {
        try {
            $ch = curl_init();
            
            // Авторизация через URL (как в примере документации)
            $url = sprintf(
                'https://%s:%s@%s/oauth/token',
                urlencode($client_id),
                urlencode($secret),
                $_ENV['MONCASH_TEST_HOST_REST_API']
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
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            //Log::info("MonCash token response: HTTP {$httpCode}, body: {$response}");

            if ($error) {
                Log::error("MonCash cURL error: " . $error);
            }

            return json_decode($response, true);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} line {$e->getLine()}");
            return null;
        }
    }

    private function createMonCashPayment($access_token, $payment_data) {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => "https://{$_ENV['MONCASH_TEST_HOST_REST_API']}/v1/CreatePayment",
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
                ])
            ]);
            Log::info("moncash payment data: " . print_r($payment_data, true));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            //Log::info("MonCash create payment response: HTTP {$httpCode}, body: {$response}");

            $result = json_decode($response, true);
            
            // Формируем URL для редиректа на основе payment_token
            if (isset($result['payment_token']['token'])) {
                $gatewayBase = $_ENV['MONCASH_TEST_GATEWAY_BASE'];
                $result['payment_url'] = $gatewayBase . '/Payment/Redirect/?token=' . $result['payment_token']['token'];
            }
            
            return $result;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} line {$e->getLine()}");
            return null;
        }
    }

    public function moncashWebhook($req, $res){
        try {
            
        } catch(\Exception $e){}
    }
}
?>