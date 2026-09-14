<?php
namespace Konektem\Utils;

require_once __DIR__ . '/../../config/config.php';
include HTDOCS .'/vendor/autoload.php';

use XPRSS\Application;
use XPRSS\Router;

class CorsRouter {
    private $router;
    
    public function __construct(Router $router) {
        $this->router = $router;
    }
    
    // Обёртка для всех методов
    public function get($route, $callback) {
        $this->router->get($route, function($req, $res) use ($callback) {
            $this->setCorsHeaders($req);
            return $callback($req, $res);
        });
    }
    
    public function post($route, $callback) {
        $this->router->post($route, function($req, $res) use ($callback) {
            $this->setCorsHeaders($req);
            return $callback($req, $res);
        });
    }
    
    public function put($route, $callback) {
        $this->router->put($route, function($req, $res) use ($callback) {
            $this->setCorsHeaders($req);
            return $callback($req, $res);
        });
    }
    
    public function delete($route, $callback) {
        $this->router->delete($route, function($req, $res) use ($callback) {
            $this->setCorsHeaders($req);
            return $callback($req, $res);
        });
    }

    public function use(string $baseURL, array $routes){
        foreach($routes as $route){
            $this->{"{$route['method']}"}($baseURL . $route['url'], $route['handler']);
        }
    }
    
    private function setCorsHeaders($req) {
        // Можно добавить логику проверки allowed origins
        $allowedOrigins = ['*']; // или ['https://site1.com', 'https://site2.com']
        $origin = $req->headers['origin'] ?? '';
        
        if (in_array('*', $allowedOrigins) || in_array($origin, $allowedOrigins)) {
            header('Access-Control-Allow-Origin: ' . ($origin ?: '*'));
        }
        
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        // Обработка preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
}
