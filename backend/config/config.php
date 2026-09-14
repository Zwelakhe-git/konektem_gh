<?php
define('IMAGETYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif', 'image/svg+xml']);
define('VIDEOTYPES', ['video/mp4', 'video/*']);
define('AUDIOTYPES', ['audio/mpeg', 'audio/wav', 'audio/mp3', 'video/mp4']);
define('ADMIN_EMAIL','zwelakhe.mzwet@gmail.com');
define('SITE_EMAIL', 'zwelakhe.mzwet@gmail.com');
define('SERVICE_MANAGER_EMAIL', '');
define('GOOGLE_EMAIL_PASSWORD', 'bavbrdrighdrgemm');
define('ADMIN_NAME', '');
define('SITE_ROOT', realpath(__DIR__ . '/../..'));
define('BASE_DIR', realpath(__DIR__ . '/../..'));
define('HTDOCS', BASE_DIR);
define('TEMPLATES_DIR', BASE_DIR . '/frontend/src/Pages');
define('ADMIN_DIR', TEMPLATES_DIR . '/Admin');
define('MEDIA_ROOT', BASE_DIR . '/media');
define('UPLOAD_DIR', BASE_DIR . '/uploads');
define('IMAGES_PATH', UPLOAD_DIR . '/images');
define('VIDEOS_PATH', UPLOAD_DIR . '/videos');
define('DOCUMENTS_PATH', UPLOAD_DIR . '/documents');
define('OS_ORG_API_KEY', '');
define('OS_APP_API_KEY', '');
define('OS_PN_APP_ID', '');
define('BASE_URL', '');
define('LOG_FILE', __DIR__ . '/../logs/log.log');

define("TINY_API", "g23kch440bemtvvaejf63nukznpuwd12l7nk7whkpijtejvc");

function getErrorType($code) {
    $errorNames = [
        1 => 'FATAL',      // E_ERROR
        2 => 'WARNING',    // E_WARNING
        4 => 'PARSE',      // E_PARSE
        8 => 'NOTICE',     // E_NOTICE
        16 => 'CORE FATAL',
        32 => 'CORE WARNING',
        64 => 'COMPILE FATAL',
        128 => 'COMPILE WARNING',
        256 => 'USER FATAL',
        512 => 'USER WARNING',
        1024 => 'USER NOTICE',
        2048 => 'STRICT',
        4096 => 'RECOVERABLE',
        8192 => 'DEPRECATED',
        16384 => 'USER DEPRECATED'
    ];
    
    return $errorNames[$code] ?? 'UNKNOWN';
}


?>