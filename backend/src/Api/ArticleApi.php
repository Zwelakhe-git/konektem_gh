<?php
namespace Konektem\Api;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\NewsModel;
use Konektem\Models\AdminModel;
use Konektem\Auth\Auth;
use Konektem\Utils\Log;

Log::init();

/**
 * response format:
 * {
 * 'success': bool,
 * 'message': string,
 * 'data': {
 *  'count': int,
 *  'category': string,
 *  'source': string
 *  'date': timestamp,
 *  'articles': array
 * }
 * }
 */
class ArticleApi{
    private $adminModel;
    private $model;
    public function __construct(){
        $this->adminModel = new AdminModel();
        $this->model = new NewsModel();
    }
    
    public function getArticles($req, $res){
        $quantity = $req->params->qty;

        if(!$this->authorize()){
            $res->status(200)->json([
                'success' => false,
                'message' => 'invalid api key'
            ]);
            exit;
        }
        //Log::info("No error in admin model");

        $articles = $this->model->getAllArticles();
        
        if(isset($req->params->category)){
            $cat = $req->params->category;
            $articles = array_filter($articles, fn($article) => $article['category'] === $cat);
        }
        if($quantity !== 'all' && $quantity > 0){
            $articles = array_slice($articles, 0, $quantity);
        }

        //$res->json(array_splice($res, 0, $quantity));
        $res->status(200)->json([
            'success'=> true,
            'date' => date('Y-m-d H:i:s'),
            'data'=> [
                'count'=> count($articles),
                'category' => $cat,
                'articles'=> $articles
            ]
        ]);
    }

    public function createArticle($req, $res){
        try {
            $payload = (new Auth())->getAuthTokenPayload();
            if(empty($payload)){
                return $res->status(401)->json([
                    'success' => false,
                    'message' => 'Missing Authorization header' 
                ]);
            }
            $userId = $payload['user_id'];
            $this->model->createArticle();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server Error'
            ]);
        }
    }

    public function deleteArticle($req, $res){
        try {
            
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server Error'
            ]);
        }
    }

    public function publishArticle($req, $res){
        try {
            
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return $res->status(500)->json([
                'success' => false,
                'message' => 'Server Error'
            ]);
        }
    }

    public function authorize(): bool{
        if(!isset($_GET['key']) || !$this->adminModel->acceptAPIKey($_GET['key'])) return false;
        return true;
    }
}

?>