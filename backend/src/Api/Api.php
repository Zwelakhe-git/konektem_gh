<?php
namespace Konektem\Api;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\AdminModel;
use Konektem\Auth\Auth;
use Konektem\Utils\Log;

Log::init();

class Api{
    private $adminModel;

    public function __construct(){
        $this->adminModel = new AdminModel();
    }
    public function musicApi($req, $res){
        if(!$this->authorize()){
            return $res->json([
                'success' => false,
                'message' => "Invalid API key"
            ]);
        }
        $model = new \Konektem\Models\MusicModel();
        $tracks = [];
        if(isset($req->query->id)){
            $track = $model->getMusicById($req->query->id);
            if(!$track){
                return $res->status(403)->json([
                    'success' => false,
                    'message' => 'Track not found'
                ]);
            }
            return $res->status(200)->json([
                'success' => true,
                'track' => $track
            ]);
        }
        if(isset($req->params->genre)){
            $genre = $req->params->genre;
            $tracks = $genre !== 'all' ? $model->getTracksByGenre($genre): $model->getAllTracks();
        } else if(isset($req->params->artist)){
            $artist = $req->params->artist;
            $tracks = $model->getTracksByArtist($artist);
        } else {
            $tracks = $model->getAllMusic();
        }
        if(isset($req->params->qty) && count($tracks) > 0 && $req->params->qty !== 'all' && 
            (int)$req->params->qty > 0 && $req->params->qty <= count($tracks)
        ){
            $tracks = array_slice($tracks, 0, $req->params->qty);
        }

        return $res->json([
            'success' => true,
            'date' => date('Y-m-d H:i:s'),
            'data' => [
                'count' => count($tracks),
                'tracks' => $tracks
            ]
        ]);
    }

    public function albumsApi($req, $res){
        if(!$this->authorize()){
            return $res->json([
                'success' => false,
                'message' => "Invalid API key"
            ]);
        }
        $model = new \Konektem\Models\AlbumModel();
        $albums = [];
        if(isset($req->params->artist)){
            $artist = preg_replace('/\W/', ' ', $req->params->artist);
            $albums = $model->getAlbumsByArtist($artist);
        } else {
            $albums = $model->getAllAlbums();
        }

        if(isset($req->params->qty) && count($albums) > 0 && $req->params->qty !== 'all' && 
            (int)$req->params->qty > 0 && $req->params->qty <= count($albums)
        ){
            $albums = array_slice(0, $req->params->qty);
        }

        return $res->json([
            'success' => true,
            'date' => date('Y-m-d H:i:s'),
            'data' => [
                'count' => count($albums),
                'albums' => $albums
            ]
        ]);
    }

    public function booksApi($req, $res){
        $model = new \Konektem\Models\BooksModel();
        $books = [];
        if(isset($req->params->genre) && $req->params->genre !== 'all'){
            $books = $model->getBooksByGenre($req->params->genre);
        } else if(isset($req->params->id)){
            $books = $model->getBookById((int)$req->params->id);
        } else if(isset($req->params->author_name)){
            $name = preg_replace('/\W/',' ', $req->params->author_name);
            $books = $model->getBooksByAuthor($name);
        } else {
            $books = $model->getAllBooks();
        }

        if(isset($req->params->qty) && count($books) > 0 && $req->params->qty > 0 && $req->params->qty <= count($bboks)){
            $books = array_slice($books, 0, $req->params->qty);
        }
        $res->status(200)->json([
            'success' => true,
            'date' => date('Y-m-d H:i:s'),
            'data' => [
                'count' => !empty($books) ? count($books) : 0,
                'books' => $books
            ]
        ]);
    }

    public function eventsApi($req, $res){
        // validate api key
        if(!$this->authorize()){
            return $res->json([
                'success' => false,
                'message' => "Invalid API key"
            ]);
        }
        $model = new \Konektem\Models\EventsModel();
        if(isset($req->params->id)){
            $event = $model->getEventById($req->params->id);
            return $res->json([
                'success' => true,
                'date' => date('Y-m-d H:i:s'),
                'data' => [
                    'count' => $event ? 1 : 0,
                    'event' => $event
                ]
            ]);
        } else {
            $events = $model->getAllEvents();
            if(isset($req->params->qty) && $req->params->qty !== 'all'){
                $events = array_slice($events, 0, (int)$req->params->qty);
            }
            return $res->json([
                'success' => true,
                'date' => date('Y-m-d H:i:s'),
                'data' => [
                    'count' => !empty($events) ? count($events) : 0,
                    'events' => $events
                ]
            ]);
        }
    }

    public function siteSettingsApi($req, $res){
        if(!$this->authorize()){
            return $res->json([
                'success' => false,
                'message' => "Invalid API key"
            ]);
        }
        $model = new \Konektem\Models\SiteSettingsModel();
        if(isset($req->params->group)){
            $group = $req->params->group;
            $settings = $group !== 'all' ?
            $model->getSettingsByGroup($group):
            $model->getAllSettings();

            $settings = $model->normalise($settings);
            
            return $res->json([
                'success' => true,
                'date' => date('Y-m-d H:i:s'),
                'data' => [
                    'count' => count($settings),
                    'settings' => $settings
                ]
            ]);
            
        } else if (isset($req->params->key)) {
            $key = $req->params->key;
            $settingValue = $model->getSettingValueByKey($key);
            
            return $res->json([
                'success' => true,
                'date' => date('Y-m-d H:i:s'),
                'data' => [
                    $key => $settingValue ?? 'key not found'
                ]
            ]);
        }
    }

