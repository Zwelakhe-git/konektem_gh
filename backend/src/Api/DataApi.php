<?php
namespace Konektem\Api;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Konektem\Utils\Log;

$dotenv = Dotenv::createImmutable(BASE_DIR, '.env');
$dotenv->load();

Log::init();

class DataApi{
    public function getAllData($req, $res){
        try {
            $articles = (new \Konektem\Models\NewsModel())->getAllArticles();
            $books = [];
            $services = [];
            $interviews = [];
            $music = [];
            $events = [];
            
            return $res->status(200)->json([
                'success' => true,
                'data' => [
                    'articles' => $articles,
                    'events' => $events,
                    'books' => $books,
                    'interviews' => $interviews,
                    'music' => $music,
                    'services' => $services
                ]
            ]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }

    public function getAllArticles($req, $res){
        try {
            $model = new \Konektem\Models\NewsModel();
            $articles = $model->getAllArticles();
            
            return $res->status(200)->json([
                'success' => true,
                'date' => date('Y-m-d H:i:s'),
                'data' => [
                    'articles' => $articles
                ]
            ]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(503)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    public function getAllMusic($req, $res){
        try {
            $model = new \Konektem\Models\MusicModel();

            return $res->status(200)->json([
                'success' => true,
                'date' => date('Y-m-d H:i:s'),
                'data' => [
                    'articles' => $music
                ]
            ]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(503)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    public function getAllInterviews($req, $res){
        try {
            $model = new \Konektem\Models\InterviewsModel();

            return $res->status(200)->json([
                'success' => true,
                'date' => date('Y-m-d H:i:s'),
                'data' => [
                    'articles' => $interviews
                ]
            ]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(503)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    public function getAllEvents($req, $res){
        try {
            $model = new \Konektem\Models\EventsModel();

            return $res->status(200)->json([
                'success' => true,
                'date' => date('Y-m-d H:i:s'),
                'data' => [
                    'articles' => $events
                ]
            ]);
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(503)->json([
                'success' => false,
                'message' => 'Server error'
            ]);
        }
    }

    public function getAllBooks($req, $res){
        try{
            $model = new \Konektem\Models\BooksModel();
            $books = $model->getAllBooks();

            if(isset($req->params->qty)){
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
        } catch(\Exception $e){
            Log::error("BooksApi - {$e->getMessage()}");
            $res->status(500)->json([
                'success' => false,
                'message' => 'Server Error'
            ]);
        }
    }

    public function getBooksByGenre($req, $res){
        try{
            $name = $req->params->genre;
            $model = new \Konektem\Models\BooksModel();
            $books = $model->getAllBooks();

            $books = array_filter($books, fn($book) => strtolower($book['genre']) === strtolower($name));

            if(isset($req->params->qty)){
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
        } catch(\Exception $e){
            Log::error("BooksApi - {$e->getMessage()}");
            $res->status(500)->json([
                'success' => false,
                'message' => 'Server Error'
            ]);
        }
    }

    public function getBookById($req, $res){
        $id = $req->params->id;
        $model = new \Konektem\Models\BooksModel();
        $books = $model->getBookById($id);

        $res->status(200)->json([
            'success' => true,
            'date' => date('Y-m-d H:i:s'),
            'data' => [
                'count' => 1,
                'book' => $book['book']
            ]
        ]);
    }

    public function getBooksByAuthor($req, $res){
        $name = $req->params->author_name;
        $name = preg_replace('/\W/',' ', $name);
        $model = new \Konektem\Models\BooksModel();
        $books = $model->getBooksByAuthor($name);

        if(isset($req->params->qty)){
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
}
?>