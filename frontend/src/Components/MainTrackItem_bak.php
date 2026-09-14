<div class="track"
    id="track-<?= $track['id'] ?>"
    data-itemid="<?= $track['id'] ?>">
    <div class="track-img">
        <img class="full-w-h" alt="track image" src="<?= $track['image_url'] ?>" />
    </div>
    <div class="music-info">
        <div class="music-title"><?= $track['title'] ?></div>
        <div class="music-artist"><?= $track['artist_name'] ?></div>
        <div class="player-controls justify-content-center">
            <div class="play-btn play-track" data-itemid="<?= $track['id'] ?>">
                <i class="fas fa-play media-ico" data-itemid="<?= $track['id'] ?>"></i>
            </div>
            <div class="d-flex flex-row gap-1">
                <div class="progress-bar" style="--text: black;">
                    <div class="progress track-<?= $track['id'] ?>"></div>
                </div>
                <div class="play-time track-<?= $track['id'] ?>"></div>
            </div>
            <div class="media-actions d-flex flex-row justify-content-between" style="--text: black;">
                <div class="action-btn download-btn">
                    <i class="fas fa-download media-ico"
                    data-itemname="track"
                    data-itemid="<?= $track['id'] ?>" ></i>
                    <span><?= $track['downloads'] ?></span>
                    <!-- <a id="dd-a<?= $track['id'] ?>" download="<?= $track['title'] ?>"
                    data-href="<?= $track['url'] ?>" style="display: none;"></a> -->
                </div>
                <div class="action-btn like-btn">
                    <i class="fa-regular fa-heart media-ico"
                    data-itemname="track"
                    data-itemid="<?= $track['id'] ?>"></i>
                    <span><?= $track['likes'] ?></span>
                </div>
                <div class="action-btn share-btn">
                    <i class="fa-regular fa-paper-plane media-ico"
                    data-itemname="track" 
                    data-itemid="<?= $track['id'] ?>"
                    data-trackid="<?= $track['id'] ?>"></i>
                    <span><?= $track['shares'] ?? 0 ?></span>
                </div>
            </div>
            
        </div>
    </div>
    <span style="font-size: 5px;position: absolute;right: 0px;bottom: 0px;"><?= $track['owner'] ?? '' ?></span>                                        
</div>