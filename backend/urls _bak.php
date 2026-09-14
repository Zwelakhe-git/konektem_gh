<?php
require_once __DIR__ . '/config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

require_once __DIR__ . '/controllers.php';
require_once __DIR__ . '/routes/path.php';


$urlpatterns = [
    /*path_('', 'get', $index, 'index'),
    path_('/', 'get', $index, 'index'),
    path_('/actuality', 'get', $news, 'news'),
    path_('/actuality/:title_hash', 'get', $news, 'article'),
    path_('/music', 'get', $mainMusic, 'music'),
    path_('/music/album/id/:id', 'get', $albumPreview, 'album'),
    path_('/music/:section', 'get', $mainMusic, 'music'),
    path_('/music/artist/:name', 'get', $musicByArtist, 'music_filter'),
    path_('/music/id/:id', 'get', $trackById, 'track_id_filter'),
    path_('/events', 'get', $events, 'events'),
    path_('/interviews', 'get', $interviews, 'interviews'),
    path_('/interviews/id/:id', 'get', $interviews, 'interview_id'),
    path_('/books', 'get', $books, 'books'),
    path_('/books/id/:id', 'get', $bookById, 'book_id'),
    path_('/services/:id/order', 'get',$services, 'service-order'),
    path_('/services', 'get', $services, 'services'),
    path_('/stream', 'get', $livestream, 'stream'),
    path_('/contacts', 'get', $contacts, 'contacts'),
    path_('/checkout/:intent', 'get', $payment, 'payment'),
    path_('/payment/:platform/callback', 'get', $paymentCallback, 'payment_callback'),
    path_('/payment/:platform/confirm', 'get', $paymentConfirm, 'payment_confirmation'),
    path_('/error/:code', 'get', $error, 'error_page'),*/

    /* api urls */
    /*path_('/api/v1/books/all', 'get', $dataApi,'books_api_all'),
    path_('/api/v1/books/all/:qty', 'get', $dataApi,'books_api_all_qty'),
    path_('/api/v1/books/genre/:genre', 'get', $dataApi,'books_api_cat'),
    path_('/api/v1/books/genre/:genre/:qty', 'get', $dataApi,'books_api_cat_qty'),
    path_('/api/v1/books/id/:id', 'get', $dataApi,'books_api_id'),
    path_('/api/v1/books/author/:author_name', 'get', $dataApi,'books_api_author'),
    path_('/api/v1/site-settings/key/:key', 'get', $dataApi,'site_settings'),
    path_('/api/v1/site-settings/:group', 'get', $dataApi,'site_settings'),
    path_('/api/v1/interviews/:qty', 'get', $dataApi,'interviews'),
    path_('/api/v1/events/:qty', 'get', $dataApi,'events'),
    path_('/api/v1/news/category/:category/:qty', 'get', $dataApi,'news'),
    path_('/api/v1/news/:qty', 'get', $dataApi,'news'),
    path_('/api/v1/media-stats-update/:item/:id/:action/', 'get', $dataApi,'news'),
    path_('/api/v1/music/genre/:genre/:qty', 'get', $dataApi,'music'),
    path_('/api/v1/music/artist/:artist', 'get', $dataApi,'music'),
    path_('/api/v1/music/:qty', 'get', $dataApi,'music'),
    path_('/api/v1/albums/genre/:genre/:qty', 'get', $dataApi,'music'),
    path_('/api/v1/albums/artist/:artist', 'get', $dataApi,'music'),
    path_('/api/v1/albums/:qty', 'get', $dataApi,'music'),
    path_('/api/v1/services', 'get', $dataApi,'services'),
    path_('/api/v1/services/:qty', 'get', $dataApi,'services'),

    path_('/api/v1/livestreams', 'get', $dataApi, 'livestream'),*/

    /*path_('/api/livestream/:action', 'post', $liveStreamApi, 'stream_api'),
    path_('/api/payment/:platform/webhook', 'post', $paymentWebhook, 'payment_webhook'),
    path_('/api/payment/:platform/webhook', 'put', $paymentWebhook, 'payment_webhook'),
    path_('/api/payment/:platform/webhook', 'get', $paymentWebhook, 'payment_webhook'),
    path_('/api/payment', 'post', $paymentApi, 'payment_api'),*/
    
    /*path_('/api/(albums|music|news|events|interviews|stream|orders|partners|books|services|settings)/(create|edit)', 'post', $adminProfileApi, 'admin_profile_create'),
    path_('/api/(albums|music|news|events|interviews|stream|orders|partners|books|services|settings)/delete', 'delete', $adminProfileApi, 'admin_profile_delete'),*/

    /** service apis */
    path_('/api.service/:service_name', 'post', $serviceApi, 'service-api'),
    /*path_('/api/upload/:type/:source', 'post', $uploadApi, 'upload'),
    path_('/api/upload/:source', 'post', $uploadApi, 'upload'),
    path_('/api/delete-upload/:type/:source', 'get', $deleteUploadApi, 'upload'),*/

    /*path_('/auth/(login|register)', 'get', $login, 'login'),
    path_('/auth/admin/login', 'get', $login, 'admin_login'),
    path_('/auth/google', 'get', $googleAuth, 'google-login'),
    path_('/auth/google/callback', 'get',$googleAuthCallback, 'google-authenticate'),
    path_('/auth/logout', 'get', $logout, 'logout'),
    path_('/auth/admin/logout', 'get', $logout, 'admin_login'),*/

    /** download urls */
    //path_('/user/me/:item/:id/download', 'get', $download, 'download-url'),
    path_('/:item/:id/download', 'get', $download, 'download-url'),

    /* profile */
    /*path_('/user/me', 'get', $profile, 'profile-post'),
    path_('/user/api/data/(music|events)', 'get', $userApi, 'user-data'),
    path_('/user/me/:item', 'get', $profile, 'profile-post'),
    path_('/user/me/:item/:action', 'get', $profile, 'profile-post'),
    path_('/user/me/:item/:id/:action', 'get', $profile, 'profile-post'),*/
    
    /*path_('/admin', 'get', $profile, 'admin-get'),
    path_('/admin/:item', 'get', $profile, 'profile-post'),
    path_('/admin/:item/:action', 'get', $profile, 'profile-post'),
    path_('/admin/:item/:id/:action', 'get', $profile, 'profile-post'),*/
    
    /* post urls */
    /*path_('/user/me/:item/:id/:action', 'post', $profilePost, 'profile-post'),
    path_('/user/me/:item/:action', 'post', $profilePost, 'profile-post'),
    path_('/admin/:action', 'post', $profilePost, 'admin-get'),*/

    /*path_('/auth/(login|register)', 'post', $authenticate, 'auth'),
    path_('/auth/admin/login', 'post', $authenticate, 'auth'),*/

    /* delete urls */
    //path_('/user/me/:item/:id/:action', 'delete', $profileDelete, 'profile-delete'),

    path_('/template', 'get', $template, 'tempalate'),
    
    /*  */
    path_('*', 'get', function($req, $res){
        $res->status(404);
        $errorPage = file_get_contents(TEMPLATES_DIR . '/Error/404.html');
        $res->send($errorPage);
    }, 'geterror'),
    path_('*', 'post', function($req, $res){
        $res->status(404);
        $res->json([
            'error' => 'Not found'
        ]);
    }, 'posterror')
];

?>