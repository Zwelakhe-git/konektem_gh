export default function eventsFadeAnimation(){
    let cards = document.querySelectorAll(".event-card");;
    let current = null;
    let index = 0;
    let animationTimer = null;
    let numCards = cards.length;;
    const animationDelay = 10000;
    
    runFadeAnimation();
    animationTimer = setInterval(runFadeAnimation, animationDelay);

    function runFadeAnimation(){
        try{
            showNext();
        }catch(error){
            console.log("An error occured:", error.message);
            console.log(index);
        }
        
    }

    function showNext(){
        if(index > numCards - 1) index = 0;
        if(index + 1 > numCards - 1){
            current = [cards[0], cards[index]];
        }
        else{
            current = [cards[index], cards[index + 1]];
        }
        cards.forEach(card => {
            card.classList.toggle("selected", current.includes(card));
        }); 

        index += 2;
    }
}
