import mediaStat from '../mediaStat.js';
import {cachedFetch} from '../api/data-load.js';
import musicPlayer, {
    playAudio, pauseAudio,
    updateProgress, updateTimer,
    initButtons, formatTime
} from '../musicPlayer.js';

// content is for music tracks

let musicData = [];
let root = null;
let styles = [
  "/experimental/CSS/globalStyle.css",
  "/experimental/CSS/musicpage.css"
];
let externalScripts = [];
function groupTracksByGenre(){
    /*
    * only display the thumbnail and track name
    */
    let musicGenre = [
    ];


    for(let i = 0; i < musicData.length; ++i){
        let formattedName = musicData[i].track_name.replace(/[^A-Za-z0-9]/g, "_");

        if(!musicData[i].genre){
            /*
            * the new changes require that every music item have a genre
            */
            continue;
        }
        /*
        * find the given genre from the list. make a container if none is found
        */
        let dest = musicGenre.length > 0 ? musicGenre.find( g => g.name.toLowerCase() === musicData[i].genre.toLowerCase()) : null;
        if(!dest){
            dest = {
                name: musicData[i].genre,
                container: `<div class="genre" id='${musicData[i].genre}'>
                    <h1 class="section-title">
                        <i class="fa-solid fa-music"></i>
                        ${musicData[i].genre}
                    </h1>
                    <div class="music-content">
                    <!-- dynamically add items -->
                    `
            };
            musicGenre.push(dest);
        }
        dest.container += `<div class="track-thumbnail"
        data-itemid="${musicData[i].id}">
            <div class="track-img">
                <div class="icon-container">
                    <div class="icon-boundary"></div>
                    <i class="fa-solid fa-music icon"></i>
                </div>
            </div>
            <div class="music-info">
                <div class="music-title">${musicData[i].track_name}</div>
            </div>
        </div>`;
    }

    let result = '';
    musicGenre.forEach(g => {
        result += g.container + `</div>
            <div class="expand-controller" data-genre="${g.name}">
                <span>we splis</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>`;
    });
    return result;
}

function musicPageHtml(){
    let content = groupTracksByGenre();
    return `
    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-play-circle"></i>
                <span>MediaNews</span>
            </div>
            <a href="/" class="back-to-news nav-link" style="color: white">
                <i class="fas fa-arrow-left"></i>
                <span>Retounen</span>
            </a>
        </header>
        
        <h1>Galri Mizik</h1>
        <p class="subtitle">Amize w avek bel mizik sa yo</p>
        
        <div class="media-container">
            <div class="music-section">
                ${content}
            </div>
        </div>
    </div>
    <!--<div class="now-playing">
        <div class="now-playing-info">
            <div class="music-thumbnail">
                <i class="fas fa-music"></i>
            </div>
            <div class="now-playing-text">
                <div class="now-playing-title">Select a track to play</div>
                <div class="now-playing-artist">MediaHub Player</div>
            </div>
        </div>
        <div class="controls">
            <div class="control-btn">
                <i class="fas fa-step-backward"></i>
            </div>
            <div class="control-btn" id="main-play">
                <i class="fas fa-play"></i>
            </div>
            <div class="control-btn">
                <i class="fas fa-step-forward"></i>
            </div>
        </div>
    </div>-->`;
}

function makeMusicItem(data){
    const div = document.createElement('div');
    div.className = 'track modal small';
    div.setAttribute('data-itemid', data.id);
    div.setAttribute('data-src', data.location);
    div.innerHTML = `
            <div class="close-modal-btn">
                <i class="fas fa-x"></i>
            </div>
            <div class="track-img">
                <img class="full-w-h" alt="track image" src="${data.image_location}" />
            </div>
            <div class="music-info">
                <div class="music-title">${data.track_name}</div>
                <div class="music-artist">${data.artist_name}</div>
                <div class="player-controls">
                    <div class="play-btn" data-trackid="track-${data.id}" data-state="play">
                        <i class="fas fa-play media-ico play"></i>
                    </div>
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                    <div class="music-duration" id="timer">
                        <span class="currtime">00.00</span>/
                        <span class="duration">00.00</span>
                    </div>
                </div>
                <div class="media-actions">
                    <div class="action-btn download-btn">
                        <i class="fas fa-download media-ico" data-itemname="music" data-itemid="${data.id}"></i>
                        <span>${data.downloads}</span>
                        <a id="dd-a${data.id}" download="${data.track_name}"
                        data-href="${data.location}" style="display: none;"></a>
                    </div>
                    <div class="action-btn like-btn">
                        <i class="fa-regular fa-heart media-ico" data-itemname="music" data-itemid="${data.id}"></i>
                        <span>${data.likes}</span>
                    </div>
                    <div class="action-btn plays-btn">
                        <i class="fa fa-eye media-ico" data-itemname="music" data-itemid="${data.id}"></i>
                        <span>${data.plays}</span>
                    </div>
                </div>
                <span style="font-size: 5px;position: absolute;right: 0px;">${data.owner ?? ''}</span>
            </div>
            <audio id="player" src="${data.location}"></audio>`;
        return div;
}

async function clearTracks(){
    let tracks = document.querySelectorAll('.track');
    if(!tracks) return;

    tracks.forEach(t => {
        t.remove();
    });
}

async function handleTrackEvents(){
    let tt = document.querySelectorAll('.track-thumbnail');
    if(!tt) return;

    tt.forEach(t => {
        t.addEventListener('click', ()=>{
            let tid = parseInt(t.dataset.itemid);
            let targetItm = musicData.find( itm => itm.id === tid);
            if(!targetItm) return;

            /**
             * replace thumbnail with playable container. or make an audio player
             */
            clearTracks();
            root?.appendChild(makeMusicItem(targetItm));
            console.log(targetItm);
            
            setTimeout(()=>{
                initButtons();
                mediaStat();
            }, 100);
            let smallModal = document.querySelector('.track.modal.small');

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

function pageScript(){
    let contentExpandControllers = docunemt.querySelectorAll('.expand-controller');
    contentExpandControllers.forEach(ctrl => {
        ctrl.onclick = ()=>{
            document.querySelector(`#${ctrl.dataset.genre} .music-content`).classList.toggle('expand');
        }
    });
}

export default function musicAndVids(){
    let title = document.querySelector("title");
      if(!title){
          title = document.createElement("title");
          document.head.insertAdjacentElement("afterbegin", title);
      }
    title.textContent = "Music";
    //musicPageStyle();
    root = document.querySelector("#root");
    if(!root){
        console.log("Music: root element not found");
        return;
    }
    
    externalScripts.forEach(script => {
        let scriptTag = document.createElement("script");
        scriptTag.type = script.type;
        scriptTag.src = script.src;
        document.head.appendChild(scriptTag);
    });
    
    styles.forEach(style => {
        let linkTag = document.createElement("link");
        linkTag.rel = "stylesheet";
        linkTag.href = style;
        document.head.appendChild(linkTag);
    });
    
    setTimeout( async ()=>{
        //let data = await fetch('/php/dbReader.php?r=musicContent');
        musicData = await cachedFetch('/php/dbReader.php?r=musicContent', 'musicData');

        let content = musicPageHtml();
        root.innerHTML = content;

        setTimeout(()=>{
            handleTrackEvents();
        }, 200)
    },250);
}

