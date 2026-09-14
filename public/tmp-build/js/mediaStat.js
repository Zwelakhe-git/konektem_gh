import userAuthPage from './authentication.js';

function customLog(message, level = 'info'){
    let ds = (new Date()).toISOString();;
    let timeRegex = /[0-9]{2}:[0-9]{2}:[0-9]{2}/;
    let [d, t] = ds.split('T');
    console.log(`${d} ${timeRegex.exec(t)[0]} | mediaStat.js | ${level} | ` + message);
}
// this module installs event listeners for recording likes, downloads and shares on music
async function recordMediaStats(activity, id, user, itemname, container){
    let response = null;
    let data = null;
    try{
        //const id = element.dataset.trackid;
        const url = `/php/dbReader.php?q=mediaActivity&activity=${activity}&id=${id}&item=${itemname}&user=${user}`;
        if(activity === 'like' || activity === 'dislike') activity = 'like';
        
        let selector = `.media-ico[data-itemid='${id}']`;
        customLog(`${activity} for ${container}`, 'info');

        let element = container.querySelector(selector);
        if(!element){
            return false;
        }
        let parent = element.parentElement;
        let spanTag = parent.querySelector("span");
        if(!spanTag){
            console.log(`${activity} span tag not found`);
            return;
        }
        response = await fetch(url);
        data = await response.json();
        if(data.success){
            spanTag.textContent = data.response.count;
        } else {
            customLog('response from server: ' + JSON.stringify(data), 'warn');
        }

        return true;
    } catch(error){
        customLog('media stat error: ' + error.message, 'error');
        customLog(`${id}, ${response}, ${data}`, 'error');
        return false;
    }
}

export default async function mediaStat(){
    const session = JSON.parse(sessionStorage.getItem("user"));

    let [downdloadBtns, likeBtns, playBtns, shareBtns] = [
        document.querySelectorAll(".download-btn"),
        document.querySelectorAll(".like-btn"),
        document.querySelectorAll(".play-btn"),
        document.querySelectorAll(".share-btn")
    ];

    shareBtns.forEach( btn => {
        let icon = btn.querySelector(".media-ico");
        
        if(icon){
            icon.addEventListener("click", async (event) => {
                event.stopPropagation();
                //event.preventDefault();
                
                if(! await allowUserAction()){
                    return;
                }
                
                if(!icon.classList.contains("shared") &&
                    recordMediaStats('share', icon.dataset.itemid, session.name, icon.dataset.itemname, btn)){
                    icon.classList.add("shared");
                    icon.classList.add("fa-solid");
                    icon.classList.remove("fa-regular");
                }
            });
        }
        else {
            customLog("like share not found");
        }
    } );
    likeBtns.forEach( btn => {
        let icon = btn.querySelector(".media-ico");
        if(icon){
            icon.addEventListener("click", async (event) => {
                event.stopPropagation();
                //event.preventDefault();
                
                if(!await allowUserAction()){
                    return;
                }
                
                if(!icon.classList.contains("liked") &&
                    recordMediaStats('like', icon.dataset.itemid, session.name, icon.dataset.itemname, btn)){
                    icon.classList.add("liked");
                    icon.classList.add("fa-solid");
                    icon.classList.remove("fa-regular");
                }
            });
        }
        else {
            customLog("like icon not found");
        }
    } );

    downdloadBtns.forEach( btn => {
        let icon = btn.querySelector(".media-ico");
        if(icon){
            icon.addEventListener("click", async (event) => {
                console.log('downloading', event.currentTarget, event.target);
                event.stopPropagation();
                //event.preventDefault();
                
                if(! await allowUserAction()){
                    return;
                }
                
                let audEle = document.querySelector(`#dd-a${icon.dataset.itemid}`);
                if(audEle && audEle.dataset.href &&
                    recordMediaStats('download', icon.dataset.itemid, session.name, icon.dataset.itemname, btn)
                ){
                    audEle.href = audEle.dataset.href;
                    audEle.onclick = (e)=>{
                        e.stopPropagation();
                    }
                    audEle.click();
                } else {
                    alert("Failed to download. invalid link");
                    console.log(audEle);
                }

            });
        } else {
            customLog("download icon not found");
        }
    });
    
    playBtns.forEach( (btn) => {
        let icon = btn.querySelector(".media-ico");
        if(icon){
            icon.addEventListener("click", (event) => {
                if(event.target.classList.contains("play")){
                    recordMediaStats('plays', icon.dataset.itemid, session.name, icon.dataset.itemname, btn);
                }
            })
        } else {
            customLog("play icon not found");
        }
    });
    
    async function allowUserAction(){

        if(session.name != 'guest'){
            return true;
        }

        userAuthPage();
        return false;
    }
}

export {recordMediaStats, customLog};