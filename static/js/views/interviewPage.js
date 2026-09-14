import {handleShare} from '../handlers.js';
import {cachedFetch} from '../api/data-load.js';

function interviewPageHtml(interviewData) {
    // Format the description with paragraphs
    let formattedDescription = '';
    if (interviewData.description) {
        interviewData.description.split('\n').forEach(p => {
            if (p.trim().length > 0) {
                formattedDescription += `<p>${p}</p>`;
            }
        });
    }

    // Format date for display
    const interviewDate = new Date(interviewData.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    // Determine if we have a video
    const hasVideo = interviewData.video_url && interviewData.video_url.trim().length > 0;

    return `
    <div class="container interview-item-page" style="color: black;">
        <div class="interview-header">
            <h1 class="interview-title">${interviewData.title}</h1>
            <div class="interview-meta">
                <span class="interview-date">
                    <i class="fas fa-calendar-alt"></i>
                    ${interviewDate}
                </span>
                <div class="interview-stats">
                    <span class="stat-item">
                        <i class="fas fa-eye"></i>
                        ${interviewData.views} Views
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-share-alt"></i>
                        ${interviewData.shares} Shares
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-heart"></i>
                        ${interviewData.likes} Likes
                    </span>
                </div>
            </div>
        </div>

        ${hasVideo ? `
        <!-- Video Player Section -->
        <div class="video-player-section">
            <div class="video-container" id="video-container">
                ${getVideoPlayerHtml(interviewData.video_url, interviewData.image_location)}
            </div>
        </div>
        ` : `
        <!-- Image Section (if no video) -->
        <div class="interview-image-container">
            <div class="blurred-bg"></div>
            <img src="https://konektem.net${interviewData.image_location}" 
                 alt="${interviewData.title}" 
                 class="interview-main-image">
        </div>
        `}

        <div class="interview-content">
            ${formattedDescription}
        </div>

        <div class="interview-actions">
            <button class="like-btn" data-id="${interviewData.id}">
                <i class="fas fa-heart media-ico" data-itemname="interviews" data-itemid="${interviewData.id}"></i>
                Renmen
                <span class="count">${interviewData.likes}</span>
            </button>
            
            <button class="share-btn" data-id="${interviewData.id}">
                <i class="fas fa-share-alt media-ico" data-itemname="interviews" data-itemid="${interviewData.id}"></i>
                Pataje
                <span class="count">${interviewData.shares}</span>
            </button>

            ${hasVideo ? `
            <button class="watch-btn" id="watch-video-btn">
                <i class="fas fa-play" id="watch-icon"></i>
                <span id="watch-text">Jwe Videyo</span>
            </button>
            ` : ''}
        </div>
    </div>`;
}

// Helper function to generate video player HTML
function getVideoPlayerHtml(videoUrl, imageLocation) {
    console.log('Creating video player for URL:', videoUrl);
    console.log('Image location:', imageLocation);
    
    // Get full image URL
    const fullImageUrl = imageLocation ? `https://konektem.net${imageLocation}` : '';
    
    // If it's a YouTube URL
    if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be')) {
        let videoId = '';
        
        if (videoUrl.includes('youtu.be/')) {
            // Short YouTube URL
            videoId = videoUrl.split('youtu.be/')[1];
            // Remove any query parameters
            videoId = videoId.split('?')[0];
        } else if (videoUrl.includes('youtube.com/watch')) {
            // Regular YouTube URL
            try {
                const urlObj = new URL(videoUrl);
                videoId = urlObj.searchParams.get('v');
            } catch (e) {
                // If URL parsing fails, try regex
                const match = videoUrl.match(/v=([^&]+)/);
                videoId = match ? match[1] : '';
            }
        } else if (videoUrl.includes('youtube.com/embed/')) {
            // Embed YouTube URL
            videoId = videoUrl.split('youtube.com/embed/')[1];
            videoId = videoId.split('?')[0];
        }
        
        if (videoId) {
            return `
                <iframe src="https://www.youtube.com/embed/${videoId}?autoplay=0&rel=0" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
                <div class="video-overlay" id="video-overlay" style="background-image: url('${fullImageUrl}');">
                    <div class="play-button">
                        <i class="fas fa-play"></i>
                    </div>
                </div>`;
        }
    }
    // If it's a Vimeo URL
    else if (videoUrl.includes('vimeo.com')) {
        const videoId = videoUrl.split('/').pop();
        if (videoId) {
            return `
                <iframe src="https://player.vimeo.com/video/${videoId}?autoplay=0" 
                        frameborder="0" 
                        allow="autoplay; fullscreen; picture-in-picture" 
                        allowfullscreen>
                </iframe>
                <div class="video-overlay" id="video-overlay" style="background-image: url('${fullImageUrl}');">
                    <div class="play-button">
                        <i class="fas fa-play"></i>
                    </div>
                </div>`;
        }
    }
    
    // For direct video files or any other URL
    // Check if URL is absolute or relative
    const fullVideoUrl = videoUrl.startsWith('http') ? videoUrl : `https://konektem.net${videoUrl}`;
    
    return `
        <video id="interview-video" poster="${fullImageUrl}">
            <source src="${fullVideoUrl}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="video-overlay" id="video-overlay" style="background-image: url('${fullImageUrl}');">
            <div class="play-button">
                <i class="fas fa-play"></i>
            </div>
        </div>`;
}

function interviewPageStyle() {
    let styleTag = document.querySelector("#root-style");
    if (!styleTag) {
        console.log("interviewPageStyle: style tag not found");
        return;
    }
    
    styleTag.innerHTML = `
/* Interview Page Styles */
`;
}

