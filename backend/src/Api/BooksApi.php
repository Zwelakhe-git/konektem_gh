<?php 
namespace Konektem\Api;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\AdminModel;
use Konektem\Models\BooksModel;
use Konektem\Utils\Log;

Log::init();

class BooksApi{
    private static $model;

    public static function init(){
        self::$model = new BooksModel();
    }

    static function getAllBooks($req, $res){
        try{
            $books = self::$model->getAllBooks();

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

    static function getBooksByGenre($req, $res){
        try{
            $name = $req->params->genre;
            $books = self::$model->getAllBooks();

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

    static function getBookById($req, $res){
        $id = $req->params->id;
        $books = self::$model->getBookById($id);

        $res->status(200)->json([
            'success' => true,
            'date' => date('Y-m-d H:i:s'),
            'data' => [
                'count' => 1,
                'book' => $book['book']
            ]
        ]);
    }

    static function getBooksByAuthor($req, $res){
        $name = $req->params->author_name;
        $name = preg_replace('/\W/',' ', $name);
        $books = self::$model->getBooksByAuthor($name);

        if(isset($req->params->qty)){
            $books = array_slice($books, 0, $req->params->qty);
        }
        $res->status(200)->json([
            'success' => true,
            'date' => date('Y-m-d H:i:s'),
            'data' => [
                'count' => !empty($books) ? count($books) : 0,
                'book' => $books
            ]
        ]);
    }
}
?>