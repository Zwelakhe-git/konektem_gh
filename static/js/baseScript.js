//import Index from './views/index.js';
//import actuality from './views/actualityPage.js';
import musicAndVids from './views/musicPage.js';
import streaming from './views/streamingPage.js';
import emailSubPage from './views/emailSubPage.js';
import servicesPage from './views/services.js'; 
import eventsPage from './views/events.js';
//import {profLinkClickHandle} from '/account/profile.js';
import  contactsPage from './views/contactsPage.js';
import ticketFormPage from './views/ticketForm.js';
import serviceFormPage from './views/servicesForm.js';
import initInterviewsPage from './views/interviews-init.js';
import booksPage from './views/booksPage.js';
import {iconLinks, loadStyles} from "./fontawesome.js";
//import {cachedFetch} from './api/data-load.js';

const settings = {
    "contact": {},
    "footer": {},
    "social": {},
}
let scripts = [
    {
        type: "text/javascript",
        url: "/experimental/JS/vanish-on-scroll-panel.js"
    }
];
async function loadSettings(){
    try{
        for(const k of Object.keys(settings)){
            //const response = await fetch('/php/dbReader.php?q=siteSettings&group=' + k);
            const data = await cachedFetch('/php/dbReader.php?q=siteSettings&group=' + k, `siteSettings-g-${k}`);
            if(data.success){
                data.settings.forEach(s => {
                    settings[k][s['setting_key']] = s['setting_value'];
                });
            }
        }
    } catch(error){
        console.log(error.message);
    }
}
function loadScript(url){
    let tag = document.createElement('script');
    tag.src = url;
    document.body.appendChild(tag);
}

async function setTopBar(){
    let optsIcon = document.querySelector(".opts-icon");
    let closeIcon = document.querySelector("#nav-panel .close-btn");
    const navPanel = document.querySelector("#nav-panel");
    if(!optsIcon){
        console.log("Options icon not found");
        return;
    }
    
    closeIcon.addEventListener('click', toggleNavPanel);

    optsIcon.addEventListener("click", toggleNavPanel);
    const initPos = navPanel.style.top ?? window.getComputedStyle(navPanel).getPropertyValue('top');
    
    function toggleNavPanel(event = null){
        const navPanel = document.querySelector("#nav-panel");
        if( !navPanel ){
            console.log("nav-panel not found");
            return;
        }
        
        navPanel.classList.toggle("open");
        if(navPanel.classList.contains("open")){
            navPanel.style.display = "block";
            navPanel.style.maxHeight = `${navPanel.scrollHeight}px`;
            document.querySelector('#top-bar')?.style.setProperty('transform', 'translate(0%, 0%)');
        }
        else{
            navPanel.style.maxHeight = "0px";
            setTimeout(() => {
                navPanel.style.display = "none";
                document.querySelector('#top-bar')?.style.removeProperty('transform');
            }, 350);
        }
    }
    
    return;
}

