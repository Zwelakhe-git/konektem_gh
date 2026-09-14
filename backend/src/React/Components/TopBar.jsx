import React, { useEffect, useRef } from "react"


export default function TopBar({
    showNavPanel, setShowNavPanel,
    isVisible
}){
    const topBarStyle = useRef({
    });

    useEffect(()=>{
        topBarStyle.current = showNavPanel ? {
            transform: "translate(0%,0%)"
        } : topBarStyle.current;
        
    }, [showNavPanel])
    return (
    <div id="top-bar" className="no-margin flxDisp" style={{
        ...topBarStyle.current, 
        'transform': isVisible ? 'translateY(0%)' : 'translateY(-100%)'
        }}>
        <div className="top-bar-container">
            
            <div id="top-bar-content" className="flxDisp full-h">
                <div id="logo">
                    <img alt="logo" type="image/jpg" src="/media/images/Konektem1.png"/>
                </div>
                <div className="container links search flxDisp">
                    <div className="search-bar flxDisp">
                        <input id="tb-inp-el" className="input no-outln no-bdr full-w" name='search' placeholder='Chache...' />
                        <div id="tb-inp-div" className="input search-inp no-outln no-bdr full-w"><span>Chache</span></div>
                        <i className="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <div className="social evenFlx">
                    </div>
                </div>
                <div className="icon-container opts" onClick={()=>{}}>
                    <i className="fa-solid fa-bars icon opts-icon"></i>
                </div>
            </div>
        </div>
    </div>
    );
}