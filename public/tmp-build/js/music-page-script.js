import musicPlayer, {
    playAudio, pauseAudio,
    updateProgress, updateTimer,
    initButtons, formatTime
} from './musicPlayer.js';
import mediaStat from './mediaStat.js';

function makeMusicItem(data){
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

async function clearTracks(){
    document.querySelectorAll('.track').forEach(t => {
        t.remove();
    });
}

async function handleTrackEvents(){
    let tt = document.querySelectorAll('.track-thumbnail');
    if(!tt) return;

    tt.forEach(t => {
        t.addEventListener('click', ()=>{
            let tid = parseInt(t.dataset.itemid);
            // risky. make sure the musicData array is defined before this code
            let targetItm = musicData.find( itm => itm.id === tid);
            if(!targetItm) return;

            /**
             * replace thumbnail with playable container. or make an audio player
             */
            clearTracks();
            document.getElementById('root')?.appendChild(makeMusicItem(targetItm));
            
            setTimeout(()=>{
                //initButtons();
                mediaStat();
            }, 100);
            let smallModal = document.querySelector('.track.small');

            smallModal?.addEventListener('click', (e)=>{
                const cel = e.target.className;
                if(cel === "track modal small" || !(cel.match("-btn") ||
                    cel.match("media-ico") || cel.match("fa-"))){
                    pauseAudio(null);
                    musicPlayer(targetItm);
                    clearTracks();
                }
            });
        });
    });
}

handleTrackEvents();