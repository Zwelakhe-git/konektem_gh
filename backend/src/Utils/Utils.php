<?php
namespace Konektem\Utils;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Utils\FileUploader;
use Konektem\Utils\Log;
use function curl_init;
use function curl_setopt;
use function curl_exec;
use function curl_close;

Log::init();
class Utils{
    
    private $uploader;
    
    public function generate_slug($title){
        
        return preg_replace('/-+/', '-',
                trim(preg_replace('/[^a-z0-9]+/', '-',
                    $this->translate(strtolower($title),
                            'абвгдеёжзийклмнопрстуфхцчшщъыьэюя',
                            'abvgdeejziyklmnoprstufhccssyyeya')
                )
                ,'-')
            );
    }

    /**
     * changes all symbols from the first string to the corresponding symbols
     * in the second string
     * if the sizes of the strings are not equal then change only the available symols
     * @param data [str] the source string
     * @param from_chars [str] symbols to be translated
     * @param to_chars [str] translations
     */
    public function translate($data, $from_chars, $to_chars): string{
        for($i = 0; $i < strlen($from_chars) && $i < strlen($to_chars); ++$i){
            $data = str_replace($from_chars[$i], $to_chars[$i], $data);
        }
        return $data;
    }

    public function upload($upload_dir, $types, $file){
        $this->uploader = new FileUploader($upload_dir, $types);
        return $this->uploader->upload($file);
    }
    public function deleteFile($filePath, $path_type='url'){
        $this->uploader = new FileUploader();
        $this->uploader->delete_file($filePath);
    }
    
    public function sendOSPushNotification($title, $content, $url){
        Log::info("sending notifications about $title");
        $api_key = OS_APP_API_KEY;
        $app_id = OS_PN_APP_ID;
        $base_url = "https://konektem.net$url";

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
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . $api_key,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
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
    }

    public function generate_api_key(){
        $key = uniqid('konektem', true);
        return $key;
    }

    public function normalizeUrl($url) {
        $parts = parse_url($url);
        $path = isset($parts['path']) ? $parts['path'] : '';
        
        // Нормализуем путь
        $segments = explode('/', $path);
        $result = [];
        
        foreach ($segments as $seg) {
            if ($seg === '' || $seg === '.') continue;
            if ($seg === '..') {
                array_pop($result);
            } else {
                $result[] = $seg;
            }
        }
        
        $normalizedPath = '/' . implode('/', $result);
        
        // Собираем обратно
        $scheme = isset($parts['scheme']) ? $parts['scheme'] . '://' : '';
        $host = isset($parts['host']) ? $parts['host'] : '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';
        
        return $scheme . $host . $port . $normalizedPath . $query . $fragment;
    }
}
?>