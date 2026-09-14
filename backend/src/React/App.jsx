import React, { useState, useEffect, useRef } from "react";
import { BrowserRouter, Routes, Route, Link, useParams } from "react-router-dom";

import Topbar from "./Components/TopBar";
import TopNavPanel from "./Components/TopNavPanel";
import LinkRoutes from "./Components/LinkRoutes";
import BottomNavPanel from "./Components/BottomNavPanel";
import { Footer } from "./Components/Footer";


import "@styles/footerStyle.css";
import "@styles/topBar.css";
import "@styles/navpanelstyle.css";
import "@styles/globalStyle.css";
import "@styles/profile-settings-modal.css"


export default function App(){
    const [isVisible, setIsVisible] = useState(true);
    const [showNavPanel, setShowNavPanel] = useState(false);

    const winSizeRef = useRef(window.innerWidth);
    const lastScrollY = useRef(0);

    useEffect(() => {
        if(winSizeRef.current > 768) return;

        const handleScroll = () => {
            const currentScrollY = window.scrollY;
            if (currentScrollY <= 0) {
                setIsVisible(true);
            } else if (currentScrollY > lastScrollY.current) {
                setIsVisible(false);
            } else {
                setIsVisible(true);
            }
            lastScrollY.current = currentScrollY;
        };

        window.addEventListener("scroll", handleScroll, { passive: true });
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);
    return (
        <>
        <Topbar showNavPanel={showNavPanel} setShowNavPanel={setShowNavPanel} isVisible={isVisible}/>
        <BrowserRouter>
            <TopNavPanel showNavPanel={showNavPanel} setShowNavPanel={setShowNavPanel}/>
            <LinkRoutes />
        </BrowserRouter>
        <BottomNavPanel isVisible={isVisible} />
        <Footer />
        </>
    );
    
}