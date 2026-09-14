<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\MusicModel;
use Konektem\Models\AdminModel;
use Konektem\Models\AlbumModel;
use Konektem\uth\Auth;
use Konektem\Utils\ZipDownloader;
use Konektem\Utils\Log;

Log::init();

class DownloadController {
    private $auth;
    private $model;
    private $albumModel;
    private $musicModel;
    
    public function __construct() {
        $this->musicModel = new MusicModel();
        $this->auth = new Auth();
        $this->model = new AdminModel();
        $this->albumModel = new AlbumModel();
    }
    /**
     * Télécharger l'album complet en ZIP
     */
    public function downloadAlbum($id) {
        $album = $this->albumModel->getAlbumById($id);
        
        if (!$album || empty($album['tracks'])) {
            return [
                'success' => false,
                'error' => 'Album non trouvé ou aucune piste disponible'
            ];
        }
        
        // Mettre à jour le compteur de téléchargements
        $this->albumModel->updateAlbumStats($id, 'downloads', 1);
        
        // Lancer le téléchargement ZIP
        $result = ZipDownloader::downloadAlbumZip($id, $album['tracks'], $album['name']);
        
        return $result;
    }

    /**
     * Télécharger une seule piste
     */
    public function downloadTrack($id) {
        $track = $this->musicModel->getMusicById($id);
        
        if (!$track || empty($track['location'])) {
            return [
                'success' => false,
                'error' => 'Piste non trouvée'
            ];
        }
        
        // Mettre à jour le compteur de téléchargements de la piste
        $this->musicModel->updateMusicStats($id, 'downloads', 1);
        
        // Lancer le téléchargement
        ZipDownloader::downloadSingleTrack($track['location'], $track['track_name']);
        
        return true;
    }
}