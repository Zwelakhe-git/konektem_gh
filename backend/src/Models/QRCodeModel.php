<?php
namespace Konektem\Models;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Utils\Log;

Log::init();

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeModel{
    private $data;
    private $qrcode;
    private $writer;
    
    public function __construct($url){
        $this->qrcode = new QrCode($url);
        $this->writer = new PngWriter();
    }
    
    private function create(){
        return $this->writer->write($this->qrcode);
    }
    
    public function getBase64Data(){
        $data = $this->create();
        $data_base64 = base64_encode($data->getString());
        return $data_base64;
    }
}

?>