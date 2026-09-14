import React from "react";

export function BottomSlide(){
    const imageUrls = [
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_4657.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_4658.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_4659.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_4656.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_4660.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_4661.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_4662.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_4664.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_4665.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_5227.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_5244.JPG.jpg"
        },
        {
        "mime_type": "image/jpeg",
        "src": "/media/images/bottom/IMG_5245.JPG.jpg"
        }
    ];
    return (
    <div className="bottom-slide container-outer" style={{padding: '0px'}}>
        {imageUrls.map((slide, indx) => (
            <div key={indx} className="slide-container">
                <img type={slide.mime_type} src={slide.src}/>
            </div>
        ))}
    </div>
    );
}