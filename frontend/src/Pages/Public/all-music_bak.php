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
            <div class="music-title"><?= $track['title'] ?></div>
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
//\Konektem\Utils\Log::info(print_r($music, true));
foreach($music as $track){
    if(!isset($track['genre']) || empty($track['genre'])){
        //\Konektem\Utils\Log::info("track: {$track['id']} has no genre");
        continue;
    }
    //\Konektem\Utils\Log::info("current track genre: {$track['genre']}");
    

    $target = array_filter($musicGenre, function($g, $idx) use ($track){
        //\Konektem\Utils\Log::info("filter index: " . print_r($idx, true));
        return strtolower($g['name']) === strtolower($track['genre']);
    }, ARRAY_FILTER_USE_BOTH);
    $dest = !empty($target) ? array_key_first($target) : -1;
    
    if($dest < 0){
        $musicGenre[] = [
            'name' => $track['genre'],
            'container' => createGenreContainer($track['genre'])
        ];
        $dest = array_key_last($musicGenre);
        
    }
    //\Konektem\Utils\Log::info("new genre: '$dest' " . count($musicGenre));
    //\Konektem\Utils\Log::info(print_r($dest, true));
    //\Konektem\Utils\Log::info(print_r($musicGenre[$dest], true));
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
<?php require_once __DIR__ . '/../../Components/MusicPlayer.php' ?>
<script>
    window.musicData = <?= json_encode($music)?>;
</script>
