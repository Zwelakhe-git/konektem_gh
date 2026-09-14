<?php
require_once __DIR__ . '/config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

// ======= CONTROLLERS =======
/*use Konektem\Controllers\ArticleController;
use Konektem\Controllers\MusicController;
use Konektem\Controllers\BooksController;
use Konektem\Controllers\AdminController;
use Konektem\Controllers\AlbumController;*/
use Konektem\Controllers\DownloadController;

// ====== MODELS =======
use Konektem\Models\MusicModel;
/*use Konektem\Models\QRCodeModel;
use Konektem\Models\AlbumModel;*/
use Konektem\View\View;
use Konektem\Utils\Log;
/*use Konektem\Utils\Utils;*/

Log::init();
$view = new View(TEMPLATES_DIR);
$GLOBALS['error5xPage'] = file_get_contents(TEMPLATES_DIR . '/Error/5xx.html');
$GLOBALS['error4xPage'] = file_get_contents(TEMPLATES_DIR . '/Error/404.html');

function find_(array $arr, $callback){
    foreach($arr as $indx => $el){
        if($callback($el)){
            return [$indx, $el];
        }
    }
    return [-1, null];
}

function getScript($url, array $params = null, array $values = null){
    $result = [
        'url' => $url
    ];
    if(($params && $values) && (count($params) === count($values))){
        $result['params'] = array_combine($params, $values);
    }
    return $result;
}

