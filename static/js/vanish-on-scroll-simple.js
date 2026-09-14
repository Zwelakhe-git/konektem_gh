

function setIsVisible(isVisible){
    let topBar = document.querySelector('.top-bar') ?? document.querySelector("#top-bar");
    let navPanelBottom = document.querySelector('.nav-panel-bottom') ?? document.querySelector('#nav-panel-bottom');
    if(isVisible){
        topBar?.style.setProperty('--pos', '0%');
        navPanelBottom?.style.setProperty('--pos', '0%');
    } else {
        topBar?.style.setProperty('--pos', '-100%');
        navPanelBottom?.style.setProperty('--pos', '100%');
    }
}


document.addEventListener('DOMContentLoaded',() => {
    let lastScrollY = 0;
    let scrollHandlerSet = false;
    const handleScroll = () => {
        
        const currentScrollY = window.scrollY;
        if (currentScrollY <= 0) {
            setIsVisible(true);
        } else if (currentScrollY > lastScrollY) {
            setIsVisible(false);
        } else {
            setIsVisible(true);
        }
        lastScrollY = currentScrollY;
    };

    window.addEventListener("scroll", handleScroll, { passive: true });
    scrollHandlerSet = true;

    window.addEventListener('resize', ()=>{
        if(window.innerWidth > 768 && scrollHandlerSet){
            window.removeEventListener('scroll', handleScroll);
            scrollHandlerSet = false;
        } else if(!scrollHandlerSet){
            window.addEventListener('scroll', handleScroll);
        }
    });
});
