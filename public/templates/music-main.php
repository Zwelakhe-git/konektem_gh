<div class="container music-section">
    <header>
        <div class="logo">
            <i class="fas fa-play-circle"></i>
            <span>MediaNews</span>
        </div>
        <div class="flx flex flex-dis">
            <a href="/" class="back-to-news nav-link" style="color: white">
                <i class="fas fa-arrow-left"></i>
                <span style="color: inherit">Retounen</span>
            </a>
            <div class="spacer" style="width: 10px;"></div>
            <a href="/konektem/music/albums" class="back-to-news nav-link" style="color: white">
                <i class="fas fa-compact-disc text-muted"></i>
                <span style="color: inherit">Album</span>
            </a>
            <div class="spacer" style="width: 10px;"></div>
            <a href="/?p=music" class="back-to-news nav-link" style="color: white">
                <i class="fas fa-music"></i>
                <span style="color: inherit">trek</span>
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
        <a href="/konektem/music/tracks" class="sections-nav-item card box w-full">
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
        <a href="/konektem/music/albums" class="sections-nav-item card box w-full">
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
