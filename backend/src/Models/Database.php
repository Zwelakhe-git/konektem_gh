<?php
namespace Konektem\Models;

error_reporting(E_ALL); 
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../log/log.log');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/servers.php';
require_once HTDOCS . '/vendor/autoload.php';

use PDO;
use Predis\Client;
use Predis\Connection\ConnectionException;
use Konektem\Utils\Log;


Log::init();
class Database {
    protected static $sharedPdo = null; // Static shared connection
    protected static $sharedRedisClient = null;
    protected static $sharedStorePdo = null;
    public $pdo;
    public $storePdo;
    public $redisClient;
    private $lastConnectTime = 0;
    private $queryCount = 0;
    
    private const MAX_RETRIES = 2;
    private const CONNECT_TIMEOUT = 300;
    private const QUERY_TIMEOUT = 10;
    private const RECONNECT_DELAY = 2;
    private const MAX_QUERIES_PER_CONNECTION = 100;
    
    public function __construct() {
        // Use shared connection instead of creating new one
        if (self::$sharedPdo === null) {
            self::$sharedPdo = $this->createConnection();
        }
        if (self::$sharedStorePdo === null){
            self::$sharedStorePdo = $this->createConnection(STORE_DB_NAME);
        }
        if(self::$sharedRedisClient === null){
            self::$sharedRedisClient = $this->createRedisConnection();
        }
        $this->pdo = self::$sharedPdo;
        $this->storePdo = self::$sharedStorePdo;
        $this->redisClient = self::$sharedRedisClient;
    }
    
    private function createConnection($dbName = DB_NAME) {
        $retryCount = 0;
        while ($retryCount <= self::MAX_RETRIES){
            try {
                $pdo = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . $dbName . ";charset=utf8mb4",
                    DB_USER, 
                    DB_PASS,
                    [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_PERSISTENT => false,
                        PDO::ATTR_TIMEOUT => self::CONNECT_TIMEOUT,
                        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                    ]
                );
                
                $this->lastConnectTime = time();
                Log::info("Database connection established successfully");
                return $pdo;

            } catch(\PDOException $e) {
                $errorMessage = $e->getMessage();
                Log::error("Database connection attempt " . ($retryCount + 1) . " failed: " . $errorMessage);
                
                if (strpos($errorMessage, 'max_user_connections') !== false) {
                    $retryCount++;
                    if ($retryCount <= self::MAX_RETRIES) {
                        Log::error("Waiting " . self::RECONNECT_DELAY . " seconds before retry...");
                        sleep(self::RECONNECT_DELAY);
                        continue;
                    }
                }

                if ($retryCount === 0) {
                    sleep(1);
                    $retryCount++;
                    continue;
                }

                $this->handleCriticalError($errorMessage);
            }
        }
    }
    private function createRedisConnection(){
        $retryCount = 0;
        
        while ($retryCount <= self::MAX_RETRIES){
            try {
                //$client = new Redis();
                //$client->connect(REDIS_HOST, REDIS_PORT);
                $client = new Client(REDIS_URL);
                $client->connect();
                
                if($client->isConnected()){
                    Log::info("Redis connection established successfully");
                	return $client;
                } else {
                    Log::info("retrying to connect to redis...");
                    $retryCount++;
                }
            } catch(ConnectionException $e){
                $errorMessage = $e->getMessage();
                Log::error("Redis connection attempt " . ($retryCount + 1) . " failed: " . $errorMessage);
                
                //if (strpos($errorMessage, 'max_user_connections') !== false) {
                $retryCount++;
                if ($retryCount <= self::MAX_RETRIES) {
                    Log::error("Waiting " . self::RECONNECT_DELAY . " seconds before retry...");
                    sleep(self::RECONNECT_DELAY);
                    continue;
                }
                //}

                if ($retryCount === 0) {
                    sleep(1);
                    $retryCount++;
                    continue;
                }
            } catch(\Exception $e){
                Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
                $retryCount++;
            } catch(\Throwable $e){
                Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
                $retryCount++;
            }
        }
        return null;
    }

    public function isConnected(){
        try {
            $this->pdo->query('SELECT 1');
            return true;
        } catch(\PDOException $e){
            return false;
        }
    }

    public function reconnect(){
        self::$sharedPdo = $this->createConnection();
        $this->pdo = self::$sharedPdo;
    }
    
    public static function closeSharedConnection() {
        if (self::$sharedPdo !== null) {
            self::$sharedPdo = null;
            Log::info("Shared database connection closed");
        }
        if (self::$sharedRedisClient !== null) {
            self::$sharedRedisClient = null;
            Log::info("Shared sharedRedisClient connection closed");
        }
    }

    public function getPdo(){
        return $this->pdo;
    }

    public function getSharedPdo(){
        return self::$sharedPdo;
    }
    
    private function handleCriticalError($errorMessage) {
        Log::error("CRITICAL DB ERROR: " . $errorMessage);
        
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            die("Database connection failed: " . htmlspecialchars($errorMessage));
        }
        
        if (!headers_sent()) {
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Retry-After: 30');
        }
        
        if (file_exists(TEMPLATES_DIR . '/errors/database_error.html')) {
            include TEMPLATES_DIR . '/errors/database_error.html';
        } else {
            Log::warn('Service Temporarily Unavailable');
            Log::warn('The database is currently experiencing high load. Please try again in a few moments.');
        }
        exit;
    }

}
?>