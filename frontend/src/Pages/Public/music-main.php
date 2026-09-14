<div class="container music-section">
    <header>
        <div class="logo">
            <i class="fas fa-play-circle"></i>
            <span>MediaNews</span>
        </div>
        <div class="grid" style="grid-template-columns:repeat(3, minmax(70px, 1fr));--text-sz: 0.875rem;gap:5px;">
            <a href="/" class="back-to-news flex even">
                <i class="fas fa-arrow-left"></i>
                <span>Retounen</span>
            </a>
            <a href="<?= BASE_URL?>/music/albums" class="back-to-news flex even">
                <i class="fas fa-compact-disc text-muted"></i>
                <span>Album</span>
            </a>
            <a href="<?= BASE_URL?>/music" class="back-to-news flex even">
                <i class="fas fa-music"></i>
                <span>trek</span>
            </a>
        </div>
    </header>
    
    <!-- <h1>Galri Mizik</h1>
    <p class="subtitle">Amize w avek bel mizik sa yo</p> -->
    
    <!-- <div class="media-container"> -->
    <?php if(isset($section) && !empty($section) && file_exists(__DIR__ . "/$section")){
        require_once $section;?>
    <?php } else {?>
    <div class="sections-nav grid col-1fr">
        <a href="<?= BASE_URL?>/music/tracks" class="sections-nav-item card box w-full">
            <div class="abs-card-bg circular-slide-container">
                <div class="circular-slide">
                    <div class="slide-item"></div>
                    <div class="slide-item"></div>
                    <div class="slide-item"></div>
                    <div class="slide-item"></div>
                </div>
            </div>
            <div class="box-content">
                <h2>Tracks</h2>
                <div>
                    <i class="fas fa-music"></i>
                </div>
            </div>
        </a>
        <a href="<?= BASE_URL?>/music/albums" class="sections-nav-item card box w-full">
            <div class="abs-card-bg circular-slide-container">
                <div class="circular-slide">
                    <div class="slide-item"></div>
                    <div class="slide-item"></div>
                    <div class="slide-item"></div>
                    <div class="slide-item"></div>
                </div>
            </div>
            <div class="box-content">
                <h2>Albums</h2>
                <div>
                    <i class="fas fa-album"></i>
                </div>
            </div>
        </a>
    </div>
    <?php }?>
    <!-- </div> -->
</div>
