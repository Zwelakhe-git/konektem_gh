import React from "react";
import SafeHtmlRenderer from "../Utils/html-purifier";

export function MainBookItem({book}){
    return (
    <div className="book-item no-shrink full-wh flx-disp space-btwn" data-itemid="{book.id}">
        <div className="book-img" style={{width: '48%'}}>
            <div className="full-wh">
                <img className="full-wh" alt="book-image" src={book.image_location}
                style={{borderRadius: '10px'}}/>
            </div>
        </div>
        <div className="book-info flx-disp col full-h space-btwn no-ovrflw events-description" style={{width: '50%'}}>
            <div className="no-ovrflw" style={{height: '80%'}}>
                <div className="book-title" style={{marginBottom: '10px'}}>
                    <h3>{book.title}</h3>
                </div>
                <div className="book-desc" style={{overflowY: 'auto', height: '100%'}}>
                    {SafeHtmlRenderer(book.description)}
                </div>
            </div>
            <div className="book-media-stats f1-s f6-b flx-disp pad-5 row bdr-top solid-bdr">
                <div className="book-read">
                    <i className="fa-regular fa-eye" data-itemid="{book.id}" ></i>
                    <span>{book.reads ?? 0}</span>
                </div>
                <div className="book-like -btn">
                    <i className="fa-solid fa-link media-ico" data-itemname="books"
                        data-itemid="{book.id}"></i>
                </div>
                <div className="book-share share-btn">
                    <i className="fa-regular fa-paper-plane media-ico" data-itemname="books"
                        data-itemid="{book.id}"></i>
                    <span>{book.shares ?? 0}</span>
                </div>
            </div>
        </div>
    </div>
    );
}