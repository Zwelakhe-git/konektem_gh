<div class="albums-grid grid">
    <?php foreach($albums as $album): ?>
        <a class="album-card" href="<?= BASE_URL ?>/album/<?= $album['id'] ?>">
            <!-- Card Header - Image -->
            <div class="card-header">
                <div class="image-container album-image full">
                    <?php if(!empty($album['image_url'])): ?>
                        <img 
                            src="<?= htmlspecialchars($album['image_url']) ?>" 
                            alt="<?= htmlspecialchars($album['name']) ?>" 
                            class="w-full"
                            loading="lazy"
                            onerror="this.style.display='none'; this.parentElement.querySelector('.no-image').style.display='flex';"
                        />
                        <div class="no-image" style="display: none;">
                            <i class="fa-solid fa-record-vinyl"></i>
                        </div>
                    <?php else: ?>
                        <div class="no-image">
                            <i class="fa-solid fa-record-vinyl"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Card Body - Text -->
            <div class="card-body">
                <div class="album-stats">
                    <div class="album-name"><?= htmlspecialchars($album['name']) ?></div>
                    <div class="album-song-count">
                        <i class="fa-solid fa-music"></i>
                        <span><?= $album['songs_count'] ?? 0 ?></span>
                    </div>
                </div>
                <div class="album-description">
                    <?= htmlspecialchars($album['description'] ?? 'No description available') ?>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="card-footer">
                <?php if(!empty($album['artist_name'])): ?>
                    <div class="artist-info">
                        <i class="fa-solid fa-user"></i>
                        <span class="artist-name"><?= htmlspecialchars($album['artist_name']) ?></span>
                    </div>
                <?php endif; ?>
                <div class="album-year">
                    <i class="fa-solid fa-calendar"></i>
                    <span><?= $album['release_year'] ?? 'N/A' ?></span>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>