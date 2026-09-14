<?php
namespace Konektem\Models;
use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();
class InterviewsModel extends Database {
    private const INTERVIEW_COLUMNS = [
        'id',
        'title',
        'description',
        'interview_date',
        'created_at',
        'duration',
        'guest_name',
        'guest_title',
        'likes',
        'views',
        'shares',        
    ];
    public function __construct(){
        parent::__construct();
    }
    
    public function getAllInterviews() {
        $stmt = $this->pdo->query("
            SELECT ". implode(',', array_map(fn($col) => "i.$col", self::INTERVIEW_COLUMNS)) .", img.url as image_url, v.url as video_url, img2.url as video_poster,
            CASE WHEN i.id IN (SELECT interviews FROM mainpagecontent) THEN 'mainpage' ELSE 'general' END position
            FROM interview i 
            LEFT JOIN images img ON i.image_id = img.id
            LEFT JOIN videos v ON v.id = i.video_id
            LEFT JOIN images img2 ON img2.id = v.image_id
            ORDER BY i.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getInterviewsByUser($userId){}

    public function getNInterviews(int $qty){
        try{
            $interviews = $this->getAllInterviews();
            return array_slice($interviews, 0, $qty);
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
        return [];
    }

    public function getInterviewByTitleHash($titleHash){
        try {
            $sql = "SELECT ". implode(',', array_map(fn($col) => 'i.' . $col, self::INTERVIEW_COLUMNS)) .", img.url as image_url, v.url as video_url, img2.url as video_poster,
            CASE WHEN i.id IN (SELECT interviews FROM mainpagecontent) THEN 'mainpage' ELSE 'general' END position
            FROM interview i 
            LEFT JOIN images img ON i.image_id = img.id
            LEFT JOIN videos v ON v.id = i.video_id
            LEFT JOIN images img2 ON img2.id = v.image_id
            ORDER BY i.created_at DESC
            WHERE title_hash = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$titleHash]);
            return $stmt->fetch();
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }  catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function getInterviewById($id) {
        $stmt = $this->pdo->prepare("
            SELECT i.*, img.url as image_url, v.url as video_url,
            CASE WHEN i.id IN (SELECT interviews FROM mainpagecontent) THEN 'mainpage' ELSE 'general' END position
            FROM interview i 
            LEFT JOIN images img ON i.image_id = img.id
            LEFT JOIN videos v ON v.id = i.video_id
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function createInterview($data) {
        try{
            $columns = ['title', 'description', 'image_id', 'interview_date', 'guest_name', 'guest_title'];
            
            if(isset($data['video_id'])){
                $columns[] = 'video_id';
            }
            $values = array_map(fn($col) => $data[$col], $columns);
            $placeholders = str_repeat('?,', count($columns) - 1) . '?';
            $stmt = $this->pdo->prepare("
                INSERT INTO interview (". implode(',', $columns) .") 
                VALUES ($placeholders)
            ");
            
            $stmt->execute($values);
            $id = $this->pdo->lastInsertId();
            if(isset($data['position']) && $data['position'] === 'mainpage'){
                (new \Konektem\Models\MainPageContentModel())->addItem('interviews', $id);
            }
            return [
                'success' => true,
                'message' => 'Interview successfully created'
            ];
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function updateInterview($id, $data) {
        try{
            $stmt = $this->pdo->prepare("
                UPDATE interview
                SET title = ?, description = ?, image_id = ?, video_id = ?,
                interview_date = ?, guest_name = ?, guest_title = ?,
                updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            $stmt->execute([
                $data['title'],
                $data['description'],
                $data['image_id'],
                $data['video_id'],
                $data['interview_date'],
                $data['guest_name'],
                $data['guest_title'],
                $id
            ]);
            return [
                'success' => true,
                'message' => 'Interview successfully updated'
            ];
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function deleteInterview($id) {
        $stmt = $this->pdo->prepare("DELETE FROM interview WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateInterviewStats($interviewId, $type, $increment = 1) {
        $allowedTypes = ['likes', 'shares'];
        if (!in_array($type, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid stat type'];
        }
        
        try {
            $sql = "UPDATE interview SET `$type` = `$type` + ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$increment, $interviewId]);
            $stmt = $this->pdo->prepare("SELECT `$type` FROM interview WHERE id = ?");
            $stmt->execute([$interviewId]);
            $currValue = $stmt->fetchColumn();
            
            return ['success' => true, 'message' => 'Stats updated', $type => $currValue];
        } catch (\PDOException $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return ['success' => false, 'message' => 'Server error'];
        }
    }
}
?>