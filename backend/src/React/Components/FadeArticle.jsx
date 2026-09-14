import React from "react";


export function FadeArticle({article}){
    return (
        <div className='artcl-itm full-wh' data-itemid={article.id}>
            <div className='artcl-cont full-wh'>
            <div className='artcl-info full-wh'>
                <a href={`/?p=actuality&id=${article.id}`} className='img-link'>
                    <div className='artcl-img'>
                    <img alt='article img' className="full-wh" src='{article.image_location}'/>
                </div>
                </a>
                <div className="artcl-text flx-disp col space-btwn">
                    <div className="artcl-text-content elipsis no-ovrflw">
                        <span className="line">
                            <b className="break-word">{article.newsTitle}</b> {article.newsHeadline}
                        </span>
                    </div>
                    <div className="artcl-media-stats f1-s f6-b flx-disp row">
                        <div className="artcl-date">
                            <i className="fa-regular fa-clock"></i>
                            <span>{article.newsDate}</span>
                        </div>
                        
                        <div className="artcl-like like-btn">
                            <i className="fa-regular fa-heart media-ico" data-itemname="actuality" data-itemid="{article.id}"></i>
                            <span>{article.likes ?? 0}</span>
                        </div>
                        <div className="artcl-share share-btn">
                            <i className="fa-regular fa-paper-plane media-ico" data-itemname="actuality" data-itemid="{article.id}"></i>
                            <span>{article.shares ?? 0}</span>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    )
}