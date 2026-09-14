import React, { useState, useEffect, useLayoutEffect } from "react";

import { FadeArticle } from "../Components/FadeArticle";
import { InterviewItem } from "../Components/InterviewItem";
import { EventItem } from "../Components/EventItem";
import { MainTrackItem } from "../Components/MainTrackItem";
import { MainBookItem } from "../Components/MainBookItem";
import { PartnerItem } from "../Components/PartnerItem";
import {WhatsappWidget} from "../Components/WhatsappWidget";


/*import "@styles/mobile.css";
import "@styles/sections.css";
import "@styles/desktop.css";
import "@styles/bottomScrollstyle.css";
import "@styles/customWindows.css";*/

function MainNewsSlide({articles}){
    const [currentSlideIndx, setCurrentSlideIndx] = useState(0);

    return (
    <div id="head" className="section container-outer pad-20 white-bg bdr-box">
        <div id="news-slides" data-itempage="actuality">
        {articles.map(article => (
            <div key={article.id} className="slide-image-container full-w-h" data-itemid={article.id}>
                <img alt="news image"  src={article.image_location} className="slide-image cvr full-w" />
                <p className="slide-img-desc">{ article.newsTitle }</p>
            </div>
        ))}
        </div>
        <div className="scroll-btn right">
            <i className="fa-solid fa-chevron-right"></i>
        </div>
        <div className="scroll-btn left">
            <i className="fa-solid fa-chevron-left"></i>
        </div>
        <a href={`/?p=actuality&id=${articles[0]?.id}`} id="slide-link" className="link no-dec slide-link">
            <div className="more-actions beep-anim">
                <span>Wè Plis</span>
                <i className="fa-solid fa-chevron-right"></i>
            </div>
        </a>
    </div>
    );
}

function MainNewsFade({articles}){
    const [currentItemIndx, setCurrentItemIndx] = useState(0);

    return (
    <div id="last-news" className="section shelf-anim margin-rl-1 container-outer pad-20 white-bg bdr-box">
        <div className="section-info">
        <a href="/?p=actuality">
            <h1>DÈNYE NOUVÈL</h1>
        </a>
        </div>
        <div className="container-inner full-w" style={{paddingTop: '0px', paddingLeft: '0px', paddingRight: '0px'}}>
            <div id="fade-articles-list" className="articles white-bg bdr-box">
            {articles.map(article => (
                <FadeArticle key={article.id} article={article}/>
            ))}
            </div>
            <a id='fade-news-link' href={`/?p=actuality&id=${articles[0]?.id}`} className='fade-link' style={{paddingLeft: '15px'}}>
                <div className="more-actions beep-anim">
                    <span>Wè Plis</span>
                    <i className="fa-solid fa-chevron-right"></i>
                </div>
            </a>
        </div>
    </div>
    );
}


function Interviews({interviews}){
    const [currentItemIndx, setCurrentItemIndx] = useState(0);

    return (
    <div className="section intrvw-cont shelf-anim margin-rl-1 container-outer pad-20 white-bg bdr-box">
        <div className="section-info">
            <a>
                <h1>ENTÈVYOU</h1>
            </a>
        </div>
        <div className="container-inner full-wh" style={{paddingTop: '0px', paddingLeft: '0px', paddingRight: '0px'}}>
            <div id="intrvws-list" data-itempage="interviews" className="intrvws no-ovrflw white-bg flx-disp row bdr-box">
                {interviews.map(interview => (
                    <InterviewItem key={interview.id} interview={interview}/>
                ))}
            </div>
            <a href={`/?p=interviews&id=${interviews[0]?.id}`} style={{paddingLeft: '15px'}} className="slide-link">
                <div className="more-actions beep-anim">
                    <span>Wè Plis</span>
                    <i className="fa-solid fa-chevron-right"></i>
                </div>
            </a>
        </div>
    </div>
    );
}


function Events({events}){
    return (
    <div id="events-section" className="section shelf-anim margin-rl-1 colDir container-outer pad-20 white-bg bdr-box">
        <div id="events-section-bg" className="full-h"></div>
        <div className="section-info">
            <a href="/?p=events"><h1>EVENEMAN</h1></a>
        </div>
        <div id="events-list" data-itempage="events" className="container-inner">
            {events.map(event => (
                <EventItem key={event.id} event={event}/>
            ))}
        </div>
        <a href="/?p=events" className='fade-link'>
            <div className="more-actions beep-anim">
                <span>Wè Plis</span>
                <i className="fa-solid fa-chevron-right"></i>
            </div>
        </a>
    </div>
    );
}


