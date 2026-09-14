import { InfFreeFetch} from './InfFreeUtils.js';
import userAuthPage from './authentication.js';

const BASE_URL = '';
let user = JSON.parse(sessionStorage.getItem('user') ?? '{}');
let streamId = null;

function promptUserToSub(){
    let regForm = `
    <div class="btn-div blu">SUBSCRIBE</div>
    <div class="btn-div grn">LOGIN</div>`;
    let regPage = document.createElement("div");
    regPage.id = "reg-page";
    regPage.innerHTML = regForm;
    let bodyContainer = document.querySelector("#body");
    if(!bodyContainer){
        bodyContainer = document.body;
    }
    bodyContainer.appendChild(regPage);
}

async function registrationForm(){
    let regForm = `
    <form id="regForm">
    <div class="field">
        <label>username</label>
        <input type="text" name="login" placeholder="name" required/>
    </div>
    <div class="field">
        <label>email</label>
        <input type="email" name="email" placeholder="email" required/>
    </div>
    <div class="field">
        <label>Password</label>
        <input name="password" type="password" placeholder="password" required/>
    </div>
    <div controls>
        <button type="button" id="send-btn">subscribe</button>
        <button type="button" id="login-btn">already have an account</button>
        <button type="button" id="cancel-btn">Cancel</button>
        
    </div>
    </form>`;
    let regPage = document.createElement("div");
    regPage.id = "reg-page";
    regPage.innerHTML = regForm;
    let bodyContainer = document.querySelector("#body");
    if(!bodyContainer){
        bodyContainer = document.body;
    }
    bodyContainer.appendChild(regPage);
    setTimeout(()=>{
        let cancelBtn = document.querySelector("#cancel-btn");
        let sendBtn = document.querySelector("#send-btn");
        let loginBtn = document.querySelector("#login-btn");
        if(!cancelBtn || !sendBtn || !loginBtn){
            console.log("insertRegForm: control buttons not found");
            return;
        }
        
        loginBtn.addEventListener('click', ()=>{
            accessAuthentification();
        })
        cancelBtn.addEventListener("click", ()=>{
            bodyContainer.removeChild(regPage);
        });

        sendBtn.addEventListener("click", async () => {
            let form = document.querySelector("#regForm");
            if(!form){
                console.log("form sendBtn: form not found");
                return;
            }
            let formData = new FormData(form);
            for(const [key, value] of formData.entries()){
                if(value.length === 0){
                    alert("missing field: " + key);
                    return;
                }
            }

            try{
                let result = await registerUser(formData);
                if(result){
                    redirectToStreamingPage();
                }
            }catch(error){
                console.log(error);
            }
        })
    }, 350);
}