async function footer(){
  loadStyles(iconLinks);
  let footerEl = document.createElement("footer");
  footerEl.id = "footer";
  footerEl.innerHTML = `
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>${settings.footer.site_title}</h3>
                    <p>${settings.footer.site_description}</p>
                </div>
                <div class="footer-section">
                    <h3>Kategori</h3>
                    <ul>
                        <li><a href="/?p=actuality">Politik</a></li>
                        <li><a href="/?p=actuality">Biznis</a></li>
                        <li><a href="/?p=actuality">Teknoloji</a></li>
                        <li><a href="/?p=actuality">Spo</a></li>
                        <li><a href="/?p=actuality">Divetisman</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Konekte m</h3>
                    <div class="social-icons">
                        <a href="${settings.social.facebook}"><i class="fab fa-facebook"></i></a>
                        <a href="${settings.social.twitter}"></i></a>
                        <a href="${settings.social.instagram}"><i class="fab fa-instagram"></i></a>
                    </div>
                    <div class="contacts&location" style="margin-top: 10px;">
                    	<ul>
                        	<li>
                            	<i class="fas fa-phone"></i>
                                <span>${settings.contact.phone}</span>
                            </li>
                            <li>
                            	<i class="fas fa-map-marker-alt"></i>
                                <span>14, Delmas 79, Village Daniel Roy., Delmas,HT6120 Haiti</span>
                            </li>
                            <li>
                            	<i class="fas fa-globe"></i>
                                <a href="/">https://konektem.net</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="footer-section">
                    <p>${settings.footer.subscription_invitation}</p>
                    <form method="POST" action="/php/dbReader.php?q=emailsub&content=news">
                        <input type="email" placeholder="konektemtv@gmail.com" style="padding:10px; width:100%; margin-top:10px; border-radius:4px; border:none;">
                        <button type="submit" style="background:#ffcc00; color:#1a4b8c; border:none; padding:10px 15px; margin-top:10px; border-radius:4px; font-weight:bold; cursor:pointer;">Abone</button>
                    </form>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; ${settings.footer.copyright}.</p>
            </div>
        </div>`;

    document.body.insertAdjacentElement("beforeend", footerEl);
}

// global variables
let innerW = window.innerWidth;
let indexContentLoaded = false;
let profileLinkHandleSet = false;

var searchParams = new URLSearchParams(window.location.search);
let availablePages =
{
    "actuality" : actuality,
    "music": musicAndVids,
    "streaming" : streaming,
    "emailsubscribtion" : emailSubPage,
    "payments" : function (){
        try{
            let entries = Object.fromEntries(searchParams.entries());
            let urlRequestString = Object.keys(entries).map(key => `${key}=${entries[key]}`).join('&');
            window.location.href = `https://konektem.net/paymentApplication/?${urlRequestString}`;
        } catch(error) {
            console.log("failed to redirect to payment page: ", error);
        }
    },
    "services": servicesPage,
    "events": eventsPage,
    "contacts": contactsPage,
    "buytickets": function (){
        const id = searchParams.get('id');
        if(id) ticketFormPage(id);
        else eventsPage();
    },
    "orderservice": function (){
        const id = searchParams.get('id');
        if(id) serviceFormPage(id);
        else servicesPage();
    },
    "interviews": initInterviewsPage,
    "books": booksPage
}

document.addEventListener("DOMContentLoaded", async () => {
    await loadSettings();
    setTopBar();
    profLinkClickHandle(profileLinkHandleSet);
    profileLinkHandleSet = true;
    scripts.forEach(js => {
        loadScript(js.url);
    });
    
    // Initialize mobile navigation
    initMobileNavigation();
    responsiveWindow();
    
    // highlight active page
    highlightActivePage();
    
    // Initialize search functionality
    initSearchFunctionality();
    
    if(searchParams.get("p")){
        try{
            availablePages[searchParams.get("p")]();
            //const searchParams = new URLSearchParams(window.location.search);
        } catch(error){
            console.log("invalid page name: " + error);
        }
        
    } else {
        //Index(indexContentLoaded);
        indexContentLoaded = true;
    }
    footer();
});

async function highlightActivePage(){
    const navLinksCollections = [document.querySelectorAll("#nav-panel .nav-link"), document.querySelectorAll("#nav-panel-bottom .nav-link")];
    if(navLinksCollections.length > 0){
        navLinksCollections.forEach(navLinks=>{
        	let target = Array.from(navLinks).find(lk => {
                let hr = lk.getAttribute("href");
                let url = `${window.location.protocol}//${window.location.host}${hr}`;
                let matchSp = (new URL(url)).searchParams;

                // only the index page can have 'p' absent
                return matchSp.get('p') == searchParams.get('p');
            });
            target?.classList.toggle("active");
        });
        
    } else{ console.log("no nav links"); }
}


