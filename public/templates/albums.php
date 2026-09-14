<div class="grid albums-grid box">
    <?php foreach($albums as $album){?>
    <a class="album-card card box" href="/konektem/music/album/id/<?= $album['id']?>">
        <div class="card-header">
            <div class="image-container album-image full">
                <img alt="image" src="<?= $album['image_url']?>" class="w-full"/>
            </div>
        </div>
        <div class="card-body">
            <div class="album-stats">
                <div class="album-name stats-item"><?= $album['name']?></div>
                <div class="album-song-count stats-item">
                    <i class="fa-solid fa-music"></i>
                    <span><?= $album['songs_count']?></span>
                </div>
            </div>
            <div class="album-description">
                <?= $album['description']?>
            </div>
        </div>
        <div class="card-footer">
            <div class="album-year stats-item">
                <i class="fa-solid fa-calendar"></i>
                <span><?= $album['release_year']?></span>
            </div>
        </div>
    </a>
    <?php }?>
    
</div>