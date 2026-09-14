import {centerPoster} from './admin-page-engine.js';

const navBar = document.querySelector(".navbar")
const mainBody = document.querySelector(".container.mt-4");
var mainBodyStyles = window.getComputedStyle(mainBody);
var numberRegex = /[0-9]+[.]?[0-9]+/;
var currentDisplay = window.innerWidth;
let rect = mainBody.getBoundingClientRect();
let poster = document.querySelector(".poster");

mainBody.style.top = `${navBar.clientHeight + Number(numberRegex.exec(mainBodyStyles.marginTop))}px`;
document.body.style.marginBottom = `${Number(numberRegex.exec(mainBody.style.top)) + 50}px`;

window.addEventListener('resize', ()=>{
    if(window.innerWidth < 768 && currentDisplay > 768){
        centerPoster();
    }
    if((window.innerWidth > 768 && currentDisplay < 768) || 
        (window.innerWidth < 768 && currentDisplay > 768) ||
        (window.innerWidth > 992 && currentDisplay < 992) ||
        (window.innerWidth < 992 && currentDisplay > 992)
    ){
        mainBody.style.top = `${navBar.clientHeight + Number(numberRegex.exec(mainBodyStyles.marginTop))}px`;
        document.body.style.marginBottom = `${Number(numberRegex.exec(mainBody.style.top)) + 50}px`;
        currentDisplay = window.innerWidth;
        rect = mainBody.getBoundingClientRect();
        poster?.style.setProperty("top",`${rect.top + 10}px`);
    }
});