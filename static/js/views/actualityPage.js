import {cachedFetch} from '../api/data-load.js';
import {initOS} from '../onesignal-init.js';
// <div class='onesignal-customlink-container'></div>

const max_slide_news= 10;
// do not edit this function
function actualityHtml(newsData){
    let result = `<div class='container' id='current-news-root'></div>
    <div class="container all-N">
    <div class="breaking-news">
        <h2><i class="fas fa-bolt"></i> BREAKING NEWS</h2>
        <a href='#'><p class='wht-clr'>TOP STORY</p></a>
    </div>

    <div class="section-nav">
        <div class="container">
            <ul>
                <li><a href="#politics" class="active">Politik</a></li>
                <li><a href="#business">Biznis</a></li>
                <li><a href="#technology">Teknoloji</a></li>
                <li><a href="#sports">Spo</a></li>
                <li><a href="#entertainment">Amizman</a></li>
                <li><a href="#health">Sante</a></li>
                <li><a href="#science">Syans</a></li>
                <li><a href="#security">Sekirite</a></li>
                <li><a href="#society">Sosyete</a></li>
                <li><a href="#diplomatie">Diplomasi</a></li>
                <li><a href="#international">Entènasyonal</a></li>
                <li><a href="#economy">Ekonomi</a></li>
                <li><a href="#family">Fanmi</a></li>
                <li><a href="#culture">Kilti</a></li>
                <li><a href="#Music & Video">Mizik & Videyo</a></li>
                <li><a href="#cinema">Sinema</a></li>
                <li><a href="#mode">Mòd & Estil</a></li>
                <li><a href="#personality">Pèsonalite</a></li>
                <li><a href="#religion">Relijyon</a></li>
                <li><a href="#kitchen">Kizin & Resèt</a></li>
                <li><a href="#trip">Vwayaj</a></li>
                <li><a href="#education">Edikasyon</a></li>
            </ul>
        </div>
    </div>`;
    let newsCategories = [
        {
            name: "technology",
            content: `<section id="technology" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Teknoloji</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "business",
            content: `<section id="business" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Biznis</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "politics",
            content: `<section id="politics" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Politik</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "entertainment",
            content: `<section id="entertainment" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Amizman</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "sports",
            content: `<section id="sports" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Espo</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "health",
            content: `<section id="health" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Sante</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "science",
            content: `<section id="science" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Syans</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        //new 
        {
            name: "security",
            content: `<section id="security" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Sekirite</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "society",
            content: `<section id="society" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Sosyete</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "diplomatie",
            content: `<section id="diplomatie" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Diplomasi</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "international",
            content: `<section id="international" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Entènasyonal</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "economy",
            content: `<section id="economy" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Ekonomi</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "family",
            content: `<section id="family" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Fanmi</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "culture",
            content: `<section id="culture" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Kilti</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        //new again
         {
            name: "Music & Video",
            content: `<section id="Music & Video" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Mizik & Videyo</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "cinema",
            content: `<section id="cinema" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Sinema</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "mode",
            content: `<section id="mode" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Mòd & Estil</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "pesonnality",
            content: `<section id="pesonnality" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Pèsonalite</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        //new again again
         {
            name: "religion",
            content: `<section id="religion" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">relijyon</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "kitchen",
            content: `<section id="kitchen" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Kizin & Resèt</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "trip",
            content: `<section id="trip" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Vwayaj</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        },
        {
            name: "education",
            content: `<section id="education" class="news-section">
                        <div class="section-header">
                            <h2 class="section-title">Edikasyon</h2>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="news-grid">
                        `
        }
        
    ];
    
    for(let i = 0; i < newsData.length; ++i){
        let newsCard = `<div class="news-card" id="${newsData[i].id}">
                <div class="news-img">
                    <img src="${newsData[i].image_location}" alt="Politics news">
                </div>
                <div class="news-content">
                    <div class="news-date">${newsData[i].newsDate}</div>
                    <h3 class="news-title" data-newsid=${newsData[i].id}>${newsData[i].newsTitle}</h3>
                    <p class="news-excerpt">${newsData[i].newsHeadline}</p>
                    <div class="full-content">
                        ${newsData[i].fullContent}
                    </div>
                    <a class="read-more" data-state="more">Read More <i class="fas fa-chevron-down"></i></a>
                </div>
            </div>`;
        	for(let j = 0; j < newsCategories.length; ++j){
                let category = newsCategories[j];
                if(newsData[i].newsCategory == category.name){
                    category.content += newsCard;
                    break;
                }
            }
            newsCategories.forEach(category => {
                
            });
    }
    
    newsCategories.forEach(category => {
        category.content += `</div>
                    </section>`
        result += category.content;
    });
    
    return result + `</div>`;
}
function showMainNewsContent(newsId){
    let container = document.querySelector('#root');
    let allNewsContainer = document.querySelector('.all-N');
    if(!container){
        console.log('container not found');
        return;
    }
    
    let newsData = globNewsData.find(itm => itm.id == Number(newsId));
    let paragraphs = '';
        newsData.fullContent.split('\n').forEach(p => {
            if(p.trim().length > 0) paragraphs += `<p>${p}</p>`;
        });
    let content = `<div class='container main-news' style='color: black'>
                <div class="news-content">
                	<h3 class="news-title" data-newsid=${newsData.id} style="
                            color: black;
                            text-align: center;
                            font-size: 20px;
                        ">${newsData.newsTitle}</h3>
                	<div class="news-img" style="
                            margin-bottom: 10px;
                            border-radius: 8px;
                        ">
                        <img src="${newsData.image_location}" alt="Politics news">
                    </div>
                	<div class="news-date">${newsData.newsDate}</div>
                    <p class="news-excerpt" style="color: inherit;
                        max-height: fit-content;
                        ">${newsData.newsHeadline}</p>
                    <div class="main-Ncontent">
                    ${paragraphs}
                    </div>
                </div>
                </div>`;
    container.innerHTML = content;
}

function newsPageContentHandler(event){
    let n_id = event.CurrentTarget.dataset.newsid;
    showMainNewsContent(n_id);
}

function actualityPageStyle(){
    let styleTag = document.querySelector("#root-style");
    if(!styleTag){
        console.log("actualityPageStyle: style tag not found");
        return;
    }
    styleTag.innerHTML = `
`;

}


function actualityPageScript(){
    // Smooth scrolling for section navigation
    document.querySelectorAll('.section-nav a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            window.scrollTo({
                top: targetSection.offsetTop - 70,
                behavior: 'smooth'
            });
            
            // Update active class
            document.querySelectorAll('.section-nav a').forEach(a => a.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Update active nav link based on scroll position
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('.news-section');
        const navLinks = document.querySelectorAll('.section-nav a');
        
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (pageYOffset >= (sectionTop - 100)) {
                currentSection = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + currentSection) {
                link.classList.add('active');
            }
        });
    });
    
    // Read More functionality
    document.querySelectorAll('.read-more').forEach(button => {
        button.addEventListener('click', function() {
            const fullContent = this.previousElementSibling;
            const excerpt = fullContent.previousElementSibling;
            
            if (this.getAttribute('data-state') === 'more') {
                // Expand content
                fullContent.classList.add('expanded');
                excerpt.classList.add('expanded');
                this.innerHTML = 'Read Less <i class="fas fa-chevron-up"></i>';
                this.setAttribute('data-state', 'less');
            } else {
                // Collapse content
                fullContent.classList.remove('expanded');
                excerpt.classList.remove('expanded');
                this.innerHTML = 'Read More <i class="fas fa-chevron-down"></i>';
                this.setAttribute('data-state', 'more');
            }
        });
    });
}

var globNewsData = [];

export default function actuality(){
    // highlight nav link
    initOS();
    const searchParams = new URLSearchParams(window.location.search);
    let title = document.querySelector("title");
      if(!title){
          title = document.createElemnt("title");
          document.head.insertAdjacentElement("afterbegin", title);
      }
      title.textContent = "Actuality";
    
    let root = document.querySelector("#root");
    if(!root){
        console.log("actuality: root element not found");
        return;
    }
    actualityPageStyle();
    
    setTimeout(async () => {
        let data = await cachedFetch('/php/dbReader.php?r=news', 'newsData')
        globNewsData = data;
        root.innerHTML = actualityHtml(data);
        actualityPageScript();
        let url = new URL(window.location.href);
        let hash = url.hash;
        if(searchParams.get("id")){
            showMainNewsContent(searchParams.get("id"));
        }        
    },200);
}