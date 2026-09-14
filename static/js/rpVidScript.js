const rpVidScript = () => {
    container = document.querySelector("#rp-vid-container");
    if (!container) {
        console.log("embedYTVid: vid container not found");
        return;
    }
    container.innerHTML += `<iframe class="full-wh" src="https://www.youtube.com/embed/qUfA_j2weEI?si=FRr-x22LFESfNGN0"
            title="YouTube video player"
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            referrerpolicy="strict-origin-when-cross-origin" 
            allowfullscreen>
    </iframe>`;
};

export default rpVidScript;