<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/backend/logs/log.log');

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/backend/urls.php';
require_once __DIR__ . '/backend/routes/store.php';
require_once __DIR__ . '/backend/routes/app.php';
require_once __DIR__ . '/backend/routes/dataApi.php';
require_once __DIR__ . '/backend/routes/api.php';
require_once __DIR__ . '/backend/routes/auth.php';
require_once __DIR__ . '/backend/routes/user.php';
require_once __DIR__ . '/backend/routes/admin.php';

use XPRSS\Application;
use XPRSS\Router;
use Konektem\Utils\Log;
use Konektem\Utils\CorsRouter;

Log::init();

$app = new Application();
$router = new Router();
$corsRouter = new CorsRouter($router);

function handleError($errno, $errstr, $errfile, $errline){
    $errName = getErrorType($errno);
    Log::error("$errName [$errno] $errstr at $errfile line $errline");
}

function handleException($exception){
    Log::error("Exception | {$exception->getMessage()} in file {$exception->getFile()} line {$exception->getLine()}");
}

set_error_handler('handleError');
set_exception_handler('handleException');


$corsRouter->use('', $appRoutes);
$corsRouter->use('/store', $storeRoutes);
$corsRouter->use('/api/v1', $dataApiRoutes);
$corsRouter->use('/api', $apiRoutes);
$corsRouter->use('/auth', $authRoutes);
$corsRouter->use('/user', $userRoutes);
$corsRouter->use('/admin', $adminRoutes);

foreach($urlpatterns as $pattern){
    try {
        $corsRouter->{$pattern['method']}($pattern['url'], $pattern['handler']);
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
    } catch(\Throwable $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
    }
}

$app->listen($router);
?>