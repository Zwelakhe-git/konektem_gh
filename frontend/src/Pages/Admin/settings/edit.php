<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Setting</h3>
                <a href="<?= BASE_URL?>/admin/settings" class="btn btn-sm btn-secondary float-end">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
            
            <form method="POST" class="edit-form" enctype="multipart/form-data">
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="setting_key" class="form-label">
                                    Setting Key *
                                    <small class="text-muted">(unique identifier)</small>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="setting_key" 
                                       name="setting_key" 
                                       value="<?= htmlspecialchars($setting['setting_key']) ?>" 
                                       required
                                       <?= $setting['setting_group'] === 'system' ? 'readonly' : '' ?>>
                                <?php if ($setting['setting_group'] === 'system'): ?>
                                    <div class="form-text text-warning">System settings key cannot be changed</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="setting_group" class="form-label">Group *</label>
                                <select class="form-control" id="setting_group" name="setting_group" required>
                                    <option value="">Select Group</option>
                                    <option value="general" <?= $setting['setting_group'] === 'general' ? 'selected' : '' ?>>General</option>
                                    <option value="seo" <?= $setting['setting_group'] === 'seo' ? 'selected' : '' ?>>SEO</option>
                                    <option value="contact" <?= $setting['setting_group'] === 'contact' ? 'selected' : '' ?>>Contact</option>
                                    <option value="social" <?= $setting['setting_group'] === 'social' ? 'selected' : '' ?>>Social Media</option>
                                    <option value="email" <?= $setting['setting_group'] === 'email' ? 'selected' : '' ?>>Email</option>
                                    <option value="footer" <?= $setting['setting_group'] === 'footer' ? 'selected' : '' ?>>Footer</option>
                                    <option value="header" <?= $setting['setting_group'] === 'header' ? 'selected' : '' ?>>Header</option>
                                    <option value="system" <?= $setting['setting_group'] === 'system' ? 'selected' : '' ?>>System</option>
                                    <option value="other" <?= $setting['setting_group'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="setting_type" class="form-label">Type *</label>
                                <select class="form-control" id="setting_type" name="setting_type" required>
                                    <option value="">Select Type</option>
                                    <option value="text" <?= $setting['setting_type'] === 'text' ? 'selected' : '' ?>>Text</option>
                                    <option value="textarea" <?= $setting['setting_type'] === 'textarea' ? 'selected' : '' ?>>Text Area</option>
                                    <option value="number" <?= $setting['setting_type'] === 'number' ? 'selected' : '' ?>>Number</option>
                                    <option value="email" <?= $setting['setting_type'] === 'email' ? 'selected' : '' ?>>Email</option>
                                    <option value="boolean" <?= $setting['setting_type'] === 'boolean' ? 'selected' : '' ?>>Boolean (Yes/No)</option>
                                    <option value="json" <?= $setting['setting_type'] === 'json' ? 'selected' : '' ?>>JSON</option>
                                    <option value="html" <?= $setting['setting_type'] === 'html' ? 'selected' : '' ?>>HTML</option>
                                    <option value="image" <?= $setting['setting_type'] === 'image' ? 'selected' : '' ?>>Image</option>
                                    <option value="video" <?= $setting['setting_type'] === 'video' ? 'selected' : '' ?>>Video</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_order" class="form-label">Display Order</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="display_order" 
                                       name="display_order" 
                                       value="<?= htmlspecialchars($setting['display_order'] ?? 0) ?>" 
                                       min="0">
                                <div class="form-text">Lower numbers display first</div>
                            </div>
                        </div>
                    </div>

                    <!-- Media Input Group (для image/video) -->
                    <div class="mb-3" id="mediaInputGroup" style="display: none;">
                        <label class="form-label">Media Source</label>
                        <div class="btn-group mb-2" role="group">
                            <input type="radio" class="btn-check" name="media_source" id="mediaFile" value="file" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="mediaFile">📁 Upload File</label>

                            <input type="radio" class="btn-check" name="media_source" id="mediaUrl" value="url" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="mediaUrl">🔗 URL</label>
                        </div>

                        <!-- File input -->
                        <div id="fileInputWrapper">
                            <input type="file" class="form-control" id="media_file" name="media_file" accept="">
                            <div class="form-text" id="fileSizeInfo">Max size: 5 MB</div>
                            <?php if (!empty($setting['setting_value']) && $setting['setting_type'] !== 'url'): ?>
                                <div class="mt-2">
                                    <span class="badge bg-info">Current file: <?= htmlspecialchars(basename($setting['setting_value'])) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- URL input -->
                        <div id="urlInputWrapper" style="display: none;">
                            <input type="text" class="form-control" id="media_url" name="media_url" 
                                   placeholder="https://example.com/image.jpg"
                                   value="<?= htmlspecialchars($setting['setting_type'] === 'url' ? $setting['setting_value'] : '') ?>">
                        </div>

                        <!-- Скрытое поле для хранения выбранного значения -->
                        <input type="hidden" id="setting_value" name="setting_value" value="<?= htmlspecialchars($setting['setting_value'] ?? '') ?>">
                    </div>

                    <!-- Обычные поля для Value (скрываются для image/video) -->
                    <div class="mb-3" id="standardValueGroup">
                        <label for="setting_value_text" class="form-label">Value</label>
                        
                        <?php if ($setting['setting_type'] === 'boolean'): ?>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="setting_value_checkbox" 
                                       name="setting_value_text" 
                                       value="1"
                                       <?= $setting['setting_value'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="setting_value_checkbox">
                                    Enabled
                                </label>
                            </div>
                            <input type="hidden" id="setting_value_hidden" name="setting_value" value="<?= $setting['setting_value'] ? '1' : '0' ?>">
                        <?php else: ?>
                            <?php if ($setting['setting_type'] === 'textarea'): ?>
                                <textarea class="form-control" 
                                          id="setting_value_text" 
                                          name="setting_value_text" 
                                          rows="5"><?= htmlspecialchars($setting['setting_value']) ?></textarea>
                            <?php elseif ($setting['setting_type'] === 'json'): ?>
                                <textarea class="form-control" 
                                          id="setting_value_text" 
                                          name="setting_value_text" 
                                          rows="5"
                                          placeholder='{"key": "value"}'><?= htmlspecialchars($setting['setting_value']) ?></textarea>
                                <div class="form-text">Enter valid JSON data</div>
                            <?php elseif ($setting['setting_type'] === 'html'): ?>
                                <textarea class="form-control" 
                                          id="setting_value_text" 
                                          name="setting_value_text" 
                                          rows="8"><?= htmlspecialchars($setting['setting_value']) ?></textarea>
                                <div class="form-text">HTML content allowed</div>
                            <?php else: ?>
                                <input type="text" 
                                       class="form-control" 
                                       id="setting_value_text" 
                                       name="setting_value_text" 
                                       value="<?= htmlspecialchars($setting['setting_value']) ?>">
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="2"><?= htmlspecialchars($setting['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_public" 
                                   name="is_public" 
                                   value="1"
                                   <?= $setting['is_public'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_public">
                                Public Setting (visible through API)
                            </label>
                        </div>
                    </div>
                    
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Setting Information</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Created:</dt>
                                <dd class="col-sm-8"><?= date('Y-m-d H:i:s', strtotime($setting['created_at'] ?? 'now')) ?></dd>
                                
                                <dt class="col-sm-4">Last Updated:</dt>
                                <dd class="col-sm-8"><?= date('Y-m-d H:i:s', strtotime($setting['updated_at'] ?? 'now')) ?></dd>
                                
                                <?php if ($setting['setting_group'] === 'system'): ?>
                                    <dt class="col-sm-4 text-warning">Status:</dt>
                                    <dd class="col-sm-8"><span class="badge bg-warning">System Setting</span></dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="<?= BASE_URL?>/admin/settings" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('setting_type');
    const mediaGroup = document.getElementById('mediaInputGroup');
    const standardGroup = document.getElementById('standardValueGroup');
    const fileWrapper = document.getElementById('fileInputWrapper');
    const urlWrapper = document.getElementById('urlInputWrapper');
    const fileInput = document.getElementById('media_file');
    const urlInput = document.getElementById('media_url');
    const hiddenValue = document.getElementById('setting_value');
    const textValue = document.getElementById('setting_value_text');
    const fileSizeInfo = document.getElementById('fileSizeInfo');

    function updateMediaFields() {
        const type = typeSelect.value;
        const isMedia = (type === 'image' || type === 'video');

        mediaGroup.style.display = isMedia ? 'block' : 'none';
        standardGroup.style.display = isMedia ? 'none' : 'block';

        if (isMedia) {
            if (type === 'image') {
                fileInput.accept = 'image/*';
                fileSizeInfo.textContent = 'Max size: 5 MB';
                fileInput.dataset.maxSize = '5242880';
            } else if (type === 'video') {
                fileInput.accept = 'video/*';
                fileSizeInfo.textContent = 'Max size: 20 MB';
                fileInput.dataset.maxSize = '20971520';
            }

            // Определяем, что выбрано: file или url
            const currentValue = hiddenValue.value || '';
            const isUrl = currentValue.startsWith('http://') || currentValue.startsWith('https://') || currentValue.startsWith('/');

            if (isUrl) {
                document.getElementById('mediaUrl').checked = true;
                fileWrapper.style.display = 'none';
                urlWrapper.style.display = 'block';
                urlInput.value = currentValue;
            } else {
                document.getElementById('mediaFile').checked = true;
                fileWrapper.style.display = 'block';
                urlWrapper.style.display = 'none';
                if (currentValue) {
                    // Показываем имя текущего файла
                    const badge = fileWrapper.querySelector('.badge');
                    if (badge) {
                        badge.textContent = 'Current file: ' + currentValue;
                    }
                }
            }
        } else {
            // Для обычных типов — восстанавливаем значение
            if (textValue) {
                textValue.value = hiddenValue.value || '';
            }
        }
    }

    typeSelect.addEventListener('change', function() {
        updateMediaFields();
        // Если переключились с медиа — сбрасываем
        if (!['image', 'video'].includes(this.value)) {
            // Ничего не делаем, просто обновляем
        }
    });

    // Переключение между File и URL
    document.querySelectorAll('input[name="media_source"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'file') {
                fileWrapper.style.display = 'block';
                urlWrapper.style.display = 'none';
                hiddenValue.value = '';
                urlInput.value = '';
            } else {
                fileWrapper.style.display = 'none';
                urlWrapper.style.display = 'block';
                fileInput.value = '';
                hiddenValue.value = urlInput.value.trim();
            }
        });
    });

    urlInput.addEventListener('input', function() {
        hiddenValue.value = this.value.trim();
    });

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const maxSize = parseInt(this.dataset.maxSize, 10);
        if (file.size > maxSize) {
            showError(`File size exceeds the limit. Maximum allowed: ${maxSize / 1024 / 1024} MB`);
            this.value = '';
            hiddenValue.value = '';
            return;
        }

        const type = typeSelect.value;
        if (type === 'image' && !file.type.startsWith('image/')) {
            showError('Please select a valid image file.');
            this.value = '';
            hiddenValue.value = '';
            return;
        }
        if (type === 'video' && !file.type.startsWith('video/')) {
            showError('Please select a valid video file.');
            this.value = '';
            hiddenValue.value = '';
            return;
        }

        hiddenValue.value = file.name;
        // Обновляем badge
        const badge = fileWrapper.querySelector('.badge');
        if (badge) {
            badge.textContent = 'Current file: ' + file.name;
        }
    });

    document.querySelector('#submitBtn').addEventListener('click', function(e) {
        const type = typeSelect.value;
        if (['image', 'video'].includes(type)) {
            e.preventDefault();
            const isFile = document.getElementById('mediaFile').checked;
            const fileSelected = fileInput.files.length > 0;
            const urlEntered = urlInput.value.trim() !== '';

            if (!fileSelected && !urlEntered && !hiddenValue.value) {
                showError('Please either upload a file or enter a URL.');
                return;
            }

            if (!isFile) {
                hiddenValue.value = urlInput.value.trim();
            } else if (fileSelected) {
                hiddenValue.value = fileInput.files[0].name;
            }
        } else {
            hiddenValue.value = textValue.value;
        }
        document.querySelector('form').requestSubmit();
    });

    // Для boolean
    const checkbox = document.getElementById('setting_value_checkbox');
    const hiddenCheck = document.getElementById('setting_value_hidden');
    if (checkbox && hiddenCheck) {
        checkbox.addEventListener('change', function() {
            hiddenCheck.value = this.checked ? '1' : '0';
        });
    }

    // Инициализация
    setTimeout(() => {
        updateMediaFields();
    }, 100);

    // Валидация JSON
    if (textValue) {
        textValue.addEventListener('blur', function() {
            if (typeSelect.value === 'json') {
                try {
                    JSON.parse(this.value);
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } catch (e) {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            }
        });
    }
});
</script>