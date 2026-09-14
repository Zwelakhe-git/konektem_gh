import React from "react";

export function PartnerItem({partners}){
    return (
    <div id="partners-panel" className="shelf-anim margin-rl-1 container-outer pad-20 white-bg bdr-box">
        <div className="section-info">
            <h1>PATNÈ NOU YO</h1>
        </div>
        <div id="partners-list" className="partners">
            {partners.map((partner, indx) => (
                <div key={indx} className={`partner-img-cont ${indx < 2 && 'small'}` }>
                    {/* style={{width: '63%', height: '63%'}} {i == 1 && } */}
                    <img src={partner.image_location} /> 
                </div>
            ))}
        </div>
    </div>
    );
}