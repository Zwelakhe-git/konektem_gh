import { useState, useEffect } from "react";

export default function AlbumComponent({album, setPreview, setCurrentPreviewAlbumId}){
    const previewAlbum = ()=>{
        setCurrentPreviewAlbumId(album.id);
        setPreview(true);
    }
    return (<>
    <div className="album-card card box" onClick={previewAlbum}>
        <div className="card-header">
            <div className="image-container album-image full">
                <img className="w-full" alt="image" src={album.cover_image_url}/>
            </div>
        </div>
        <div className="card-body">
            <div className="album-description">
                <span className="album-name">{album.name}</span>
                <span className="album-song-count">{album.songs_count}</span>
                <span className="album-year">{album.release_year}</span>
            </div>
        </div>
        <div className="card-footer">
            <div className="artist-info">
                <div className="image-container">
                    <img alt="image" src={album.artist.avatar_url ?? ''}/>
                </div>
                <span>{album.artist.name ?? 'unknown artist'}</span>
            </div>
        </div>
    </div>
    </>);
}