import mediaStat from "./mediaStat.js";


function makeMusicItem(data) {
    const div = document.createElement('div');
    div.className = 'track small';
    div.setAttribute('data-itemid', data.id);
    div.setAttribute('data-src', data.url);
    div.innerHTML = `
            <div class="close-modal-btn">
                <i class="fas fa-x"></i>
            </div>
            <div class="track-img">
                <img class="full-w-h" alt="track image" src="${data.image_url}" />
            </div>
            <div class="music-info">
                <div class="music-title">${data.title}</div>
                <div class="music-artist">${data.artist_name}</div>
                <div class="player-controls">
                    <div class="play-btn" data-itemid="${data.id}">
                        <i class="fas fa-play media-ico play" data-itemname="track" data-itemid="${data.id}"></i>
                    </div>
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                    <div class="play-time" id="timer">
                    </div>
                </div>
                <div class="media-actions">
                    <div class="action-btn download-btn">
                        <i class="fas fa-download media-ico" data-itemname="track" data-itemid="${data.id}"></i>
                        <span>${data.downloads}</span>
                    </div>
                    <div class="action-btn like-btn">
                        <i class="fa-regular fa-heart media-ico" data-itemname="track" data-itemid="${data.id}"></i>
                        <span>${data.likes}</span>
                    </div>
                    <div class="action-btn plays-btn">
                        <i class="fa fa-eye media-ico" data-itemname="track" data-itemid="${data.id}"></i>
                        <span>${data.plays}</span>
                    </div>
                </div>
                <span style="font-size: 5px;position: absolute;right: 0px;">${data.owner ?? ''}</span>
            </div>`;
    return div;
}

async function clearTracks() {
    document.querySelectorAll('.track.small').forEach(t => {
        t.remove();
    });
}

