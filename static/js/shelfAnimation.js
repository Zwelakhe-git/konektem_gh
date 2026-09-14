function shelfAnimation(){
    const containerElements = document.querySelectorAll('.shelf-anim');
    containerElements.forEach((container) => {
        container.addEventListener('click', () => {
            containerElements.forEach((cont) => {
                cont.classList.toggle('focus', cont === container);
            });
        });
    });
    document.addEventListener('click', (event) => {
        if (!event.target.closest('.shelf-anim')) {
            containerElements.forEach((cont) => {
                cont.classList.remove('focus');
            });
        }
    });

    window.addEventListener('scroll', () => {
        const scrollPosition = window.scrollY;
        containerElements.forEach((container) => {
            const containerPosition = container.offsetTop;

            if (scrollPosition + window.innerHeight > containerPosition + 100) {
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';

            }
            else{
                container.classList.remove('focus');
                container.style.opacity = '0.2';
                container.style.transform = 'translateY(50px)';
            }

        });
    }, {passive: true});
}

export default shelfAnimation;