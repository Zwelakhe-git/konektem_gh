import '../css/musicplayer.css';

function playerHtml(){
    let modal = document.createElement('div');
    modal.className = 'modal big slide-in from-right';
    modal.id = "music-player";
    modal.innerHTML = `
        <div class="left-panel playlist">
            <div class="playlist-nav">
                <div class="nav-item active">
                    <span id="playlist">playlist</span>
                </div>
                <div class="nav-item">
                    <span id="genre">genre</span>
                </div>
                <div class="nav-item">
                    <span id="artist">artist</span>
                </div>
            </div>
        </div>
        <div id="player-win">
            <div id="win-header">
                <div class="icon-box close-modal-btn">
                    <i class="fas fa-chevron-left icon"></i>
                </div>
                <div class="track-title">
                    <span class="title-text">${musicData.track_name}</span><br/>
                    <span>[${musicData.artist_name}]</span>
                </div>
            </div>
            <div id="win-body">
                <div class="track-img">
                    <div class="image"
                    ${musicData.image_location ?
                        `style="background-image: url(${musicData.image_location});
                        background-size: contain; background-position: center;"` :
                        ""}>
                        <!-- default - music icon -->
                        ${musicData.image_location ? '' : `<i class="fas fa-music icon"></i>`}
                    </div>
                </div>
                <div class="controls">
                    <div id="play-stat">
                        <div class="progress-bar">
                            <div class="progress"></div>
                        </div>
                        <div id="timer">
                            <span class="currtime">00.00</span>
                            <span class="duration">00.00</span>
                        </div>
                    </div>
                    <div class="btns">
                        <div class="ff-btn" id="ff-l">
                            <i class="fas fa-backward"></i>
                        </div>
                        <div class="play-btn" data-state="play">
                            <i class="fas fa-circle-play"></i>
                        </div>
                        <div class="ff-btn" id="ff-r">
                            <i class="fas fa-forward"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <audio id="player" src="${musicData.location}"></audio>
    `;
    return modal;
}

async function updateTimer(player){
    // update count during music play
    let curCount = document.querySelector(".modal #timer .currtime");
    let dCount = document.querySelector(".modal #timer .duration");
    dCount.textContent = formatTime(player.duration);
    
    // init play time
    curCount.textContent = formatTime(player.currentTime);
    while((player.currentTime < player.duration) && !player.paused){
        curCount.textContent = formatTime(player.currentTime);
        await new Promise(resolve => setTimeout(resolve, 100));
    }
    
    if(player.currentTime >= player.duration){
        changeButtonUI();
        let imgRecord = document.querySelector(".modal .track-img .image");
        pb.querySelector(".progress").style.setProperty('width', '0%');
        imgRecord?.style.setProperty('animation-play-state', 'paused');
    }
    isPlaying = false;
    return true;
}

async function updateProgress(player){
    // progressBar/Track
    let pb = document.querySelector(".modal .progress-bar");
    let pt = pb.querySelector(".progress");
    
    // initialize track pos
    pt.style.width = `${(player.currentTime / player.duration) * 100}%`;
    while((player.currentTime < player.duration) && !player.paused){
        pt.style.width = `${(player.currentTime / player.duration) * 100}%`;
        await new Promise(resolve => setTimeout(resolve, 100));
    }
    return true;
}

function formatTime(sec){
    let i = Math.floor(sec / 60);
    let s = Math.floor(sec % 60);

    let fi = isNaN(i) ? `00` : i.toString().padStart(2,'0');
    let fs = isNaN(s) ? `00` : s.toString().padStart(2,'0');

    return `${fi}.${fs}`;
}

function changeButtonUI(){
    let playBtn = document.querySelector('.modal .play-btn');
    let icon = playBtn.querySelector("i");
    if(icon.className.match('play')){
        icon.className = icon.className.match('circle') ? 'fas fa-circle-pause' : 'fas fa-pause';
        playBtn.dataset.state = 'pause';
    } else{
        icon.className = icon.className.match('circle') ? 'fas fa-circle-play' : 'fas fa-play';
        playBtn.dataset.state = 'play';
    }
}
function initButtons(){
    let playBtn = document.querySelector('.modal .play-btn');
    let ffLeftBtn = document.querySelector('.modal .ff-btn#ff-l');
    let ffRightBtn = document.querySelector('.modal .ff-btn#ff-r');
    let player = document.querySelector('.modal #player');
    let closeBtn = document.querySelector('.modal .close-modal-btn');
    let navLinks = document.querySelectorAll('.modal .playlist-nav .nav-item');

    playBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        let playState = playBtn.dataset.state;
        let container = document.querySelector(".modal .track-img .image");
        let icon = playBtn.querySelector("i");
        switch(playState){
            
            case 'play':
                if(playAudio(player)){
                    playBtn.dataset.state = 'pause';
                    icon.className = icon.className.match('circle') ? 'fas fa-circle-pause' : 'fas fa-pause';
                    container?.style.setProperty('animation', '10s rotate-animation 0s linear infinite');
                    isPlaying = true;
                }
                break;
            case 'pause':
                if(pauseAudio(player)){
                    playBtn.dataset.state = 'play';
                    icon.className = icon.className.match('circle') ? 'fas fa-circle-play' : 'fas fa-play';
                    container?.style.setProperty('animation-play-state', 'paused');
                    isPlaying = false;
                }
                break;
            default:
                console.log('unknown button state');
                break;
        }
    });
    closeBtn?.addEventListener('click', (e) => {
        player.pause();
        let modal = document.querySelector('.modal#music-player');
        if(modal){
            modal.style.setProperty('left','100%');
        } else {
            modal = document.querySelector('.track.modal');
            modal.remove();
            return;
        }
        setTimeout(() => {
            modal?.remove();
            document.documentElement.style.overflow = 'auto';
            // consider dynamic import when the player is loaded then dynamic delete
            // when its closed.
            // styles?.forEach(src => {
            //     document.querySelector(`link[href="${src}"]`)?.remove();
            // });
        }, 300);
    });

    ffRightBtn?.addEventListener('click', ()=>{
        if(player && player.src.length > 0){
            player.currentTime = Math.min(player.duration, player.currentTime + 5);
            updateProgress(player);
            updateTimer(player);
        }
    });

    ffLeftBtn?.addEventListener('click', ()=>{
        if(player && player.src.length > 0){
            player.currentTime = Math.max(0, player.currentTime - 5);
            updateProgress(player);
            updateTimer(player);
        }
    });

    navLinks?.forEach(nl => {
        nl.addEventListener('click', ()=>{
            navLinks.forEach( ol => {
                ol.classList.remove('active');
            })
            nl.classList.add('active');
        })
    });
}

function playAudio(player){
    // asuming that the src url is already set

    // if null is passed but element exists
    if(!player && document.querySelector(".modal #player")){
        player = document.querySelector(".modal #player");
    } else if(!player) {
        console.log("audio player not found");
        return false;
    }
    if(!player.src || player.src.length === 0){
        console.log("no audio source");
        return false;
    }

    player.onplay = () =>{
        updateTimer(player);
        updateProgress(player);
    }
    player.play();
    return true;
}

async function pauseAudio(player){
    // stop progress and timer updates
    // if null is passed but element exists
    if(!player && document.querySelector(".modal #player")){
        player = document.querySelector(".modal #player");
    } else if(!player) {
        console.log("audio player not found");
        return false;
    }
    player.pause();
    return true;
}

async function loadFile(url){
    
    try{
        //let b_url = URL.createObjectURL(blob);
        let container = document.querySelector(".modal .track-img .image");
        container.style.backgroundImage = `url(${url})`;
        container.style.backgroundSize = 'contain';
        container.style.backgroundPosition = 'center';
        container.querySelector('i').style.display = 'none'
    } catch(error){
        console.log(error.message);
    }
}

export default function musicPlayer(data){
    let modal = document.querySelector('.modal#music-player');
    if(modal){
        // if the player is hiding bring it to view
        console.log("a player already exists");
        return;
    }
    if(typeof data !== 'object'){
        console.log("expected object for music data");
        return;
    }
    // insert into the main body

    //musicData = data;
    document.documentElement.style.overflow = 'hidden';

    document.body.appendChild(playerHtml());
    modal = document.querySelector('.modal#music-player');
    modal.style.top = `${window.pageYOffset}px`;
    setTimeout(()=>{
        initButtons();
        //loadFile(musicData.image_location);
        modal.style.left = '0%';
    }, 200);
}

export {
    playAudio, pauseAudio,
    updateProgress, updateTimer,
    initButtons, formatTime
};