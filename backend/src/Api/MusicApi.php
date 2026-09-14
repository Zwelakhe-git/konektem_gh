<?php
namespace Konektem\Api;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\MusicModel;
use Konektem\Models\AdminModel;
use Konektem\Auth\Auth;
use Konektem\Utils\Log;

Log::init();

class MusicApi{
    private $model;

    public function __construct(){
        $this->model = new MusicModel();
    }

    public function getMusic(){
        
    }
}
?>