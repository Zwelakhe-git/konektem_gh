function fadeAnimation(containerId){
    let slides;
    let current = null;
    let running = false;
    let animationTimer = null;
    let positionTimerId = null;
    let timeoutForNextItemId = null;
    const animationDelay = 10000;
    let link ;
    let index = 0;
    
    const container = document.getElementById(containerId);
    if(container && !container.children.length > 0){
        const observer = new MutationObserver(()=>{
            if(container && container.children.length > 0){
                init();
                observer.disconnect();
            }
        });
        observer.observe(container, {subtree: true, childList: true});
    } else if(container){
        init();
    } else { return; }

    function init(){
        link = document.querySelector(`#${containerId} ~ .fade-link`);
        slides = Array.from(document.getElementById(containerId).children);
        start();
        slides.forEach((slide, i) => {
            slide.addEventListener("mouseenter",(event)=>{
                event.target.classList.add("mouseHover");
                try{
                    if(timeoutForNextItemId){
                    clearTimeout(timeoutForNextItemId);
                    timeoutForNextItemId = null;
                    }
                    if(positionTimerId){
                    clearTimeout(positionTimerId);
                    positionTimerId = null;
                    }
                }catch(Error){
                    console.log("Error");
                }
                stop();
                
                index = i + 1;
                event.target.classList.add("selected");
            });
            
            slide.addEventListener("mouseleave",(event)=>{
                event.target.classList.remove("mouseHover");
                setTimeout(() => {
                    start();
                }, 2000);
            });
        });
    }
    
    function start(){
        if(!running){
            running = true;
            runFadeAnimation();
            animationTimer = setInterval(runFadeAnimation,animationDelay);
        }
    }

    function stop(){
        if(running){
            running = false;
            clearInterval(animationTimer);
            animationTimer = null;
        }
    }

    function runFadeAnimation(){
        try{
            repositionItem();
        }catch(err){
            console.log("An error occured", err);
        }
    }

    function repositionItem(){
        if(index > slides.length - 1) index = 0;
        current = slides[index++];
        link?.setAttribute('href', current?.dataset.lk)

        slides.forEach(other => {
            other.classList.toggle("selected", other === current);
        });
    }
};
export default fadeAnimation;