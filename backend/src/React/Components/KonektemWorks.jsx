import React from "react";

export default function KonektemWorks(){
    const images = [
        '/media/images/pictures/m1.jpg',
        '/media/images/pictures/m2.jpg',
        '/media/images/pictures/m3.jpg',
        '/media/images/pictures/m4.png',
        '/media/images/pictures/m5.jpg',
        '/media/images/pictures/m6.png',
        '/media/images/pictures/m7.png',
        '/media/images/pictures/m8.png',
        '/media/images/pictures/m9.png',
        '/media/images/pictures/m10.png',
        '/media/images/pictures/m11.png',
        '/media/images/pictures/m12.png',
    ];
    return (
    <>
    <div className='works-container'>
        <div className='section-title'>
            <h1>Travay Nou Yo</h1>
        </div>
        <div className='works-list'>
            {images.map((image, i) => (
            <div key={i} className='works-l1'>
                <div className='works-img-container'>
                    <img alt="image" src={image}/>
                </div>
            </div>
            ))}
        </div>
    </div>
    
    </>
    );
}