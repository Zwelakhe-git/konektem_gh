import updateProfileUI from './profile.js';
import '../css/auth-form.css';

function signForm(){
    return `<form class='regForm'>
        <div class='form-close'>
            <i class="fa-solid fa-xmark close-icon"></i>
        </div>
        
        <div class="form-header">
            <h2>Log in to your account</h2>
            <p class="form-subtitle">Welcome back! Please enter your details.</p>
        </div>
        
        <div id="social-login-container"></div>
        
        <div class="divider">
            <span>Or</span>
        </div>
        
        <div class="email-login-section">
            <div class='field'>
                <label for='login'>USERNAME</label>
                <input id='login' type='text' name='login' placeholder='Enter your username'/>
            </div>
            
            <div class='field'>
                <label for='email'>EMAIL ADDRESS</label>
                <input id='email' type='email' name='email' placeholder='Enter your email' required/>
            </div>
            
            <div class='field'>
                <label for='password'>PASSWORD</label>
                <input id='password' type='password' name='password' placeholder='Enter your password' required/>
            </div>
            
            <div class='field row remember-me'>
                <input id='show-pswd' type='checkbox' name='show-pswd'/>
                <label for='show-pswd'>SHOW PASSWORD</label>
            </div>
            
            <div class='form-btns'>
                <button id='send-btn' type='submit'>SIGN IN</button>
            </div>
        </div>
        
        <p id='form-btm-text'></p>
       </form>
        `;
}

function createSocialLoginButtons() {
    return `
        <div class="social-login-options">
            <button type="button" id="google-login-btn" class="social-btn google-btn">
                <i class="fa-brands fa-google"></i>
                <span>Continue with Google</span>
            </button>
        </div>
    `;
}

