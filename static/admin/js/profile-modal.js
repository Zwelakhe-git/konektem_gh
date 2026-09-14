
function modalHtml(){
    return `<div class="profile-modal" id="profileModal">
        <!-- close button -->
        <button class="modal-close-btn" id="modalCloseBtn">&times;</button>

        <!-- grid: avatar left, form right -->
        <div class="modal-grid">
            <!-- LEFT: avatar -->
            <div class="avatar-section">
            <div class="avatar-wrapper" id="avatarWrapper">
                <img id="profileImgPreview" alt="avatar" class="hidden">
                <div id="avatar-placeholder" class="hidden"></div>
                <div class="upload-hint"><i class="fas fa-camera"></i> change photo</div>
            </div>
            <!-- hidden file input -->
            <input type="file" accept="image/*" id="avatarFileInput">
            <div style="font-size:0.75rem; color:#4a6a8a; text-align:center; margin-top:-0.2rem;">
                <i class="fas fa-circle" style="color:#d32f2f; font-size:0.5rem;"></i> click image to upload
            </div>
            </div>

            <!-- RIGHT: single form -->
            <div class="form-section">
            <h2><i class="fas fa-pen" style="color:#d32f2f; margin-right: 10px;"></i>Edit profile</h2>

            <form id="profileForm" method="POST" enctype="multipart/form-data">
                <!-- hidden field to carry avatar data (we use file input separately) -->
                <input type="hidden" name="avatar_url" id="hiddenAvatarUrl" value="">

                <div class="form-group">
                <label for="profileName"><i class="fas fa-user" style="margin-right: 6px;"></i>Name</label>
                <input type="text" id="profileName" name="name" placeholder="username" required>
                </div>

                <div class="form-group">
                <label for="profileEmail"><i class="fas fa-envelope" style="margin-right: 6px;"></i>Email</label>
                <input type="email" id="profileEmail" name="email" placeholder="email" required>
                </div>

                <!-- optional: avatar URL field (but we use file input, we keep it hidden or visible? we keep visible for fallback) -->
                <div class="form-group">
                <label for="profileAvatar"><i class="fas fa-link" style="margin-right: 6px;"></i>Avatar URL (optional)</label>
                <input type="text" id="profileAvatar" name="avatar_url_inp" placeholder="https://example.com/avatar.jpg" value="" disabled>
                </div>

                <div class="form-group">
                <label for="currentPassword"><i class="fas fa-lock" style="margin-right: 6px;"></i>Current password (required)</label>
                <input type="password" id="currentPassword" name="current_password" required placeholder="••••••••">
                </div>

                <div class="form-group">
                <label for="newPassword"><i class="fas fa-key" style="margin-right: 6px;"></i>New password (leave empty to keep)</label>
                <input type="password" id="newPassword" name="new_password" placeholder="new password">
                </div>

                <div class="form-group">
                <label for="confirmPassword"><i class="fas fa-check-circle" style="margin-right: 6px;"></i>Confirm new password</label>
                <input type="password" id="confirmPassword" name="confirm_password" placeholder="confirm new password">
                </div>

                <div class="form-actions">
                <button type="button" class="btn btn-cancel" id="modalCancelBtn">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save" style="margin-right: 8px;"></i>Save changes</button>
                </div>
            </form>
            </div>
        </div>

        <div class="theme-badge">✦ KONEKTEM ✦</div>
        </div>`;
}

