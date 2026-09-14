import React, { useState, useEffect } from "react";
import AlbumsSection from "./Sections/AlbumsSection";
import AllSongsSection from "./Sections/AllSongsSection";

export function MusicPage(){
    const [currentSection, setCurrentSection] = useState(1);
    return (<>
    {Array.from(Object.entries(sections)).map((Comp, secIndx)=>{
        return currentSection === secIndx && <Comp />
    })}
    </>);
}