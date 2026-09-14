<?php
require_once __DIR__ . '/path.php';
require_once __DIR__ . '/../controllers.php';

use Konektem\Utils\Log;
use Konektem\Utils\Utils;
Log::init();

$index = function ($req, $res) use($view){
    try {    
        /**
         * handles urls to the index page
         */
        $controller = new \Konektem\Controllers\AdminController();
        $styles = [
            BASE_URL . "/static/css/main-page.5b13af9b8c4913243f7d.css"
        ];
        $scripts = [
            // getScript(BASE_URL . '/dist/main-page.5b13af9b8c4913243f7d.js', ['defer'], ['']),
            //getScript(BASE_URL . '/static/js/main-page-script.js', ['type'], ['module'])
        ];
        $data = $controller->index();
        $data['music'] = array_values(array_filter($data['music'], fn($track) => $track['public']));
        $data['events'] = array_values(array_filter($data['events'], fn($event) => $event['public']));
        $data['books'] = array_values(array_filter($data['books'], fn($book) => $book['public']));
        $data['siteSettings'] = (new \Konektem\Models\SiteSettingsModel())->normalise($data['siteSettings']);
        $data['news'] = array_values(array_filter($data['news'], fn($article) => $article['published_at'] !== null));
        session_start();
        $context = array_merge($data,[
            'title' => 'Konektem',
            'template' => 'main.php',
            'scripts' => $scripts,
            'styles' => $styles,
            'page' => 'main'
        ]);
        $res->send($view->render('Public/layout', $context));
    } catch(\Exception $e){
        Log::error($e->getMessage() . " in {$e->getFile()} line {$e->getLine()}");
        return $res->redirect(BASE_URL . '/error/505');
    } catch(\Throwable $e){
        Log::error($e->getMessage() . " in {$e->getFile()} line {$e->getLine()}");
        return $res->redirect(BASE_URL . '/error/505');
    }
};

$news = function ($req, $res) use($view){
    try {
        $controller = new \Konektem\Controllers\ArticleController();
        $styles = [BASE_URL . '/static/css/actuality.css'];
        $scripts = [];
        //Log::info(print_r($_SERVER, true));
        if(isset($req->params->title_hash)){
            $article = $controller->get(['title_hash' => $req->params->title_hash]);
            if(!$article){
                Log::error("article not found: " . print_r($article, true));
                return $res->redirect(BASE_URL . '/error/404');
            }
            $context = [
                'article' => $article,
                'title' => $article['title'],
                'template' => 'article_preview.php',
                'og_params' => [
                    'title' => $article['title'],
                    'image' => (new Utils())->normalizeUrl("https://{$_SERVER['HTTP_HOST']}{$article['image_url']}"),
                    'description' => '',
                    'url' => "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"
                ]
            ];
        } else {
            $articles = $controller->get();
            $articles = array_values(array_filter($articles, fn($article) => $article['published_at'] !== null));
            $context = [
                'news' => $articles,
                'template' => 'actuality.php',
            ];
        }
        $context = [
            ...$context,
            'page' => 'news',
            'styles' => $styles
        ];

        $res->send($view->render('Public/layout', $context));
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} at {$e->getFile()} line {$e->getLine()}");
        return $res->redirect(BASE_URL . '/error/500');
    } catch(\Throwable $e){
        Log::error("{$e->getMessage()} at {$e->getFile()} line {$e->getLine()}");
        return $res->redirect(BASE_URL . '/error/500');
    }
};


$mainMusic = function($req, $res) use($view){
    
    try {    
        $musicController = new \Konektem\Controllers\MusicController();
        $sections = [
            'main' => '',
            'tracks' => 'all-music.php',
            'albums' => 'albums.php'
        ];
        $section = $sections['tracks'];
        $styles = [
            BASE_URL . '/static/css/musicpage.css',
            //BASE_URL . '/dist/music_with_auth.9dadaf8804c120ff8024.css',
            BASE_URL . '/static/css/musicplayer.css',
            BASE_URL . '/static/css/adaptive_theme_v1.css'
        ];
        $scripts = [
            
        ];
        $music = $musicController->get();
        $context = [
            'music' => $music,
            'styles' => $styles,
            'scripts' => $scripts,
            'title' => 'Konektem Music',
            'template' => 'music-main.php',
            'page' => 'music'
        ];
        if(isset($req->params->section) && isset($sections[$req->params->section])){
            $section = $sections[$req->params->section];
            switch($req->params->section){
                case "albums":
                    $albumController = new \Konektem\Controllers\AlbumController();
                    $context['styles'] = array_merge($styles, [
                        BASE_URL . '/static/css/music-albums.css'
                    ]);
                    $context['albums'] = $albumController->get();
                    break;
                default:
                    
                    $section = $sections['tracks'];
                    break;
            }
        } else {
            $context['scripts'][] = getScript(BASE_URL . '/static/js/music-page-script.js', ['type', 'defer'], ['module', '']);
        }
        $context['section'] = $section;

        $res->send($view->render('Public/layout', $context));
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} at {$e->getFile()} line {$e->getLine()}");
        return $res->redirect(BASE_URL . '/error/500');
    }
};

