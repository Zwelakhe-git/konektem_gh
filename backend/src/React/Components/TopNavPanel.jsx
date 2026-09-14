import React, { useEffect, useRef, useState } from "react"
import { Link } from "react-router-dom";

export default function TopNavPanel({
    showNavPanel, setShowNavPanel
}){
    const [displayPanel, setDisplayPanel] = useState(false);
    const npRef = useRef(null);

    useEffect(()=>{
        if(!showNavPanel){
            setTimeout(()=>{
                setDisplayPanel(false);
            }, 350);
        } else {
            setDisplayPanel(true);
        }
    }, [showNavPanel]);

    return (
    <div ref={npRef} id="nav-panel" className="grey-clr no-ovrflw no-margin" style={{
        display: displayPanel ? 'block' : 'none',
        maxHeight: showNavPanel ? `${npRef.current.scrollHeight}px` : '0px'
    }}>
        <nav id="nav" className="no-ovrflw">
            <Link tabIndex="0" className="bdr-10 nav-link" to="/">
                <i className="fa-solid fa-house"></i>
                <span>Akèy</span>
            </Link>
            <Link tabIndex="0" className="bdr-10 nav-link" to="/?p=actuality">
                <ion-icon name="calendar-clear-outline"></ion-icon>
            <span>Aktyalite</span>
            </Link>
            <Link tabIndex="0" className="bdr-10 nav-link" to="/?p=interviews">
                <i className="fa-solid fa-user-tie"></i>
                <span>Entèvyou</span>
            </Link>
            <Link tabIndex="0" className="bdr-10 nav-link" to="/?p=events">
                <ion-icon name="calendar-outline"></ion-icon>
                <span>Evenman</span>
            </Link>
            <Link tabIndex="0" className="bdr-10 nav-link" to="/?p=music">
                <ion-icon name="musical-notes-outline"></ion-icon>
                <span>Mizik</span>
            </Link>
            <Link tabIndex="0" className="bdr-10 nav-link" to="/konektem/books">
                <i className="fa-brands fa-readme"></i>
                <span>Bibliyotèk</span>
            </Link>
            <Link tabIndex="0" className="bdr-10 nav-link" to="/?p=services">
                <i className="fa-solid fa-satellite-dish"></i>
                <span>Sèvis</span>
            </Link>
            <Link tabIndex="0" className="bdr-10 nav-link" to="/?p=streaming">
                <ion-icon name="radio-outline"></ion-icon>
                <span>Layv</span>
            </Link>
            <Link tabIndex="0" className="bdr-10 nav-link" to="/?p=contacts">
                <i className="fa-solid fa-phone"></i>
                <span>Kontak</span>
            </Link>
            <Link id='prof-link-' tabIndex="0" className="bdr-10 nav-link" to="/account/me/index.php">
                <i className="fa-solid user-prof fa-circle-user"></i>
                <span className='user-id'>login/profile</span>
            </Link>
            <Link className="nav-link close-btn" onClick={()=>{setShowNavPanel(!showNavPanel)}}>
                <i className="fa-regular fa-x"></i>
                <span>Fèmen</span>
            </Link>
        </nav>
    </div>
    );
}