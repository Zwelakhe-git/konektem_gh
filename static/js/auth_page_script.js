import {renderForm} from './authentication.js';
import updateProfileUI from './profile.js';

document.addEventListener('DOMContentLoaded', ()=>{
    renderForm('login', false);
    
    // Handle Google auth callback parameters
    const urlParams = new URLSearchParams(window.location.search);
    const googleSuccess = urlParams.get('google_success');
    const googleError = urlParams.get('google_error');
    const token = urlParams.get('token');
    
    if (googleSuccess === '1') {
        // Update UI to show logged in state
        
        localStorage.setItem("token", token);
        
        updateProfileUI();
        
        showAlert('Successfully logged in with Google!', 'success');
        
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
        
        // Redirect to home page after successful login
        setTimeout(() => {
            window.location.href = '/konektem/user/me';
        }, 2000);
    }
    
    if (googleError === '1') {
        const message = urlParams.get('message') || 'Google authentication failed';
        showAlert(message, 'error');
        
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'ok' : 'err'}`;
    alertDiv.innerHTML = `
        <i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}