async function accessAuthentification(){
    window.scrollTo(0, -window.scrollTop);
    document.documentElement.style.overflow = "hidden";
    let accessForm = `
    <form id="accessForm">
    <h2>Enter your stream access information</h2>
    <div class="field">
        <label>login</label>
        <input type="text" name="login" placeholder="login" value=${user['name'] ?? ''} readonly/>
    </div>
    <div class="field">
        <label>Access key</label>
        <input type="text" name="accessToken" placeholder="accessToken" required/>
    </div>
    <!-- <div class="field">
        <label>Password</label>
        <input name="password" type="password" placeholder="password" required/>
    </div> -->
    <div class='controls'>
        <button type="submit" id="send-btn">Watch</button>
        <button type="reset">clear</button>
        <button type="button" id="cancel-btn">Cancel</button>
    </div>
    <p>Don't have subscription key?<a href="${BASE_URL}/api.pay/stream">Buy</a></p>
    </form>`;
    let regPage = document.createElement("div");
    regPage.id = "reg-page";
    regPage.innerHTML = accessForm;
    let bodyContainer = document.querySelector("#body");
    if(!bodyContainer){
        bodyContainer = document.body;
    }
    bodyContainer.appendChild(regPage);
    setTimeout(()=>{
        let cancelBtn = document.querySelector("#cancel-btn");
        let form = document.querySelector("#accessForm");
        if(!cancelBtn){
            console.log("insertRegForm: control buttons not found");
            return;
        }

        cancelBtn.addEventListener("click", ()=>{
            bodyContainer.removeChild(regPage);
            document.documentElement.style.overflow = "auto";
        });

        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            for(const [key, value] of formData.entries()){
                if(value.length === 0){
                    alert("missing field: " + key);
                    return;
                }
            }
            formData.append('streamId', streamId);

            try{
                let response = await InfFreeFetch('/konektem/api/livestream/login', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${sessionStorage.getItem('token')}`
                    },
                    body: formData
                });
                let result = await response.json();
                if(response.status === 401){
                    sessionStorage.removeItem('token');
                    sessionStorage.removeItem('user');
                    showError(result.message);
                } else if(response.status >= 200 && response.status < 300){
                    if(result.success){
                        //sessionStorage.setItem('stream_username', result.data.name);
                        sessionStorage.setItem('stream_access_token', result.data.access_token);
                        //redirectToStreamingPage(strm_id);
                        showSuccess("login successful");
                        cancelBtn.click();
                    } else {
                        showError(result.message);
                    }
                } else {
                    showError(result.message);
                }
            }catch(error){
                console.log(error);
            }
        })
    }, 350);
}

function renderFrame(streamUrl){
    window.scrollTo(0, -window.scrollTop);
    let root = document.querySelector('#body');
    //let url = `https://try.antmedia.io/zwelakhemzwet/play.html?id=${streamkey}`;
    //let url = `http://94.103.12.109:5080/WebRTCAppEE/play.html?id=${streamkey}`;
    let container = document.createElement('div');
    container.classList.add('streamWin-container');
    container.innerHTML = `
        <div class="stream-win">
          <iframe sandbox="allow-scripts allow-same-origin allow-top-navigation allow-forms" id="stream-frame" src="${streamUrl}">
          </iframe>
        </div>
        <div class="btn stream-close-btn">
          <i class="fa-solid fa-circle-stop stop-btn"></i>
        </div>
      </div>`
    ;
    root.appendChild(container);
    setTimeout(()=>{
        let streamCloseBtn = document.querySelector('.stream-close-btn .stop-btn');
        streamCloseBtn.onclick = async function(){
            closeFrame();
            exitStream();
        };
    },100);
    document.documentElement.style.overflow = 'hidden';
}
function closeFrame(){
    let root = document.querySelector('#body');
    let container = document.querySelector('.streamWin-container');
    root.removeChild(container);
    document.documentElement.style.overflow = 'auto';
}

function popup(){
    let div = document.createElement('div');
    div.classList.add('pop-up-prompt');
    div.innerHTML = `
        <div class='pop-form'>
          <button type='button' onclick="window.location.href='/?p=payments&f=stream'">
            PAY TO WATCH MORE
          </button>
          <p>already paid? <span class='undlr rd-clr pp-login-btn'>login</span></p>
          <button type='button' class='rd-bg pp-close-btn'
          style="background-color: red">close</button>
        </div>`;
    return div;
}

export default function streamingReg(){
    let playBtn = document.querySelector(".play-button");
    let watchBtn = document.querySelectorAll(".watch-link");

    if(!playBtn){
        console.log("play button not found");
        return;
    }
    
    if(watchBtn.length > 0){
        watchBtn.forEach(btn => {
            btn.addEventListener('click', (e)=>{
                accessAuthentification();
            });
        })
    }
    
    playBtn.addEventListener('click', async ()=>{
        streamId = playBtn.dataset.streamid;
        if(streamId === '0'){
            alert('Stream is Currently offline');
            return;
        }
        if(!sessionStorage.getItem('user') || !sessionStorage.getItem('token')){
            //location.href = '/konektem/auth/login';
            userAuthPage();
            return;
        }
        user = Object.entries(user).length > 0 ? user : JSON.parse(sessionStorage.getItem('user')) ;
        if(!sessionStorage.getItem('stream_access_token')){
            accessAuthentification();
            return;
        }

        const response = await InfFreeFetch('/konektem/api/livestream/watch', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${sessionStorage.getItem('token')}`
            },
            body: {
                streamId: streamId,
                //login: sessionStorage.getItem('stream_username'),
                accessToken: sessionStorage.getItem('stream_access_token'),
            }
        });
        const result = await response.json();

        if(response.status === 401){
            sessionStorage.removeItem('user');
            sessionStorage.removeItem('token');
            location.href = '/konektem/auth/login';
            return;
        } else if (response.status >= 200 && response.status < 300){
            if(result.success) {
                const streamUrl = result.data.stream_url;
                sessionStorage.setItem('stream_token', result.data.token);
                renderFrame(streamUrl);
                
                /*setTimeout(()=>{
                    try{
                        let container = document.querySelector('.streamWin-container');
                        let frame = container.querySelector('iframe');
                        let pp_win = popup();

                        document.body.appendChild(pp_win);
                        setTimeout(()=>{
                            let form = pp_win.querySelector('.pop-form');
                            let closePPBtn = pp_win.querySelector('.pp-close-btn');
                            let loginPPBtn = pp_win.querySelector('.pp-login-btn');
                            form.classList.add('open');
                            frame.style.filter = 'blur(5px)';

                            closePPBtn.onclick = ()=>{
                                form.classList.remove('open');
                                exitStream();
                                setTimeout(()=>{
                                    document.body.removeChild(pp_win);
                                    closeFrame();
                                },300);
                            }
                            
                            loginPPBtn.onclick = ()=>{
                                form.classList.remove('open');
                                exitStream();
                                setTimeout(()=>{
                                    document.body.removeChild(pp_win);
                                    closeFrame();
                                    accessAuthentification(streamid);
                                },300);
                            }
                        },100)
                    } catch(error){
                        console.log(error.message);
                    }

                }, 5000);*/
            } else {
                showError(result.message);
            } 
        } else {
            showError(result.message);
        }
    });
    /*playBtn.addEventListener("click", ()=>{
        registrationForm();//access form
    });*/
}

async function exitStream(){
    const response = await InfFreeFetch('/konektem/api/livestream/exit-stream', {
        method: 'POST',
        headers: {
            Authorization: `Bearer ${sessionStorage.getItem('token')}`
        },
        body: {
            accessToken: sessionStorage.getItem('stream_access_token'),
            token: sessionStorage.getItem('stream_token')
        }
    });
    const result = await response.json();
    if(response.status === 402){
        sessionStorage.removeItem('stream_username');
        sessionStorage.removeItem('stream_access_key');
        sessionStorage.removeItem('stream_token');
        showError(result.message);
    } else if(response.status >= 200 && response.status < 300){
        if(result.success){
            // sessionStorage.removeItem('stream_username');
            // sessionStorage.removeItem('stream_access_key');
            // sessionStorage.removeItem('stream_token');
            showSuccess('Successfully exited stream');
        } else {
            showError(result.message);
        }
    } else {
        showError(result.message);
    }
}

async function registerUser(data){
    const alertDiv = document.createElement('div');
    alertDiv.classList.append('alert', 'success', 'abs-pos');
    
}

function makeRequest(method, url, data){
    return new Promise((resolve, reject) => {
        var xmlHttp;
        if(window.ActiveXObject){
            xmlHttp = new ActiveXObject();
        }
        else{
            xmlHttp = new XMLHttpRequest();
        }
        xmlHttp.open(method, url, true);
        xmlHttp.responseType = "json";
        xmlHttp.onreadystatechange = function (){
            if(xmlHttp.readyState === 4){
                if(xmlHttp.status >= 200 && xmlHttp.status < 300){
                    var jsonResponse = xmlHttp.response;
                    resolve(jsonResponse);
                }
                else{
                    reject({
                        status: xmlHttp.status,
                        text: xmlHttp.statusText
                    });
                }
            }
        };

        xmlHttp.onerror = function (){
            reject({
                status: xmlHttp.status,
                text: xmlHttp.statusText
            });
            console.log("error when sending request");
        }

        xmlHttp.send(data);

    })
}
streamingReg();