export function renderForm(formtype, closeOnLog = true){
    if(document.querySelector('.form-container')){
        return;
    }
    let formContainer = document.createElement('div');
    formContainer.classList.add('form-container');
    formContainer.innerHTML = signForm();
    document.body.appendChild(formContainer);
    
    let form = formContainer.querySelector('form');
    renderFormUI();
    form.style.display = 'block';
    let timer1 = null;
    let timer2 = null;

    timer1 = setTimeout(()=>{
      form.classList.add('open');
      let pswdField = form.querySelector('#password');
      let loginField = form.querySelector('#login');
      let emailField = form.querySelector('#email');
      let closeIcon = form.querySelector('.close-icon');
      let sendBtn = form.querySelector('#send-btn');
      let socialLoginContainer = form.querySelector('#social-login-container');
        
      // Add social login buttons to the form
      socialLoginContainer.innerHTML = createSocialLoginButtons();
        
      loginField.addEventListener('input', (event) => {
          loginField.value = loginField.value.replace(/[^A-Za-z0-9]/g, '');
      });
        
      if(closeOnLog){
          closeIcon.addEventListener('click', ()=>{
            form.classList.remove('open');
            timer2 = setTimeout(()=>{
              formContainer.style.display = 'none';
              document.body.removeChild(formContainer);
            }, 500);
          });
      }
      
      // Google login button event listener
      let googleLoginBtn = form.querySelector('#google-login-btn');
      googleLoginBtn.addEventListener('click', async () => {
          googleLoginBtn.disabled = true;
          googleLoginBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Loading...</span>';
          
          try {
              let response = await fetch('/konektem/auth/google');
              let data = await response.json();
              
              if (data.auth_url) {
                  window.location.href = data.auth_url;
              } else if (data.error) {
                  throw new Error(data.error);
              } else {
                  throw new Error('No authentication URL received from server');
              }
          } catch (error) {
              console.error('Google login error:', error);
              let errorMessage = 'Google authentication is not available at the moment.';
              
              if (error.message.includes('not configured') || error.message.includes('configuration missing')) {
                  errorMessage = 'Google authentication is not configured. Please contact administrator.';
              } else if (error.message.includes('config file missing')) {
                  errorMessage = 'Google authentication setup incomplete. Please contact administrator.';
              }
              
              showAlert(errorMessage, 'error', formContainer);
              
              // Reset button
              googleLoginBtn.disabled = false;
              googleLoginBtn.innerHTML = '<i class="fa-brands fa-google"></i><span>Continue with Google</span>';
          }
      });
      
      form.addEventListener('submit', (e)=>{
          e.preventDefault();
      });
      sendBtn.addEventListener('click', async (e)=>{
        try{
            let formData = new FormData(form);
            let hasEmptyFields = false;

            // Check only visible fields for login, all fields for signup
            if(formtype === 'login') {
                // For login, only check email and password
                const email = form.querySelector('#email').value.trim();
                const password = form.querySelector('#password').value.trim();

                if(email.length === 0 || password.length === 0 || email.indexOf("@") < 0) {
                    showAlert('Please fill in all required fields', 'error', formContainer);
                    return;
                }
            } else {
                // For signup, check all fields including username
                for(const [key, value] of formData.entries()){
                    if(value.trim().length === 0){
                        showAlert('Please fill in all required fields', 'error', formContainer);
                        return;
                    }
                }
            }

            const params = new URLSearchParams(formData);
            let url = (formtype === 'login') ? '/konektem/auth/login' : '/konektem/auth/register';
            let response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: params.toString()
            })
            let data = await response.json();

            if(data.success){
                showAlert('Success! Redirecting...', 'success', formContainer);
                console.log(data.message);
                console.log(data);
                let session = {
                    name: formtype === 'login' ? data.message : 'guest'
                };
                sessionStorage.setItem('user', JSON.stringify(session));
                // currentUser
                window.session = session;
                //window.user = formtype === 'login' ? data.message : 'guest';
                updateProfileUI();

                if(closeOnLog){
                    setTimeout(()=>{
                        closeIcon.click();
                    }, 1000);
                } else if (formtype === 'login') {
                    window.location.href = "/konektem/user/me";
                } else {
                    showAlert("registration successful. Log in to your account", 'success', formContainer);
                }

            } else {
                console.log('showing alert on login')
                showAlert(data.message, 'error', formContainer);
            }

        } catch(error){
            console.log(error.message);
            showAlert('An error occurred. Please try again.', 'error', formContainer);
        }
    });

      let showPswdCheckBx = form.querySelector('#show-pswd');
      showPswdCheckBx.addEventListener('change', (e)=>{
        pswdField.type = e.currentTarget.checked ? 'text' : 'password';
      });
    }, 500);
    
    async function renderFormUI(){
        let sendBtn = form.querySelector('#send-btn');
        let formBtmParag = form.querySelector('#form-btm-text');
        let formHeader = form.querySelector('.form-header h2');
        let formSubtitle = form.querySelector('.form-subtitle');
        let usernameField = form.querySelector('.field:first-child');
        
        if(formtype === 'login') {
            formHeader.textContent = 'Log in to your account';
            formSubtitle.textContent = 'Welcome back! Please enter your details.';
            sendBtn.textContent = 'SIGN IN';
            formBtmParag.innerHTML = "Don't have an account? <a id='sign-link'>Sign up</a>";
            // Hide username field for login
            usernameField.style.display = 'none';
        } else {
            formHeader.textContent = 'Create your account';
            formSubtitle.textContent = 'Join us today! Enter your details to get started.';
            sendBtn.textContent = 'SIGN UP';
            formBtmParag.innerHTML = "Already have an account? <a id='reg-link'>Sign in</a>";
            // Show username field for signup
            usernameField.style.display = 'block';
        }
        
        let formLink = form.querySelector('a');
        formLink.addEventListener('click', ()=>{
            formtype = formtype === 'login' ? 'register' : 'login';
            renderFormUI();
        });
    }
}

export function showAlert(message, type, container) {
    // Remove existing alerts
    const existingAlerts = container.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'ok' : 'err'}`;
    alertDiv.innerHTML = `
        <i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
    
function userAuthPage(){    
    var formtype = 'register';
    renderForm(formtype);
}
export default userAuthPage;