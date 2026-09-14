<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\Database;
use Konektem\Models\UserModel;
use Konektem\Utils\Log;

Log::init();

class AlbumModel extends Database {
    private $userModel;
    private const ALBUM_COLUMNS = [
        'id', 'name', 'image_url', 'songs_count','release_year',
        'owner', 'description', 'downloads', 'likes', 'shares'
    ];
    
    public function __construct(){
        parent::__construct();
        $this->userModel = new UserModel();
    }
    
    /**
     * Создание нового альбома с треками
     * @param array $albumData Данные альбома
     * @param array $tracksData Массив треков (каждый с name, audio_file, image_file)
     * @param int $userId ID пользователя (владельца)
     * @return array Результат операции
     */
    public function createAlbum($albumData, $tracksData, $userId) {
        try {
            $this->pdo->beginTransaction();
            
            // 1. Получаем или создаем исполнителя
            $artistId = $this->getOrCreateArtist($albumData['artist']);
            
            if (!$artistId) {
                throw new \Exception("Failed to get or create artist");
            }
            
            // 2. Вставляем альбом
            $sql = "INSERT INTO albums (name, image_url, songs_count, release_year, owner, user_id, description, downloads, likes, shares) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0, 0)";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                $albumData['name'],
                $albumData['image_url'],
                count($tracksData), // songs_count
                $albumData['release_year'],
                $albumData['owner_name'],
                $userId,
                $albumData['description'] ?? null
            ]);
            
            if (!$result) {
                throw new \Exception("Failed to insert album");
            }
            
            $albumId = $this->pdo->lastInsertId();
            
            // 3. Вставляем треки
            foreach ($tracksData as $index => $track) {
                $this->addTrackToAlbum($albumId, $track, $artistId, $index + 1);
            }
            
            $this->pdo->commit();
            
