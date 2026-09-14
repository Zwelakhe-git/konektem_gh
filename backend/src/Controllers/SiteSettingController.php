<?php
namespace Konektem\Controllers;
require_once __DIR__ . '/../../config/config.php';
require_once HTDOCS . '/vendor/autoload.php';

use Konektem\Models\SiteSettingsModel;
use Konektem\Utils\Utils;
use Konektem\Utils\Log;

Log::init();
class SiteSettingController {
    private $model;
    private $utils;
    
    public function __construct() {
        $this->model = new SiteSettingsModel();
        $this->utils = new Utils();
    }
    
    public function index() {
        $settings = $this->model->getAllSettings();
        $groups = $this->model->getGroups();
        
        // Группируем настройки по группам
        $groupedSettings = [];
        foreach ($settings as $setting) {
            $groupedSettings[$setting['setting_group']][] = $setting;
        }
        
    }

    public function get($params=[]){
        try {
            if(isset($params['id']) && !empty($params['id'])){
                return $this->model->getSettingById($params['id']);
            }
            return $this->model->getAllSettings();
        } catch(\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [];
        }
    }
    
    public function create() {
        $error = null;
        $uploadedFiles = [];
        try {
            // Валидация
            if (empty($_POST['setting_key'])) {
                throw new \Exception("A key is required");
            }
            
            $data = [
                'setting_key' => $_POST['setting_key'],
                'setting_value' => $_POST['setting_value'] ?? '',
                'setting_type' => $_POST['setting_type'],
                'setting_group' => $_POST['setting_group'],
                'description' => $_POST['description'] ?? null,
                'is_public' => isset($_POST['is_public']) ? 1 : 0,
                'display_order' => $_POST['display_order'] ?? 0
            ];
            if($_POST['setting_type'] === 'video' || $_POST['setting_type'] === 'image'){
                if($_FILES['media_file']['name']){
                    $uploadDir = UPLOAD_DIR . ($_POST['setting_type'] === 'video' ? '/videos' : '/images');
                    $allowedTypes = $_POST['setting_type'] === 'video' ? VIDEOTYPES : IMAGETYPES;
                    $fileInfo = $this->utils->upload($uploadDir, $allowedTypes, $_FILES['media_file']);
                    $data['setting_value'] = $fileInfo['filepath'];
                    $uploadedFiles[] = $fileInfo['filepath'];
                }
            }
            if ($this->model->keyExists($_POST['setting_key'])) {
                return [
                    'success' => false,
                    'message' => 'Key already exists. Please edit instead to avoid duplicates.'
                ];
            }
            $result = $this->model->createSetting($data);
            
            return $result;
        } catch (\Exception $e) {
            if(!empty($uploadedFiles)){
                foreach($uploadedFiles as $url){
                    $this->utils->deleteFile($url);
                }
            }
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function edit() {
        $body = $_POST ?: json_decode(file_get_contents('php://input'), true);
        if(empty($body) || !isset($body['id']) || empty($body['id'])){
            return [
                'successs' => false,
                'message' => 'Missing parameters'
            ];
        }
        $id = $body['id'];
        $error = null;
        $setting = $this->model->getSettingById($id);
        $uploadedFiles = [];
        
        if (!$setting) {
            //header('Location: ?action=settings&error=not_found');
            return [
                'success' => false,
                'message' => 'setting not found'
            ];
        }
        
        try {
            // Проверяем, не занят ли новый ключ другим элементом
            if ($_POST['setting_key'] !== $setting['setting_key']) {
                if ($this->model->keyExists($_POST['setting_key'])) {
                    return [
                        'success' => false,
                        'message' => 'Setting key already being used by another parameter'
                    ];
                }
            }
            
            $data = [
                'setting_key' => $_POST['setting_key'],
                'setting_value' => $_POST['setting_value'] ?? '',
                'setting_type' => $_POST['setting_type'],
                'setting_group' => $_POST['setting_group'],
                'description' => $_POST['description'] ?? null,
                'is_public' => isset($_POST['is_public']) ? 1 : 0,
                'display_order' => $_POST['display_order'] ?? 0
            ];
            if($_POST['setting_type'] === 'video' || $_POST['setting_type'] === 'image'){
                if($_FILES['media_file']['name']){
                    $uploadDir = UPLOAD_DIR . ($_POST['setting_type'] === 'video' ? '/videos' : '/images');
                    $allowedTypes = $_POST['setting_type'] === 'video' ? VIDEOTYPES : IMAGETYPES;
                    $fileInfo = $this->utils->upload($uploadDir, $allowedTypes, $_FILES['media_file']);
                    $data['setting_value'] = $fileInfo['filepath'];
                    $uploadedFiles[] = $fileInfo['filepath'];

                    if($setting['setting_type'] === 'video' || $setting['setting_type'] === 'image'){
                        $this->utils->deleteFile($setting['setting_value']);
                    }
                }
            }
            $result = $this->model->updateSetting($id, $data);
            return $result;
        } catch (\Exception $e) {
            if(!empty($uploadedFiles)){
                foreach($uploadedFiles as $url){
                    $this->utils->deleteFile($url);
                }
            }
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
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
            $setting = $this->model->getSettingById($id);
            
            if (!$setting) {
                return [
                    'success' => false,
                    'message' => 'Setting not found'
                ];
            }
            
            // Запрещаем удаление системных настроек
            if ($setting['setting_group'] === 'system') {
                return [
                    'success' => false,
                    'message' => 'System settings are protected'
                ];
            }
            $result = $this->model->deleteSetting($id);
            return [
                'success' => true,
                'message' => 'setting successfully deleted'
            ];
        } catch (\Exception $e){
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function bulkUpdate() {
        try {
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'setting_') === 0) {
                    $settingId = substr($key, 8); // Убираем 'setting_'
                    $setting = $this->model->getSettingById($settingId);
                    
                    if ($setting) {
                        $this->model->updateSetting($settingId, [
                            'setting_key' => $setting['setting_key'],
                            'setting_value' => $value,
                            'setting_type' => $setting['setting_type'],
                            'setting_group' => $setting['setting_group'],
                            'description' => $setting['description'],
                            'is_public' => $setting['is_public'],
                            'display_order' => $setting['display_order']
                        ]);
                    }
                }
            }
            
            return [
                'success' => true,
                'message' => 'updates successful'
            ];
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}");
            return [
                'success' => false,
                'message' => 'Server error'
            ];
        }
    }
    
    public function getSettingsByGroup(){
        $body = $_POST ?? json_decode(file_get_contents('php://input'), true);
        if(empty($body) || !isset($body['group']) || empty($body['group'])){
            return [
                'successs' => false,
                'message' => 'Missing parameters'
            ];
        }
        $group = $body['group'];
        $settings = $this->model->getSettingsByGroup($group);
        if($settings){
            return [
                'success' => true,
                'settings' => $settings
            ];
        } else {
            return [
                'success' => false,
                'message' => 'group not found'
            ];
        }
    }
    // Метод для получения настройки через AJAX
    public function getSettingValue($key) {
        $setting = $this->model->getSettingByKey($key);
        if ($setting) {
            return [
                'success' => true,
                'value' => $setting['setting_value']
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Setting not found'
            ];
        }
        exit;
    }
    
    // Метод для обновления настройки через AJAX
    public function updateSettingValue($key, $value) {
        $result = $this->model->updateValueByKey($key, $value);
        return $result;
    }
}
?>
