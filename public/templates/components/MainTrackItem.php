<div class="track"
    id="track-<?= $track['id'] ?>"
    data-trackid="<?= $track['id'] ?>" data-itemid="<?= $track['id'] ?>">
    <div class="track-img">
        <img class="full-w-h" alt="track image" src="<?= $track['image_location'] ?>" />
    </div>
    <div class="music-info">
        <div class="music-title"><?= $track['track_name'] ?></div>
        <div class="music-artist"><?= $track['artist_name'] ?></div>
        <div class="player-controls">
            <div class="play-btn" data-trackid="<?= $track['id'] ?>" data-itemid="<?= $track['id'] ?>">
                <i class="fas fa-play media-ico play"
                data-trackid="<?= $track['id'] ?>" data-itemid="<?= $track['id'] ?>" 
                <?= strlen($track['location']) > 0 && 'data-src="{$track[\'location\']}"' ?>
                data-tracktitle=<?= $track['track_name'] ?>></i>
            </div>
            <div class="progress-bar">
                <div class="progress track-<?= $track['id'] ?>"></div>
            </div>
            <div class="music-duration track-<?= $track['id'] ?>"></div>
        </div>
        <div class="media-actions">
            <div class="action-btn download-btn">
                <i class="fas fa-download media-ico"
                data-itemname="music"
                data-trackid="<?= $track['id'] ?>"
                data-itemid="<?= $track['id'] ?>" 
                data-tracktitle="<?= $track['track_name'] ?>"></i>
                <span><?= $track['downloads'] ?></span>
                <a id="dd-a<?= $track['id'] ?>" download="<?= $track['track_name'] ?>"
                data-href="<?= $track['location'] ?>" style="display: none;"></a>
            </div>
            <div class="action-btn like-btn">
                <i class="fa-regular fa-heart media-ico"
                data-itemname="music"
                data-trackid="<?= $track['id'] ?>"
                data-itemid="<?= $track['id'] ?>"
                data-tracktitle="<?= $track['track_name'] ?>"></i>
                <span><?= $track['likes'] ?></span>
            </div>
            <div class="action-btn share-btn">
                <i class="fa-regular fa-paper-plane media-ico"
                data-itemname="music" 
                data-itemid="<?= $track['id'] ?>"
                data-trackid="<?= $track['id'] ?>"></i>
                <span><?= $track['shares'] ?? 0 ?></span>
            </div>
        </div>
        <span style="font-size: 5px;position: absolute;right: 0px;"><?= $track['owner'] ?? '' ?></span>
    </div>                                            
</div>