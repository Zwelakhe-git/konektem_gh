<h2>Redije strim</h2>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST" class="edit-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="stream_title" class="form-label">Non strim *</label>
                <input type="text" class="form-control" id="stream_title" name="stream_title" 
                       value="<?= htmlspecialchars($stream['name'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="start_time" class="form-label">Dat e le kòmansman *</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time" 
                       value="<?= date('Y-m-d\TH:i', strtotime($stream['start_time'] ?? 'now')) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="end_time" class="form-label">Dat e le finisyon</label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time" 
                       value="<?= isset($stream['end_time']) ? date('Y-m-d\TH:i', strtotime($stream['end_time'])) : '' ?>">
                <div class="form-text">Kite vid si ou pa konnen le fini an</div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsyon</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($stream['description'] ?? '') ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="price" class="form-label">Pri (USD)</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" 
                       value="<?= $stream['price'] ?? 0 ?>">
                <div class="form-text">0 si strim lan gratis</div>
            </div>
            
            <div class="mb-3">
                <label for="max_viewers" class="form-label">Limit spectateur</label>
                <input type="number" class="form-control" id="max_viewers" name="max_viewers" min="0" 
                       value="<?= $stream['max_viewers'] ?? '' ?>" placeholder="Sans limit">
                <div class="form-text">Kantite maksimòm moun ki ka gade an menm tan</div>
            </div>
            
            <div class="mb-3">
                <label for="is_active" class="form-label">Estati</label>
                <select class="form-control" id="is_active" name="is_active">
                    <option value="1" <?= ($stream['is_active'] ?? 1) ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= isset($stream['is_active']) && !$stream['is_active'] ? 'selected' : '' ?>>Inaktif</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Imaj kouvèti</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_cover_image" 
                                   <?= isset($stream['cover_image']) && !empty($stream['cover_image']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="enable_cover_image">Ajoute imaj kouvèti</label>
                        </div>
                    </div>
                    
                    <div class="mb-3" id="cover_image_container" style="<?= isset($stream['cover_image']) && !empty($stream['cover_image']) ? 'display: block;' : 'display: none;' ?>">
                        <?php if (isset($stream['cover_image']) && !empty($stream['cover_image'])): ?>
                            <div class="mb-2">
                                <img src="<?= $stream['cover_image'] ?>" alt="Cover image" style="max-width: 100%; max-height: 150px;">
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeCurrentImage()">
                                    <i class="fas fa-times"></i> Remove current image
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <label for="cover_image" class="form-label">Chwazi imaj (opsyonèl)</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                        <div id="image_preview" class="mt-2" style="display: none;">
                            <img id="preview_img" src="#" alt="Preview" style="max-width: 100%; max-height: 150px;">
                            <button type="button" class="btn btn-sm btn-danger mt-1" onclick="removeNewImage()">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </div>
                        <div class="form-text">Imaj rekòmande: 1920x1080 px</div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Kle strim lan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <label for="stream_key" class="form-label">Kle strim</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="stream_key" name="stream_key" 
                                   value="<?= htmlspecialchars($stream['stream_key'] ?? '') ?>" readonly>
                            <button type="button" class="btn btn-outline-secondary" onclick="generateNewKey()">
                                <i class="fas fa-sync-alt"></i> Nouvo kle
                            </button>
                        </div>
                        <div class="form-text">Kle sa itilize pou konekte ak OBS</div>
                    </div>
                    
                    <div class="mt-2" id="stream_url_display">
                        <strong>URL strim (read-only):</strong><br>
                        <code id="stream_url_preview"><?= htmlspecialchars($stream['stream_url'] ?? '') ?></code>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Enfomasyon de strim la</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>ID:</strong> <?= $stream['id'] ?? 'Nouvo' ?>
                    </div>
                    <div class="mb-2">
                        <strong>Estati:</strong>
                        <?php
                        $startTime = strtotime($stream['start_time'] ?? '');
                        $endTime = isset($stream['end_time']) ? strtotime($stream['end_time']) : null;
                        $now = time();
                        
                        if ($startTime > $now) {
                            echo '<span class="badge bg-warning">Planifye</span>';
                        } elseif ($startTime <= $now && (!$endTime || $endTime > $now)) {
                            echo '<span class="badge bg-success">An difizyon</span>';
                        } else {
                            echo '<span class="badge bg-secondary">Fini</span>';
                        }
                        ?>
                    </div>
                    <div class="mb-2">
                        <strong>Kreyasyon:</strong><br>
                        <?= date('d.m.Y H:i', strtotime($stream['created_at'] ?? 'now')) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Sovgade chanjman
        </button>
        <a href="<?= BASE_URL?>/admin/stream" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>Anile
        </a>
    </div>
</form>

<script>
function generateNewKey() {
    if (!confirm('Èske ou vle jenere yon nouvo kle strim? Sa pral chanje tout URL yo.')) {
        return;
    }
    
    // Génération via API
    fetch('<?= BASE_URL?>/api/stream/generate-key', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('stream_key').value = data.stream_key;
            updateStreamUrl(data.stream_key);
        } else {
            // Fallback
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let newKey = '';
            for (let i = 0; i < 16; i++) {
                newKey += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('stream_key').value = newKey;
            updateStreamUrl(newKey);
        }
    })
    .catch(error => {
        console.error('Error generating key:', error);
        // Fallback
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let newKey = '';
        for (let i = 0; i < 16; i++) {
            newKey += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('stream_key').value = newKey;
        updateStreamUrl(newKey);
    });
}

function updateStreamUrl(streamKey) {
    const baseUrl = '<?= $_ENV['ANT_MEDIA_BASE_URL'] ?? 'https://try.antmedia.io/zwelakhemzwet/play.html' ?>';
    const url = baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'id=' + streamKey;
    document.getElementById('stream_url_preview').textContent = url;
}

// Gestion de l'image de couverture
document.getElementById('enable_cover_image').addEventListener('change', function() {
    const container = document.getElementById('cover_image_container');
    if (this.checked) {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
        document.getElementById('cover_image').value = '';
        document.getElementById('image_preview').style.display = 'none';
    }
});

// Preview new image
document.getElementById('cover_image').addEventListener('change', function(e) {
    const preview = document.getElementById('image_preview');
    const img = document.getElementById('preview_img');
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(this.files[0]);
    } else {
        preview.style.display = 'none';
    }
});

function removeCurrentImage() {
    if (confirm('Èske ou vle retire imaj kouvèti aktyèl la?')) {
        document.getElementById('cover_image').value = '';
        // Ajouter un champ caché pour indiquer la suppression
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_cover_image';
        hiddenInput.value = '1';
        document.querySelector('form').appendChild(hiddenInput);
        
        // Cacher l'image actuelle
        const imgContainer = document.querySelector('#cover_image_container img')?.parentElement;
        if (imgContainer) {
            imgContainer.style.display = 'none';
        }
    }
}

function removeNewImage() {
    document.getElementById('cover_image').value = '';
    document.getElementById('image_preview').style.display = 'none';
    document.getElementById('preview_img').src = '#';
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Si une image existe déjà, activer le switch
    const hasImage = <?= isset($stream['cover_image']) && !empty($stream['cover_image']) ? 'true' : 'false' ?>;
    if (hasImage) {
        document.getElementById('enable_cover_image').checked = true;
        document.getElementById('cover_image_container').style.display = 'block';
    }
});
</script>

<style>
.card {
    border: 1px solid #e3f2fd;
}
.card-header {
    background-color: #e3f2fd;
    font-weight: 600;
}
#cover_image_container img {
    border-radius: 5px;
    border: 1px solid #ddd;
}
</style>