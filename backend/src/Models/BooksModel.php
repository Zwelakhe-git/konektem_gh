<?php
namespace Konektem\Models;
use Konektem\Models\Database;
use Konektem\Utils\Log;

Log::init();

class BooksModel extends Database {
    public function __construct(){
        parent::__construct();
    }
    public function getAllBooks() {
        $sql = "
            SELECT b.*, i.url as image_url 
            FROM books b
            LEFT JOIN images i ON b.cover_image = i.id
            ORDER BY b.id DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    public function getBooksByOwner($owner) {
        $sql = "
            SELECT b.*, i.url as image_url 
            FROM books b
            LEFT JOIN images i ON b.cover_image = i.id
            WHERE b.owner = ?
            ORDER BY b.id DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$owner]);
        return $stmt->fetchAll();
    }

    public function getBooksByAuthor($name) {
        try{
            $sql = "
                SELECT b.*, i.url as image_url 
                FROM books b
                LEFT JOIN images i ON b.cover_image = i.id
                WHERE b.author LIKE ?
                ORDER BY b.id DESC
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$name]);
            return $stmt->fetchAll();
        } catch(PDOException $e){
            Log::error("BooksModel - {$e->getMessage()}");
            return [];
        }
    }
    
    public function getBookById($id) {
        $stmt = $this->pdo->prepare("
            SELECT b.*, i.url as image_url 
            FROM books b
            LEFT JOIN images i ON b.cover_image = i.id 
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getBooksByGenre($genreName){
        try {
            $stmt = $this->pdo->prare("SELECT * FROM books WHERE genre = ?");
            $stmt->execute([$genreName]);
            return $stmt->fetchAll();
        } catch(\PDOException $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        } catch (\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
        }
        return [];
    }
    
    public function createBook($data) {
        try {
            $cols = ["title", "author", "release_date", "cover_image", "description", "pdfUrl", "genre", "owner", "public"];
            $values = array_map(fn($col) => $data[$col], $cols);
            $params = str_repeat("?,", count($cols) - 1) . "?";
            $stmt = $this->pdo->prepare("
                INSERT INTO books (" . implode(",", $cols). ")
                VALUES ($params)
            ");
            $stmt->execute($values);
            return [
                'success' => true,
                'message' => 'Book successfully created'
            ];
        } catch(\Exception $e){
            Log::error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function updateBook($id, $data) {
            $stmt = $this->pdo->prepare("
                UPDATE books SET title = ?, author = ?, release_date = ?, cover_image = ?, description = ?,
                pdfUrl = ?, genre = ?, public = ? WHERE id = ? AND owner = ?
            ");
            $stmt->execute([
                $data['title'],
                $data['author'],
                $data['release_date'],
                $data['bookImg'],
                $data['description'],
                $data['pdfUrl'],
                $data['genre'],
                $data['public'],
                $id,
                $data['owner'],
            ]);
            return [
                'success' => true,
                'message' => 'Book successfully updated'
            ];
    }
    
    public function deleteBook($id) {
        (new \Konektem\Auth\Auth())->ensureSession();
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = ? AND owner = ?");
        return $stmt->execute([$id, $_SESSION['user']['name']]);
    }

    public function updateBookStats($eventId, $type, $increment = 1) {
        $allowedTypes = ['likes', 'shares', 'downloads'];
        if (!in_array($type, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid stat type'];
        }
        
        try {
            $sql = "UPDATE books SET $type = $type + ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$increment, $eventId]);

            $stmt = $this->pdo->prepare("SELECT $type FROM books WHERE id = ?");
            $stmt->execute([$eventId]);
            $currVal = $stmt->fetchColumn();
            return ['success' => true, 'message' => 'Stats updated', $type => $currVal];
        } catch (\PDOException $e) {
            Log::error("Error updating event stats: " . $e->getMessage());
            return ['success' => false, 'message' => 'Server error'];
        } catch(\Exception $e){
            Log::error("Error updating event stats: " . $e->getMessage());
            return ['success' => false, 'message' => 'Server error'];
        }
    }
}
?>