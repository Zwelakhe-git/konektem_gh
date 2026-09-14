<?php
namespace Konektem\RedisManager;
require_once __DIR__ . '/../../config/config.php';

use Predis\Client;

class RedisManager{
    private $redisClient;
    public function __construct(array $redis_config, string $redis_url){
        if($redis_url){
            $this->redisClient = new Client($redis_url);
        } else {
            $this->redisClient = new Client($redis_config);
        }
    }

    public function query($query){}

    public function set($key, $value){}

    public function get($key){}

    public function del($key){}

    public function hset($key, array $payload){}
}
?>