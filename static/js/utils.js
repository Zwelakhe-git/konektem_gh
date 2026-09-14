
function formatTime(sec){
    let i = Math.floor(sec / 60);
    let s = Math.floor(sec % 60);

    let fi = isNaN(i) ? `00` : i.toString().padStart(2,'0');
    let fs = isNaN(s) ? `00` : s.toString().padStart(2,'0');

    return `${fi}.${fs}`;
}

window.timeUpdateHandlerAttached = false;

export function playAudio(url, btn){
    let audioPlayer = document.getElementById('audioPlayer');
    let icon = btn.querySelector('[class^=fa]') ?? btn.querySelector('.media-ico');
    const trackId = btn.dataset.itemid ?? icon.dataset.itemid;
    if (!audioPlayer) {
        console.log("creating player");
        audioPlayer = document.createElement('audio');
        audioPlayer.id = 'audioPlayer';
        document.body.appendChild(audioPlayer);
    }
    let currentBtn = btn;
    if(!timeUpdateHandlerAttached){
        sessionStorage.setItem('currentTrackId', `-1`);
        audioPlayer.addEventListener('timeupdate', function(){
            
            let cachedTrackId = sessionStorage.getItem('currentTrackId');
            if(Number(cachedTrackId) !== Number(window.currentTrackId)){
                currentBtn = document.querySelector(`.play-btn[data-itemid='${window.currentTrackId ?? -1}']`);
                sessionStorage.setItem('currentTrackId', `${window.currentTrackId}`);
            }
            if(!currentBtn) {
                return;
            }
            let progressBar = currentBtn.parentElement.querySelector('.progress-bar .progress');
            let playTimeUI = currentBtn.parentElement.querySelector('.play-time');
            if(progressBar){
                if(progressBar.tagName.toLowerCase() === 'div'){
                    progressBar.style.setProperty('width', `${(this.currentTime / this.duration) * 100}%`);
                } else if(progressBar.tagName.toLowerCase() === 'progress'){
                    progressBar.value = ( this.currentTime / this.duration);
                }
            } else {
                console.log("no progress bar for playing track: " + window.currentTrackId);
            }
            if(playTimeUI){
                playTimeUI.innerHTML = `<span>${formatTime(this.currentTime)}/${formatTime(this.duration)}</span>`;
            } else {
                console.log("play time container not found");
            }
        });
        timeUpdateHandlerAttached = true;
    }
    if(!trackId){
        showError("unrecognised track");
        return;
    }
    if (window.currentTrackId && window.currentTrackId === trackId) {
        if(!audioPlayer.paused && !audioPlayer.ended && window._audioPlaying){
            audioPlayer.pause();

            window._audioPlaying = false;
            icon?.classList.remove('fa-pause');
            icon?.classList.add('fa-play');
            //btn.innerHTML = '<i class="fas fa-play"></i>';
        } else {
            audioPlayer.play().then(() => {
                window._audioPlaying = true;
                icon?.classList.add('fa-pause');
                icon?.classList.remove('fa-play');
            }).catch(e => console.warn("error playing track", e));
        }
    } else {
        audioPlayer.src = url;
        audioPlayer.load();
        audioPlayer.play().then(() => {
            window._audioPlaying = true;
            icon?.classList.add('fa-pause');
            icon?.classList.remove('fa-play');
            window.currentTrackId = trackId;
        }).catch(e => console.warn("error playing track", e));

        document.querySelectorAll('.play-track').forEach(otherBtn => {
            if (otherBtn !== btn) {
                const icon = otherBtn.querySelector('[class^=fa]') ?? otherBtn.querySelector('.media-ico');
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-play');
                //otherBtn.innerHTML = '<i class="fas fa-play"></i>';
            }
        });
    }

    // Arrêter les autres lecteurs

    // Quand la piste se termine
    audioPlayer.onended = () => {
        window._audioPlaying = false;
        icon.classList.remove('fa-pause');
        icon.classList.add('fa-play');
        //this.innerHTML = '<i class="fas fa-play"></i>';
    };
}

export async function shareItem(url=window.location.href, title='', text=''){
    try {
        // Use Web Share API if available
        if (navigator.share) {
            await navigator.share({
                title: title,
                text: text,
                url: url,
            });
                        
        } else {
            await navigator.clipboard.writeText(url);
            showSuccess('Link copied to clipboard!');
        }
        
    } catch (error) {
        if (error.name !== 'AbortError') {
            showError('Error sharing interview');
        }
    }
}

export function base64UrlDecode(str){
    try {
        let base64 = str.replace(/-/g, '+').replace(/_/g, '/');
        while(base64.length % 4){
            base64 += '=';
        }
        return atob(base64);
    } catch(err){
        console.error(err);
        return null;
    }
}

export function basename(file){
    try {
        const filename = file.name;
        return filename.replace(/(\.\w+)$/, '');
    } catch(err){
        console.error(err);
        return null;
    }
}

export function getFileExt(file){
    try {
        const filename = file.name;
        const ext = /(\.\w+)$/.exec(filename);
        return ext[1].replace('.', '');
    } catch(err){
        console.error(err);
        return null;
    }
}