$profile = function($req, $res) use ($view){
    
    try {    
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        //Log::info("profile method called");
        $allowedActions = ['edit', 'create', 'list', 'preview'];
        $controller = new \Konektem\Controllers\AdminController();
        $controller->requireLogin();
        // display важнее form-controls
        $scripts = [
            // ape.hash contains centerPoster, which is used by display-control
            getScript(BASE_URL . '/static/js/vanish-on-scroll-simple.js'),
            //getScript(BASE_URL . '/dist/admin_page_script.e3516037a3e53a55c98b.js', ['defer'], ['']),
        ];
        $styles = [
            BASE_URL . '/static/admin/css/header.css',
            BASE_URL . '/static/admin/css/global.css',
        ];
        $context = [
            'title' => '',
            'styles' => $styles,
            'scripts' => $scripts,
        ];
        if(preg_match('/admin/', $_GET['route'])){
            $dir = 'Admin';
        } else {
            $uid = $_SESSION['user']['id'];
            //$user = (new \Konektem\Models\UserModel())->getUserDetails(null, null, $uid);
            //$orderModel = new \Konektem\Models\OrderModel();
            //$user['orders'] = $orderModel->getOrdersByUser($uid);
            $context['user'] = $_SESSION['user'];//$user;
            $dir = 'User';
        }
        $template = TEMPLATES_DIR . '/' . $dir;
        preg_match('/(user\/me|admin)\/([^\/]*)(\/\d+)?(\/.*)?/', $_GET['route'], $matches);
        $item = count($matches) > 1 ? $matches[2] : 'dashboard';
        if($item !== 'dashboard'){
            //Log::info(print_r($matches, true));
            //$item = $req->params->item;
            $context['page'] = $item;
            $template .= "/{$item}";
            if($item === 'music' || $item === 'albums'){
                $context['artists'] = (new MusicModel())->getAllArtists();
            }
            
            $action = (isset($matches[4]) && !empty($matches[4])) ? ltrim($matches[4], '/') : 'list';

            if($action === 'edit' || $action === 'create'){
                $context['scripts'][] = getScript(BASE_URL . '/static/js/tinymce-init.js');
                $context['scripts'][] = getScript(BASE_URL . '/static/js/profile-page-form-controls.js');
            }

            if(!in_array($action, $allowedActions)){
                return $res->redirect($GLOBALS['error4xPage']);
            }
            //if($item !== 'albums'){
                //$context['scripts'][] = getScript(BASE_URL . '/static/js/tinymce-init.js');
            $context['scripts'][] = getScript(BASE_URL . '/static/js/admin_profile_control.js', ['type', 'defer'], ['module', '']);
            //}
            $mainPageControlledItems = [
                'news', 'events', 'interviews', 'music'
            ];
            if(in_array($item, $mainPageControlledItems) && $action === 'list'){
                $context['styles'][] = BASE_URL . '/static/css/profile-page-list.css';
                $context['scripts'][] = getScript(BASE_URL . '/static/js/profile-page-display-filter.js');
                $context['scripts'][] = getScript(BASE_URL . '/static/js/profile-page-select-mode.js');
            }
            switch($item){
                case "albums":
                    $controller = new \Konektem\Controllers\AlbumController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $album = $controller->get(['id' => ltrim($matches[3], '/')]);
                        $context['album'] = $album;
                        if($album){
                            $qr = new \Konektem\Models\QRCodeModel("https://konektem.net/konektem/album/{$album['id']}");
                            $context['qrcode_base64'] = $qr->getBase64Data();
                        }
                    } else if($action === 'list'){
                        $albums = $controller->get(['user_id' => $_SESSION['user']['id']]);
                        $context['albums'] = $albums;
                    } else if($action === 'create'){
                        //$context['scripts'] = array_merge($context['scripts'], [getScript(BASE_URL . '/dist/albums_admin.5b13af9b8c4913243f7d.js', ['defer'], [''])]);
                    } else if($action === 'delete' && isset($matches[3]) && !empty($matches[3])){
                        $controller->delete(ltrim($matches[3], '/'));
                        $action = 'list';
                    }
                    break;
                case "music":
                    $controller = new \Konektem\Controllers\MusicController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $track = $controller->get(['id' => ltrim($matches[3], '/')]);
                        $context['track'] = $track;
                    } else if($action === 'list'){
                        $tracks = $controller->get(['user_name' => $_SESSION['user']['name']]);
                        $context['tracks'] = $tracks;
                    }
                    if($action === 'edit' || $action === 'create'){
                        $context['scripts'][] = getScript(BASE_URL . '/static/js/profile-page-music.js', ['type'], ['module']);
                    }
                    break;
                case "news":
                    $controller = new \Konektem\Controllers\ArticleController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $article = $controller->get(['id' => ltrim($matches[3], '/')]);
                        $context['article'] = $article;
                    } else if($action === 'list'){
                        $articles = $controller->get();
                        $context['articles'] = $articles;
                    }
                    break;
                case "events":
                    $controller = new \Konektem\Controllers\EventController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $event = $controller->get(['id' => ltrim($matches[3], '/')]);
                        $context['event'] = $event;
                    } else if($action === 'list'){
                        $events = $controller->get(['user_name' => $_SESSION['user']['name']]);
                        $context['events'] = $events;
                    }
                    break;
                case "interviews":
                    $controller = new \Konektem\Controllers\InterviewController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $interview = $controller->get(['id' => ltrim($matches[3], '/')]);
                        $context['interview'] = $interview;
                    } else if($action === 'list'){
                        // add the user_id key in the get params
                        $interviews = $controller->get();
                        $context['interviews'] = $interviews;
                        
                    }
                    break;
                case "stream":
                    $controller = new \Konektem\Controllers\LiveStreamController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $stream = $controller->get(['id' => ltrim($matches[3], '/')]);
                        $context['stream'] = $stream;
                    } else if($action === 'list'){
                        $streams = $controller->get();
                        $context['streams'] = $streams;
                    }
                    break;
                case "orders":
                    $controller = new \Konektem\Controllers\OrderController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $order = $controller->get(['id' => ltrim($matches[3], '/')]);
                        if($order['user_id'] !== $_SESSION['user']['id']){
                            return $res->redirect(BASE_URL . '/error/404');
                        }
                        $context['order'] = $order;
                        $context['styles'][] = BASE_URL . "/static/css/profile-order-preview.css";
                    } else if($action === 'list'){
                        $orders = $controller->get(['user_id' => $_SESSION['user']['id']]);
                        $context['orders'] = $orders;
                        $context['styles'][] = BASE_URL . "/static/css/profile-orders-list.css";
                    }
                    break;
                case "partners":
                    $controller = new \Konektem\Controllers\PartnersController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $partner = $controller->get(['id' => ltrim($matches[3], '/')]);
                        $context['partner'] = $partner;
                    } else if($action === 'list'){
                        $partners = $controller->get();
                        $context['partners'] = $partners;
                    }
                    break;
                case "books":
                    $controller = new \Konektem\Controllers\BooksController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $book = $controller->get(['id' => ltrim($matches[3], '/')]);
                        $context['book'] = $book;
                    } else if($action === 'list'){
                        $books = $controller->get(['user_name' => $_SESSION['user']['name']]);
                        $context['books'] = $books;
                    }
                    if($action === 'edit' || $action === 'create'){
                        $context['scripts'][] = getScript(BASE_URL . '/static/js/profile-page-books.js', ['type'], ['module']);
                    }
                    break;
                case "services":
                    $controller = new \Konektem\Controllers\ServiceController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $service = $controller->get(['id' => ltrim($matches[3], '/')]);
                        $context['service'] = $service;
                    } else if($action === 'list'){
                        $services = $controller->get(['user_name' => $_SESSION['user']['name']]);
                        $context['services'] = $services;
                    }
                    break;
                case "settings":
                    $controller = new \Konektem\Controllers\SiteSettingController();
                    if(($action === 'edit' || $action === 'preview') && isset($matches[3]) && !empty($matches[3])){
                        $setting = $controller->get(['id' => ltrim($matches[3], '/')]);
                        $context['setting'] = $setting;
                    } else if($action === 'list'){
                        $settings = $controller->get();
                        $groups = array_reduce($settings, function($acc, $prev){
                            if(!array_key_exists($prev['setting_group'], $acc)){
                                $acc[$prev['setting_group']] = [];
                            }
                            $acc[$prev['setting_group']][] = $prev;
                            return $acc;
                        }, []);
                        $context['groupedSettings'] = $groups;
                    }
                    break;
            }
            $template .= "/{$action}.php";
        } else {
            $template .= '/dashboard.php';
            $context['page'] = 'dashboard';
        }

        $context['template'] = $template;
        
        $res->send($view->render(TEMPLATES_DIR . "/$dir/layout/layout", $context));
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        return $res->redirect(BASE_URL . '/error/500');
    } catch(\Throwable $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        return $res->redirect(BASE_URL . '/error/500');
    }
};


