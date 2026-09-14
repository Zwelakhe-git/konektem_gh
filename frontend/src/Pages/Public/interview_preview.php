<div class="interview-item-page" style="color: black;">
    <div class="interview-header">
        <h1 class="interview-title"><?= $interview['title']?></h1>
        <div class="interview-meta">
            <span class="interview-date">
                <i class="fas fa-calendar-alt"></i>
                <?= $interview['created_at']?>
            </span>
        </div>
    </div>

    <?php
    $hasVideo = isset($interview['video_url']) && !empty($interview['video_url']);
    if($hasVideo){?>
    <div class="video-player-section">
        <div class="video-container" id="video-container">
            <?php
            require __DIR__ . '/../../Utils/Utils.php';
            echo createVideoPlayerFromUrl($interview['video_url'], $interview['video_poster'] ?? null);
            ?>
            <div class="video-overlay" id="video-overlay" style="background-image: url('<?= "https://konektem.net{$interview['image_url']}"?>');">
                <div class="play-button">
                    <i class="fas fa-play"></i>
                </div>
            </div>
        </div>
    </div>
    <?php } else {?>
    <div class="interview-image-container">
        <div class="blurred-bg" style="background-image: url('<?= "https://konektem.net{$interview['image_url']}"?>');"></div>
        <img src="<?= $interview['image_url']?>" 
                alt="image" 
                class="interview-main-image">
    </div>
    <?php }?>
    <div class="interview-content" style="max-height: 250px; overflow-y: scroll;">
        <?= $interview['description']?>
    </div>

    <div class="interview-actions">
        <div class="interview-stats">
            <div class="stat-item">
                <i class="fas fa-eye"></i>
                <span><?= $interview['views']?> Views</span>
            </div>
            <div class="stat-item share-btn">
                <i class="fas fa-share-alt media-ico" data-itemname="interview" data-itemid="<?= $interview['id']?>"></i>
                <span><?= $interview['shares']?> Shares</span>
            </div>
            <div class="stat-item like-btn">
                <i class="fas fa-heart media-ico" data-itemname="interview" data-itemid="<?= $interview['id']?>"></i>
                <span><?= $interview['likes']?> Likes</span>
            </div>
        </div>
        <!--<button class="like-btn">
            <i class="fas fa-heart media-ico" data-itemname="interview" data-itemid="<?= $interview['id']?>"></i>
            Renmen
            <span class="count"><?= $interview['likes']?></span>
        </button>
        
        <button class="share-btn">
            <i class="fas fa-share-alt media-ico" data-itemname="interview" data-itemid="<?= $interview['id']?>"></i>
            Pataje
            <span class="count"><?= $interview['shares']?></span>
        </button>-->

        <?php if($hasVideo){?>
        <button class="watch-btn" id="watch-video-btn">
            <i class="fas fa-play" id="watch-icon"></i>
            <span id="watch-text">Jwe Videyo</span>
        </button>
        <?php }?>
    </div>
</div>
<script>
    (() => {
        const video = document.querySelector('video');
        const videoOverlay = document.querySelector('#video-overlay');
        const watchBtn = document.querySelector('#watch-video-btn');
        const watchIcon = document.querySelector('#watch-icon');
        const watchText = document.querySelector('#watch-text');
        
        if (video && videoOverlay) {
            // Remove default controls initially
            video.controls = false;
            
            // Click overlay to play video
            videoOverlay.addEventListener('click', () => {
                playVideo();
            });
            
            // Play video function
            function playVideo() {
                if (video.paused) {
                    video.play();
                    videoOverlay.style.display = 'none';
                    video.controls = true;
                    if (watchBtn && watchIcon && watchText) {
                        watchIcon.className = 'fas fa-pause';
                        watchText.textContent = 'Pause Videyo';
                    }
                } else {
                    video.pause();
                    if (watchBtn && watchIcon && watchText) {
                        watchIcon.className = 'fas fa-play';
                        watchText.textContent = 'Jwe Videyo';
                    }
                }
            }
            
            // Make playVideo function globally available
            window.playVideo = playVideo;
            
            // Watch button functionality
            if (watchBtn) {
                watchBtn.addEventListener('click', playVideo);
            }
            
            // Update button text based on video state
            video.addEventListener('play', () => {
                if (watchBtn && watchIcon && watchText) {
                    watchIcon.className = 'fas fa-pause';
                    watchText.textContent = 'Pause Videyo';
                }
            });
            
            video.addEventListener('pause', () => {
                if (watchBtn && watchIcon && watchText) {
                    watchIcon.className = 'fas fa-play';
                    watchText.textContent = 'Jwe Videyo';
                }
            });
            
            // Show overlay again when video ends
            video.addEventListener('ended', () => {
                videoOverlay.style.display = 'flex';
                video.controls = false;
                if (watchBtn && watchIcon && watchText) {
                    watchIcon.className = 'fas fa-play';
                    watchText.textContent = 'Jwe Videyo';
                }
            });
        }
        
        // Handle YouTube/Vimeo iframes
        const iframe = document.querySelector('iframe');
        if (iframe && videoOverlay) {
            // Hide iframe initially, show overlay
            iframe.style.visibility = 'hidden';
            iframe.style.position = 'absolute';
            
            // Click overlay to show iframe
            videoOverlay.addEventListener('click', function() {
                iframe.style.visibility = 'visible';
                iframe.style.position = 'relative';
                videoOverlay.style.display = 'none';
                
                // Try to play iframe video
                try {
                    iframe.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
                } catch (e) {
                    console.log('Cannot control iframe video:', e);
                }
                
                if (watchBtn && watchIcon && watchText) {
                    watchIcon.className = 'fas fa-pause';
                    watchText.textContent = 'Pause Videyo';
                }
            });
            
            // Watch button functionality for iframes
            if (watchBtn) {
                watchBtn.addEventListener('click', function() {
                    if (iframe.style.visibility === 'hidden') {
                        // Show and play iframe
                        iframe.style.visibility = 'visible';
                        iframe.style.position = 'relative';
                        videoOverlay.style.display = 'none';
                        if (watchIcon && watchText) {
                            watchIcon.className = 'fas fa-pause';
                            watchText.textContent = 'Pause Videyo';
                        }
                    } else {
                        // Try to pause iframe (may not work due to cross-origin)
                        try {
                            iframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                            if (watchIcon && watchText) {
                                watchIcon.className = 'fas fa-play';
                                watchText.textContent = 'Jwe Videyo';
                            }
                        } catch (e) {
                            console.log('Cannot pause iframe video:', e);
                        }
                    }
                });
            }
        }
    })();
</script>