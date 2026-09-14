<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create New Setting</h3>
                <a href="<?= BASE_URL?>/admin/settings" class="btn btn-sm btn-secondary float-end">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
            
            <form method="POST" class="create-form" enctype="multipart/form-data">
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
                                       value="<?= htmlspecialchars($_POST['setting_key'] ?? '') ?>" 
                                       required
                                       placeholder="e.g., site_name, contact_email">
                                <div class="form-text">Use lowercase with underscores</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="setting_group" class="form-label">Group *</label>
                                <select class="form-control" id="setting_group" name="setting_group" required>
                                    <option value="">Select Group</option>
                                    <option value="general" <?= ($_POST['setting_group'] ?? '') === 'general' ? 'selected' : '' ?>>General</option>
                                    <option value="seo" <?= ($_POST['setting_group'] ?? '') === 'seo' ? 'selected' : '' ?>>SEO</option>
                                    <option value="contact" <?= ($_POST['setting_group'] ?? '') === 'contact' ? 'selected' : '' ?>>Contact</option>
                                    <option value="social" <?= ($_POST['setting_group'] ?? '') === 'social' ? 'selected' : '' ?>>Social Media</option>
                                    <option value="email" <?= ($_POST['setting_group'] ?? '') === 'email' ? 'selected' : '' ?>>Email</option>
                                    <option value="footer" <?= ($_POST['setting_group'] ?? '') === 'footer' ? 'selected' : '' ?>>Footer</option>
                                    <option value="header" <?= ($_POST['setting_group'] ?? '') === 'header' ? 'selected' : '' ?>>Header</option>
                                    <option value="system" <?= ($_POST['setting_group'] ?? '') === 'system' ? 'selected' : '' ?>>System</option>
                                    <option value="other" <?= ($_POST['setting_group'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
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
                                    <option value="text" <?= ($_POST['setting_type'] ?? '') === 'text' ? 'selected' : '' ?>>Text</option>
                                    <option value="textarea" <?= ($_POST['setting_type'] ?? '') === 'textarea' ? 'selected' : '' ?>>Text Area</option>
                                    <option value="number" <?= ($_POST['setting_type'] ?? '') === 'number' ? 'selected' : '' ?>>Number</option>
                                    <option value="email" <?= ($_POST['setting_type'] ?? '') === 'email' ? 'selected' : '' ?>>Email</option>
                                    <option value="boolean" <?= ($_POST['setting_type'] ?? '') === 'boolean' ? 'selected' : '' ?>>Boolean (Yes/No)</option>
                                    <option value="json" <?= ($_POST['setting_type'] ?? '') === 'json' ? 'selected' : '' ?>>JSON</option>
                                    <option value="html" <?= ($_POST['setting_type'] ?? '') === 'html' ? 'selected' : '' ?>>HTML</option>
                                    <option value="image" <?= ($_POST['setting_type'] ?? '') === 'image' ? 'selected' : '' ?>>Image</option>
                                    <option value="video" <?= ($_POST['setting_type'] ?? '') === 'video' ? 'selected' : '' ?>>Video</option>
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
                                       value="<?= htmlspecialchars($_POST['display_order'] ?? 0) ?>" 
                                       min="0">
                                <div class="form-text">Lower numbers display first</div>
                            </div>
                        </div>
                    </div>

                    <!-- Media Input Group (для image/video) -->
                    <div class="mb-3" id="mediaInputGroup" style="display: none;">
                        <label class="form-label">Media Source</label>
                        <div class="btn-group mb-2" role="group">
                            <input type="radio" class="btn-check" name="media_source" id="mediaFile" value="file" checked autocomplete="off">
                            <label class="btn btn-outline-secondary" for="mediaFile">📁 Upload File</label>

                            <input type="radio" class="btn-check" name="media_source" id="mediaUrl" value="url" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="mediaUrl">🔗 URL</label>
                        </div>

                        <!-- File input -->
                        <div id="fileInputWrapper">
                            <input type="file" class="form-control" id="media_file" name="media_file" accept="">
                            <div class="form-text" id="fileSizeInfo">Max size: 5 MB</div>
                        </div>

                        <!-- URL input -->
                        <div id="urlInputWrapper" style="display: none;">
                            <input type="text" class="form-control" id="media_url" name="media_url" placeholder="https://example.com/image.jpg">
                        </div>

                        <!-- Скрытое поле для хранения выбранного значения -->
                        <input type="hidden" id="setting_value" name="setting_value" value="">
                    </div>

                    <!-- Обычные поля для Value (скрываются для image/video) -->
                    <div class="mb-3" id="standardValueGroup">
                        <label for="setting_value_text" class="form-label">Value</label>
                        <textarea class="form-control" 
                                  id="setting_value_text" 
                                  name="setting_value_text" 
                                  rows="3"
                                  placeholder="Enter setting value"><?= htmlspecialchars($_POST['setting_value'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="2"
                                  placeholder="Brief description of this setting"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_public" 
                                   name="is_public" 
                                   value="1"
                                   <?= isset($_POST['is_public']) ? 'checked' : 'checked' ?>>
                            <label class="form-check-label" for="is_public">
                                Public Setting (visible through API)
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Create Setting
                    </button>
                    <a href="<?= BASE_URL?>/admin/settings" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Common Settings Examples</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Footer Settings:</h6>
                        <ul class="list-unstyled">
                            <li><code>footer_copyright</code> - Copyright text</li>
                            <li><code>footer_phone</code> - Contact phone</li>
                            <li><code>footer_email</code> - Contact email</li>
                            <li><code>footer_address</code> - Physical address</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>General Settings:</h6>
                        <ul class="list-unstyled">
                            <li><code>site_name</code> - Website name</li>
                            <li><code>site_description</code> - Meta description</li>
                            <li><code>contact_email</code> - Main contact email</li>
                            <li><code>contact_phone</code> - Main contact phone</li>
                        </ul>
                    </div>
                </div>
            </div>
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
    const submitBtn = document.getElementById('submitBtn');

    // Обработка смены типа
    typeSelect.addEventListener('change', function() {
        const type = this.value;
        const isMedia = (type === 'image' || type === 'video');

        mediaGroup.style.display = isMedia ? 'block' : 'none';
        standardGroup.style.display = isMedia ? 'none' : 'block';

        if (isMedia) {
            // Настройка accept и лимита размера
            if (type === 'image') {
                fileInput.accept = 'image/*';
                fileSizeInfo.textContent = 'Max size: 5 MB';
                fileInput.dataset.maxSize = '5242880'; // 5MB
            } else if (type === 'video') {
                fileInput.accept = 'video/*';
                fileSizeInfo.textContent = 'Max size: 20 MB';
                fileInput.dataset.maxSize = '20971520'; // 20MB
            }

            // Сброс значений
            fileInput.value = '';
            urlInput.value = '';
            hiddenValue.value = '';

            // По умолчанию выбран File
            document.getElementById('mediaFile').checked = true;
            fileWrapper.style.display = 'block';
            urlWrapper.style.display = 'none';
        } else {
            // Для обычных типов
            textValue.value = hiddenValue.value || '';
        }
    });

    // Переключение между File и URL
    document.querySelectorAll('input[name="media_source"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'file') {
                fileWrapper.style.display = 'block';
                urlWrapper.style.display = 'none';
                hiddenValue.value = '';
            } else {
                fileWrapper.style.display = 'none';
                urlWrapper.style.display = 'block';
                fileInput.value = '';
                hiddenValue.value = urlInput.value.trim();
            }
        });
    });

    // При вводе URL обновляем скрытое поле
    urlInput.addEventListener('input', function() {
        hiddenValue.value = this.value.trim();
    });

    // Валидация файла (размер и тип)
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

        // Для image/video проверяем тип
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

        // Здесь можно добавить отображение имени файла или превью
        //hiddenValue.value = file.name;
    });

    // Обработка отправки формы — проверка, что выбрано что-то одно
    document.querySelector('#submitBtn').addEventListener('click', function(e) {
        const type = typeSelect.value;
        if (type === 'image' || type === 'video') {
            const isFile = document.getElementById('mediaFile').checked;
            const fileSelected = fileInput.files.length > 0;
            const urlEntered = urlInput.value.trim() !== '';

            if (!fileSelected && !urlEntered) {
                showError('Please either upload a file or enter a URL.');
                return;
            }

            // Если выбран URL, но поле скрыто — синхронизируем
            if (!isFile) {
                hiddenValue.value = urlInput.value.trim();
            } else if (fileSelected) {
                // Если файл выбран, но не загружен на сервер — нужно обработать
                // В этом примере просто сохраняем имя файла
                hiddenValue.value = fileInput.files[0].name;
            }
        } else {
            hiddenValue.value = textValue.value;
        }
        document.querySelector('.create-form').requestSubmit();
    });

    // Инициализация при загрузке
    setTimeout(() => {
        typeSelect.dispatchEvent(new Event('change'));
    }, 100);

    // Пример подсказки для ключа
    document.getElementById('setting_key').addEventListener('input', function() {
        const key = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '_');
        this.value = key;
    });
});
</script>