function interviewPageScript(interviewData) {
    console.log('Interview data loaded:', interviewData);
    console.log('Video URL:', interviewData.video_url);
    console.log('Has video?', interviewData.video_url && interviewData.video_url.trim().length > 0);
    
    // Set blurred background (only if we have image container)
    let blurredBG = document.querySelector('.blurred-bg');
    if(blurredBG && interviewData.image_location){
        blurredBG.style.backgroundImage = `url(https://konektem.net${interviewData.image_location})`;
    }
    
    // Initialize video player with overlay
    setTimeout(() => {
        const video = document.querySelector('#interview-video');
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
    }, 100); // Small delay to ensure DOM is ready
    
    // Like button functionality
    const likeBtn = document.querySelector('.like-btn');
    if (likeBtn) {
        likeBtn.addEventListener('click', async function() {
            const interviewId = this.dataset.id;
            const countSpan = this.querySelector('.count');
            let currentCount = parseInt(countSpan.textContent);
            
            try {
                // In a real implementation, you would send a request to your server
                // For now, we'll just update the UI
                currentCount++;
                countSpan.textContent = currentCount;
                
                // Update the like count visually
                this.style.backgroundColor = '#e74c3c';
                this.style.color = 'white';
                
                // You would typically make an API call here:
                // await fetch(`/api/interviews/${interviewId}/like`, { method: 'POST' });
                
                console.log(`Liked interview ${interviewId}`);
                
            } catch (error) {
                console.error('Error liking interview:', error);
                countSpan.textContent = currentCount - 1; // Revert on error
            }
        });
    }
    
    // Share button functionality
    const shareBtn = document.querySelector('.share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', async function() {
            const interviewId = this.dataset.id;
            const countSpan = this.querySelector('.count');
            let currentCount = parseInt(countSpan.textContent);
            
            try {
                // Use Web Share API if available
                if (navigator.share) {
                    await navigator.share({
                        title: interviewData.title,
                        text: interviewData.description ? interviewData.description.substring(0, 100) + '...' : 'Check out this interview',
                        url: window.location.href,
                    });
                    
                    // Increment share count after successful share
                    currentCount++;
                    countSpan.textContent = currentCount;
                    
                } else {
                    // Fallback: copy to clipboard
                    await navigator.clipboard.writeText(window.location.href);
                    alert('Link copied to clipboard!');
                    
                    // Increment share count
                    currentCount++;
                    countSpan.textContent = currentCount;
                }
                
                // You would typically make an API call here:
                // await fetch(`/api/interviews/${interviewId}/share`, { method: 'POST' });
                
                
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error('Error sharing interview:', error);
                }
            }
        });
    }
}

export default function interviewPage() {
    let title = document.querySelector("title");
    if (!title) {
        title = document.createElement("title");
        document.head.insertAdjacentElement("afterbegin", title);
    }
    title.textContent = "Interview - Konektem";
    
    let root = document.querySelector("#root");
    if (!root) {
        console.log("interviewPage: root element not found");
        return;
    }
    
    // Apply styles
    interviewPageStyle();
    
    // Get interview ID from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const interviewId = urlParams.get('id');
    
    if (!interviewId) {
        root.innerHTML = `
            <div class="container error-message" style="text-align: center; padding: 50px;">
                <h2 style="color: #e74c3c;">Interview Not Found</h2>
                <p>No interview ID specified in the URL.</p>
                <a href="/?p=interviews" style="color: #2b6cb0; text-decoration: none;">
                    ← Back to Interviews
                </a>
            </div>
        `;
        return;
    }
    
    // Show loading state
    root.innerHTML = `
        <div class="container loading" style="text-align: center; padding: 50px;">
            <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #2b6cb0; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
            <style>
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            </style>
            <p>Loading interview...</p>
        </div>
    `;
    
    // Fetch interview data
    setTimeout(() => {
        cachedFetch('https://konektem.net/php/dbReader.php?r=interviews', 'interviewsData')
            .then(interviews => {
                // Find the specific interview
                const interview = interviews.find(item => item.id == interviewId);
                
                if (!interview) {
                    root.innerHTML = `
                        <div class="container error-message" style="text-align: center; padding: 50px;">
                            <h2 style="color: #e74c3c;">Interview Not Found</h2>
                            <p>The requested interview could not be found.</p>
                            <a href="/?p=interviews" style="color: #2b6cb0; text-decoration: none;">
                                ← Back to Interviews
                            </a>
                        </div>
                    `;
                    return;
                }
                
                console.log('Found interview:', interview);
                
                // Update page title with interview title
                title.textContent = `${interview.title} - Konektem`;
                
                // Render the interview
                root.innerHTML = interviewPageHtml(interview);
                
                // Initialize scripts
                interviewPageScript(interview);
                
                // Update view count (you would typically do this via API)
                // fetch(`/api/interviews/${interviewId}/view`, { method: 'POST' });
                
            })
            .catch(error => {
                console.error('Error fetching interview:', error);
                root.innerHTML = `
                    <div class="container error-message" style="text-align: center; padding: 50px;">
                        <h2 style="color: #e74c3c;">Error Loading Interview</h2>
                        <p>There was an error loading the interview. Please try again later.</p>
                        <a href="/?p=interviews" style="color: #2b6cb0; text-decoration: none;">
                            ← Back to Interviews
                        </a>
                    </div>
                `;
            });
    }, 200);
}