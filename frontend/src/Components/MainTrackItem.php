<div class="track-item p-md-2 pb-md-3" 
     id="track-<?= $track['id'] ?>" 
     data-itemid="<?= $track['id'] ?>">
    
    <!-- Track Image -->
    <div class="track-item__image">
        <img 
            src="<?= $track['image_url'] ?>" 
            alt="<?= htmlspecialchars($track['title']) ?>"
            loading="lazy"
            onerror="this.style.display='none'; this.parentElement.querySelector('.track-item__fallback').style.display='flex';"
        />
        <div class="track-item__fallback" style="display: none;">
            <i class="fas fa-music"></i>
        </div>
    </div>
    
    <!-- Track Info -->
    <div class="track-item__info">
        <div class="track-item__header flex-column align-items-start justify-content-center">
            <span class="track-item__title" title="<?= htmlspecialchars($track['title']) ?>">
                <?= htmlspecialchars($track['title']) ?>
            </span>
            <span class="track-item__artist"><?= htmlspecialchars($track['artist_name']) ?></span>
        </div>
        
        <!-- Player Controls -->
        <div class="track-item__controls">
            <button class="track-item__play play-track play-btn" data-itemid="<?= $track['id'] ?>">
                <i class="fas fa-play media-ico" data-itemid="<?= $track['id'] ?>"></i>
            </button>
            
            <div class="track-item__progress-wrap">
                <div class="track-item__progress-bar">
                    <div class="track-item__progress-fill track-<?= $track['id'] ?>"></div>
                </div>
                <span class="track-item__time track-<?= $track['id'] ?>">0:00</span>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="track-item__actions gap-md-0 justify-content-md-between">
            <button class="track-item__action p-md-0 gap-md-0 track-item__action--download download-btn" 
                    data-itemname="track" 
                    data-itemid="<?= $track['id'] ?>">
                <i class="fas fa-download media-ico"></i>
                <span><?= $track['downloads'] ?></span>
            </button>
            
            <button class="track-item__action p-md-0 gap-md-0 track-item__action--like like-btn" 
                    data-itemname="track" 
                    data-itemid="<?= $track['id'] ?>">
                <i class="fa-regular fa-heart media-ico"></i>
                <span><?= $track['likes'] ?></span>
            </button>
            
            <button class="track-item__action p-md-0 gap-md-0 track-item__action--share share-btn" 
                    data-itemname="track" 
                    data-itemid="<?= $track['id'] ?>"
                    data-trackid="<?= $track['id'] ?>">
                <i class="fa-regular fa-paper-plane media-ico"></i>
                <span><?= $track['shares'] ?? 0 ?></span>
            </button>
        </div>
    </div>
    
    <!-- Owner (optional) -->
    <?php if (!empty($track['owner'])): ?>
    <span class="track-item__owner"><?= htmlspecialchars($track['owner']) ?></span>
    <?php endif; ?>
</div>