$albumPreview = function($req, $res) use($view){
    $controller = new \Konektem\Controllers\AlbumController();
    $styles = [
        BASE_URL . '/static/css/musicpage.css',
        BASE_URL . '/dist/music_with_auth.9dadaf8804c120ff8024.css',
        BASE_URL . '/static/css/album-preview-style.css',
        //"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css",
        //"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css",
    ];
    
    $scripts = [
        getScript('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js')
    ];
    
    $id = $req->params->id;
    $album = $controller->get(['id' => $id]);
    $name = $album ? $album['name'] : 'Albums';
    $context = [
        'album' => $album,
        'styles' => $styles,
        'scripts' => $scripts,
        'title' => 'Konektem Album ' . $name,
        'template' => 'album-preview.php',
        'page' => 'music'
    ];

    $res->send($view->render('Public/layout', $context));
};
$musicByArtist = function($req, $res){};

$trackById = function($req, $res){};

$events = function($req, $res) use($view){
    $controller = new \Konektem\Controllers\EventController();
    $events = $controller->get();
    $styles = [];
    $scripts = [
        getScript('https://cdn.tailwindcss.com'),
        getScript(BASE_URL . '/static/js/events-script.js'),
    ];
    $context = [
        'styles' => $styles,
        'scripts' => $scripts,
        'page' => 'events'
    ];
    if(isset($req->params->id)){
        $eventId = $req->params->id;
        $event = $controller->get(['id' => $eventId]);
        if(!$event){
            $res->redirect(BASE_URL . "/error/404");
        }
        $context['styles'][] = BASE_URL . '/static/css/events-page.css';
        $context = [...$context,
            'event' => $event,
            'template' => 'event-preview.php',
            'title' => $event['title']
        ];
    } else {
        $context['styles'][] = BASE_URL . '/static/css/events.css';
        $context = [
            ...$context,
            'events' => $events,
            'title' => 'Eveneman kap vini',
            'template' => 'events.php',
        ];
    }
    $res->send($view->render('Public/layout', $context));
};

$interviews = function($req, $res) use($view){
    $controller = new \Konektem\Controllers\InterviewController();
    $context = [
        'page' => 'interviews'
    ];
    if(isset($req->params->id)){
        $interview = $controller->get(['id' => $req->params->id]);
        if(!$interview || !is_array($interview) || empty($interview)){
            return $res->redirect(BASE_URL . '/error/404');
        }
        $context = [
            ...$context,
            'title' => $interview['title'],
            'interview' => $interview,
            'styles' => [
                BASE_URL . '/static/css/interviews-page.css'
            ],
            'og_params' => [
                'title' => $interview['title'],
                'image' => "https://{$_SERVER['HTTP_HOST']}{$interview['image_url']}",
                'description' => '',
                'url' => "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"
            ],
            'template' => 'interview_preview.php'
        ];
    } else {
        $interviews = $controller->get();
        $context = [
            ...$context,
            'title' => 'Entèvyou - Konektem',
            'interviews' => $interviews,
            'styles' => [
                BASE_URL . '/static/css/interviews-styles.css'
            ],
            'template' => 'interviews.php'
        ];
    }
    
    $res->send($view->render('Public/layout', $context));
};

$interviewById = function($req, $res) use($view){
    $id = $req->params->id;
    $controller = new \Konektem\Controllers\InterviewController();
    $interview = $controller->get(['id' => $id]);
    $interview = $interview;
    $context = [
        'interview' => $interview,
        'styles' => [
            BASE_URL . '/static/css/interviews-page.css'
        ],
        'title' => $interview ? "{$interview['title']}" : "interview konektem",
        'template' => 'interview-item.php',
        'page' => 'interviews'
    ];
    $res->send($view->render('Public/layout', $context));
};

