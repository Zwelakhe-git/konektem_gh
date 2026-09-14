<?php
namespace Konektem\Utils;

require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Utils\Log;

Log::init();

class ZipDownloader {
    private static $saveDir;
    /**
     * Créer et télécharger un ZIP avec les pistes d'un album
     * @param int $albumId ID de l'album
     * @param array $tracks Liste des pistes avec leurs chemins
     * @param string $albumName Nom de l'album
     * @return bool
     */
    public static function downloadAlbumZip($albumId, $tracks, $albumName) {
        try {
            if(!self::$saveDir){
                self::$saveDir = DOCUMENTS_PATH;
                if (!is_dir(self::$saveDir)) {
                    mkdir(self::$saveDir, 0755, true);
                    Log::info("Created upload dir");
                }
            }
            // Nettoyer le nom de l'album pour le nom du fichier
            $cleanAlbumName = self::sanitizeFileName($albumName);
            $zipFileName = $cleanAlbumName . '_' . date('Y-m-d') . '.zip';
            $zipPath = self::$saveDir . "/$zipFileName";
            
            // Créer une instance de ZipArchive
            $zip = new \ZipArchive();
            
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                Log::error("Failed to open file $cleanAlbumName, $zipFileName, $zipPath");
                throw new \Exception("Failed to create ZIP archive");
            } else {
                Log::info(file_exists($zipPath) ? 'exists' : 'doesnt exist');
            }
            
            $fileCount = 0;
            
            // Ajouter chaque piste au ZIP
            foreach ($tracks as $index => $track) {
                $filePath = $track['url'] ?? $track['audio_url'] ?? null;
                
                if (empty($filePath)) {
                    Log::warn("Track {$track['id']} has no file path, skipping");
                    continue;
                }
                $filePath = strpos($filePath, 'htdocs') ? $filePath : HTDOCS . $filePath;
                // Vérifier que le fichier existe
                if (!file_exists($filePath)) {
                    Log::warn("File not found: $filePath");
                    continue;
                }
                
                // Formater le nom du fichier dans le ZIP: 01 - Nom de la piste.mp3
                $trackNumber = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                $trackName = self::sanitizeFileName($track['title']);
                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                $fileNameInZip = $trackNumber . ' - ' . $trackName . '.' . $fileExtension;
                
                // Ajouter le fichier au ZIP
                if ($zip->addFile($filePath, $fileNameInZip)) {
                    $fileCount++;
                    //Log::info("Added to ZIP: $fileNameInZip");
                } else {
                    Log::warn("Failed to add file: $filePath");
                }
            }
            
            // Ajouter un fichier README.txt avec les informations
            $readmeContent = self::generateReadmeContent($albumName, $tracks);
            $zip->addFromString('README.txt', $readmeContent);
            
            // Fermer l'archive
            $zip->close();
            
            if ($fileCount === 0) {
                throw new \Exception("Aucun fichier valide n'a été trouvé");
            }
            
            // Envoyer le fichier au navigateur
            //self::sendZipToBrowser($zipPath, $zipFileName);
            
            $zipUrl = $zipPath;
            if(preg_match("/htdocs(.*)/", str_replace("\\", "/", $zipPath), $matches)){
                $zipUrl = $matches[1];
            }
            
            return [
                'success' => true,
                'file_name' => $zipFileName,
                'file_url' => $zipUrl
            ];
            
        } catch (\Exception $e) {
            Log::error("Error creating ZIP: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server Error'
            ];
        }
    }
    
    /**
     * Télécharger une seule piste
     * @param string $filePath Chemin du fichier
     * @param string $trackName Nom de la piste
     * @return bool
     */
    public static function downloadSingleTrack($filePath, $trackName) {
        try {
            $filePath = strpos($filePath, 'htdocs') ? $filePath : HTDOCS . $filePath;
            if (!file_exists($filePath)) {
                throw new \Exception("File not found: $filePath");
            }
            
            $cleanName = self::sanitizeFileName($trackName);
            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
            $downloadName = $cleanName . '.' . $fileExtension;
            
            // Headers pour le téléchargement
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $downloadName . '"');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            
            // Lire et envoyer le fichier
            readfile($filePath);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error("Error downloading track: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Créer un ZIP personnalisé à partir d'une liste de fichiers
     * @param array $files Liste des fichiers avec ['path' => chemin, 'name' => nom dans ZIP]
     * @param string $zipName Nom du fichier ZIP
     * @return string|null Chemin du ZIP ou null si erreur
     */
    public static function createCustomZip($files, $zipName = 'download.zip') {
        try {
            $zipPath = sys_get_temp_dir() . '/' . self::sanitizeFileName($zipName);
            $zip = new \ZipArchive();
            
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                throw new \Exception("Impossible de créer l'archive ZIP");
            }
            
            foreach ($files as $file) {
                $filePath = $file['path'] ?? $file;
                $fileNameInZip = $file['name'] ?? basename($filePath);
                
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $fileNameInZip);
                }
            }
            
            $zip->close();
            return $zipPath;
            
        } catch (\Exception $e) {
            Log::error("Error creating custom ZIP: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Envoyer un fichier ZIP au navigateur et le supprimer après
     * @param string $zipPath Chemin du fichier ZIP
     * @param string $fileName Nom du fichier à télécharger
     */
    private static function sendZipToBrowser($zipPath, $fileName) {
        // Désactiver la mise en mémoire tampon
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Headers pour le téléchargement
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($zipPath));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');
        
        // Lire et envoyer le fichier
        readfile($zipPath);
        
        // S'assurer que le script s'arrête après l'envoi
        exit;
    }
    
    /**
     * Nettoyer un nom de fichier (enlever caractères spéciaux)
     * @param string $name Nom à nettoyer
     * @return string
     */
    private static function sanitizeFileName($name) {
        // Remplacer les caractères spéciaux
        $name = preg_replace('/[^\w\s\-\.]/u', '', $name);
        // Remplacer les espaces par des underscores
        $name = preg_replace('/[\s]+/', '_', $name);
        // Enlever les accents
        $name = self::removeAccents($name);
        // Limiter la longueur
        return substr($name, 0, 100);
    }
    
    /**
     * Supprimer les accents d'une chaîne
     * @param string $str
     * @return string
     */
    private static function removeAccents($str) {
        $search = explode(',', 'ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ');
        $replace = explode(',', 'c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE');
        return str_replace($search, $replace, $str);
    }
    
    /**
     * Générer le contenu du fichier README.txt
     * @param string $albumName
     * @param array $tracks
     * @return string
     */
    private static function generateReadmeContent($albumName, $tracks) {
        $content = "========================================\n";
        $content .= "ALBUM: " . strtoupper($albumName) . "\n";
        $content .= "========================================\n\n";
        $content .= "Date de téléchargement: " . date('d/m/Y H:i:s') . "\n";
        $content .= "Nombre de pistes: " . count($tracks) . "\n\n";
        $content .= "LISTE DES PISTES:\n";
        $content .= "-----------------\n";
        
        foreach ($tracks as $index => $track) {
            $trackNumber = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            $duration = $track['duration'] ?? 'N/A';
            $content .= $trackNumber . ". " . $track['title'] . " [" . $duration . "]\n";
        }
        
        $content .= "\n";
        $content .= "Merci d'avoir téléchargé cet album!\n";
        $content .= "Pour plus de musique, visitez notre site.\n";
        
        return $content;
    }
    
    /**
     * Télécharger un ZIP en streaming (pour les très gros fichiers)
     * @param string $zipPath
     * @param string $fileName
     */
    private static function streamZipFile($zipPath, $fileName) {
        // Pour les très gros fichiers, lecture par chunks
        $chunkSize = 1024 * 1024; // 1MB chunks
        
        $handle = fopen($zipPath, 'rb');
        if ($handle === false) {
            return;
        }
        
        while (!feof($handle)) {
            echo fread($handle, $chunkSize);
            ob_flush();
            flush();
        }
        
        fclose($handle);
    }
}

