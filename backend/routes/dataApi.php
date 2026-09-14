<?php
require_once __DIR__ . '/path.php';
require_once __DIR__ . '/../controllers.php';

use Konektem\Utils\Log;
Log::init();

$dataApi = function($req, $res){
    $pattern = '/api\/v\d+\/([^\/]+)(\/.*)?/';

    if(preg_match($pattern, $_GET['route'], $matches)){
        $item = $matches[1];
        $apiController = new Konektem\Api\Api();
        switch($item){
            case "news":
                return $apiController->newsApi($req, $res);
                break;
            case "site-settings":
                return $apiController->siteSettingsApi($req, $res);
                break;
            case "interviews":
                return $apiController->interviewsApi($req, $res);
                break;
            case "events":
                return $apiController->eventsApi($req, $res);
                break;
            case "media-stats-update":
                return $apiController->mediaStatsApi($req, $res);
            case "books":
                return $apiController->booksApi($req, $res);
                break;
            case "albums":
                return $apiController->albumsApi($req, $res);
                break;
            case "music":
                return $apiController->musicApi($req, $res);
                break;
            case "services":
                return $apiController->servicesApi($req, $res);
                break;
            case "livestreams":
                $streams = (new \Konektem\Controllers\LiveStreamController())->get();
                return $res->status(200)->json([
                    'success' => true,
                    'data' => $streams
                ]);
                break;
            deafult:
                $res->json([
                    'success' => false,
                    'message' => 'invalid url: ' . $_SERVER['REQUEST_URI']
                ]);
        }
    } else {
        $res->json([
            'success' => false,
            'message' => 'invalid url'
        ]);
    }
};
$dataApiRoutes = [
    /* api urls */
    path_('/books/all', 'get', $dataApi,'books_api_all'),
    path_('/books/all/:qty', 'get', $dataApi,'books_api_all_qty'),
    path_('/books/genre/:genre', 'get', $dataApi,'books_api_cat'),
    path_('/books/genre/:genre/:qty', 'get', $dataApi,'books_api_cat_qty'),
    path_('/books/id/:id', 'get', $dataApi,'books_api_id'),
    path_('/books/author/:author_name', 'get', $dataApi,'books_api_author'),
    path_('/site-settings/key/:key', 'get', $dataApi,'site_settings'),
    path_('/site-settings/:group', 'get', $dataApi,'site_settings'),
    path_('/interviews/:qty', 'get', $dataApi,'interviews'),
    path_('/events/:qty', 'get', $dataApi,'events'),
    path_('/news/category/:category/:qty', 'get', $dataApi,'news'),
    path_('/news/:qty', 'get', $dataApi,'news'),
    path_('/media-stats-update/:item/:id/:action/', 'get', $dataApi,'news'),
    path_('/music/genre/:genre/:qty', 'get', $dataApi,'music'),
    path_('/music/artist/:artist', 'get', $dataApi,'music'),
    path_('/music/:qty', 'get', $dataApi,'music'),
    path_('/albums/genre/:genre/:qty', 'get', $dataApi,'music'),
    path_('/albums/artist/:artist', 'get', $dataApi,'music'),
    path_('/albums/:qty', 'get', $dataApi,'music'),
    path_('/services', 'get', $dataApi,'services'),
    path_('/services/:qty', 'get', $dataApi,'services'),
    path_('/livestreams', 'get', $dataApi, 'livestream'),
];
?>