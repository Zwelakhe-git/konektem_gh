import React from "react";


export function MainTrackItem({track}){
    return (
    <div className="track"
        id="track-{track.id}"
        data-trackid="{track.id}" data-itemid="{track.id}">
        <div className="track-img">
            <img className="full-w-h" alt="track image" src={track.image_location} />
        </div>
        <div className="music-info">
            <div className="music-title">{track.track_name}</div>
            <div className="music-artist">{track.artist_name}</div>
            <div className="player-controls">
                <div className="play-btn" data-trackid={track.id} data-itemid={track.id}>
                    <i className="fas fa-play media-ico play"
                    data-trackid={track.id} data-itemid={track.id}
                    data-src={track.location}
                    data-tracktitle={track.track_name}></i>
                </div>
                <div className="progress-bar">
                    <div className={`progress track-${track.id}`}></div>
                </div>
                <div className="music-duration track-{track.id}"></div>
            </div>
            <div className="media-actions">
                <div className="action-btn download-btn">
                    <i className="fas fa-download media-ico"
                    data-itemname="music"
                    data-trackid="{track.id}"
                    data-itemid="{track.id}" 
                    data-tracktitle="{track.track_name}"></i>
                    <span>{track.downloads}</span>
                    <a id="dd-a{track.id}" download="{track.track_name}"
                    data-href="{track.location}" style={{display: 'none'}}></a>
                </div>
                <div className="action-btn like-btn">
                    <i className="fa-regular fa-heart media-ico"
                    data-itemname="music"
                    data-trackid="{track.id}"
                    data-itemid="{track.id}"
                    data-tracktitle="{track.track_name}"></i>
                    <span>{track.likes}</span>
                </div>
                <div className="action-btn share-btn">
                    <i className="fa-regular fa-paper-plane media-ico"
                    data-itemname="music" 
                    data-itemid="{track.id}"
                    data-trackid="{track.id}"></i>
                    <span>{track.shares ?? 0}</span>
                </div>
            </div>
            <span style={{fontSize: '5px', position: 'absolute', right: '0px'}}>{track.owner ?? ''}</span>
        </div>                                            
    </div>
    );
}