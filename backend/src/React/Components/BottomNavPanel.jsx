import React from "react";


export default function BottomNavPanel({isVisible}){
    return (
    <div id="nav-panel-bottom" className="grey-clr no-ovrflw no-margin"
        style={{
            'transform': isVisible ? 'translateY(0%)' : 'translateY(100%)'
        }}
    >
        <div className="nav no-ovrflw">
            <a tabIndex="0" className="bdr-10 nav-link" href="/">
                <i className="fa-solid fa-house"></i> 
                
            </a>
            <a tabIndex="0" className="profile bdr-10 nav-link p-modal-control">
                <i className="fa-regular fa-user"></i>
            
            </a>
            <a tabIndex="0" className="bdr-10 nav-link" href="/?">
                <i className="fa-solid fa-cart-arrow-down"></i>
                
            </a>
            <a tabIndex="0" className="bdr-10 nav-link" href="/?p=music">
                <i className="fa-solid fa-music"></i>
                
            </a>
            
            <a tabIndex="0" className="profile bdr-10 nav-link" href="/account/me/index.php">
                <i className="fa-solid fa-gauge"></i>
                
            </a>
        </div>
    </div>
    );
}