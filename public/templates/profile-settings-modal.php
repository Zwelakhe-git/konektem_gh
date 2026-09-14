<div id="profileModal" class="modal" style="display:none">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Profile</h3>
            <span class="close profile-modal-close-btn">&times;</span>
        </div>
        <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" id="avatar-edit-form" style="display: none">
                <div class="form-group">
                    <div class="image-preview" style="width: 250px;
                                                    height: 250px; 
                                                    border-radius: 50%; 
                                                    outline: 3px solid white; 
                                                    margin: auto;
                                                    margin-bottom: 10px;">
                        <img id="profile-img-preview" style="width: 100%; height: 100%; border-radius: 50%" src="<?= htmlspecialchars($_SESSION['user']['avatar_url'] ?? "/media/images/default_profile_avatar.jpg")?>" alt="profile picture"/>
                    </div>
                    <label for="avatar-img">upload image</label>
                    <input type="file" accept="image/*" class="form-control" data-preview="profile-img-preview" id="avatar-img" name="image" required/>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary profile-edit-cancel-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
            <form id="profileForm" style="display: none">
                <div class="form-group">
                    <label for="profileName">Name</label>
                    <input type="text" id="profileName" name="name" 
                        value="<?php echo htmlspecialchars($_SESSION['user']['name'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="profileEmail">Email</label>
                    <input type="email" id="profileEmail" name="email" 
                        value="<?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="profileAvatar">Avatar URL (optional)</label>
                    <input type="text" id="profileAvatar" name="avatar_url" 
                        value="<?php echo htmlspecialchars($_SESSION['user']['avatar_url'] ?? ''); ?>"
                        placeholder="https://example.com/avatar.jpg">
                </div>

                <div class="form-group">
                    <label for="currentPassword">Current Password (required for changes)</label>
                    <input type="password" id="currentPassword" name="current_password" required>
                </div>

                <div class="form-group">
                    <label for="newPassword">New Password (leave empty to keep current)</label>
                    <input type="password" id="newPassword" name="new_password">
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm New Password</label>
                    <input type="password" id="confirmPassword" name="confirm_password">
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary profile-edit-cancel-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= BASE_URL . '/dist/pp-settings-api.js'?>"></script>