function newsFadeAnimation(){
  let cards = document.querySelectorAll(".artcl-itm");
  let current = null;
  let running = true;
  let animationTimer = null;
  let positionTimerId = null;
  let timeoutForNextItemId = null;
  const animationDelay = 10000;
  let link = document.querySelector('#fade-news-link');
  let index = 0;

  
  if(!link) console.log('link not found');
  cards.forEach(card => {
    card.addEventListener("mouseenter",(event)=>{
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
      
      index = Array.from(cards).indexOf(event.target) + 1;
      event.target.classList.add("selected");
    });
    
    card.addEventListener("mouseleave",(event)=>{
      event.target.classList.remove("mouseHover");
      setTimeout(() => {
          start();
      }, 2000)
    });
  })

  runFadeAnimation();
  animationTimer = setInterval(runFadeAnimation, animationDelay);
  
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
    }catch(TypeError){
      console.log("An error occured");
    }
  }

  function repositionItem(){
    if(index > cards.length - 1) index = 0;
    current = cards[index++];
      //console.log(current.dataset);
     link.href = link && `/?p=actuality&id=${current.dataset.newsid}`;

    cards.forEach(other => {
      other.classList.toggle("selected", other === current);
    })
  }
};
//newsFadeAnimation();
export default newsFadeAnimation;