            return [
                'success' => true,
                'message' => 'Album created successfully',
                'album_id' => $albumId
            ];
            
        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Database error: '
            ];
        }
    }
    
    /**
     * Получение или создание исполнителя
     * @param mixed $artistData Данные исполнителя (id или массив с name для нового)
     * @return int|null ID исполнителя
     */
    private function getOrCreateArtist($artistData) {
        try {
            // Если передан ID существующего исполнителя
            if (is_array($artistData) && isset($artistData['id'])) {
                // Проверяем, существует ли такой исполнитель
                // table Artists starts with a capital letter, though changes will be made
                $stmt = $this->pdo->prepare("SELECT id FROM artists WHERE id = ?");
                $stmt->execute([$artistData['id']]);
                if ($stmt->fetch()) {
                    return $artistData['id'];
                }
            }
            
            // Если передан массив с новым исполнителем
            if (is_array($artistData) && isset($artistData['new_name']) && !empty($artistData['new_name'])) {
                $stmt = $this->pdo->prepare("INSERT INTO artists (name) VALUES (?)");
                $stmt->execute([$artistData['new_name']]);
                return $this->pdo->lastInsertId();
            }
            
            return null;
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return null;
        }
    }
    
    /**
     * Добавление трека в альбом
     * @param int $albumId ID альбома
     * @param array $track Данные трека
     * @param int $artistId ID исполнителя
     * @param int $trackNumber Номер трека в альбоме
     * @return bool
     */
    private function addTrackToAlbum($albumId, $track, $artistId, $trackNumber) {
        $sql = "INSERT INTO music (title, artist_id, genre, image_id, url, album_id, track_number, downloads, likes, shares, owner, mime_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0, 0, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $track['name'],
            $artistId,
            $track['genre'] ?? null,
            $track['image_id'] ?? null,
            $track['audio_url'],
            $albumId,
            $trackNumber,
            $track['owner_name'] ?? null,
            $track['audio_mime_type']
        ]);
    }
    
    /**
     * Получение всех альбомов с количеством треков
     * @param int $limit Лимит записей
     * @param int $offset Смещение
     * @return array
     */
    public function getAllAlbums($limit = 50, $offset = 0) {
        try {
            $sql = "SELECT ". implode(',', self::ALBUM_COLUMNS)."
                    FROM albums";
            
            $stmt = $this->pdo->query($sql);
            //$stmt->execute([$limit, $offset]);
            $albums = $stmt->fetchAll();
            $sql = "SELECT m.*, ar.name AS artist_name FROM music m LEFT JOIN artists ar ON ar.id = m.artist_id WHERE m.album_id = ?";
            $stmt = $this->pdo->prepare($sql);
            foreach($albums as &$album){
                $stmt->execute([$album['id']]);
                $album['tracks'] = $stmt->fetchAll();
            }
            return $albums;
        } catch (\PDOException $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    /**
     * Получение альбома по ID со всеми треками
     * @param int $albumId ID альбома
     * @return array|null
     */
    public function getAlbumById($albumId) {
        try {
            //Log::info("looking for album $albumId");
            // Получаем информацию об альбоме
            $sql = "SELECT a.*, u.name as owner_name 
                    FROM albums a
                    LEFT JOIN users u ON u.id = a.user_id
                    WHERE a.id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$albumId]);
            $album = $stmt->fetch();

            if (!$album) {
                return null;
            }

            $stmt = $this->pdo->prepare("SELECT ar.id, ar.name, m.genre FROM music m LEFT JOIN artists ar ON ar.id = m.artist_id WHERE m.album_id = ? LIMIT 1");
            $stmt->execute([$albumId]);
            $artist = $stmt->fetch();
            $album['artist_name'] = $artist['name'];
            $album['artist_id'] = $artist['id'];
            $album['genre'] = $artist['genre'];
            
            // Получаем треки альбома
            $sql = "SELECT m.*, ar.name as artist_name 
                    FROM music m
                    LEFT JOIN artists ar ON ar.id = m.artist_id
                    WHERE m.album_id = ?
                    ORDER BY m.track_number ASC, m.id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$albumId]);
            $album['tracks'] = $stmt->fetchAll();
            
            return $album;
        } catch (\PDOException $e) {
            Log::error("Error getting album by id: " . $e->getMessage());
            return null;
        } catch(\Exception $e){
            Log::error("Error getting album by id: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Получение альбомов пользователя
     * @param int $userId ID пользователя
     * @return array
     */
    public function getAlbumsByUser($userId) {
        try {
            $sql = "SELECT id 
                    FROM albums
                    WHERE user_id = ?
                    ORDER BY release_year DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            $albumIds = array_map(fn($row) => $row['id'], $stmt->fetchAll());
            $albums = [];
            
            if(!empty($albumIds)){
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM music WHERE album_id = ?");
                foreach($albumIds as $idx => $id){
                    $stmt->execute([$id]);
                    $tracksCount = $stmt->fetchColumn();
                    $album = $this->getAlbumById($id);
                    $album['tracks_count'] = $tracksCount;
                    $albums[] = $album;
                }
            } else {
                //Log::info("no albums for user $userId");
            }
            return $albums;
        } catch (\PDOException $e) {
            Log::error("Error getting user albums: " . $e->getMessage());
            return [];
        }
    }

    public function getAlbumsByArtist($artistName){
        try{
            $artist = $this->userModel->getUsetDetails($artistName);
            if(!isset($artist['id'])){
                return [];
            }
            return $this->getAlbumsByUser($artist['id']);
        } catch(\PDOException $e){
            Log::error("Error getting albums by artist: " . $e->getMessage());
        }
        return [];
    }
    
    /**
     * Обновление информации об альбоме
     * @param int $albumId ID альбома
     * @param array $data Данные для обновления
     * @return array
     */
    public function updateAlbum($albumId, $data) {
        try {
            $updateFields = [];
            $updateParams = [];
            
            $allowedFields = ['name', 'image_url', 'release_year', 'description'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updateFields[] = "$field = ?";
                    $updateParams[] = $data[$field];
                }
            }
            
            if (empty($updateFields)) {
                return ['success' => true, 'message' => 'Nothing to update'];
            }
            
            $updateParams[] = $albumId;
            $sql = "UPDATE albums SET " . implode(', ', $updateFields) . " WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($updateParams);
            
            return [
                'success' => true,
                'message' => 'Album updated successfully'
            ];
        } catch (\PDOException $e) {
            Log::error("Error updating album: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Удаление альбома и всех его треков
     * @param int $albumId ID альбома
     * @return array
     */
    public function deleteAlbum($albumId) {
        try {
            $this->pdo->beginTransaction();
            
            // Получаем все треки альбома для удаления файлов
            $stmt = $this->pdo->prepare("SELECT audio_url FROM music WHERE album_id = ?");
            $stmt->execute([$albumId]);
            $tracks = $stmt->fetchAll();
            
            // Удаляем треки (каскадное удаление настроено в БД)
            $stmt = $this->pdo->prepare("DELETE FROM music WHERE album_id = ?");
            $stmt->execute([$albumId]);
            
            // Удаляем альбом
            $stmt = $this->pdo->prepare("DELETE FROM albums WHERE id = ?");
            $stmt->execute([$albumId]);
            
            $this->pdo->commit();
            
            return [
                'success' => true,
                'message' => 'Album deleted successfully',
                'deleted_tracks' => count($tracks)
            ];
        } catch (\PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            Log::error("Error deleting album: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    /**
     * Обновление счетчиков (лайки, скачивания, шары)
     * @param int $albumId ID альбома
     * @param string $type Тип счётчика (likes, downloads, shares)
     * @param int $increment На сколько увеличить (по умолчанию 1)
     * @return array
     */
    public function updateAlbumStats($albumId, $type, $increment = 1) {
        $allowedTypes = ['likes', 'downloads', 'shares'];
        if (!in_array($type, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid stat type'];
        }
        
        try {
            $sql = "UPDATE albums SET $type = $type + ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$increment, $albumId]);
            
            return ['success' => true, 'message' => 'Stats updated'];
        } catch (\PDOException $e) {
            Log::error("Error updating album stats: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }
    
    /**
     * Поиск альбомов по названию или исполнителю
     * @param string $searchTerm Поисковый запрос
     * @return array
     */
    public function searchAlbums($searchTerm) {
        try {
            $searchTerm = '%' . $searchTerm . '%';
            $sql = "SELECT a.*, 
                    (SELECT ar.name FROM music m2 
                     LEFT JOIN artists ar ON ar.id = m2.artist_id 
                     WHERE m2.album_id = a.id LIMIT 1) as artist_name
                    FROM albums a
                    WHERE a.name LIKE ? 
                    OR EXISTS (
                        SELECT 1 FROM music m 
                        LEFT JOIN artists ar ON ar.id = m.artist_id 
                        WHERE m.album_id = a.id AND ar.name LIKE ?
                    )
                    ORDER BY a.release_year DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$searchTerm, $searchTerm]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            Log::error("Error searching albums: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Получение популярных альбомов (по лайкам/скачиваниям)
     * @param int $limit Лимит
     * @return array
     */
    public function getPopularAlbums($limit = 10) {
        try {
            $sql = "SELECT a.*, 
                    (SELECT ar.name FROM music m2 
                     LEFT JOIN artists ar ON ar.id = m2.artist_id 
                     WHERE m2.album_id = a.id LIMIT 1) as artist_name,
                    (a.likes + a.downloads + a.shares) as popularity_score
                    FROM albums a
                    ORDER BY popularity_score DESC
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            Log::error("Error getting popular albums: " . $e->getMessage());
            return [];
        }
    }
}
?>