$books = function($req, $res) use($view){
    $controller = new \Konektem\Controllers\BooksController();
    $books = $controller->get();
    $books = array_values(array_filter($books, fn($book) => $book['public']));
    $scripts = [
        getScript(BASE_URL . '/static/js/books.js')
    ];
    $styles = [
        BASE_URL . '/static/css/books.css'
    ];
    $context = [
        'books' => $books,
        'title' => 'Konketem - Books',
        'template' => 'books.php',
        'scripts' => $scripts,
        'styles' => $styles,
        'page' => 'books'
    ];
    $res->send($view->render('Public/layout', $context));
};

$bookById = function($req, $res) use($view){
    $controller = new \Konektem\Controllers\BooksController();
    $id = $req->params->id;
    $book = $controller->get(['id' => $id]);
    $context = [
        'book' => $book,
        'styles' => [
            BASE_URL . '/static/css/books.css'
        ],
        'title' => $book ? "{$book['title']}" : "konektem book",
        'template' => 'books.php',
        'page' => 'books'
    ];
    $res->send($view->render('Public/layout', $context));
};

// services
$services = function ($req, $res) use($view){
    $styles =  [
        //BASE_URL . "/static/css/sectionsStyle.css",
        //BASE_URL . "/static/css/partners.css",
        //BASE_URL . "/static/css/bottomScrollstyle.css",
        //BASE_URL . "/static/css/services-style.css",
        BASE_URL . "/dist/react-services-page.css"
    ];
    $scripts = [
        getScript('https://cdn.tailwindcss.com')
    ];
    $context = [
        'title' => 'Sevis nou yo',
        'page' => 'services',
        'template' => 'services.php'
    ];
    $konektemWorksImages = [
        '/media/images/pictures/m1.jpg',
        '/media/images/pictures/m2.jpg',
        '/media/images/pictures/m3.jpg',
        '/media/images/pictures/m4.png',
        '/media/images/pictures/m5.jpg',
        '/media/images/pictures/m6.png',
        '/media/images/pictures/m7.png',
        '/media/images/pictures/m8.png',
        '/media/images/pictures/m9.png',
        '/media/images/pictures/m10.png',
        '/media/images/pictures/m11.png',
        '/media/images/pictures/m12.png'
    ];
    $controller = new \Konektem\Controllers\ServiceController();
    $services = $controller->get();
    if(isset($req->params->id)){
        $service = $controller->get(['id' => $res->params->id]);
        $context['service'] = $service;
        $scripts[] = getScript(BASE_URL . "/dist/service-form.js", ['defer'], ['']);
        $context['template'] = "ServiceOrderForm";
        
    } else {
        $context['services'] = $services;
        $context['konektemWorksImages'] = $konektemWorksImages;
        //$scripts[] = getScript(BASE_URL . "/dist/react-services-page.js", ['defer'], ['']);
    }
    $context['scripts'] = $scripts;
    $context['styles'] = $styles;
    $res->send($view->render('Public/layout', $context));
};

$livestream = function($req, $res) use($view){
    $controller = new \Konektem\Controllers\LiveStreamController();
    $streams = $controller->index();
    $context = [
        'title' => 'Konektem Stream',
        'streams' => $streams,
        'template' => 'livestream.php',
        'styles' => [
            BASE_URL . "/static/css/livestream.css",
            BASE_URL . "/static/css/toast-notification.css"
        ],
        'scripts' => [
            getScript(BASE_URL . '/static/js/livestream.js', ['type'], ['module']),
            getScript(BASE_URL . '/static/js/toast-notification.js', ['defer'], [''])
        ],
        'page' => 'stream'
    ];
    $res->send($view->render('Public/layout', $context));
};

$payment = function($req, $res) use ($view){
    $context = [
        'scripts' => [
            //getScript(BASE_URL . '/static/js/index-pSVdCgWl.js', ['type', 'crossorigin'], ['module', ''])
            getScript(BASE_URL . '/static/js/index-0b102616.js', ['type', 'crossorigin'], ['module', ''])
        ],
        'styles' => [
            BASE_URL . "/static/css/checkout.css"
        ],
        'page' => ''
    ];
    return $res->send($view->render('Public/layout', $context));
};

$contacts = function($req, $res) use($view){
    $context = [
        'title' => 'Kontakt',
        'template' => 'contacts.php',
        'styles' => [
            BASE_URL . '/static/css/contacts.css'
        ],
        'page' => 'contacts'
    ];
    return $res->send($view->render('Public/layout', $context));
};

$paymentCallback = function($req, $res){
    try {
        Log::info("response from {$req->query->platform}");
        return $res->status(200)->json([
            'success' => true,
            'message' => 'Response received'
        ]);
    } catch(\Exception $e){
        Log::error($e->getMessage());
    }
};

