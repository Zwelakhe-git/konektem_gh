<?php
require_once __DIR__ . '/../../config/config.php';
//require_once HTDOCS . '/vendor/autoload.php';

// Указываем множество папок для обработки
$workDirs = [
    BASE_DIR . '/backend/routes',
];

// Определяем правила замены для URL
$urlReplacements = [
    //'/user/me' => '/admin',
    '/user/me' => ''
];

// Определяем правила замены для пространств имен (из оригинального кода)
$namespaceReplacements = [
    // Можно добавить другие замены пространств имен
];

function processFile($fullPath, $urlReplacements, $namespaceReplacements) {
    if(!file_exists($fullPath) || !is_file($fullPath)) {
        return false;
    }

    $content = file_get_contents($fullPath);
    if(strlen(trim($content)) === 0) {
        return false;
    }

    $modified = false;

    // Заменяем URL-пути
    foreach($urlReplacements as $search => $replace) {
        // Экранируем спецсимволы для регулярного выражения
        $pattern = '/' . preg_quote($search, '/') . '/';
        if(preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $replace, $content);
            $modified = true;
        }
    }

    // Заменяем пространства имен (оригинальная логика)
    foreach($namespaceReplacements as $search => $replace) {
        // Для use
        $patternUse = '/use ' . preg_quote($search, '/') . '(\\\\.*?)/';
        if(preg_match($patternUse, $content)) {
            $content = preg_replace($patternUse, 'use ' . $replace . '$1', $content);
            $modified = true;
        }

        // Для namespace
        $patternNamespace = '/namespace ' . preg_quote($search, '/') . '(\\\\.*?)/';
        if(preg_match($patternNamespace, $content)) {
            $content = preg_replace($patternNamespace, 'namespace ' . $replace . '$1', $content);
            $modified = true;
        }
    }

    if($modified) {
        file_put_contents($fullPath, $content);
        echo "Обработан: " . $fullPath . "\n";
        return true;
    }

    return false;
}

function scanDirectory($dir, $urlReplacements, $namespaceReplacements) {
    if(!is_dir($dir)) {
        echo "Папка не существует: " . $dir . "\n";
        return;
    }

    $items = scandir($dir);
    foreach($items as $item) {
        if($item === '.' || $item === '..') {
            continue;
        }

        $fullPath = $dir . '/' . $item;

        if(is_dir($fullPath)) {
            // Рекурсивно обходим подпапки
            scanDirectory($fullPath, $urlReplacements, $namespaceReplacements);
        } else if(is_file($fullPath)) {
            // Обрабатываем только PHP-файлы (можно изменить расширение)
            $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
            if(in_array($extension, ['php', 'phtml', 'html'])) {
                processFile($fullPath, $urlReplacements, $namespaceReplacements);
            }
        }
    }
}

// Обрабатываем все указанные папки
foreach($workDirs as $dir) {
    echo "Обработка папки: " . $dir . "\n";
    scanDirectory($dir, $urlReplacements, $namespaceReplacements);
}

echo "Готово!\n";
?>