<?php

function fetch($url, $method='GET', $body=[], $headers=[]){
    if(!strpos($url, 'http')){
        $url = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}" . $url;
    }
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . "");

        if($method === 'POST'){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }
        if($headers){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $response;
    } catch(\Exception $e){
        \Konektem\Utils\Log::error($e->getMessage());
        return null;
    } catch(\Throwable $e){
        \Konektem\Utils\Log::error($e->getMessage());
        return null;
    }
}
?>