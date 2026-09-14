<?php
function createTrackComponent($track){
    ob_start();
    $imageUrl = htmlspecialchars($track['image_url'] ?? '');
    ?>
    <div class="track-thumbnail" data-itemid="<?= $track['id'] ?>">
        <div class="track-img">
            
            <div class="icon-wrapper" style="display: flex;">
                <div class="icon-ring">
                    <div class="icon-circle">
                        <i class="fa-solid fa-music"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="music-info">
            <div class="music-title" title="<?= htmlspecialchars($track['title']) ?>">
                <?= htmlspecialchars($track['title']) ?>
            </div>
            <div class="music-artist" title="<?= htmlspecialchars($track['artist_name'] ?? '') ?>">
                <?= htmlspecialchars($track['artist_name'] ?? '') ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Group tracks by genre more efficiently
$musicGenre = [];
foreach($music as $track) {
    $genre = $track['genre'] ?? 'Uncategorized';
    $genreKey = strtolower(trim($genre));
    
    if (!isset($musicGenre[$genreKey])) {
        $musicGenre[$genreKey] = [
            'name' => $genre,
            'tracks' => [],
            'container' => ''
        ];
    }
    $musicGenre[$genreKey]['tracks'][] = $track;
}

// Sort genres alphabetically
ksort($musicGenre);

// Build HTML for each genre
foreach($musicGenre as $key => &$genreData) {
    $genreData['container'] = createGenreContainer($genreData['name'], count($genreData['tracks']));
    foreach($genreData['tracks'] as $track) {
        $genreData['container'] .= createTrackComponent($track);
    }
    $genreData['container'] .= '</div><div class="expand-controller" data-genre="' . preg_replace('/\W/', '-', strtolower($key)) . '">
        <span>Voir plus</span>
        <i class="fa-solid fa-chevron-down"></i>
    </div></div>';
}

function createGenreContainer($name, $tracksCount = 0){
    ob_start();
    ?>
    <div class="genre" id="<?= preg_replace('/\W/', '-', strtolower($name)) ?>">
        <div class="genre-header">
            <h3 class="section-title">
                <i class="fa-solid fa-music"></i>
                <?= htmlspecialchars($name) ?>
            </h3>
            <span class="track-count" id="count-<?= preg_replace('/\W/', '-', strtolower($name)) ?>">
                <?= $tracksCount ?>
            </span>
        </div>
        <div class="music-content">
    <?php
    return ob_get_clean();
}
?>
<h1>Galri Mizik</h1>
<p class="subtitle">Amize w avek bel mizik sa yo</p>

<div class="media-container">
    <div class="music-section">
        <?php foreach($musicGenre as $g){?>
        <?= $g['container']?>
        <?php
        }
        ?>
        <script>
            let contentExpandControllers = document.querySelectorAll('.expand-controller');
            contentExpandControllers.forEach(ctrl => {
                ctrl.addEventListener('click',()=>{
                    document.querySelector(`#${ctrl.dataset.genre} .music-content`).classList.toggle('expand');
                });
            });
        </script>
    </div>
</div>
<?php require_once __DIR__ . '/../../Components/MusicPlayer.php' ?>
<script>
    window.musicData = <?= json_encode($music)?>;
</script>