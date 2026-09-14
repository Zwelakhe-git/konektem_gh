/*const formSelector = '';
const action = 'create';
const item = 'services';
const BASE_URL = '/konektem';
const role = 'admin';
*/
const url = new URL(location.href);
const parts = /(konektem)?\/(admin|user\/me)\/?(\w+)?\/?(\d+)?\/?(\w+)?/.exec(url.pathname);
window.BASE_URL = '/konektem';
if(parts){
    window.role = parts[2];
    window.item = parts[3];
    if(parts[5]){
        window.action = parts[5];
    }
}


export function showProgress(){
    document.querySelector('#upload-progress')?.remove();
    let progressModal = document.createElement('div');
    progressModal.id = 'upload-progress';
    progressModal.className = 'flex-disp flex-column full-w full-h position-fixed align-content-center justify-content-center align-items-sm-center top-0 bg-opacity-75 bg-light';
    progressModal.style.zIndex = '9999';
    progressModal.innerHTML = `
        <div class="card shadow-lg p-4" style="max-width: 500px; width: 90%; border-radius: 1.2rem; border: none;">
            <div class="text-center mb-3">
                <i class="fa-solid fa-cloud-upload-alt text-primary" style="font-size: 3rem;"></i>
                <h5 class="mt-2 fw-bold">Uploading...</h5>
                <p class="text-muted small" id="upload-status-text">Preparing to upload</p>
            </div>
            <div class="progress" style="height: 12px; border-radius: 20px; background: #e9ecef;">
                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" 
                     style="width: 0%; background: linear-gradient(90deg, #1976d2, #1565c0);"
                     aria-valuenow="0" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="small text-muted" id="progressPercent">0%</span>
                <span class="small text-muted" id="progressSize">0 / 0 MB</span>
            </div>
            <div class="mt-3 text-center" id="upload-status-icon">
                <i class="fa-solid fa-spinner fa-spin text-primary"></i>
            </div>
        </div>
    `;
    document.body.appendChild(progressModal);

    // Add close button (X) in corner of card
    const card = progressModal.querySelector('.card');
    const closeBtn = document.createElement('button');
    closeBtn.className = 'btn-close position-absolute top-0 end-0 mt-2 me-2';
    closeBtn.type = 'button';
    closeBtn.setAttribute('aria-label', 'Close');
    closeBtn.style.zIndex = '10';
    closeBtn.addEventListener('click', function() {
        progressModal.remove();
    });
    card.style.position = 'relative';
    card.appendChild(closeBtn);
}

export function uploadProgressHandler(e){
    if(e.lengthComputable){
        let percent = (e.loaded / e.total) * 100;
        const progressBar = document.querySelector('#progressBar');
        const progressPercent = document.querySelector('#progressPercent');
        const progressSize = document.querySelector('#progressSize');
        const statusText = document.querySelector('#upload-status-text');
        
        // Update progress bar
        progressBar.style.width = percent + '%';
        progressBar.setAttribute('aria-valuenow', percent);
        progressBar.textContent = Math.round(percent) + '%';
        
        // Update percentage text
        progressPercent.textContent = Math.round(percent) + '%';
        
        // Update file size info
        const loadedMB = (e.loaded / (1024 * 1024)).toFixed(2);
        const totalMB = (e.total / (1024 * 1024)).toFixed(2);
        progressSize.textContent = `${loadedMB} / ${totalMB} MB`;
        
        // Update status text
        if (percent < 25) {
            statusText.textContent = 'Starting upload...';
        } else if (percent < 50) {
            statusText.textContent = 'Uploading...';
        } else if (percent < 75) {
            statusText.textContent = 'Almost there...';
        } else if (percent < 100) {
            statusText.textContent = 'Finalizing...';
        } else {
            statusText.textContent = 'Complete!';
        }
    }
}

