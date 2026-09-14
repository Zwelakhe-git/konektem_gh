import slideAnimation from './slideAnimation.js';
import fadeAnimation from './fadeAnimation.js'
import shelfAnimation from './shelfAnimation.js';
import mediaStat from './mediaStat.js';


document.addEventListener('DOMContentLoaded', ()=>{
    let slideContainers = [
        document.querySelector('#news-slides'),
        document.querySelector('#intrvws-list'),
        document.querySelector('#services-list'),
        document.querySelector('#books-list')

    ].filter(el => el !== null);
    if(slideContainers.length === 0){
        console.log('no slides')
    }
    slideContainers.forEach((el, indx) => {
        slideAnimation(el.id, indx * 3000);
    });
    let fadeContainers = [
        document.querySelector('#fade-articles-list'),
        document.querySelector('#events-list')
    ].filter(el => el !== null);
    if(fadeContainers.length === 0){
        console.log('no fading containers')
    }
    fadeContainers.forEach(el => {
        fadeAnimation(el.id);
    });
    shelfAnimation();
    mediaStat();
})