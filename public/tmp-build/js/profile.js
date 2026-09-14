
function updateProfileUI(){
    try{
        let icon = document.querySelector('#nav-panel .user-prof');
        let profileText = document.querySelector('#nav-panel .user-id');
        let bottomProfileIcon = document.querySelector('#nav-panel-bottom .profile');
        //modify the login
        
        if(!window.session.name || window.session.name === 'guest'){
            icon?.classList.remove('fa-arrow-right-from-bracket');
            icon?.classList.add('fa-user-plus');
            profileText.textContent = 'Konekte/Enskri';
            bottomProfileIcon.classList.remove('online');
        } else {
            icon?.classList.remove('fa-user-plus');
            icon?.classList.add('fa-circle-user');
            profileText.textContent = window.session.name + "/profile";
            bottomProfileIcon.classList.add('online');
        }
    } catch(error){
        console.log(error.message);
    }
}

export async function profLinkClickHandle(handleSet){
    if(handleSet) return;
    try{
        //localStorage.getItem('currentUser');
        
        let session = sessionStorage.getItem('user');
        let response = await fetch('/php/dbReader.php?q=uid');
        let user = await response.json();
        if(session === "[object Object]"){
            session = { name: user.name};
        } else {
            session = JSON.parse(session);
        }
        if(!session || session.name !== user.name){
            session = { name: user.name };
            sessionStorage.setItem('user', JSON.stringify(session));
        }
        /**
        * the handler is obsolete
        */
        let clickHandler = async () => {
            if(!window.session.name || window.session.name === 'guest'){
                window.location.href = '/account/login.html';
            } else{
                window.location.href = '/account/me/index.php';
            }
        }
        window.session = session;
        updateProfileUI();
        let profileLink = document.querySelector('#prof-link');
        let topBarProfIcon = document.querySelector("#top-bar .icon-container .prof-icon");
        profileLink?.addEventListener('click', clickHandler);
        topBarProfIcon?.addEventListener('click', clickHandler);
        
    } catch(error){
        console.log(error.message);
    }
}

function updateProfile(username){}

export default updateProfileUI;
