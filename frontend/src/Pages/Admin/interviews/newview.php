<?php
require_once __DIR__ . '/../../models/InterviewModel.php';

class InterviewView {
    private $model;
    
    public function __construct() {
        $this->model = new InterviewModel();
    }
    
    public function render() {
        $interviews = $this->model->getAllInterviews();
        
        // Start output
        echo '<!DOCTYPE html>
        <html lang="ht">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Entèvyou - Konektem</title>
            <style>
                /* ... (include all CSS styles from newview.html) ... */
            </style>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        </head>
        <body>
            <div class="container">
                <div id="interviews-section" class="margin-rl-1 colDir container-outer pad-20 white-bg bdr-box">
                    <div id="interviews-section-bg" class="full-h"></div>
                    <div class="section-info">
                        <h1>ENTÈVYOU</h1>
                        <p>Dekouvri tout entèvyou ak moun enpòtan yo</p>
                    </div>
                    <div id="interviews-list" class="container-inner">';
        
        // Loop through interviews
        foreach ($interviews as $interview) {
            $this->renderInterviewCard($interview);
        }
        
        echo '
                    </div>
                    <a href="/?p=more-interviews" class="more-actions">
                        <span>Wè Plis Entèvyou</span>
                        <ion-icon name="arrow-forward-outline"></ion-icon>
                    </a>
                </div>
            </div>';
        
        // Include video modal and JavaScript
        echo '
            <!-- Video Player Modal -->
            <div id="videoModal" class="video-modal">
                <button class="close-video" id="closeVideo">
                    <i class="fas fa-times"></i>
                </button>
                <div class="video-container">
                    <div class="video-player">
                        <video id="interviewVideo" controls preload="metadata">
                            <source id="videoSource" src="" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="video-loading" id="videoLoading">
                            <div class="spinner"></div>
                            <p>Video ap chaje...</p>
                        </div>
                    </div>
                    <div class="video-info">
                        <h3 id="videoTitle"></h3>
                        <p id="videoDescription"></p>
                        <div class="video-duration">
                            <i class="fas fa-clock"></i>
                            <span id="videoTime">0:00 / 0:00</span>
                        </div>
                    </div>
                    <div class="video-controls">
                        <button class="control-btn" id="playPauseBtn">
                            <i class="fas fa-play"></i> Jwe/Poze
                        </button>
                        <button class="control-btn" id="muteBtn">
                            <i class="fas fa-volume-up"></i> Silans/Fè bri
                        </button>
                        <button class="control-btn" id="fullscreenBtn">
                            <i class="fas fa-expand"></i> Ekran tout
                        </button>
                    </div>
                </div>
            </div>
            
            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
            <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>';
        
        // Include JavaScript
        $this->renderJavaScript();
        
        echo '
        </body>
        </html>';
    }
    
    private function renderInterviewCard($interview) {
        $imagePath = $interview['image_location'] ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop&crop=face';
        $videoUrl = $interview['video_url'] ?? '/uploads/videos/default.mp4';
        $personName = htmlspecialchars($interview['personName'] ?? 'Jean Pierre');
        $personTitle = htmlspecialchars($interview['personTitle'] ?? 'CEO nan Biznis Enpòtan');
        $date = isset($interview['interviewDate']) ? date('d F Y', strtotime($interview['interviewDate'])) : '15 Janvye 2024';
        $description = htmlspecialchars($interview['description'] ?? 'Nan entèvyou sa a...');
        
        echo '
        <div class="interview-card">
            <div class="iv-container">
                <img src="' . $imagePath . '" alt="' . $personName . '" class="interview-image" />
                <div class="interview-content">
                    <div class="person-info">
                        <h3 class="person-name">' . $personName . '</h3>
                        <p class="person-title">' . $personTitle . '</p>
                        <div class="interview-meta">
                            <span class="date">
                                <i class="fas fa-calendar"></i>
                                ' . $date . '
                            </span>
                            <span class="read-time">
                                <i class="fas fa-clock"></i>
                                5 min
                            </span>
                        </div>
                    </div>
                    <p class="interview-description">' . $description . '</p>
                    <div class="interview-highlights">
                        <h4>Pwen Enpòtan:</h4>
                        <ul>
                            <li>Kijan li kòmanse biznis li a</li>
                            <li>Chaleng li te fè fas</li>
                            <li>Konsey pou jèn antreprenè</li>
                        </ul>
                    </div>
                    <div class="interview-actions">
                        <a href="#" class="watch-btn" data-video-url="' . $videoUrl . '" data-title="' . $personName . ' - ' . $personTitle . '">
                            <i class="fas fa-play"></i>
                            Gade Entèvyou
                        </a>
                        <button class="share-btn">
                            <i class="fas fa-share-alt"></i>
                            Pataje
                        </button>
                    </div>
                </div>
            </div>
        </div>';
    }
    
