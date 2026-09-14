import userAuthPage from './authentication.js';
import {InfFreeFetch} from './InfFreeUtils.js'
import { playAudio, shareItem } from './utils.js';

// this module installs event listeners for recording likes, downloads and shares on music
// it only sends the data through an api, and doesnt display any modals. seperate event listeners should
// be installed for that.
async function recordMediaStats(activity, id, itemname){
    try{
        const response = await InfFreeFetch(`/konektem/api.service/${activity}`, {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`
            },
            body: {
                item: itemname,
                id: id
            }
        });
        
        const result = await response.json();
        if(response.status === 401){
            localStorage.removeItem('user');
            localStorage.removeItem('token');
            userAuthPage();
        } else if(!result.success){
            showError(result.message);
        }
        return result;
    } catch(error){
        console.error(error.message);
        return {};
    }
}

export default async function mediaStat(){

    let [downdloadBtns, likeBtns, playBtns, shareBtns] = [
        document.querySelectorAll(".download-btn"),
        document.querySelectorAll(".like-btn"),
        document.querySelectorAll(".play-btn"),
        document.querySelectorAll(".share-btn")
    ];

    shareBtns.forEach( btn => {
        let icon = btn.querySelector(".media-ico");
        if(icon){
            btn.addEventListener("click", async function (event){
                event.stopPropagation();
                //event.preventDefault();
                
                if(! await allowUserAction()){
                    return;
                }
                
                if(!icon.classList.contains("shared") && !btn.classList.contains("shared")){
                    const result = await recordMediaStats('share', icon.dataset.itemid, icon.dataset.itemname);
                    if(result.success){
                       const spanTag = btn.querySelector("span");
                        if(spanTag){
                            spanTag.textContent = result.data.shares;
                        }
                        shareItem(result.data.url, result.data.title, result.data.text);
                        icon.classList.add("shared");btn.classList.add("shared")
                        icon.classList.add("fa-solid");
                        icon.classList.remove("fa-regular");
                    }
                }
            });
        }
        else {
            console.log("share not found");
        }
    } );
    likeBtns.forEach( btn => {
        let icon = btn.querySelector(".media-ico");
        if(icon){
            btn.addEventListener("click", async function (event){
                event.stopPropagation();
                //event.preventDefault();
                
                if(!await allowUserAction()){
                    return;
                }
                if(!icon.classList.contains("liked") && !btn.classList.contains("liked")){
                    const result = await recordMediaStats('like', icon.dataset.itemid, icon.dataset.itemname);
                    if(result.success){
                        const spanTag = btn.querySelector("span");
                        if(spanTag){
                            spanTag.textContent = result.data.likes;
                        }
                        icon.classList.add("liked");
                        btn.classList.add("liked");
                        icon.classList.add("fa-solid");
                        icon.classList.remove("fa-regular");
                    }
                    
                }
            });
        }
        else {
            console.log("like icon not found");
        }
    } );

    downdloadBtns.forEach( btn => {
        let icon = btn.querySelector(".media-ico");
        if(icon){
            btn.addEventListener("click", async function (event){
                event.stopPropagation();
                //event.preventDefault();
                
                if(! await allowUserAction()){
                    return;
                }
                btn.disabled = true;
                const defaultBtnContent = btn.innerHTML;
                btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i>`;
                const result = await recordMediaStats('download', icon.dataset.itemid, icon.dataset.itemname);
                if(result.success){
                    const spanTag = btn.querySelector('span');
                    if(spanTag) spanTag.textContent = result.data.downloads;
                    let a = document.createElement('a');
                    a.download = result.data.file_name;
                    a.href = result.data.file_url;
                    a.click();
                }
                btn.innerHTML = defaultBtnContent;
                btn.disabled = false;

            });
        } else {
            console.log("download icon not found");
        }
    });
    
    playBtns.forEach( (btn) => {
        let icon = btn.querySelector(".media-ico");
        if(icon){
            btn.addEventListener("click", async function(event){
                event.stopPropagation();
                if(Array.from(icon.classList).find(cls => /play/.exec(cls))){
                    const result = await recordMediaStats('play-track', icon.dataset.itemid, icon.dataset.itemname);
                    if(result.success){
                        playAudio(result.data.file_url, this);
                    }
                } else {
                    playAudio('', this);
                }
                
            })
        } else {
            console.log("play icon not found");
        }
    });
    
    async function allowUserAction(){

        if(!localStorage.getItem('token')){
            userAuthPage();
            return false;
        }

        return true;
    }
}

export {recordMediaStats};