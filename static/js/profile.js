import {base64UrlDecode} from './utils.js';

export default function updateProfileUI(){
    try{
        let icon = document.querySelector('#nav-panel .user-prof');
        let profileText = document.querySelector('#nav-panel .user-id');
        let bottomProfileIcon = document.querySelector('#nav-panel-bottom .profile');
        //modify the login
        let token = localStorage.getItem('token');
        let user = JSON.parse(token ? (base64UrlDecode(token.split('.')[1]) ?? '{}') : '{}');
        if(user){
            icon?.classList.remove('fa-arrow-right-from-bracket');
            icon?.classList.add('fa-user-plus');
            profileText.textContent = 'Konekte/Enskri';
            bottomProfileIcon.classList.remove('online');
        } else {
            user = JSON.parse('user');
            icon?.classList.remove('fa-user-plus');
            icon?.classList.add('fa-circle-user');
            profileText.textContent = user.name + "/profile";
            bottomProfileIcon.classList.add('online');
        }
    } catch(error){
        console.error(error.message);
    }
}

export async function profLinkClickHandle(handleSet){
    if(handleSet) return;
    try{
       
        let clickHandler = async () => {
            if(!sessionStorage.getItem('user')){
                window.location.href = '/konektem/auth/login';
            } else{
                window.location.href = '/konektem/user/me';
            }
        }
        
        updateProfileUI();
        let profileLink = document.querySelector('#prof-link');
        let topBarProfIcon = document.querySelector("#top-bar .icon-container .prof-icon");
        profileLink?.addEventListener('click', clickHandler);
        topBarProfIcon?.addEventListener('click', clickHandler);
        
    } catch(error){
        console.error(error.message);
    }
}