$paymentConfirm = function ($req, $res) use ($view) {
    try {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $context = [];
        $platform = $req->params->platform ?? '';
        $context['platform'] = $platform;

        // Данные о платеже (могут быть получены из сессии или GET параметров)
        $context['paymentStatus'] = $_GET['status'] ?? 'success';
        $context['orderNumber'] = $_GET['order_id'] ?? null;
        $context['amount'] = $_GET['amount'] ?? null;
        $context['transactionId'] = $_GET['transaction_id'] ?? null;

        // Названия платежных систем
        $platformNames = [
            'moncash' => 'MonCash',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'payoneer' => 'Payoneer'
        ];

        $context['platformName'] = $platformNames[$platform] ?? ucfirst($platform);

        // Иконки для платежных систем
        $context['platformIcons'] = [
            'moncash' => '💳',
            'paypal' => '💰',
            'stripe' => '💳',
            'payoneer' => '🏦'
        ];

        $context['icon'] = $context['platformIcons'][$platform] ?? '💳';

        // Сообщение в зависимости от статуса
        if ($context['paymentStatus'] === 'success') {
            $context['title'] = "✅ Paiement réussi !";
            $context['message'] = "Votre paiement via {$context['platformName']} a été effectué avec succès.";
            $context['subMessage'] = "Merci pour votre confiance ! Un email de confirmation vous a été envoyé.";
            $context['alertClass'] = "success";
        } elseif ($context['paymentStatus'] === 'cancel' || $context['paymentStatus'] === 'cancelled') {
            $context['title'] = "⏸️ Paiement annulé";
            $context['message'] = "Le paiement via {$context['platformName']} a été annulé.";
            $context['subMessage'] = "Vous pouvez réessayer à tout moment depuis notre site.";
            $context['alertClass'] = "warning";
        } else {
            $context['title'] = "⏳ Paiement en attente";
            $context['message'] = "Votre paiement via {$context['platformName']} est en cours de traitement.";
            $context['subMessage'] = "Vous recevrez une confirmation par email dès que le paiement sera finalisé.";
            $context['alertClass'] = "info";
        }
        Log::info("$platform payment successful");
        return $res->send($view->render(TEMPLATES_DIR . '/Payment/confirm', $context));
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        //return $res->redirect(BASE_URL . '/error/505');
    }
};

$premiumSubscription = function($req, $res) use ($view) {
    try {
        $context = [
            'template' => 'premium-subscription.php',
            'styles' => [
                BASE_URL . '/static/css/premium.css'
            ],
            'scripts' => [
                //getScript(BASE_URL . '/static/js/premium.js')
            ],
            'page' => ''
        ];
        return $res->send($view->render('Public/layout', $context));
    } catch(\Exception $e){
        Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
    }
};

$appRoutes = [
    path_('', 'get', $index, 'index'),
    path_('/', 'get', $index, 'index'),
    path_('/actuality', 'get', $news, 'news'),
    path_('/actuality/:title_hash', 'get', $news, 'article'),
    path_('/music', 'get', $mainMusic, 'music'),
    path_('/album/:id', 'get', $albumPreview, 'album'),
    path_('/music/:section', 'get', $mainMusic, 'music'),
    path_('/music/artist/:name', 'get', $musicByArtist, 'music_filter'),
    path_('/music/id/:id', 'get', $trackById, 'track_id_filter'),
    path_('/events', 'get', $events, 'events'),
    path_('/events/id/:id', 'get', $events, 'event-prepview'),
    path_('/interviews', 'get', $interviews, 'interviews'),
    path_('/interviews/id/:id', 'get', $interviews, 'interview_id'),
    path_('/books', 'get', $books, 'books'),
    path_('/books/id/:id', 'get', $bookById, 'book_id'),
    path_('/services/:id/order', 'get',$services, 'service-order'),
    path_('/services', 'get', $services, 'services'),
    path_('/stream', 'get', $livestream, 'stream'),
    path_('/contacts', 'get', $contacts, 'contacts'),
    path_('/checkout', 'get', $payment, 'payment'),
    path_('/payment/:platform/callback', 'get', $paymentCallback, 'payment_callback'),
    path_('/payment/:platform/confirm', 'get', $paymentConfirm, 'payment_confirmation'),
    path_('/error/:code', 'get', $error, 'error_page'),
    path_('/store/counter/:token', 'get', $store, 'konektem-store'),
    path_('/premium-subscription', 'get', $premiumSubscription, 'premium-subscription'),
];
?>