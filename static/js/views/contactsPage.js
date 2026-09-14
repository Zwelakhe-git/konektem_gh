
const contactData = {
    companyName: "Kontak",
    description: "Kontakte nou nan youn nan fòm sa yo",
    contactInfo: [
        {
            type: "address",
            icon: "fas fa-map-marker-alt",
            title: "Adrès:",
            content: "14, Delmas 79, Village Daniel Roy., Delmas,HT6120<br>Haiti",
            link: "#"
        },
        {
            type: "telefòn",
            icon: "fas fa-phone",
            title: "telefòn",
            content: "+1 (728)2143510",
            link: "tel:+1 7282143510"
        },
        {
            type: "imèl",
            icon: "fas fa-envelope",
            title: "imèl",
            content: "konektemtv@gmail.com",
            link: "mailto:konektemtv@gmail.com"
        },
        {
            type: "website",
            icon: "fas fa-globe",
            title: "sit nou",
            content: "https://konektem.net",
            link: "https://Konektem.net"
        }
    ],
    mapText: "Interaktif Map - Lokalizasyon nou"
};

// Contact section function
function contactSection(contactData) {
    let contactCards = '';
    for(let index = 0; index < contactData.contactInfo.length; ++index) {
        contactCards += `
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="${contactData.contactInfo[index].icon}"></i>
                    </div>
                    <h3>${contactData.contactInfo[index].title}</h3>
                    <p>${contactData.contactInfo[index].content}</p>
                </div>`;
    }

    return `<div id="contact-section-container">
            <div id="contact-section" class="margin-rl-1 colDir container-outer pad-20 white-bg bdr-box">
                <div id="contact-section-bg" class="full-h"></div>
                <div class="section-info">
                    <h1> ${contactData.companyName.toUpperCase()}</h1>
                    <p>${contactData.description}</p>
                </div>
                <div id="contact-cards" class="container-inner">
                    ${contactCards}
                </div>
                <div class="map-container">
                    <div class="map-placeholder">
                        ${contactData.mapText}
                    </div>
                </div>
                <a href="/">
                    <div class="more-actions">
                        <span>Back to Home</span>
                        <ion-icon name="arrow-back-outline"></ion-icon>
                    </div>
                </a>
            </div>
            </div>`;
}

function pageInnerStyle(){
    const rootPageStyle = document.querySelector("#root-style");
    if(!rootPageStyle){
        console.log("contact page: no style container");
        return;
    }
    rootPageStyle.innerHTML = `
    `;
}


// Initialize contact section
export default function contactsPage() {
    let root = document.querySelector("#root");
    if(!root){
        console.log("contacts: root element not found");
        return;
    }
    pageInnerStyle();
    root.innerHTML = contactSection(contactData);
}
