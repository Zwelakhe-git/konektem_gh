<?php
function createVideoPlayerFromUrl(string $url, string $poster = null): string{
    $isYoutube = false;
    $isVimeo = false;
    $embedUrl = '';
    
    // Регулярка для разных форматов YouTube ссылок
    if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        $videoId = $matches[1];
        $embedUrl = "https://www.youtube.com/embed/" . htmlspecialchars($videoId);
        $isYoutube = true;
    } elseif(preg_match('/vimeo\.com/', $url)){
        $parts = explode('/', $url);
        $videoId = htmlspecialchars($parts[array_key_last($parts)]);
        $embedUrl = "https://player.vimeo.com/video/$videoId?autoplay=0";
        $isVimeo = true;
    }
    
    if ($isYoutube || $isVimeo) {
    ob_start();
    ?>
        <iframe class="full-wh" src="<?= $embedUrl ?>"
                title="YouTube video player"
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                referrerpolicy="strict-origin-when-cross-origin" 
                allowfullscreen>
        </iframe>
    <?php return ob_get_clean();
    } else {
        // Безопасная проверка локального файла (не даём выводить любые URL)
        // Предполагаем, что это относительный путь к файлу на сервере
        $allowedExtensions = ['mp4', 'webm', 'ogg'];
        $pathInfo = pathinfo($url);
        $ext = strtolower($pathInfo['extension'] ?? '');
        
        if (in_array($ext, $allowedExtensions) && file_exists($_SERVER['DOCUMENT_ROOT'] . $url)) {
            // Очищаем путь и кодируем для src
            $cleanPath = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
            ob_start();
            ?>
            <video controls class="full-wh" <?= $poster ? "poster='$poster'" : ''?>>
                <source src="<?= $cleanPath ?>" type="video/<?= $ext ?>">
                Ваш браузер не поддерживает видео.
            </video>
        <?php return ob_get_clean();
        } else {
            return null;
        }
    }
}
?>