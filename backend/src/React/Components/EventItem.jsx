import React from "react";
import SafeHtmlRenderer from "../Utils/html-purifier";

export function EventItem({event}){
    return (
        <div className="event-card" data-itemid={event.id}>
            <div className="ev-container flx-disp col">
                <img src={event.image_location} alt="event-image" className="event-image grow" />
                <div className="event-content pad-5">
                    <div className="ticket-info">
                        <span className="ticket-price">Pri Tikè: ${event.price}</span>
                    </div>
                    <a href={`/?p=buytickets&f=event&id=${event.id}`} className="buy-btn">buy tickets</a>
                </div>
            </div>
            <div className="event-info flx-disp col full-h space-btwn events-description">
                <div className="no-ovrflw" style={{height: '80%'}}>
                    {event.description ? 
                    SafeHtmlRenderer(event.description) :
                    <p className="elipsis txt-lines-12 break-word pad-r5 pad-t20">no description</p>
                    }
                </div>
                <div className="intrvws-media-stats f1-s f6-b pad-5 flx-disp row bdr-top solid-bdr" style={{backgroundColor: '#ededed'}}>
                            <div className="gap-x">
                                <ion-icon className="" name="time-outline"></ion-icon>
                                <span>{event.eventDate}</span>
                            </div>
                            <div className="like-btn gap-x">
                                <i className="fa-regular fa-heart media-ico"
                                data-itemname="events"
                                data-itemid="{event.id}"></i>
                                <span>{event.likes ?? 0}</span>
                            </div>
                            <div className="share-btn">
                                <i className="fa-regular fa-paper-plane media-ico"
                                data-itemname="events"
                                data-itemid="{event.id}"></i>
                                <span>{event.shares ?? 0}</span>
                            </div>
                    </div>
            </div>
        </div>
    );
}