    private function renderJavaScript() {
        echo '
        <script>
            document.addEventListener(\'DOMContentLoaded\', function() {
                const shareButtons = document.querySelectorAll(\'.share-btn\');
                const watchButtons = document.querySelectorAll(\'.watch-btn\');
                const videoModal = document.getElementById(\'videoModal\');
                const closeVideoBtn = document.getElementById(\'closeVideo\');
                const interviewVideo = document.getElementById(\'interviewVideo\');
                const videoSource = document.getElementById(\'videoSource\');
                const videoTitle = document.getElementById(\'videoTitle\');
                const videoLoading = document.getElementById(\'videoLoading\');
                const playPauseBtn = document.getElementById(\'playPauseBtn\');
                const muteBtn = document.getElementById(\'muteBtn\');
                const fullscreenBtn = document.getElementById(\'fullscreenBtn\');
                const videoTime = document.getElementById(\'videoTime\');
                
                let currentVideo = null;
                
                // Share button functionality
                shareButtons.forEach(button => {
                    button.addEventListener(\'click\', function() {
                        const card = this.closest(\'.interview-card\');
                        const title = card.querySelector(\'.person-name\').textContent;
                        const description = card.querySelector(\'.person-title\').textContent;
                        
                        if (navigator.share) {
                            navigator.share({
                                title: title,
                                text: description,
                                url: window.location.href
                            });
                        } else {
                            alert(\'Pataje entèvyou sa a ak zanmi ou!\');
                        }
                    });
                });
                
                // Watch button functionality
                watchButtons.forEach(button => {
                    button.addEventListener(\'click\', function(e) {
                        e.preventDefault();
                        
                        const videoUrl = this.getAttribute(\'data-video-url\');
                        const title = this.getAttribute(\'data-title\');
                        const card = this.closest(\'.interview-card\');
                        const description = card.querySelector(\'.person-title\').textContent;
                        
                        // Show loading
                        videoLoading.style.display = \'block\';
                        videoModal.classList.add(\'active\');
                        document.body.style.overflow = \'hidden\';
                        
                        // Set video source and info
                        videoSource.src = videoUrl;
                        videoTitle.textContent = title;
                        interviewVideo.load();
                        
                        currentVideo = interviewVideo;
                        
                        // Play the video when metadata is loaded
                        interviewVideo.addEventListener(\'loadedmetadata\', function() {
                            videoLoading.style.display = \'none\';
                            updateTimeDisplay();
                            interviewVideo.play().catch(e => {
                                console.log(\'Auto-play prevented:\', e);
                                videoLoading.style.display = \'none\';
                            });
                        }, { once: true });
                        
                        // Handle video errors
                        interviewVideo.addEventListener(\'error\', function(e) {
                            videoLoading.style.display = \'none\';
                            alert(\'Ere: Video a pa ka chaje. Tanpri eseye ankò.\');
                            console.error(\'Video error:\', e);
                        }, { once: true });
                    });
                });
                
                // Close video modal
                closeVideoBtn.addEventListener(\'click\', function() {
                    if (currentVideo) {
                        currentVideo.pause();
                    }
                    videoModal.classList.remove(\'active\');
                    document.body.style.overflow = \'auto\';
                });
                
                // Close modal when clicking outside
                videoModal.addEventListener(\'click\', function(e) {
                    if (e.target === videoModal) {
                        if (currentVideo) {
                            currentVideo.pause();
                        }
                        videoModal.classList.remove(\'active\');
                        document.body.style.overflow = \'auto\';
                    }
                });
                
                // Close modal with Escape key
                document.addEventListener(\'keydown\', function(e) {
                    if (e.key === \'Escape\' && videoModal.classList.contains(\'active\')) {
                        if (currentVideo) {
                            currentVideo.pause();
                        }
                        videoModal.classList.remove(\'active\');
                        document.body.style.overflow = \'auto\';
                    }
                });
                
                // Video controls
                playPauseBtn.addEventListener(\'click\', function() {
                    if (currentVideo) {
                        if (currentVideo.paused) {
                            currentVideo.play();
                            playPauseBtn.innerHTML = \'<i class="fas fa-pause"></i> Poze\';
                        } else {
                            currentVideo.pause();
                            playPauseBtn.innerHTML = \'<i class="fas fa-play"></i> Jwe\';
                        }
                    }
                });
                
                muteBtn.addEventListener(\'click\', function() {
                    if (currentVideo) {
                        currentVideo.muted = !currentVideo.muted;
                        muteBtn.innerHTML = currentVideo.muted ? 
                            \'<i class="fas fa-volume-mute"></i> Fè bri\' : 
                            \'<i class="fas fa-volume-up"></i> Silans\';
                    }
                });
                
                fullscreenBtn.addEventListener(\'click\', function() {
                    if (currentVideo) {
                        if (!document.fullscreenElement) {
                            videoModal.requestFullscreen().catch(err => {
                                console.log(\`Error attempting to enable fullscreen: \${err.message}\`);
                            });
                        } else {
                            document.exitFullscreen();
                        }
                    }
                });
                
                function updateTimeDisplay() {
                    if (currentVideo) {
                        const current = formatTime(currentVideo.currentTime);
                        const duration = formatTime(currentVideo.duration);
                        videoTime.textContent = \`\${current} / \${duration}\`;
                    }
                }
                
                function formatTime(seconds) {
                    const mins = Math.floor(seconds / 60);
                    const secs = Math.floor(seconds % 60);
                    return \`\${mins}:\${secs < 10 ? \'0\' : \'\'}\${secs}\`;
                }
                
                if (interviewVideo) {
                    interviewVideo.addEventListener(\'timeupdate\', updateTimeDisplay);
                }
                
                if (interviewVideo) {
                    interviewVideo.addEventListener(\'play\', function() {
                        playPauseBtn.innerHTML = \'<i class="fas fa-pause"></i> Poze\';
                    });
                    
                    interviewVideo.addEventListener(\'pause\', function() {
                        playPauseBtn.innerHTML = \'<i class="fas fa-play"></i> Jwe\';
                    });
                }
            });
        </script>';
    }
}

// Usage
$view = new InterviewView();
$view->render();
?>