$download = function($req, $res) use($view){
    $item = $req->params->item;
    $id = $req->params->id;
    $controller = new DownloadController();
    $fileName = null;
    $fileUrl = null;

    $context = [
        'title' => 'Konektem Download',
        'styles' => [
            BASE_URL . '/static/css/global.css',
            BASE_URL . '/static/css/cards-boxes.css',
            BASE_URL . '/static/css/file-download.css',
            BASE_URL . '/static/css/theme.css'
        ]
    ];
    if($item === 'albums'){
        Log::info("downloading album");
        $result = $controller->downloadAlbum($id);
        $context = array_merge($context, $result);
    } else if($item === 'tracks'){
        Log::info("downloading track");
        $controller->downloadTrack($id);
    }
    $res->send($view->render("file-download", $context));
};


$albumsPostApi = function($req, $res){
    $api = new \Konektem\Api\ArticleApi();
    $tokenPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();

    if(empty($tokenPayload)){
        return $res->status(401)->json([
            'success' => false,
            'message' => 'missing Authorization header'
        ]);
    }
    
    if($tokenPayload['id'] !== $_SESSION['user']['id']){
        return $res->status(401)->json([
            'success' => false,
            'message' => 'Invalid token'
        ]);
    }
    
    $controller = new \Konektem\Controllers\AlbumsController();
    if(!in_array($req->params->action, ['like', 'edit', 'create', 'delete'])){
        return $res->status(500)->json([
            'success' => false,
            'message' => 'Invalid action'
        ]);
    }
    $result = $controller->{$req->params->action}();
};

$partnersPostApi = function($req, $res){
    $api = new \Konektem\Api\ArticleApi();
    $tokenPayload = (new \Konektem\Auth\Auth())->getAuthTokenPayload();

    if(empty($tokenPayload)){
        return $res->status(401)->json([
            'success' => false,
            'message' => 'missing Authorization header'
        ]);
    }
    
    if($tokenPayload['id'] !== $_SESSION['user']['id']){
        return $res->status(401)->json([
            'success' => false,
            'message' => 'Invalid token'
        ]);
    }
    
    $controller = new \Konektem\Controllers\PartnersController();
    $result = $controller->{$req->params->action}();
};

$serviceApi = function($req, $res){
    try {
        
        $serviceName = $req->params->service_name;
        switch($serviceName){
            case "download":
                return (new \Konektem\Services\DownloadService())->download($req, $res);
            case "like":
                return (new \Konektem\Services\LikeService())->like($req, $res);
            case "share":
                return (new \Konektem\Services\ShareService())->share($req, $res);
            case "play-track":
                return (new \Konektem\Services\MusicService())->playTrack($req, $res);
            default:
                return $res->status(400)->json([
                    'success' => false,
                    'message' => 'Invalid service name'
                ]);
        }
    } catch(\Exception $e){
        Log::error($e->getMessage());
        return $res->status(500)->json([
            'success' => false,
            'message' => 'Server error'
        ]);
    } catch(\Throwable $e){
        Log::error($e->getMessage());
        return $res->status(500)->json([
            'success' => false,
            'message' => 'Server error'
        ]);
    }
};

$template = function($req, $res) use($view){
    $model = new \Konektem\Models\Database();
    $auth = new \Konektem\Auth\Auth();
    $pdo = $model->pdo;
    try {
        $responseText = "";
        $ordersUpdateCount = 0;
        $pdo->beginTransaction();
        $sql = "SELECT o.id,o.total_amount AS amount, o.order_number,u.email FROM orders o JOIN users u ON u.id = o.user_id WHERE payment_token IS NULL";
        $stmt = $pdo->query($sql);
        $orders = $stmt->fetchAll();
        $stmt = $pdo->prepare("UPDATE orders SET payment_token = ? WHERE id = ?");
        Log::info(print_r($orders, true));
        foreach($orders as $order){
            $oid = $order['id'];
            $payload = [
                'amount' => $order['amount'],
                'order_id' => $oid,
                'order_number' => $order['order_number'],
                'email' => $order['email']
            ];
            $token = $auth->createJWTToken($payload);
            $success = $stmt->execute([$token, $oid]);
            $ordersUpdateCount += $success ? 1 : 0;
        }
        $pdo->commit();
        $responseText = "SUCCESSFULLY UPDATED $ordersUpdateCount ORDERS";
        $res->send($responseText);
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        $pdo->rollBack();
        $res->send("Failed to update data");
    }
};

$error = function($req, $res){
    $page = preg_match('/5/', $req->params->code) ? $GLOBALS['error5xPage'] : $GLOBALS['error4xPage'];
    return $res->send($page);
};
?>