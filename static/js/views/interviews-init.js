import {interviewsData} from './interviews-data.js';
import {interviewsSection} from './interviews-section.js';
import interviewPage from './interviewPage.js';


function readMoreFunctionality(){
    document.querySelectorAll('.read-more').forEach(button => {
        button.addEventListener('click', function() {
            const fullContent = this.previousElementSibling;
            const excerpt = fullContent.previousElementSibling;

            if (this.getAttribute('data-state') === 'more') {
                // Expand content
                fullContent.classList.add('expanded');
                excerpt.classList.add('expanded');
                this.innerHTML = 'Read Less <i class="fa-solid fa-chevron-up"></i>';
                this.setAttribute('data-state', 'less');
            } else {
                // Collapse content
                fullContent.classList.remove('expanded');
                excerpt.classList.remove('expanded');
                this.innerHTML = 'Read More <i class="fa-solid fa-chevron-down"></i>';
                this.setAttribute('data-state', 'more');
            }
        });
    });
}
function initInterviewsPage() {
    const searchParams = new URLSearchParams(window.location.search);
    if(searchParams.get('id')){
        interviewPage();
        return;
    }
        
    let root = document.querySelector("#root");
    if(!root){
        console.log("interviews: root element not found");
        return;
    }
    
    // Ajouter les styles si nécessaire
    if (!document.querySelector('#interviews-styles')) {
        const styleLink = document.createElement('link');
        styleLink.rel = 'stylesheet';
        styleLink.href = '/experimental/CSS/interviews-styles.css';
        styleLink.id = 'interviews-styles';
        document.head.appendChild(styleLink);
    }
    
    root.innerHTML = interviewsSection(interviewsData);
    
    // Ajouter les event listeners
    setTimeout(() => {
        readMoreFunctionality();
        const shareButtons = document.querySelectorAll('.share-btn');
        shareButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Fonctionnalité de partage
                if (navigator.share) {
                    navigator.share({
                        title: 'Entèvyou - Konektem',
                        text: 'Gade entèvyou sa a sou Konektem',
                        url: window.location.href
                    });
                } else {
                    alert('Pataje entèvyou sa a ak zanmi ou!');
                }
            });
        });
    }, 100);
}

// Exporter pour utilisation
export default initInterviewsPage;