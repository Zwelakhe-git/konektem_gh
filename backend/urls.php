<?php
require_once __DIR__ . '/config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

require_once __DIR__ . '/controllers.php';
require_once __DIR__ . '/routes/path.php';


$urlpatterns = [

    /** service apis */
    path_('/api.service/:service_name', 'post', $serviceApi, 'service-api'),

    /** download urls */
    path_('/:item/:id/download', 'get', $download, 'download-url'),

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