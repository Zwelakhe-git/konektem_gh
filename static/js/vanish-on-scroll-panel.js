/**
 * this API has the following specifications:
 * the top-bar and nav-panel elements are to have the identifiers:
 *      .top-bar or #top-bar
 *      .nav-panel or #nav-panel
 * the css variable --pos should be set for each element,
 * with the transform property set to translate(0px, var(--pos)).
 * the initial values are -100% for the top bar and 100% for the nav panel.
 * transition should be set to transform 0.3s linear,
 * 
 */
class DataBuffer{
    constructor(size){
        this.buff_size = size;
        this.buff_data = Array(size);
        this.buff_index = 0;
        this.buff_max = null;
        this.buff_min = null;
        this.#initBuffer();
    }
    #initBuffer(){
        for(let i = 0; i < this.buff_size; ++i){
            this.buff_data[i] = 0;
        }
        this.buff_min = this.buff_data[0];
        this.buff_max = this.buff_data[0];
    }
    buff_add(value){
        
        this.buff_data[this.buff_index] = value;
        if(this.buff_index >= this.buff_size){
            /*console.log(`%creached end of buffer! %cIndx: ${this.buff_index}.`,
                'color: green; font-size: 18px;',
                'font-size: 19px; color: white;')*/
            this.buff_data.shift();
        } else {
            this.buff_index += 1;
        }
        if(!this.buff_max || this.buff_max < value){
            this.buff_max = value;
        }
        if(!this.buff_min || this.buff_min > value){
            this.buff_min = value;
        }
    }
    getBufferSize(){
        return this.buff_size;
    }
    getMin(){
        return this.buff_min;
    }
    getMax(){
        return this.buff_max;
    }
    getValueIndex(value){
        return this.buff_data.indexOf(value);
    }
    getData(start = undefined, end = undefined){
        return this.buff_data.slice(start, end);
    }
}

/*
* try to initialise the containers if elements are already loaded
*/
let topBar = document.querySelector('.top-bar') ?? document.querySelector("#top-bar");
let navPanel = document.querySelector('.nav-panel-bottom') ?? document.querySelector('#nav-panel-bottom');
let currPos = window.scrollY;
let scrollDirection = null;
let scrollBuffer = null;
let bs = 5;
let bi = 0;
let analysing = false;
const cssVars = {
    '--pos': ''
}

scrollBuffer = new DataBuffer(bs);

document.addEventListener("DOMContentLoaded", ()=>{
    if(!topBar){
        topBar = document.querySelector('.top-bar') ?? document.querySelector("#top-bar");
    }
    if(!navPanel){
        navPanel = document.querySelector('.nav-panel-bottom') ?? document.querySelector('#nav-panel-bottom');
    }
    currPos = window.scrollY;
    console.log("Window currPos " + currPos);
});


function getCSSVariable(element, varName){
    if(!varName.startsWith('--')){
        varName = '--' + varName;
    }
    let styles = window.getComputedStyle(element);
    return styles.getPropertyValue(varName);
}
function setCSSVariable(varName, value, element=undefined){
    if(!element){
        element = document.documentElement;
    }
    if(!varName.startsWith('--')){
        varName = '--' + varName;
    }
    element.style.setProperty(varName, value);
}
function removeCSSVariable(varName, element=undefined){
    if(!element){
        element = document.documentElement;
    }
    if(!varName.startsWith('--')){
        varName = '--' + varName;
    }
    element.style.removeProperty(varName);
}

function toggleHiddenElements(action){
    const allowedActions = ['hide', 'show'];
    let targetAction = allowedActions.find(a => a == action);
    if(!targetAction){
        return;
    }
    let varName = '--pos';
    let value;
    switch(action){
        case 'hide':
            value = '100%';
            setCSSVariable(varName, value, navPanel);
            setCSSVariable(varName, `-${value}`, topBar);
            /*removeCSSVariable(varName, navPanel);
            removeCSSVariable(varName, topBar);*/
            break;
        case 'show':
            value = '0%';
            removeCSSVariable(varName, navPanel);
            removeCSSVariable(varName, topBar);
            /*setCSSVariable(varName, value, navPanel);
            setCSSVariable(varName, `${value}`, topBar);*/
            break;
        default:
            console.log('unkown action for hidden elements');
            return;
    }
    

}
async function positiveSlope(){
    //console.log("%canalyser started", "color: yellow; font-size: 18px;");
    while(analysing){
        let max = scrollBuffer.getMax();
        let maxIndex = scrollBuffer.getValueIndex(max);
        maxIndex = maxIndex > 0 ? maxIndex : 0;
        let currData = scrollBuffer.getData(maxIndex);
        if(window.scrollY < 5){
            toggleHiddenElements("show");
            /*console.log(`%cTop Edge`,
                            "font-size: 20px; color: red; font-weight: 400;");*/
        }
        else {
            for(let i = maxIndex; i < bs - 1; ++i){
                if(currData[i + 1] < currData[i]){
                    if(scrollDirection != 'up'){
                        scrollDirection =  "up";
                        /*console.log(`%c${scrollDirection}`,
                            "font-size: 20px; color: red; font-weight: 400;");*/
                    }
                    toggleHiddenElements("show");
                } else if(scrollDirection != "down"){
                    toggleHiddenElements("hide");
                    scrollDirection =  "down";
                    /*console.log(`%c${scrollDirection}`,
                        "font-size: 20px; color: red; font-weight: 400;");*/
                    
                }
            }
        }
        await (new Promise((res, rej) => setTimeout(res, 500)));
    }
    //console.log("%canalyser stopped", "color: yellow; font-size: 18px");
}

//let arr = Array()

window.addEventListener('scroll', (e)=>{
    if(navPanel){
        let navPanelAvailable = window.getComputedStyle(navPanel).display != 'none';
        if(!navPanelAvailable){
            //console.log("target nav panel not detected");
            return;
        }
        scrollBuffer.buff_add(window.scrollY);
        if(!analysing){
            analysing = true;
            positiveSlope();
        }
    }
});
window.addEventListener('scrollend', ()=>{
    currPos = window.scrollY;
    analysing = false;
    //console.log("scroll end");
})