<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\AlbumModel;
use Konektem\Models\MusicModel;
use Konektem\Models\ImageModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;

Log::init();

class AlbumController {
    private $albumModel;
    private $musicModel;
    private $imageModel;
    private $utils;
    
    public function __construct() {
        $this->albumModel = new AlbumModel();
        $this->musicModel = new MusicModel();
        $this->imageModel = new ImageModel();
        $this->utils = new Utils();
    }
    
    /**
     * Liste des albums
     */
    public function index() {
        $albums = $this->albumModel->getAllAlbums();
        return [
            'albums' => $albums,
        ];
    }

    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return $this->albumModel->getAlbumById($params['id']);
            } elseif(isset($params['user_id']) && !empty($params['user_id'])){
                $albums = $this->albumModel->getAlbumsByUser($params['user_id']);
                //Log::info("albums by user {$params['user_id']}" . print_r($albums, true));
                return $albums;
            }
            return $this->albumModel->getAllAlbums();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }

    public function getAlbumsByUser(){
        $albums = $this->albumModel->getAlbumsByUser($_SESSION['user']['id']);
        return [
            'albums' => $albums,
        ];
    }
    
    /**
     * Formulaire de création d'album
     */
    public function create() {
        $artists = $this->musicModel->getAllArtists();
        $uploadedFiles = [];
        try {
            //Log::info("Album creation started - POST data: " . print_r($_POST, true));
            //Log::info("FILES data: " . print_r($_FILES, true));
            
            // 1. Validation des données de base
            if (empty($_POST['album_name'])) {
                throw new \Exception("Album name is required");
            }
            if (empty($_POST['genre'])) {
                throw new \Exception("Genre is required");
            }
            if (empty($_POST['release_year'])) {
                throw new \Exception("Release year is required");
            }
            if (empty($_FILES['album_image']) || $_FILES['album_image']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception("Album cover is required");
            }
            
            // 2. Upload de la cover de l'album
            $ownerName = $_POST['owner_name'] ?? $_SESSION['user']['name'];
            $formattedName = preg_replace("/\W/", "", $_POST['album_name']);
            $uploadDir = UPLOAD_DIR . "/$ownerName" . "_album_{$formattedName}/";

            $albumImageInfo = $this->utils->upload($uploadDir, IMAGETYPES, $_FILES['album_image']);
            $albumImageId = $this->imageModel->createImage($albumImageInfo['filepath'], $albumImageInfo['mime_type']);
            $uploadedFiles[] = [
                'type' => 'image',
                'url' => $albumImageInfo['filepath']
            ];
            
            // 3. Gestion de l'artiste
            $artistId = null;
            if ($_POST['artist_type'] === 'existing' && !empty($_POST['artist_id'])) {
                $artistId = $_POST['artist_id'];
            } elseif ($_POST['artist_type'] === 'new' && !empty($_POST['new_artist_name'])) {
                $artistId = $this->musicModel->createArtist($_POST['new_artist_name']);
            } else {
                throw new \Exception("Artist information is required");
            }
            
            // 4. Récupération des données des tracks - APPROCHE CORRIGÉE
            $trackData = [];
            
            // 4.1 Récupérer les noms des tracks depuis $_POST
            // PHP a déjà transformé tracks[0][name] en tableau $_POST['tracks'][0]['name']
            if (isset($_POST['tracks']) && is_array($_POST['tracks'])) {
                foreach ($_POST['tracks'] as $index => $trackPost) {
                    if (isset($trackPost['name']) && !empty($trackPost['name'])) {
                        $trackData[$index]['name'] = $trackPost['name'];
                        Log::info("Found track name at index $index: " . $trackPost['name']);
                    }
                }
            }
            
            // 4.2 Récupérer les fichiers audio depuis $_FILES
            // Structure: $_FILES['tracks']['name'][0]['audio']
            if (isset($_FILES['tracks']['name']) && is_array($_FILES['tracks']['name'])) {
                foreach ($_FILES['tracks']['name'] as $index => $files) {
                    if (isset($files['audio']) && !empty($files['audio']) && $_FILES['tracks']['error'][$index]['audio'] === UPLOAD_ERR_OK) {
                        $trackData[$index]['audio'] = [
                            'name' => $_FILES['tracks']['name'][$index]['audio'],
                            'type' => $_FILES['tracks']['type'][$index]['audio'],
                            'tmp_name' => $_FILES['tracks']['tmp_name'][$index]['audio'],
                            'error' => $_FILES['tracks']['error'][$index]['audio'],
                            'size' => $_FILES['tracks']['size'][$index]['audio']
                        ];
                        if(!isset($trackData[$index]['name']) || empty($trackData[$index]['name'])){
                            $trackData[$index]['name'] = $_FILES['tracks']['name'][$index]['audio'];
                        }
                        Log::info("Found audio file at index $index: " . $_FILES['tracks']['name'][$index]['audio']);
                    }
                }
            }
            
            // this is no longer neccessary
            // for the album track images wont be uploaded
            // 4.3 Récupérer les images des tracks depuis $_FILES
            /*if (isset($_FILES['tracks']['name']) && is_array($_FILES['tracks']['name'])) {
                foreach ($_FILES['tracks']['name'] as $index => $files) {
                    if (isset($files['image']) && !empty($files['image']) && $_FILES['tracks']['error'][$index]['image'] === UPLOAD_ERR_OK) {
                        $trackData[$index]['image'] = [
                            'name' => $_FILES['tracks']['name'][$index]['image'],
                            'type' => $_FILES['tracks']['type'][$index]['image'],
                            'tmp_name' => $_FILES['tracks']['tmp_name'][$index]['image'],
                            'error' => $_FILES['tracks']['error'][$index]['image'],
                            'size' => $_FILES['tracks']['size'][$index]['image']
                        ];
                        Log::info("Found image file at index $index: " . $_FILES['tracks']['name'][$index]['image']);
                    }
                }
            }*/
            
            // Vérifier qu'on a au moins un track
            if (empty($trackData)) {
                throw new \Exception("At least one track is required");
            }
            
            //Log::info("Track data collected: " . print_r($trackData, true));
            
            // 5. Traitement des tracks
            $tracksData = [];
            foreach ($trackData as $index => $track) {
                // Vérifier que le track a un nom et un fichier audio
                if (empty($track['name'])) {
                    Log::warn("Track $index has no name, skipping");
                    continue;
                }
                
                if (empty($track['audio'])) {
                    Log::warn("Track $index has no audio file, skipping");
                    continue;
                }
                
                Log::info("Processing track $index: " . $track['name']);
                
                // Upload du fichier audio
                $audioInfo = $this->utils->upload($uploadDir, AUDIOTYPES, $track['audio']);
                $uploadedFiles[] = [
                    'url' => $audioInfo['filepath']
                ];
                
                // Upload de l'image de la track (si fournie)
                $trackImageId = null;
                $trackImageUrl = null;
                // skip
                if (!empty($track['image']) && $track['image']['error'] === UPLOAD_ERR_OK) {
                    $trackImageInfo = $this->utils->upload($uploadDir, IMAGETYPES, $track['image']);
                    $trackImageId = $this->imageModel->createImage($trackImageInfo['filepath'], $trackImageInfo['mime_type']);
                    $trackImageUrl = $trackImageInfo['filepath'];
                    $uploadedFiles[] = [
                        'type' => 'image',
                        'url' => $trackImageInfo['filepath']
                    ];
                } else {
                    Log::info("track {$track['name']} uploaded without image");
                }
                
                $tracksData[] = [
                    'name' => $track['name'],
                    'audio_url' => $audioInfo['filepath'],
                    'audio_mime_type' => $audioInfo['mime_type'],
                    'image_url' => $trackImageUrl,
                    'image_id' => $trackImageId,
                    'genre' => $_POST['genre'],
                    'owner_name' => $_POST['owner_name'] ?? $_SESSION['user']['name'] ?? null,
                    'user_id' => $_SESSION['user']['id'] ?? null,
                    'artist_id' => $artistId
                ];
            }
            
            if (empty($tracksData)) {
                throw new \Exception("No valid tracks were processed");
            }
            
            // 6. Préparation des données de l'album
            $albumData = [
                'name' => $_POST['album_name'],
                'image_url' => $albumImageInfo['filepath'],
                'image_id' => $albumImageId,
                'release_year' => $_POST['release_year'],
                'owner_name' => $_POST['owner_name'] ?? $_SESSION['user']['name'] ?? null,
                'user_id' => $_SESSION['user']['id'] ?? null,
                'description' => $_POST['album_description'] ?? null,
                'artist' => ['id' => $artistId]
            ];
            
            // 7. Création de l'album avec ses tracks
            $result = $this->albumModel->createAlbum($albumData, $tracksData, $_SESSION['user']['id']);
            
            if ($result['success']) {
                Log::info("Album created successfully: " . $result['album_id']);
                
                return [
                    'success' => true,
                    'message' => 'Album created successfully',
                    'album_id' => $result['album_id'],
                ];
                
            } else {
                throw new \Exception($result['message']);
            }
            
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            
            foreach($uploadedFiles as $file){
                if(isset($file['type']) && $file['type'] === 'image'){
                    $this->imageModel->deleteImage(null, $file['url']);
                }
                $this->utils->deleteFile($file['url']);
            }
            
            return [
                'success' => false,
                'message' => 'Server Error',
            ];
        }
    }
    
    /**
     * Formulaire d'édition d'album
     */
    public function edit() {
        try {
            $body = $_POST ?: json_decode(file_get_contents('php://input'), true);
            if(empty($body) || !isset($body['id']) || empty($body['id'])){
                return [
                    'successs' => false,
                    'message' => 'Missing parameters'
                ];
            }
            $id = $body['id'];
            $album = $this->albumModel->getAlbumById($id);
            $artists = $this->musicModel->getAllArtists();
            
            if (!$album) {
                return [
                    'success' => false,
                    'album' => null,
                    'artists' => $artists,
                    'message' => 'Album not found',
                    'view' => 'error'
                ];
            }
            
            // Récupérer l'artiste principal de l'album (basé sur le premier track)
            $album['artist_id'] = !empty($album['tracks']) ? $album['tracks'][0]['artist_id'] : null;
            $album['genre'] = !empty($album['tracks']) ? $album['tracks'][0]['genre'] : null;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Log::info("upading album");
                try {
                    // 1. Mise à jour des informations de base
                    $updateData = [];
                    
                    if (!empty($_POST['album_name'])) {
                        $updateData['name'] = $_POST['album_name'];
                    }
                    if (!empty($_POST['release_year'])) {
                        $updateData['release_year'] = $_POST['release_year'];
                    }
                    if (isset($_POST['description'])) {
                        $updateData['description'] = $_POST['description'];
                    }
                    $ownerName = $_POST['owner_name'] ?? $_SESSION['user']['name'];
                    $formattedName = preg_replace("/\W/", "", $_POST['album_name']);
                    $uploadDir = UPLOAD_DIR . "/$ownerName" . "_album_{$formattedName}/";
                    
                    // 2. Gestion de la nouvelle cover
                    if (!empty($_FILES['album_image']['name'])) {
                        $imageInfo = $this->utils->upload($uploadDir, IMAGETYPES, $_FILES['album_image']);
                        $imageId = $this->imageModel->createImage($imageInfo['filepath'], $imageInfo['mime_type']);
                        $updateData['image_url'] = $imageInfo['filepath'];
                        $updateData['image_id'] = $imageId;
                        
                        // Supprimer l'ancienne image
                        if (!empty($album['image_url'])) {
                            $this->utils->deleteFile($album['image_url']);
                        }
                        if (!empty($album['image_id'])) {
                            $this->imageModel->deleteImage($album['image_id']);
                        }
                    } elseif (isset($_POST['remove_image']) && $_POST['remove_image'] == 'on') {
                        // Supprimer l'image existante
                        if (!empty($album['image_url'])) {
                            $this->utils->deleteFile($album['image_url']);
                        }
                        if (!empty($album['image_id'])) {
                            $this->imageModel->deleteImage($album['image_id']);
                        }
                        $updateData['image_url'] = null;
                    }
                    
                    // 3. Mise à jour de l'album
                    if (!empty($updateData)) {
                        Log::info("album data installed. now updating");
                        $this->albumModel->updateAlbum($id, $updateData);
                    }
                    
                    // 4. Gestion des tracks à supprimer
                    if (isset($_POST['delete_tracks']) && is_array($_POST['delete_tracks'])) {
                        foreach ($_POST['delete_tracks'] as $trackId) {
                            $track = $this->musicModel->getMusicById($trackId);
                            if ($track) {
                                // Supprimer les fichiers
                                if (!empty($track['location'])) {
                                    $this->utils->deleteFile($track['location']);
                                } else if(!empty($track['audio_url'])){
                                    $this->utils->deleteFile($track['audio_url']);
                                }
                                if (!empty($track['image_location'])) {
                                    $this->utils->deleteFile($track['image_location']);
                                }
                                if (!empty($track['track_img_id'])) {
                                    $this->imageModel->deleteImage($track['track_img_id']);
                                }
                                // Supprimer de la base
                                $this->musicModel->deleteMusic($trackId);
                            }
                        }
                    }
                    
                    // 5. Ajout des nouvelles tracks
                    if (isset($_POST['new_tracks']) && is_array($_POST['new_tracks'])) {
                        Log::info("new tracks were added to the album");
                        foreach ($_POST['new_tracks'] as $index => $newTrack) {
                            if (!empty($newTrack['name'])) {
                                // Vérifier le fichier audio
                                $audioKey = 'new_tracks_audio_' . $index;
                                if (isset($_FILES[$audioKey]) && !empty($_FILES[$audioKey]['name'])) {
                                    $audioInfo = $this->utils->upload($uploadDir, AUDIOTYPES, $_FILES[$audioKey]);
                                    
                                    // Vérifier l'image
                                    $imageKey = 'new_tracks_image_' . $index;
                                    $trackImageId = null;
                                    $trackImageUrl = null;
                                    if (isset($_FILES[$imageKey]) && !empty($_FILES[$imageKey]['name'])) {
                                        $trackImageInfo = $this->utils->upload($uploadDir, IMAGETYPES, $_FILES[$imageKey]);
                                        $trackImageId = $this->imageModel->createImage($trackImageInfo['filepath'], $trackImageInfo['mime_type']);
                                        $trackImageUrl = $trackImageInfo['filepath'];
                                    }
                                    
                                    // Récupérer ou créer l'artiste
                                    $artistId = null;
                                    if (!empty($_POST['artist_id']) && $_POST['artist_id'] !== 'new') {
                                        $artistId = $_POST['artist_id'];
                                    } elseif (!empty($_POST['new_artist_name'])) {
                                        $artistId = $this->musicModel->createArtist($_POST['new_artist_name']);
                                    } else {
                                        $artistId = $album['artist_id'];
                                    }
                                    
                                    // Créer la track
                                    $trackData = [
                                        'track_name' => $newTrack['name'],
                                        'artist_id' => $artistId,
                                        'track_img_id' => $trackImageId,
                                        'location' => $audioInfo['filepath'],
                                        'mime_type' => $audioInfo['mime_type'],
                                        'genre' => $_POST['genre'] ?? $album['genre'],
                                        'album_id' => $id
                                    ];
                                    
                                    $this->musicModel->createMusic($trackData);
                                } else {
                                    Log::info("track $index has no audio file");
                                }
                            } else {
                                Log::info("track $index has no name");
                            }
                        }
                    }
                    
                    Log::info("Album updated successfully: " . $id);
                    return [
                        'success' => true,
                        'message' => 'Album updated successfully'
                    ];
                    
                } catch (\Exception $e) {
                    Log::error("Error updating album: " . $e->getMessage());
                    return [
                        'success' => false,
                        'message' => 'Server Error'
                    ];
                }
            } else {
                return [
                    'album' => $album,
                    'artists' => $artists,
                ];
            }
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        } catch(\Throwable $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    /**
     * Suppression d'album
     */
    public function delete($params=[]) {
        try {
            $body = json_decode(file_get_contents('php://input'), true);
            if((empty($body) || !isset($body['id']) || empty($body['id'])) && (!isset($params['id']) || empty($params['id']))){
                return [
                    'successs' => false,
                    'message' => 'Missing parameters'
                ];
            }
            $id = $body['id'] ?? $params['id'];
            $album = $this->albumModel->getAlbumById($id);
            
            if (!$album) {
                return [
                    'success' => false,
                    'message' => 'Album not found'
                ];
            }
            // Supprimer l'album de la base
            $result = $this->albumModel->deleteAlbum($id);
            
            if ($result['success']) {
                // Supprimer la cover de l'album
                if (!empty($album['image_url'])) {
                    $this->utils->deleteFile($album['image_url']);
                }
                if (!empty($album['image_id'])) {
                    $this->imageModel->deleteImage($album['image_id']);
                }
                
                // Supprimer toutes les tracks et leurs fichiers
                foreach ($album['tracks'] as $track) {
                    if (!empty($track['location'])) {
                        $this->utils->deleteFile($track['location']);
                    } else if(!empty($track['audio_url'])){
                        $this->utils->deleteFile($track['audio_url']);
                    }
                    if (!empty($track['image_url'])) {
                        $this->utils->deleteFile($track['image_url']);
                    }
                    if (!empty($track['track_img_id'])) {
                        $this->imageModel->deleteImage($track['track_img_id']);
                    }
                }
                return [
                    'success' => true,
                    'message' => 'Album deleted successfully'
                ];
            } else {
                throw new \Exception($result['message']);
            }
            
        } catch (\Exception $e) {
            Log::error("Error deleting album: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server Error'
            ];
        }
    }
    
    /**
     * Détails d'un album (API)
     */
    public function show($id) {
        $album = $this->albumModel->getAlbumById($id);
        
        if (!$album) {
            return [
                'success' => false,
                'message' => 'Album not found'
            ];
        }
        
        return [
            'success' => true,
            'album' => $album
        ];
    }
    
    /**
     * Recherche d'albums (API)
     */
    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            return [
                'success' => false,
                'message' => 'Search query is required'
            ];
        }
        
        $results = $this->albumModel->searchAlbums($query);
        
        return [
            'success' => true,
            'results' => $results
        ];
    }

}
?>