(function () {
    "use strict";

    // ---------- DOM refs ----------
    const modal = document.getElementById('playerModal');
    const overlay = document.getElementById('playerOverlay');
    const openBtn = document.getElementById('openPlayerBtn');
    const closeBtn = document.getElementById('closePlayerBtn');
    const playlistToggle = document.getElementById('playlistToggleBtn');
    const playlistContainer = document.getElementById('playlistContainer');
    const artwork = document.getElementById('artworkEl');
    const trackTitle = document.getElementById('trackTitle');
    const trackArtist = document.getElementById('trackArtist');
    const progressFill = document.getElementById('progressFill');
    const progressTrack = document.getElementById('progressTrack');
    const currentTimeEl = document.getElementById('currentTime');
    const totalTimeEl = document.getElementById('totalTime');
    const playBtn = document.getElementById('playBtn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    // ---------- state ----------
    let tracks = [];                // full track list
    let currentIndex = 0;
    let isPlaying = false;
    let audio = null;              // will be created when needed
    let isPlaylistOpen = false;

    // ---------- helper: format time ----------
    function formatTime(sec) {
        if (!sec || isNaN(sec)) return '0:00';
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60);
        return `${m}:${s.toString().padStart(2, '0')}`;
    }

    // ---------- load tracks from window.tracks or fetch ----------
    function loadTracks() {
        if (window.tracks && Array.isArray(window.tracks) && window.tracks.length > 0) {
            tracks = window.tracks;
            initPlayer();
            return;
        }

        // fetch from endpoint
        fetch('/api/v1/music/all')
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(data => {
                if (data.success && data.data && Array.isArray(data.data.tracks)) {
                    tracks = data.data.tracks;
                    // save to window for later
                    window.tracks = tracks;
                    initPlayer();
                } else {
                    console.warn('Unexpected response, using fallback');
                    useFallbackTracks();
                }
            })
            .catch(err => {
                console.warn('Fetch failed, using fallback tracks:', err);
                useFallbackTracks();
            });
    }

    function useFallbackTracks() {
        tracks = [
            { id: 1, title: '1- VWA YO PA TANDE YO', artist_name: 'Zòn Pa Fè Moun Band', audio_url: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3', image_url: null },
            { id: 2, title: '2- Mwen Pa Bliye', artist_name: 'Masekozw', audio_url: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3', image_url: null },
            { id: 3, title: '3- Louange A Yo', artist_name: 'Konektem', audio_url: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3', image_url: null },
            { id: 4, title: '4- Gran Mèsi', artist_name: 'Zòn Pa Fè Moun Band', audio_url: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3', image_url: null }
        ];
        window.tracks = tracks;
        initPlayer();
    }

    // ---------- init player after tracks ready ----------
    function initPlayer() {
        if (!tracks.length) return;
        //currentIndex = 0;
        renderPlaylist();
        loadTrack(currentIndex);
        // if player not visible, no auto-play
    }

    // ---------- load track by index ----------
    function loadTrack(index) {
        if (!tracks.length) return;
        const track = tracks[index];
        if (!track) return;

        // update UI
        trackTitle.textContent = track.title || 'Untitled';
        trackArtist.textContent = track.artist_name || 'Unknown';

        // artwork
        if (track.image_url) {
            artwork.style.backgroundImage = `url(${track.image_url})`;
            artwork.classList.remove('no-image');
            artwork.innerHTML = '';
        } else {
            artwork.style.backgroundImage = '';
            artwork.classList.add('no-image');
            artwork.innerHTML = '<i class="fas fa-music"></i>';
        }

        // create audio if needed
        if (!audio) {
            audio = new Audio();
            audio.addEventListener('timeupdate', updateProgress);
            audio.addEventListener('ended', onTrackEnd);
            audio.addEventListener('loadedmetadata', onLoadedMetadata);
        }

        // set source
        const url = track.audio_url || track.url;
        if (url) {
            audio.src = url;
            audio.load();
        } else {
            console.warn('No audio url for track', track);
        }

        // reset progress
        progressFill.style.width = '0%';
        currentTimeEl.textContent = '0:00';
        totalTimeEl.textContent = '0:00';

        // if currently playing, auto-play new track
        if (isPlaying) {
            audio.play().catch(e => console.warn('autoplay blocked', e));
        }

        // highlight playlist
        highlightPlaylist(index);
    }

    // ---------- highlight playlist ----------
    function highlightPlaylist(index) {
        const items = playlistContainer.querySelectorAll('.playlist-item');
        items.forEach((el, i) => {
            el.classList.toggle('active', i === index);
        });
    }

    // ---------- render playlist ----------
    function renderPlaylist() {
        playlistContainer.innerHTML = '';
        tracks.forEach((track, idx) => {
            const div = document.createElement('div');
            div.className = 'playlist-item';
            if (idx === currentIndex) div.classList.add('active');
            div.innerHTML = `
            <span class="pl-idx">${idx + 1}</span>
            <span class="pl-title">${track.title || 'Untitled'}</span>
            <span class="pl-artist">${track.artist_name || ''}</span>
            `;
            div.dataset.index = idx;
            div.addEventListener('click', () => {
                if (idx === currentIndex && audio && !audio.paused) return;
                currentIndex = idx;
                loadTrack(currentIndex);
                // if player was paused, keep paused; if playing, it continues
                if (isPlaying) {
                    audio.play().catch(() => { });
                }
                // close playlist on mobile?
            });
            playlistContainer.appendChild(div);
        });
    }

    // ---------- progress & metadata ----------
    function updateProgress() {
        if (!audio) return;
        const dur = audio.duration || 0;
        const cur = audio.currentTime || 0;
        const pct = dur ? (cur / dur) * 100 : 0;
        progressFill.style.width = Math.min(pct, 100) + '%';
        currentTimeEl.textContent = formatTime(cur);
        totalTimeEl.textContent = formatTime(dur);
    }

    function onLoadedMetadata() {
        totalTimeEl.textContent = formatTime(audio.duration);
    }

    function onTrackEnd() {
        // auto next
        nextTrack();
    }

    // ---------- controls ----------
    function togglePlay() {
        if (!audio || !tracks.length) return;
        if (audio.paused) {
            audio.play().then(() => {
                isPlaying = true;
                updatePlayButton();
                artwork.classList.add('spinning');
            }).catch(e => console.warn('play error', e));
        } else {
            audio.pause();
            isPlaying = false;
            updatePlayButton();
            artwork.classList.remove('spinning');
        }
    }

    function updatePlayButton() {
        const icon = playBtn.querySelector('i');
        if (isPlaying) {
            icon.className = 'fas fa-pause';
            playBtn.classList.add('playing');
        } else {
            icon.className = 'fas fa-play';
            playBtn.classList.remove('playing');
        }
    }

    function nextTrack() {
        if (!tracks.length) return;
        const next = (currentIndex + 1) % tracks.length;
        currentIndex = next;
        loadTrack(currentIndex);
        if (isPlaying && audio) {
            audio.play().catch(() => { });
        }
    }

    function prevTrack() {
        if (!tracks.length) return;
        const prev = (currentIndex - 1 + tracks.length) % tracks.length;
        currentIndex = prev;
        loadTrack(currentIndex);
        if (isPlaying && audio) {
            audio.play().catch(() => { });
        }
    }

    // ---------- progress click (seek) ----------
    function handleProgressClick(e) {
        if (!audio || !audio.duration) return;
        const rect = progressTrack.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const pct = Math.min(Math.max(x / rect.width, 0), 1);
        audio.currentTime = pct * audio.duration;
    }

    // ---------- modal open/close ----------
    function openPlayer() {
        modal.classList.add('open');
        overlay.classList.add('active');
        // if no tracks, try load
        loadTracks();
        /*if (!tracks.length) loadTracks();
        else renderPlaylist();*/
    }

    function closePlayer() {
        modal.classList.remove('open');
        overlay.classList.remove('active');
        if (audio) {
            audio.pause();
            isPlaying = false;
            updatePlayButton();
            artwork.classList.remove('spinning');
        }
    }

    // ---------- playlist toggle ----------
    function togglePlaylist() {
        isPlaylistOpen = !isPlaylistOpen;
        playlistContainer.classList.toggle('open', isPlaylistOpen);
    }

    // ---------- event bindings ----------
    //openBtn.addEventListener('click', openPlayer);
    closeBtn.addEventListener('click', closePlayer);
    overlay.addEventListener('click', closePlayer);
    playlistToggle.addEventListener('click', togglePlaylist);
    playBtn.addEventListener('click', togglePlay);
    prevBtn.addEventListener('click', prevTrack);
    nextBtn.addEventListener('click', nextTrack);
    progressTrack.addEventListener('click', handleProgressClick);

    // keyboard shortcut: space to toggle
    document.addEventListener('keydown', (e) => {
        if (e.key === ' ' && modal.classList.contains('open')) {
            e.preventDefault();
            togglePlay();
        }
        if (e.key === 'Escape' && modal.classList.contains('open')) {
            closePlayer();
        }
    });

    // ---------- init: load tracks from window or fetch ----------
    if (window.tracks && Array.isArray(window.tracks) && window.tracks.length) {
        tracks = window.tracks;
        initPlayer();
    } else {
        loadTracks();
    }

    // if player hidden initially, we still prepare
    // but we don't show until open

    // expose for debugging
    window.__player = { modal, audio, tracks, currentIndex, isPlaying };

    // ==========================================================
    // for the main track elements
    let tt = document.querySelectorAll('.track-thumbnail');
    if (!tt) return;

    tt.forEach(t => {
        t.addEventListener('click', () => {
            let tid = parseInt(t.dataset.itemid);
            // risky. make sure the musicData array is defined before this code
            let targetItm = window.tracks.find(itm => itm.id === tid);
            if (!targetItm) return;

            /**
             * replace thumbnail with playable container. or make an audio player
             */
            clearTracks();
            document.getElementById('root')?.appendChild(makeMusicItem(targetItm));

            setTimeout(() => {
                mediaStat();
            }, 100);
            let smallModal = document.querySelector('.track.small');
            smallModal?.querySelector('.close-modal-btn').addEventListener('click', function () {
                try {
                    window._audioPlaying && smallModal.querySelector('.play-btn')?.click();
                    smallModal.remove();
                } catch(e){
                    console.error(e);
                }
            });
            smallModal?.addEventListener('click', (e) => {
                const cel = e.target.className;
                if (cel === "track modal small" || !(cel.match("-btn") ||
                    cel.match("media-ico") || cel.match("fa-"))) {
                    currentIndex = window.tracks.indexOf(targetItm);
                    openPlayer();
                    clearTracks();
                }
            });
        });
    });
})();
