
import {posting} from "./js-6966390.js";
import {openProfileModal} from "@/public/tmp-build/js/profile-modal-api.js";
//
try{
    posting();
} catch(err){
    alert(err);
}

document.querySelector('.profile .user-avatar').onclick = ()=>{openProfileModal('avatar-edit-form')};
document.querySelectorAll('.settings-icon:not(.admin)').forEach(icon => {
    icon.onclick = ()=>{openProfileModal('profileForm');};
});

// align the poster correctly
let adjacentElement = document.body.querySelector(".container.mt-4");
setTimeout(()=>{
    let rect = adjacentElement.getBoundingClientRect();
    document.querySelector(".poster")?.style.setProperty('top',`${rect.bottom + 10}px`);
    centerPoster();
}, 200);

const centerPoster = async function (){
    /*
    * center a 'fixed' positioned poster
    * only for mobile displays
    */
    console.log('centering fixed poster');
    const poster = document.querySelector(".poster");
    if(!poster) return;
    if(window.innerWidth > 768) return;
    let stls = window.getComputedStyle(poster);
    //rollback if its not fixed. css is the best alternative
    if(stls.position === 'relative' || stls.position === 'static') return;
    
    let rc = document.querySelector(".container.mt-4"); // relativeContainer
    let firstChild = rc.children[0];
    stls = rc ? window.getComputedStyle(rc) : null;
    let cstls = window.getComputedStyle(firstChild);
    let rw = rc ? rc.clientWidth : window.innerWidth;
    // const rec = poster.getBoundingClientRect();
    let fs = window.innerWidth - rw;// freeSpace
    let ppl = stls ? Number(/[0-9]+[.]?[0-9]+/.exec(stls.paddingLeft)) : 0;
    let ppr = stls ? Number(/[0-9]+[.]?[0-9]+/.exec(stls.paddingRight)) : 0;
    let cpl = stls ? Number(/[0-9]+[.]?[0-9]+/.exec(cstls.paddingLeft)) : 0;
    let cpr = stls ? Number(/[0-9]+[.]?[0-9]+/.exec(cstls.paddingRight)) : 0;
    poster?.style.setProperty("width", `${rw - ppl - ppr - cpl - cpr}px`);
    fs = fs + ppl + ppr + cpl;
    poster?.style.setProperty("margin-left", `${fs / 2}px`);                
}


export {centerPoster};