export function openModal(){
    try {
        const existingModal = document.querySelector('#profileModalOverlay');
        if(existingModal) {
            existingModal.remove();
        }

        const overlay = document.createElement('div');
        overlay.id = 'profileModalOverlay';
        overlay.classList = 'profile-modal-overlay';
        overlay.innerHTML = modalHtml();
        document.body.appendChild(overlay);
        (function() {
            "use strict";

            // DOM refs
            //const overlay = document.getElementById('profileModalOverlay');
            const modal = document.getElementById('profileModal');
            const closeBtn = document.querySelector('#profileModal .modal-close-btn');
            const submitBtn = document.querySelector('#profileModal button[type="submit"]');
            const cancelBtn = document.getElementById('modalCancelBtn');
            const avatarWrapper = document.getElementById('avatarWrapper');
            const fileInput = document.getElementById('avatarFileInput');
            const imgPreview = document.getElementById('profileImgPreview');
            const imgPlaceholder = document.querySelector('#profileModal #avatar-placeholder');
            const hiddenAvatarUrl = document.getElementById('hiddenAvatarUrl');
            const avatarUrlInput = document.getElementById('profileAvatar');
            const user = JSON.parse(atob(localStorage.getItem('token')?.split('.')[1]) ?? '{}')

            const updateAvatarPreviewUI = (avatar_url = null) => {
                if(avatar_url){
                    imgPreview.src = avatar_url
                    imgPreview.classList.remove('hidden');
                    imgPlaceholder.classList.add('hidden');
                } else {
                    imgPlaceholder.textContent = user.name.substring(0, 1).toUpperCase();
                    imgPreview.classList.add('hidden');
                    imgPlaceholder.classList.remove('hidden');
                }
            }

            modal.querySelector('input#profileName').value = user.name ?? '';
            modal.querySelector('input#profileEmail').value = user.email ?? '';
            updateAvatarPreviewUI(user.avatar_url);
            
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            // sync avatar from url input if any
            const urlVal = avatarUrlInput.value.trim();
            if (urlVal) {
                updateAvatarPreviewUI(urlVal);
                hiddenAvatarUrl.value = urlVal;
            }

            function closeModal() {
                overlay.classList.remove('active');
                document.body.style.overflow = '';
                // reset file input
                fileInput.value = '';
            }

            // ---------- click avatar -> trigger file input ----------
            avatarWrapper.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.click();
            });

            // ---------- file input change: preview image ----------
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(ev) {
                    updateAvatarPreviewUI(ev.target.result);
                    // also clear the avatar URL field if a file is selected
                    avatarUrlInput.value = '';
                    hiddenAvatarUrl.value = '';
                };
                reader.readAsDataURL(file);
            });

            // ---------- sync avatar URL input with preview (if user types URL) ----------
            avatarUrlInput.addEventListener('input', function() {
                const val = this.value.trim();
                if (val) {
                    updateAvatarPreviewUI(val);
                    hiddenAvatarUrl.value = val;
                } else {
                // if empty, keep current preview (don't revert to default unless we want)
                // we keep as is
                }
            });

            // ---------- form submit (prevent default, just demo) ----------
            const form = document.getElementById('profileForm');
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                submitBtn.disabled = true;
                const defaultBtnContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
                
                //showError("Profile update is under development. Please try again later");
                try {
                    const formData = new FormData(this);
                    if(fileInput.files && fileInput.files.length > 0){
                        formData.append('avatar_image', fileInput.files[0]);
                    }
                    const response = await fetch('/user/api/profile-update', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        body: formData
                    });

                    if(response.status === 401){
                        showError('Session timeout');
                        localStorage.removeItem('token');
                        setTimeout(()=>{
                            window.location.href = '/auth/logout';
                        }, 500);
                        return;
                    }
                    const result = await response.json();
                    if(result.success){
                        showSuccess(result.message || 'profile updated successfully')
                        localStorage.setItem('token', result.token);
                    } else {
                        showError(result.message || 'Server error');
                    }
                    submitBtn.innerHTML = defaultBtnContent;
                    submitBtn.disabled = false;

                } catch(err){
                    console.error(err);
                }

                //alert(`✅ Profile updated (demo)\nName: ${name}\nEmail: ${email}\nAvatar: ${imgPreview.src}`);
                //closeModal();
            });

            // ---------- close events ----------
            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) closeModal();
            });

            // ---------- esc key ----------
            document.onkeydown = function(e) {
                if (e.key === 'Escape' && overlay.classList.contains('active')) {
                closeModal();
                }
            }
        })();
    } catch(err){
        console.error(err);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // ---------- open trigger ----------

    const openModalTriggers = [
        document.querySelector('.profile .user-avatar'),
        ...document.querySelectorAll('.settings-icon:not(.admin)')
    ];
    openModalTriggers.forEach(trigger => trigger.addEventListener('click', openModal));
});