export function uploadCompleteHandler(){
    try {
        let result = JSON.parse(this.responseText);
        const statusIcon = document.querySelector('#upload-status-icon');
        const statusText = document.querySelector('#upload-status-text');
        const progressBar = document.querySelector('#progressBar');
        
        if(this.status === 401){
            localStorage.removeItem('token');
            statusIcon.innerHTML = '<i class="fa-solid fa-exclamation-circle text-danger" style="font-size: 2rem;"></i>';
            statusText.textContent = result.message || 'Session expired';
            statusText.className = 'text-danger fw-bold';
            progressBar.classList.add('bg-danger');
            setTimeout(()=>{
                location.href = role === 'admin' ? `${BASE_URL}/auth/admin/logout` : `${BASE_URL}/auth/logout`
            }, 1500);
        }
        if(this.status >= 200 && this.status < 300){
            if(result.success){
                statusIcon.innerHTML = '<i class="fa-solid fa-check-circle text-success" style="font-size: 2.5rem;"></i>';
                statusText.textContent = result.message || 'Upload completed successfully!';
                statusText.className = 'text-success fw-bold';
                progressBar.style.width = '100%';
                progressBar.setAttribute('aria-valuenow', 100);
                progressBar.textContent = '100%';
                progressBar.classList.remove('progress-bar-animated');
                progressBar.style.background = 'linear-gradient(90deg, #28a745, #20c997)';
                
                // Auto-close after success
                setTimeout(()=>{
                    document.querySelector('#upload-progress')?.remove();
                    location.href = `${BASE_URL}/${role}/${item}`;
                }, 2000);
            } else {
                statusIcon.innerHTML = '<i class="fa-solid fa-exclamation-triangle text-warning" style="font-size: 2rem;"></i>';
                statusText.textContent = result.message || 'Upload failed';
                statusText.className = 'text-warning fw-bold';
                progressBar.classList.add('bg-warning');
            }
        } else {
            statusIcon.innerHTML = '<i class="fa-solid fa-times-circle text-danger" style="font-size: 2rem;"></i>';
            statusText.textContent = result.message || 'An error occurred';
            statusText.className = 'text-danger fw-bold';
            progressBar.classList.add('bg-danger');
        }
    } catch(e){
        console.error(e);
        const statusIcon = document.querySelector('#upload-status-icon');
        const statusText = document.querySelector('#upload-status-text');
        statusIcon.innerHTML = '<i class="fa-solid fa-times-circle text-danger" style="font-size: 2rem;"></i>';
        statusText.textContent = 'An unexpected error occurred';
        statusText.className = 'text-danger fw-bold';
    } finally {
        // Remove spinner and add close button functionality
        const closeBtn = document.querySelector('#upload-progress .btn-close');
        if (closeBtn) {
            closeBtn.style.display = 'block';
        }
        
        // Enable submit button after a delay
        setTimeout(() => {
            (document.querySelector("button[type=submit]") ?? document.querySelector('#submitBtn')).disabled = false;
        }, 1000);
    }
}

export function initFormSubmitHandler(form, customResponseHandler=null){
    let method = 'POST';
    form?.addEventListener('submit', async function(e){
        e.preventDefault();
        let submitBtn = document.querySelector("button[type=submit]") ?? document.querySelector('#submitBtn');
        submitBtn.disabled = true;
        let url = `${BASE_URL}/api/${item}/${action}`;
        //console.log(url);
        const formData = new FormData(this);
        if(action === 'edit'){
            formData.append('id', parts[4]);
        }

        // XHR here
        let xhr = new XMLHttpRequest();
        xhr.open(method, url, true);

        const headers = {
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        xhr.upload.addEventListener('progress', uploadProgressHandler);
        
        for(const [header, value] of Object.entries(headers)){
            xhr.setRequestHeader(header, value);
        }
        xhr.onload = uploadCompleteHandler;
        showProgress();
        setTimeout(() => xhr.send(formData), 300);
    });
}

if(Object.hasOwn(window, 'action') && action !== 'list' && item !== 'albums'){
    window.formSelector = `.${action}-form`;
    
    const form = document.querySelector(formSelector ?? 'form');
    initFormSubmitHandler(form);
}

document.querySelectorAll('.del-btn').forEach(btn => {
    btn.addEventListener('click', async function(e){
        e.preventDefault();
        if(!confirm("delete item?")){
            return;
        }
        const id = this.dataset.id;
        if(!id){
            showError("Failed to identify");
            return;
        }
        let url = `${BASE_URL}/api/${item}/delete`;
        try {
            const body = {
                id: id,
                item: item
            };
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(body),
                credentials: 'same-origin'
            });
            const result = await response.json();
            if(result.success){
                showSuccess(`item deleted successfully`);
                setTimeout(()=>{
                    location.href = `${BASE_URL}/${role}/${item}`
                }, 1000)
            } else {
                showError(result.message);
            }
        } catch(e){
            console.error(e);
        }
    })
});