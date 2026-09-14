import React, { useEffect, useState } from "react";

export function WhatsappWidget(){
    const whatsappData = {
        "phoneNumber" : "7282143510",
        "welcomeMessage" : "Hello! I'm interested in your services.",
        "countryCodes" : [
            { "code"  : "+1", "country": "US" },
            { "code": "+44", "country": "UK" },
            { "code": "+91", "country": "IN" },
            { "code": "+33", "country": "FR" },
            { "code": "+49", "country": "DE" },
            { "code": "+7",  "country": "RU" }
            // Add more country codes as needed
        ]
    };
    const [showPopUp, setShowPopUp] = useState(false);
    const [countryCode, setCountryCode] = useState('');
    const [phoneNumber, setPhoneNumber] = useState(whatsappData.phoneNumber);


    const sendWhatsapp = ()=>{
        const fullNumber = countryCode + phoneNumber.replace(/\D/g, '');
        const message = encodeURIComponent(whatsappData.welcomeMessage);
        const whatsappUrl = `https://wa.me/${fullNumber}?text=${message}`;
        
        window.open(whatsappUrl, '_blank');
        
        setShowPopUp(false);
    }
    return (
    <div className="whatsapp-widget">
        <div className="whatsapp-button" id="whatsappToggle" onClick={()=>{setShowPopUp(!showPopUp);}}>
            <i className="fab fa-whatsapp"></i>
        </div>
        
        { showPopUp && <div className="whatsapp-popup" id="whatsappPopup" style={{display: 'flex'}}>
            <div className="whatsapp-header">
                <h3>Contact Us on WhatsApp</h3>
                <button className="close-whatsapp" id="closeWhatsapp" onClick={()=>{setShowPopUp(false);}}>
                    <i className="fas fa-times"></i>
                </button>
            </div>
            
            <div className="whatsapp-content">
                <p>Send us a message directly on WhatsApp. We typically respond within minutes.</p>
                
                <div className="whatsapp-number">
                    <select id="countryCode" onChange={(e)=>{setCountryCode(e.target.value)}}>
                        { whatsappData.countryCodes.map((code, indx) => {
                            <option key={indx} value={code.code} >
                                {code.code} ({code.country})
                            </option>
                            })
                        }
                    </select>
                    <input type="text" id="phoneNumber" placeholder="Phone number" value={phoneNumber} onChange={(e)=>{setPhoneNumber(e.target.value)}}/>
                </div>
                
                <div className="whatsapp-actions">
                    <button className="whatsapp-btn whatsapp-primary" id="sendWhatsapp" onClick={sendWhatsapp}>
                        <i className="fab fa-whatsapp"></i> Send Message
                    </button>
                    <button className="whatsapp-btn whatsapp-secondary" id="cancelWhatsapp" onClick={()=>{setShowPopUp(false);}}>
                        Cancel
                    </button>
                </div>
            </div>
        </div>}
    </div>
    );
}