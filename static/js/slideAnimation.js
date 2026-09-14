
async function slideAnimation(containerId, delay = 0, updateLink=null){
    let currentIndex = 0;
    let timer = null;
    let resumeTimer = null;
    let anyTimer = null;
    let allSlides = null;
    let slideCount = null;
    let slidesLink = null;
    let linkRedirect = null;
    let id = containerId;
    let intervalDuration = 4000;
    
    const container = document.getElementById(id);
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
        const sw = document.querySelector(`#${id}`).scrollWidth;
        const cw = document.querySelector(`#${id}`).clientWidth;
        const slides = Array.from(document.querySelector(`#${id}`).children);
        
        slideCount = slides.length;
        if(sw <= cw || slideCount < 2) return;

        slides.slice(0, 2).forEach(slide => {
            document.querySelector(`#${id}`).appendChild(slide.cloneNode(true));
        });

        allSlides = Array.from(document.querySelector(`#${id}`).children);
        linkRedirect = document.querySelector(`#${id}`).dataset.itempage;
        const p = document.querySelector(`#${id}`).parentElement;
        slidesLink = p.querySelector('.slide-link');
        if(!slidesLink){
            console.log("slides link not found");
        }

        setTimeout(() => {
            timer = setInterval(nextSlide, intervalDuration);
        }, delay);

        let rs = document.querySelector(`#${id}`).parentElement?.querySelector(".scroll-btn.right");
        let ls = document.querySelector(`#${id}`).parentElement?.querySelector(".scroll-btn.left");
        
        rs?.addEventListener("click", rightScroll, {passive: true});
        ls?.addEventListener("click", leftScroll, {passive: true});
    }
    function rightScroll() {
        try{
            clearInterval(timer);
            timer = null;

            if(resumeTimer){
                clearTimeout(resumeTimer);
                resumeTimer = null;
            }

            if(anyTimer){
                clearTimeout(anyTimer);
                anyTimer = null;
            }

            currentIndex = (currentIndex + 1) % allSlides.length;
            
            document.querySelector(`#${id}`).scrollTo({
                left: allSlides[currentIndex]?.offsetLeft,
                behavior: "smooth"
            });
            updateLink();

            if (currentIndex >= slideCount) {
                
                currentIndex = currentIndex - slideCount;
                anyTimer = setTimeout(() => {
                    document.querySelector(`#${id}`).scrollLeft = allSlides[currentIndex]?.offsetLeft;
                }, 500);
            }

            resumeSlideTimer();
        } catch(err){
            console.log(err);
        }
    }

    function leftScroll() {
        clearInterval(timer);
        timer = null;

        if(resumeTimer){
            clearTimeout(resumeTimer);
            resumeTimer = null;
        }
        if(anyTimer){
            clearTimeout(anyTimer);
            anyTimer = null;
        }

        if (currentIndex === 0) {
            currentIndex = slideCount - 1;
            document.querySelector("#"+id).scrollLeft = allSlides[slideCount]?.offsetLeft;
            document.querySelector("#"+id).scrollTo({
                left: allSlides[currentIndex]?.offsetLeft,
                behavior: "smooth"
            })
        } else {
            currentIndex--;
            document.querySelector("#"+id).scrollTo({
                left: allSlides[currentIndex]?.offsetLeft,
                behavior: "smooth"
            });
        }
        updateLink();

        resumeSlideTimer();
    }

    function resumeSlideTimer() {
        
        resumeTimer = setTimeout(() => {
            timer = setInterval(nextSlide, intervalDuration);
        }, intervalDuration);
    }

    function nextSlide() {
        try{
            currentIndex = (currentIndex + 1) % allSlides.length;
            document.querySelector("#"+id).scrollTo({
                left: allSlides[currentIndex]?.offsetLeft,
                behavior: "smooth"
            });
            updateLink();
            if (currentIndex >= slideCount) {
                anyTimer = setTimeout(() => {
                    try{
                        const realIndex = currentIndex - slideCount;
                        document.querySelector("#"+id).scrollLeft = allSlides[realIndex].offsetLeft;
                        currentIndex = realIndex;
                    } catch(err){
                        console.log(err);
                    }
                }, 1000);
            }
        } catch(error){
            console.log(error);
        }
    }
    function updateLink(){
        if(slidesLink){
            slidesLink.href = allSlides[currentIndex].dataset.lk;
        } else {
            slidesLink = document.querySelector(`#${id}`).parentElement.querySelector('.slide-link');
            return;
        }
    }
}

export default slideAnimation;