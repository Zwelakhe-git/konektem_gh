<?php
namespace Konektem\Api;
require_once __DIR__ . '/../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\View\View;
use Konektem\Admin\Controllers\AdminController;
use Konektem\Admin\Models\ArticlesModel;
use Konektem\Auth\Auth;

$view = new View(TEMPLATES_DIR);

$newsApi = function($req, $res) use($view) {
    $controller = new AdminController();
    $auth = new Auth();
    $cat = $req->params->category;
    $apiKey = $req->params->key;
    $quantity = $req->params->qty;

    if(!$auth->acceptAPIKey($apiKey)){
        return [
            'success' => false,
            'message' => 'invalid api key'
        ];
    }
    $model = new ArticlesModel();

    $res = $model->getAllArticles('local', $cat);

    $res->json->send(array_splice($res, 0, $quantity));
};
?>