<?php
namespace Konektem\Utils;

const DEBUG = true;
class Log{
    private static $file;
    public static function init(){
        if(!self::$file){
            self::$file = defined('LOG_FILE') ? LOG_FILE : __DIR__ . '/../../../logs/log.log';
        }
    }

    public static function info($msg = "Default success message", \Throwable $e=null){
        if($e && (get_class($e) === 'Exception' || is_subclass_of($e, 'Throwable'))){
            return DEBUG && file_put_contents(self::$file, date('Y-m-d H:i:s') . " [INFO] {$e->getMessage()} in {$e->getFile()} line {$e->getLine()}" . PHP_EOL, FILE_APPEND);
        }
        DEBUG && file_put_contents(self::$file, date('Y-m-d H:i:s') . ' [INFO] ' . $msg . PHP_EOL, FILE_APPEND);
    }
    public static function warn($msg = "Default warning message", \Throwable $e=null){
        if($e && (get_class($e) === 'Exception' || is_subclass_of($e, 'Throwable'))){
            return DEBUG && file_put_contents(self::$file, date('Y-m-d H:i:s') . " [WARNING] {$e->getMessage()} in {$e->getFile()} line {$e->getLine()}" . PHP_EOL, FILE_APPEND);
        }
        DEBUG && file_put_contents(self::$file, date('Y-m-d H:i:s') . ' [WARNING] ' . $msg . PHP_EOL, FILE_APPEND);
    }
    public static function warning($msg = "Default warning message", \Throwable $e=null){
        if($e && (get_class($e) === 'Exception' || is_subclass_of($e, 'Throwable'))){
            return DEBUG && self::warn($err);
        }
        DEBUG && self::warn($msg);
    }
    public static function error($msg = "Default error message", \Throwable $e=null){
        if($e && (get_class($e) === 'Exception' || is_subclass_of($e, 'Throwable'))){
            return DEBUG && file_put_contents(self::$file, date('Y-m-d H:i:s') . " [ERROR] {$e->getMessage()} in {$e->getFile()} line {$e->getLine()}" . PHP_EOL, FILE_APPEND);
        }
        DEBUG && file_put_contents(self::$file, date('Y-m-d H:i:s') . ' [ERROR] ' . $msg . PHP_EOL, FILE_APPEND);
    }
}
?>