import React, { useState, useEffect, useRef } from "react";


export function Footer({}){
    const [settings, setSettings] = useState({});
    const settingsGroups = useRef(["footer", "social", "contact"]);
    
    useEffect(()=>{
        const load = async ()=>{
            const data = await import("@/api/mock-data.json");
            const grouped = data.siteSettings.reduce((acc, setting) => {
                if(!acc[setting.setting_group]){
                    acc[setting.setting_group] = {};
                }
                acc[setting.setting_group][setting.setting_key] = setting.setting_value;
                return acc;
            }, {});

            const result = settingsGroups.current.reduce((obj, g)=>{
                if(grouped[g]){
                    obj[g] = grouped[g];
                }
                return obj;
            }, {})

            setSettings(result);
        }
        load();
    },);
    return (
    <footer id="footer">
        <div className="container">
            <div className="footer-content">
                <div className="footer-section">
                    <h3>{settings.footer?.site_title}</h3>
                    <p>{settings.footer?.site_description}</p>
                </div>
                <div className="footer-section">
                    <h3>Kategori</h3>
                    <ul>
                        <li><a href="/?p=actuality">Politik</a></li>
                        <li><a href="/?p=actuality">Biznis</a></li>
                        <li><a href="/?p=actuality">Teknoloji</a></li>
                        <li><a href="/?p=actuality">Spo</a></li>
                        <li><a href="/?p=actuality">Divetisman</a></li>
                    </ul>
                </div>
                <div className="footer-section">
                    <h3>Konekte m</h3>
                    <div className="social-icons">
                        <a href={settings.social?.facebook}><i className="fab fa-facebook"></i></a>
                        <a href={settings.social?.twitter}></a>
                        <a href={settings.social?.instagram}><i className="fab fa-instagram"></i></a>
                    </div>
                    <div className="contacts&location" style={{marginTop: '10px'}}>
                        <ul>
                            <li>
                                <i className="fas fa-phone"></i>
                                <span>{settings.contact?.phone}</span>
                            </li>
                            <li>
                                <i className="fas fa-map-marker-alt"></i>
                                <span>14, Delmas 79, Village Daniel Roy., Delmas,HT6120 Haiti</span>
                            </li>
                            <li>
                                <i className="fas fa-globe"></i>
                                <a href="/">https://konektem.net</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div className="footer-section">
                    <p>{settings.footer?.subscription_invitation}</p>
                    <form method="POST" action="/php/dbReader.php?q=emailsub&content=news">
                        <input type="email" placeholder="konektemtv@gmail.com" style={{padding: '10px', width: '100%', marginTop: '10px', borderRadius: '4px', border: 'none'}}/>
                        <button type="submit" style={{background: '#ffcc00', color: '#1a4b8c', border: 'none', padding: '10px 15px', marginTop: '10px', borderRadius: '4px', fontWeight: 'bold', cursor: 'pointer'}}>Abone</button>
                    </form>
                </div>
            </div>
            <div className="copyright">
                <p>&copy; {settings.footer?.copyright}.</p>
            </div>
        </div>
    </footer>
    );
}