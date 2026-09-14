<?php
namespace Konektem\View;
use Exception;

class View{
    private $basePath;
    public function __construct($basePath){
        $this->basePath = $basePath;
    }

    public function render($template, $data = []){
        
        $filePath = $this->basePath . '/' . $template . '.php';
        if(!file_exists($filePath)){
            if(file_exists($template . '.php')){
                $filePath = $template . '.php';
            } else throw new Exception("Template [$filePath] not found");
        }
        extract($data);
        ob_start();

        require $filePath;

        return ob_get_clean();

    }
}

?>