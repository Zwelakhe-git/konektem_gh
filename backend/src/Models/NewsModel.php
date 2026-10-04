<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\Database;
use Konektem\Models\MainPageContentModel;
use Konektem\Utils\Log;

Log::init();
class NewsModel extends Database {
    private $mainpagemodel;
    private const array ARTICLE_COLUMNS = [
        'n.id',
        'n.title',
        'n.title_hash',
        'n.headline', 
        'n.created_at', 
        'n.published_at', 
        'n.category', 
        'n.author_id',
        'n.reads', 
        'n.likes', 
        'n.shares', 
        'n.content',
        'n.image_id',
        'i.url AS image_url',
    ];
    private const string ARTICLES_REDIS_KEY_PATTERN = 'article:*';
    private const int minCache = 100;
    private const array DEFAULT_AUTHOR_INFO = [
        'name' => 'Konektem.net',
        'avartar_url' => '/konektem/assets/images/logo.png',
        'email' => 'konektem@gmail.com'
    ];
    public function __construct(){
        parent::__construct();
        $this->mainpagemodel = new MainPageContentModel();
    }
    public function getAllArticles() {
        // switch no_pos to general
        $cached = [];
        if($this->redisClient){
            $keys = $this->redisClient->keys(self::ARTICLES_REDIS_KEY_PATTERN);
            if(count($keys) > 0){
                foreach($keys as $key){
                    $article = $this->redisClient->get($key);
                    try {
                        $article = json_decode($article, true);
                        $cached[] = $article;
                    } catch(\Exception $e){
                        Log::error("", $e);
                    }
                }
                if(count($cached) >= self::minCache){
                    Log::info("articles cache hit: " . count($cached));
                    return $cached;
                }
                Log::info("articles cache miss");
            }
        }
        $stmt = $this->pdo->query("
            SELECT ". implode(',', self::ARTICLE_COLUMNS) . ",
            CASE
                WHEN n.id IN (SELECT newsSlide FROM mainpagecontent) THEN 'mpnews_slide'
                WHEN n.id IN (SELECT fadeNews FROM mainpagecontent) THEN 'mpnews_fade'
                ELSE 'no_pos'
            END AS position
            FROM news n 
            LEFT JOIN images i ON n.image_id = i.id
            ORDER BY n.created_at DESC
        ");
        $articles = $stmt->fetchAll();
        $stmt = $this->pdo->prepare("SELECT name, last_name, email, avatar_url FROM users WHERE id = ?");
        foreach($articles as &$article){
            $aid = $article['author_id'];
            if(!$aid) continue;
            $stmt->execute([$aid]);
            $author = $stmt->fetch();
            $article['author'] = $author ?? self::DEFAULT_AUTHOR_INFO;

            if($this->redisClient){
                $key = "article:{$article['id']}";
                $this->redisClient->set($key, json_encode($article, JSON_UNESCAPED_UNICODE));
                $this->redisClient->expire($key, 3600 * 24);
            }
        }
        return $articles;
    }
    
    public function getArticleById($id) {
        try {
            if($this->redisClient){
                $key = "article:$id";
                $cached = $this->redisClient->get($key);
                if($cached){
                    return json_decode($cached, true);
                }
            }
            $stmt = $this->pdo->prepare("
                SELECT ". implode(',', self::ARTICLE_COLUMNS) .",
                CASE
                    WHEN n.id IN (SELECT newsSlide FROM mainpagecontent) THEN 'mpnews_slide'
                    WHEN n.id IN (SELECT fadeNews FROM mainpagecontent) THEN 'mpnews_fade'
                    ELSE 'no_pos'
                END AS position
                FROM news n 
                LEFT JOIN images i ON n.image_id = i.id 
                WHERE n.id = ?
            ");
            $stmt->execute([$id]);
            $article = $stmt->fetch();
            if($article['author_id']){
                $stmt = $this->pdo->prepare("SELECT name, last_name, email, avatar_url FROM users WHERE id = ?");
                $stmt->execute([$article['author_id']]);
                $article['author'] = $author ?? self::DEFAULT_AUTHOR_INFO;
            }
            if($this->redisClient){
                $key = "article:$id";
                $this->redisClient->set($key, json_encode($article, JSON_UNESCAPED_UNICODE));
                $this->redisClient->expire($key, 3600 * 24);
            }
            return $article;
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        }
    }

    public function getArticlesByUser($userId){}

    public function getArticleByTitleHash($title_hash){
        if($this->redisClient){
            $keys = $this->redisClient->keys(self::ARTICLES_REDIS_KEY_PATTERN);
            if(count($keys) > 0){
                foreach($keys as $key){
                    $article = $this->redisClient->get($key);
                    try {
                        $article = json_decode($article, true);
                        if($article['title_hash'] === $title_hash){
                            return $article;
                        }
                    } catch(\Exception $e){
                        Log::error("", $e);
                    }
                }
            }
        }
        //$sql = "SELECT " . implode(',', self::ARTICLE_COLUMNS) . " FROM news n LEFT JOIN images i ON n.image_id = i.id WHERE n.title_hash = ?";
        $sql = "SELECT id FROM news WHERE title_hash = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$title_hash]);
            $article = $stmt->fetch();
            $article = $this->getArticleById($article['id']);
            return $article;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
        return [];
    }

    public function getArticlesByCategory($category){
        try{
            $stmt = $this->pdo->prepare("
                SELECT ". implode(',', self::ARTICLE_COLUMNS) .",
                CASE
                    WHEN n.id IN (SELECT newsSlide FROM mainpagecontent) THEN 'mpnews_slide'
                    WHEN n.id IN (SELECT fadeNews FROM mainpagecontent) THEN 'mpnews_fade'
                    ELSE 'no_pos'
                END position
                FROM news n 
                LEFT JOIN images i ON n.image_id = i.id 
                WHERE n.category LIKE %?%
            ");
            $stmt->execute([$category]);
            $articles = $stmt->fetchAll();
            $stmt = $this->pdo->prepare("SELECT name, last_name, email, avatar_url FROM users WHERE id = ?");
            foreach($articles as &$article){
                $aid = $article['author_id'];
                if(!$aid) continue;
                $stmt->execute([$aid]);
                $author = $stmt->fetch();
                $article['author'] = $author ?? self::DEFAULT_AUTHOR_INFO;
            }
            return $articles;
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
        return [];
    }

    public function updateArticleStats($articleId, $type, $increment=1){
        $allowedTypes = ['likes', 'shares', 'reads'];
        if (!in_array($type, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid stat type'];
        }
        $thresholdReached = false;
        $threshold = 1000;
        try {
            
            if($this->redisClient){
                $key = "$type:article:$articleId";
                $oldValue = (int)($this->redisClient->get($key) ?? -1);
                if($oldValue < 0){
                    $stmt = $this->pdo->prepare("SELECT $type FROM news WHERE id = ?");
                    $stmt->execute([$articleId]);
                    $oldValue = $stmt->fetchColumn();
                    $this->redisClient->set($key, $oldValue);
                }
                $currValue = $oldValue + $increment;
                $this->redisClient->set($key, $currValue);

                if($currValue % $threshold === 0){
                    $sql = "UPDATE news SET $type = ? WHERE id = ?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$currValue, $articleId]);
                }
            } else {
                $sql = "UPDATE news SET $type = $type + ? WHERE id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$increment, $articleId]);

                $stmt = $this->pdo->prepare("SELECT $type FROM news WHERE id = ?");
                $stmt->execute([$articleId]);
                $currValue = $stmt->fetchColumn();
            }
            
            return ['success' => true, 'message' => 'Stats updated', $type => $currValue];
        } catch (\PDOException $e) {
            Log::error("Error updating article stats: " . $e->getMessage());
            return ['success' => false, 'message' => 'Server error'];
        } catch (\Exception $e) {
            Log::error("Error updating article stats: " . $e->getMessage());
            return ['success' => false, 'message' => 'Server error'];
        }
    }
    
    public function createArticle($data) {
        try{
            //Log::info("creating article with the following data: " . print_r($data, true));
            $columns = ['title', 'image_id', 'category', 'headline', 'content', 'author_id'];
            $values = array_map(fn($col) => $data[$col],  $columns);
            $placeholders = str_repeat('?,', count($columns) - 1) . '?';
            
            $stmt = $this->pdo->prepare("
                INSERT INTO news (". implode(',', $columns) .") 
                VALUES ({$placeholders})
            ");
            $result = $stmt->execute($values);
            $id = $this->pdo->lastInsertId();

            if($data['publish']){
                $stmt = $this->pdo->prepare("UPDATE news SET published_at = NOW() WHERE id = ?");
                $stmt->execute([$id]);
            }
            if($result && isset($data['position'])){
                if(!$this->mainpagemodel->addItem($data['position'], $id)){
                    Log::warn("article not added to main page");
                }
            }
            if($data['publish'] && $this->redisClient){
                // cache the article to redis
                $this->getArticleById($id);
            }
            return [
                'success' => true,
                'message' => 'Article successfully created'
            ];
        } catch(\PDOException $e){
            Log::error($e->getMessage() . " in file " . $e->getFile() . " line " . $e->getLine());
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function updateArticle($id, $data) {
        try{
            $this->pdo->beginTransaction();
            $params = [
                'image_id=?', 
                'category=?', 
                'title=?', 
                'headline=?', 
                'content=?', 
                'updated_at=NOW()'
            ];
            $values = [
                $data['image_id'],
                $data['category'],
                $data['title'],
                $data['headline'],
                $data['content'],
                $id
            ];
            if($data['publish']){
                $params[] = 'published_at=NOW()';
            }
            $stmt = $this->pdo->prepare("
                UPDATE news 
                SET ". implode(',', $params) ." 
                WHERE id = ?
            ");
            $cur_pos = $data['old_position'];
            $column = null;
            if($cur_pos === 'mpnews_slide'){
                $column = 'newsSlide';
            } else if($cur_pos === 'mpnews_fade'){
                $column = 'fadeNews';
            }
            $result = $stmt->execute($values);

            if($column){
                if(!$this->mainpagemodel->deleteItem($column, $id)){
                    $this->pdo->rollBack();
                    return [
                        'success' => false,
                        'message' => 'Failed to update article position'
                    ];
                };
            }
            if(isset($data['new_position']) && $data['new_position'] && $data['new_position'] !== $data['old_position']){
                $this->mainpagemodel->addItem($data['new_position'], $id);
            }
            if($this->redisClient){
                $this->redisClient->del("article:$id");
                $this->getArticleById($id);
            }
            $this->pdo->commit();
            return [
                'success' => true,
                'message' => 'article successfully updated'
            ];
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            if($this->pdo->inTransaction()){
                $this->pdo->rollBack();
            }
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            if($this->pdo->inTransaction()){
                $this->pdo->rollBack();
            }
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function deleteArticle($id) {
        $stmt = $this->pdo->prepare("DELETE FROM news WHERE id = ?");
        $stmt->execute([$id]);

        if($this->redisClient){
            $this->redisClient->del("article:$id");
            foreach(["likes", "reads", "shares"] as $action){
                $this->redisClient->del("$action:article:$id");
            }
        }
        return [
            'success' => true,
            'message' => 'Article successfully deleted'
        ];
    }

    public function cacheArticle($payload){}
}
?>