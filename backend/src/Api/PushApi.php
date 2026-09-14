<?php
namespace Konektem\Api;

require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Konektem\Utils\Log;
$dotenv = Dotenv::createImmutable(BASE_DIR, '.env');
$dotenv->load();

use function curl_setopt;
use function curl_init;
use function curl_close;

Log::init();
class PushApi{
    function sendOSPushNotification($title, $content, $url=''){
        try {
            Log::info('sending notifications about ' . $title);
            $api_key = $_ENV['OS_API_KEY'];
            $app_id = $_ENV['OS_PN_APP_ID'];
            $base_url = 'http://localhost/vkurse' . $url;

            $data = [
                'app_id' => $app_id,
                'headings' => [
                    'en' => $title
                ],
                'contents' => [
                    'en' => $content
                ],
                'url' => $base_url,
                'included_segments' => ['Total Subscriptions']
            ];

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://onesignal.com/api/v1/notifications',
                CURLOPT_HTTPHEADER => [
                    'Authorization: Basic ' . $api_key,
                    'Content-Type: application/json'
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data)
            ]);
            
            $response = curl_exec($ch);
            Log::info("original response: " . $response);
            curl_close($ch);
            $response = json_decode($response. true);
            Log::info("json response: " . $response);

            if($response['error']){
                Log::error('OS_notifications failed');
            } else {
                Log::info('OS_notifications sent successfully');
            }
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
    }
}
?>