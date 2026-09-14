<?php
require_once __DIR__ . '/path.php';
require_once __DIR__ . '/../controllers.php';

use Konektem\Utils\Log;
Log::init();



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
$paymentRoutes = [
    path_('/payment/:platform/webhook', 'post', $paymentWebhook, 'payment_webhook'),
    path_('/payment/:platform/webhook', 'put', $paymentWebhook, 'payment_webhook'),
    path_('/payment/:platform/webhook', 'get', $paymentWebhook, 'payment_webhook'),
    
];
?>