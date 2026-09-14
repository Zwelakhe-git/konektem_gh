import React from "react";
import SafeHtmlRenderer from "../Utils/html-purifier";

export function InterviewItem({interview}){
    return (
        <div className='intrvw-itm no-shrink full-wh' data-itemid="{interview.id}">
            <div className='full-wh' style={{overflow: 'hidden'}}>
                <div className='full-wh flx-disp row bdr-8' style={{backgroundColor: '#ededed'}}>
                    <a className=''>
                        <div className='left-img bg-white pad-5 abs-img-cont'>
                            <img alt='image' className="full-wh bdr-8" src={interview.image_location}/>
                        </div>
                    </a>
                    <div className="flx-disp space-btwn col intrvw-descrp no-ovrflw" >
                        <div className="elipsis">
                            <b className="break-word">{interview.title}</b>
                            {SafeHtmlRenderer(interview.description)}
                        </div>
                        <div className="intrvws-media-stats f1-s f6-b pad-5 flx-disp row bdr-top solid-bdr" style={{backgroundColor: '#ededed'}}>
                                <div className="gap-x">
                                    <i className="fa-regular fa-clock"></i>
                                    <span>{interview.created_at.split(' ')[0]}</span>
                                </div>
                                <div className="like-btn gap-x">
                                    <i className="fa-regular fa-heart media-ico" data-itemname="interviews" data-itemid="{interview.id}"></i>
                                    <span>{interview.likes}</span>
                                </div>
                                <div className="share-btn">
                                    <i className="fa-regular fa-paper-plane media-ico" data-itemname="interviews" data-itemid="{interview.id}"></i>
                                    <span>{interview.shares}</span>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}