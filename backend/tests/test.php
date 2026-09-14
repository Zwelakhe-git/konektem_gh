<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

$auth = new \Konektem\Auth\Auth();

function http(string $url, string $method = 'GET', array $body = [], array $headers = []){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if($method === 'POST'){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    }
    if($headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $bodyResponse = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'body' => $bodyResponse];
}
require __DIR__ . '/../../frontend/src/Utils/ajax.php';
require_once __DIR__ . '/config.php';

$respose = fetch('/user/me/albums', 'GET', [
    'item' => 'interview', 'id' => 4
], [
    'Authorization: Bearer ' . TEST_TOKEN
]);
print_r($respose);
?>