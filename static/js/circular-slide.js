
document.addEventListener('DOMContentLoaded', ()=>{
    let slideImages = [
        '/konektem/assets/konektem-popular-images/69b0396869e11.jpeg',
        '/konektem/assets/konektem-popular-images/697aa917eb62f.jpeg',
        '/konektem/assets/konektem-popular-images/692084bdbdc1c.jpeg',
        '/konektem/assets/konektem-popular-images/692777f395f86.jpeg'
    ];
    let slides = document.querySelectorAll('.slide-item');
    let l = slideImages.length;
    slides.forEach((slide, index) => {
        slide.style.setProperty('background-image', `url(${slideImages[index % l]})`);
        slide.style.setProperty('background-size', `cover`);
    });
    // duplicate last slide
    // we use an observer because slides are dynamically added
    let lastSlides = document.querySelectorAll(".circular-slide-container .slide-item:last-child");
    lastSlides.forEach(slide => {
        slide.parentElement.insertAdjacentElement('afterbegin', slide.cloneNode(subtree=true));
        slide?.scrollIntoView();
    })
})