// Point d'entrée pour les requêtes de téléchargement
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    // Route pour télécharger un album complet
    if (isset($_GET['action']) && $_GET['action'] === 'download_album' && isset($_GET['id'])) {
        $albumId = (int)$_GET['id'];
        
        // Récupérer les informations de l'album
        require_once __DIR__ . '/../Models/AlbumModel.php';
        $albumModel = new \Konektem\Admin\Models\AlbumModel();
        $album = $albumModel->getAlbumById($albumId);
        
        if ($album && !empty($album['tracks'])) {
            ZipDownloader::downloadAlbumZip($albumId, $album['tracks'], $album['name']);
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "Album non trouvé ou aucune piste disponible.";
        }
        exit;
    }
    
    // Route pour télécharger une piste individuelle
    if (isset($_GET['action']) && $_GET['action'] === 'download_track' && isset($_GET['id'])) {
        $trackId = (int)$_GET['id'];
        
        // Récupérer les informations de la piste
        require_once __DIR__ . '/../Models/MusicModel.php';
        $musicModel = new \Konektem\Admin\Models\MusicModel();
        $track = $musicModel->getMusicById($trackId);
        
        if ($track && !empty($track['url'])) {
            ZipDownloader::downloadSingleTrack($track['url'], $track['title']);
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "Piste non trouvée.";
        }
        exit;
    }
}

// Point d'entrée pour les requêtes de téléchargement
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    // Route pour télécharger un album complet
    if (isset($_GET['action']) && $_GET['action'] === 'download_album' && isset($_GET['id'])) {
        $albumId = (int)$_GET['id'];
        
        // Récupérer les informations de l'album
        //require_once __DIR__ . '/../Models/AlbumModel.php';
        $albumModel = new \Konektem\Admin\Models\AlbumModel();
        $album = $albumModel->getAlbumById($albumId);
        
        if ($album && !empty($album['tracks'])) {
            ZipDownloader::downloadAlbumZip($albumId, $album['tracks'], $album['name']);
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "Album non trouvé ou aucune piste disponible.";
        }
        exit;
    }
    
    // Route pour télécharger une piste individuelle
    if (isset($_GET['action']) && $_GET['action'] === 'download_track' && isset($_GET['id'])) {
        $trackId = (int)$_GET['id'];
        
        // Récupérer les informations de la piste
        //require_once __DIR__ . '/../Models/MusicModel.php';
        $musicModel = new \Konektem\Admin\Models\MusicModel();
        $track = $musicModel->getMusicById($trackId);
        
        if ($track && !empty($track['url'])) {
            ZipDownloader::downloadSingleTrack($track['url'], $track['title']);
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "Piste non trouvée.";
        }
        exit;
    }
}
?>