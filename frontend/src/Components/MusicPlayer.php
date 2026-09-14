
<!-- overlay -->
<div class="player-overlay" id="playerOverlay"></div>

<!-- PLAYER MODAL -->
<div class="player-modal" id="playerModal">
<!-- header -->
<div class="player-header">
    <h2><i class="fas fa-play" style="color:#d32f2f; margin-right: 8px;"></i> Konektem</h2>
    <div class="header-actions">
    <button id="playlistToggleBtn" title="playlist"><i class="fas fa-bars"></i></button>
    <button class="close-player" id="closePlayerBtn"><i class="fas fa-times"></i></button>
    </div>
</div>

<!-- artwork -->
<div class="artwork-container">
    <div class="artwork no-image" id="artworkEl"><i class="fas fa-music"></i></div>
</div>

<!-- track info -->
<div class="track-info">
    <div class="track-title" id="trackTitle">—</div>
    <div class="track-artist" id="trackArtist">—</div>
</div>

<!-- progress -->
<div class="progress-area">
    <span class="progress-time" id="currentTime">0:00</span>
    <div class="progress-track" id="progressTrack">
    <div class="progress-fill" id="progressFill"></div>
    </div>
    <span class="progress-time" id="totalTime">0:00</span>
</div>

<!-- controls -->
<div class="controls">
    <button id="prevBtn"><i class="fas fa-step-backward"></i></button>
    <button class="plat-btn" id="playBtn"><i class="fas fa-play"></i></button>
    <button id="nextBtn"><i class="fas fa-step-forward"></i></button>
</div>

<!-- PLAYLIST (collapsible) -->
<div class="playlist-container" id="playlistContainer">
    <!-- dynamically filled -->
</div>
</div>