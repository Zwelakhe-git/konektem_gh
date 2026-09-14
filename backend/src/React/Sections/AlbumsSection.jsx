import { useEffect, useState } from "react";
import AlbumComponent from "../Components/AlbumComponent";

export default function AlbumsSection(){
    // fetch from api
    const [albumList, setAlbumList] = useState([]);
    const [preview, setPreview] = useState(false);
    const [currentPreviewAlbumId, setCurrentPreviewAlbumId] = useState(-1);
    return (<>
    {preview ?
    (<AlbumPreview album={albumList.find(alb => alb.id === currentPreviewAlbumId)}/>) : 
    (<div class="grid albums-grid box">
        {albumList.map((album) => (<AlbumComponent key={album.id} album={album}
                                        setPreview={setPreview}
                                        setCurrentPreviewAlbumId={setCurrentPreviewAlbumId}/>
                                    ))
        }
    </div>)
    }
    </>);
}

function AlbumPreview({album}){
    return (<>
    <div className="flex album-preview"></div>
    {album ?
    (<div >
        <div className="flex">
            <div className="left-panel">
                <div className="image-container">
                    <img alt="image" src={album.image_url}/>
                </div>
                <div className="album-info">
                    <span class="obj-key">Title: {album.name}</span>
                    <span class="obj-key">Artist: {album.artist}</span>
                    <span class="obj-key">Release Year: {album.release_year}</span>
                    <span className="obj-key">Songs: {album.songs_count}</span>
                </div>
                <div className="album-actions">
                    <div className="download-btn">
                        <span>Download Full Album</span>
                        <i className="fa-solid fa-download"></i>
                    </div>
                    <div >
                        <span>View Songs</span>
                        <i className="fa-solid fa-music"></i>
                    </div>
                </div>
            </div>
            <div className="right-panel">
                <div className="album-description">
                    {album.description}
                </div>
            </div>
        </div>
        <div className="tracks-section">
            <div className="section-info">
                <h1>Songs</h1>
            </div>
            <div className="tracks-grid">
                {album.tracks.map(track => (<></>))}
            </div>
        </div>
    </div>) :
    (<div></div>)}
    </>)
}