    public function interviewsApi($req, $res){
        if(!$this->authorize()){
            return $res->json([
                'success' => false,
                'message' => "Invalid API key"
            ]);
        }
        $model = new \Konektem\Models\InterviewsModel();
        if(isset($req->params->qty)){
            $qty = $req->params->qty;
            $result = [];
            if($qty === 'all'){
                $result = $model->getAllInterviews();
            } else {
                $result = $model->getNInterviews((int)$qty);
            }

            return $res->json([
                'success' => true,
                'date' => date('Y-m-d H:i:s'),
                'data' => [
                    'count' => count($result),
                    'interviews' => $result
                ]
            ]);
            
        }
    }

    public function ordersApi($req, $res){
        if(!$this->authorize()){
            return $res->json([
                'success' => false,
                'message' => "Invalid API key"
            ]);
        }

        try {
            $model = new \Konektem\Models\OrderModel();
            if(isset($req->params->id)){
                $event = $model->getOrderById($req->params->id);
                return $res->json([
                    'success' => true,
                    'date' => date('Y-m-d H:i:s'),
                    'data' => [
                        'count' => $event ? 1 : 0,
                        'event' => $event
                    ]
                ]);
            } else {
                $events = $model->getAllEvents();
                if(isset($req->params->qty) && $req->params->qty !== 'all'){
                    $events = array_slice($events, 0, (int)$req->params->qty);
                }
                return $res->json([
                    'success' => true,
                    'date' => date('Y-m-d H:i:s'),
                    'data' => [
                        'count' => !empty($events) ? count($events) : 0,
                        'events' => $events
                    ]
                ]);
            }
        } catch(\Exception $e){
            Log::error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }

    public function getAllUsers($req, $res){
        if(!$this->authorize()){
            return $res->json([
                'success' => false,
                'message' => "Invalid API key"
            ]);
        }
    }

    public function userApi($req, $res){
        if(!$this->authorize()){
            $res->json([
                'success' => false,
                'message' => "Invalid API key"
            ]);
        }
    }

    public function newsApi($req, $res){
        if(!$this->authorize()){
            $res->json([
                'success' => false,
                'message' => "Invalid API key"
            ]);
            return;
        }
        $model = new \Konektem\Models\NewsModel();

        $articles = [];
        if(isset($req->params->category) && $req->params->category !== 'all'){
            $cat = $req->params->category;
            $articles = $model->getArticlesByCategory($cat);
        } elseif(isset($req->params->title_hash)){
            $article = $model->getArticleByTitleHash($req->params->title_hash);
            if(!$article || !is_array($article) || empty($article)){
                return $res->status(404)->json([
                    'success' => false,
                    'message' => 'Article not found'
                ]);
            }
            return $res->status(200)->json([
                'success' => false,
                'data' => [
                    'article' => $article
                ]
            ]);
        } else {
            $articles = $model->getAllArticles();
        }
        $quantity = isset($req->params->qty) ? $req->params->qty : 'all';
        if($quantity !== 'all' && $quantity > 0 && !empty($articles)){
            $articles = array_slice($articles, 0, $quantity);
        }

        return $res->status(200)->json([
            'success'=> true,
            'date' => date('Y-m-d H:i:s'),
            'data'=> [
                'count'=> count($articles),
                'category' => $cat ?? 'all',
                'articles'=> $articles
            ]
        ]);
    }

    public function servicesApi($req, $res){
        if(!$this->authorize()){
            return $res->json([
                'success' => false,
                'message' => "Invalid API key"
            ]);
        }
        $model = new \Konektem\Models\ServicesModel();
        $services = $model->getAllServices();
        if(isset($req->params->qty) && count($services) > 0 && $req->params->services !== 'all' && $req->params->services > 0){
            $services = array_slice($services, 0, $req->params->qty);
        }

        return $res->json([
            'success' => true,
            'date' => date('Y-m-d H:i:s'),
            'data' => [
                'count' => count($services),
                'services' => $services
            ]
        ]);
    }

    public function mediaStatsApi($req, $res){
        $item = $req->params->item;
        $action = $req->params->action;
        $id = $req->params->id;
        $result = [];
        switch($item){
            case "article":
                $model = new \Konektem\Models\NewsModel();
                $result = $model->updateArticleStats($id, $action);
                break;
            case "track":
                $model = new \Konektem\Models\MusicModel();
                $result = $model->updateTrackStats($id, $action);
                break;
            case "album":
                $model = new \Konektem\Models\AlbumModel();
                $result = $model->updateAlbumStats($id, $action);
                break;
            case "interview":
                $model = new \Konektem\Models\InterviewsModel();
                $result = $model->updateInterviewStats($id, $action);
                break;
            case "event":
                $model = new \Konektem\Models\EventsModel();
                $result = $model->updateEventStats($id, $action);
                break;
            case "service":
                $model = new \Konektem\Models\ServicesModel();
                $result = $model->updateServiceStats($id, $action);
                break;
            default:
                break;
        }
        $result['date'] = date('Y-m-d H:i:s');
        return $res->json($result);
    }

    public function authorize(): bool{
        if(!isset($_GET['key']) || !$this->adminModel->acceptAPIKey($_GET['key'])) return true;
        return true;
    }

}
?>