function Music({tracks}){
    return (
    <div id="middle-panel" className="section shelf-anim margin-rl-1 colDir container-outer pad-20 white-bg bdr-box">
        <div className="section-info">
            <a href="/?p=music">
            <h1>
                PLEYLIS
            </h1>
            </a>
        </div>
        <div id="music-charts-video" className="flxDisp colDir container-inner">
            <div className="media-row-content charts">
                <div className="section-info charts-title">
                    <h2>TOP CHARTS</h2>
                </div>
                <div className="audio-charts flxDisp">
                    {tracks.map(track => (
                        <MainTrackItem key={track.id} track={track}/>
                    ))}
                </div>
                <a href="/?p=music">
                    <div className="more-actions beep-anim">
                        <span>Wè Plis</span>
                        <i className="fa-solid fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>
        <audio id="audio-player"></audio>
    </div>
    );
}


function KonektemVideo(){
    useEffect(()=>{
        // this function wont be called if the IFrame has already been created
        // in order to use this function we need to put this api https://www.youtube.com/iframe_api into a script tag
        // the same way we do with tinymce
        window.onYouTubeIframeAPIReady = function() {
            const container = document.querySelector("#rp-vid-container");
            let player = new YT.Player(container, {
                videoId: "qUfA_j2weEI",
                playerVars: {
                    muted: 1,
                    autoplay: 0,
                    controls: 1,
                    playsinline: 1
                },
                events: {
                    onReady: onPlayerReady
                }
            });
        }
    }, );
    
    const onPlayerReady = function() {
        //
    }
    return (
    <div id="right-panel" className="section shelf-anim margin-rl-1 container-outer pad-20 white-bg bdr-box">
        <div id="essentials-section">
        <div className="section-info">
            <a href="/?p=actuality"><h1>VIDEYO</h1></a>
        </div>
        <div id="link-portrait-video">
            <div className="vid-container">
                <div id="rp-vid-container" className="html5-vid">
                    <iframe className="full-wh" src="https://www.youtube.com/embed/qUfA_j2weEI?si=FRr-x22LFESfNGN0"
                            title="YouTube video player"
                            frameBorder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            referrerPolicy="strict-origin-when-cross-origin" 
                            allowFullScreen>
                    </iframe>
                </div>
            </div>
        </div>
        </div>
    </div>
    );
}

function Books({books}){
    
    return (
    <div className="section shelf-anim margin-rl-1 container-outer pad-20 white-bg bdr-box">
        <div className="section-info">
        <a href=""><h1>LIV</h1></a>
        </div>
        <div className='books-container container-inner'>
            <div id="books-list" data-itempage="books" className="no-ovrflw  full-w flx-disp row">
                {books.map(book => (
                    <MainBookItem key={book.id} book={book}/>
                ))}
            </div>
            <button className='scroll-btn left' type='button'>
                <i className="fa-solid fa-chevron-left"></i>
            </button>
            <button className='scroll-btn right' type='button'>
                <i className="fa-solid fa-chevron-right"></i>
            </button>
            <a href={`/?p=books&id=${books[0]?.id}`} className="slide-link">
                <div className="more-actions beep-anim">
                <span>Wè Plis</span>
                <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>
            </a>
        </div>
    </div>
    );
}

export function Main(){
    const [articles, setArticles] = useState([]);
    const [books, setBooks] = useState([]);
    const [interviews, setInterviews] = useState([]);
    const [events, setEvents] = useState([]);
    const [tracks, setTracks] = useState([]);
    const [partners, setPartners] = useState([]);

    useEffect(()=>{
        const load = async ()=>{
            const data = await import("@/api/mock-data.json");
            setArticles(data.news);
            setBooks(data.books);
            setInterviews(data.interviews);
            setEvents(data.events);
            setTracks(data.music);
            setPartners(data.partners);
        }
        load();
    },);
    
    return (
        <>
        <MainNewsSlide articles={articles.filter(article => article.position === 'mpnews_slide')}/>,
        <MainNewsFade articles={articles.filter(article => article.position === 'mpnews_fade')}/>,
        <Interviews interviews={interviews}/>,
        <Events events={events}/>,
        <Music tracks={tracks.filter(track => track.position === 'mainpage').slice(0, 10)}/>,
        <KonektemVideo />,
        <Books books={books.slice(0,10)}/>
        <PartnerItem partners={partners}/>
        <WhatsappWidget />
        </>
    );
}