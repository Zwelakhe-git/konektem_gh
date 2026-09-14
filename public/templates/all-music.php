<?php
function createTrackComponent($track){
    ob_start();
    ?>
    <div class="track-thumbnail" data-itemid="<?= $track['id']?>">
        <div class="track-img">
            <div class="icon-container">
                <div class="icon-boundary"></div>
                <i class="fa-solid fa-music icon"></i>
            </div>
        </div>
        <div class="music-info">
            <div class="music-title"><?= $track['track_name'] ?></div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function createGenreContainer($name){
    ob_start();
    ?>
    <div class="genre" id='<?= preg_replace('/\W/','-',$name) ?>'>
        <h1 class="section-title">
            <i class="fa-solid fa-music"></i>
            <?= $name?>
        </h1>
        <div class="music-content">
    <?php
    return ob_get_clean();
}

// dynamically create music genre
$musicGenre = [];

foreach($music as $track){
    if(!isset($track['genre'])) continue;

    $dest = !empty($musicGenre) ? find_($musicGenre, fn($g) => strtolower($g['name']) === strtolower($track['genre']))[0] : null;
    if(!$dest || $dest < 0){
        $musicGenre[] = [
            'name' => $track['genre'],
            'container' => createGenreContainer($track['genre'])
        ];
        $dest = array_key_last($musicGenre);
    }
    $musicGenre[$dest]['container'] .= createTrackComponent($track);
}
?>
<h1>Galri Mizik</h1>
<p class="subtitle">Amize w avek bel mizik sa yo</p>

<div class="media-container">
    <div class="music-section">
        <?php foreach($musicGenre as $g){?>
        <?= $g['container']?></div>
            <div class="expand-controller" data-genre="<?= preg_replace('/\W/','-',$g['name'])?>">
                <span>we splis</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>
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
<script>
    let musicData = <?= json_encode($music)?>;
</script>
<script src="<?= BASE_URL . '/dist/music_with_auth.fa98b666ad8d9cbfcadc.js'?>"></script>
