<?php
$currentStream = array_filter($streams, function($stream){
    $today = date('Y-m-d');
    $streamDate = explode(' ', $stream['start_time'])[0];
    return $streamDate === $today;
}, ARRAY_FILTER_USE_BOTH);
$currentStream = !empty($currentStream) ? $currentStream[array_key_last($currentStream)] : $currentStream;
?>
<div id="body">
    <div class="stream-container">
        <!-- Neon animated lines -->
        <div class="neon-line"></div>
        <div class="neon-line"></div>
        <div class="neon-line"></div>
        <div class="neon-line"></div>

        <div class="stream-content">
        <div class="play-button" data-streamid="<?= $currentStream['id'] ?? 0 ?>"></div>
        <h2><?= $currentStream ? $currentStream['name'] : 'stream offline'?></h2>
        <p>Press the play button to start viewing the current stream</p>
        </div>       
    </div>

    <?php
    if($currentStream):
        ?>
    <div class="next-stream">
        <div class="info">
            <p style="color:#d9d9d9; margin:0;">📅 Current Stream</p>
            <h3><?= $currentStream['name'] ?></h3>
            <p>📍 <?= explode(' ', $currentStream['start_time'])[0] ?></p>
        </div>
        <div class="link watch-link" data-streamid="<?= $currentStream['id'] ?>">Watch</div>
    </div>
    <?php
    endif;
    $upcomingStreams = array_filter($streams, function($stream){
        return explode(' ', $stream['start_time'])[0] !== date('Y-m-d');
    }, ARRAY_FILTER_USE_BOTH);
    if(empty($upcomingStreams)):
        ?>
        <div class="next-stream">
            <div class="info">
            <p style="color:#d9d9d9; margin:0;">📅 Next Stream</p>
                <h3>No scheduled streams</h3>
                <p>📍 Follow for updates</p>
            </div>
            <button class="notify-btn">🔔 Get Notified</button>
        </div>
    <?php
    else:
    foreach($upcomingStreams as $index => $stream):
        ?>
        <div class="next-stream">
            <div class="info">
            <p style="color:#d9d9d9; margin:0;">📅 Next Stream</p>
                <h3><?= $stream['name'] ?></h3>
                <p>📍 <?= explode(' ', $stream['start_time'])[0] ?></p>
            </div>
            <button class="notify-btn">🔔 Get Notified</button>
        </div>
    <?php endforeach; endif;?>
</div>
<script>
    document.querySelector(".notify-btn").addEventListener("click", function() {
      window.location.href = "/?p=emailsubscribtion";
    });
</script>