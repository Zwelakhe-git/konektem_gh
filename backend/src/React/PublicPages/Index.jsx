import React, { useState } from "react";
import Topbar from "../Components/TopBar";
import TopNavPanel from "../Components/TopNavPanel";
import BottomNavPanel from "../Components/BottomNavPanel";
import { Main } from "./Main";

export function Index(){
    const [isVisible, setIsVisible] = useState(true);
    const lastScrollY = useRef(0);
    const [currentPage, setCurrentPage] = useState(0);
    const [currentPageIndx, setCurrentPageIndx] = useState(0);

    const winSize = window.innerWidth;

    useEffect(() => {
        if(winSize > 768) return;

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

    return (<>
        <Topbar />
        <TopNavPanel currentPage={currentPage} setCurrentPage={setCurrentPage}/>
        {winSize < 768 && <BottomNavPanel style={{
            '--pos': isVisible ? '0%' : '-100%'
        }}/>}
    </>);
}