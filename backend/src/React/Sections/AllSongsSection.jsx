import { useEffect, useState } from "react";

function MusicGenre({tracks}){
    return (<></>);
}

export default function AllSongsSection({tracksList}){
    // fetch from api
    const [albumList, setAlbumList] = useState([]);
    return (<>
    <div class="grid tracks-grid box">
        {tracksList.map((album) => (<AlbumComponent key={album.id} album={album}/>))}
    </div>
    </>);
}