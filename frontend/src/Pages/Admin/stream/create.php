<h2>Kreyasyon strimin</h2>

<form method="POST" class="create-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="stream_title" class="form-label">Non Strimin *</label>
                <input type="text" class="form-control" id="stream_title" name="stream_title" required>
            </div>
            
            <div class="mb-3">
                <label for="start_time" class="form-label">Dat ak le kòmansman *</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
            </div>
            
            <div class="mb-3">
                <label for="end_time" class="form-label">Dat ak le finisyon</label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time">
                <div class="form-text">Kite vid si ou pa konnen le fini an</div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsyon</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Dekri kontni strim lan..."></textarea>
            </div>
            
            <div class="mb-3">
                <label for="price" class="form-label">Pri (USD)</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="0">
                <div class="form-text">0 si strim lan gratis</div>
            </div>
            
            <div class="mb-3">
                <label for="max_viewers" class="form-label">Limit spectateur</label>
                <input type="number" class="form-control" id="max_viewers" name="max_viewers" min="0" placeholder="Sans limit">
                <div class="form-text">Kantite maksimòm moun ki ka gade an menm tan</div>
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
                            <input class="form-check-input" type="checkbox" id="enable_cover_image" disabled>
                            <label class="form-check-label" for="enable_cover_image">Ajoute imaj kouvèti</label>
                        </div>
                        <div class="form-text">Aktivasyon sa a pèmèt ou chwazi yon imaj</div>
                    </div>
                    
                    <div class="mb-3" id="cover_image_container" style="display: none;">
                        <label for="cover_image" class="form-label">Chwazi imaj</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" disabled>
                        <div id="image_preview" class="mt-2" style="display: none;">
                            <img id="preview_img" src="#" alt="Preview" style="max-width: 100%; max-height: 150px;">
                            <button type="button" class="btn btn-sm btn-danger mt-1" onclick="removeImage()">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </div>
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
                                   placeholder="Kle jenerasyon otomatik" readonly>
                            <button type="button" class="btn btn-outline-secondary" onclick="generateNewKey()">
                                <i class="fas fa-sync-alt"></i> Jenere
                            </button>
                        </div>
                        <div class="form-text">Kle sa itilize pou konekte ak OBS oswa lòt lojisyel</div>
                    </div>
                    
                    <div class="mt-2 text-muted small" id="stream_url_display" style="display: none;">
                        <strong>URL strim:</strong><br>
                        <code id="stream_url_preview"></code>
                        <input type="hidden" id="stream_url" name="stream_url"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Kreye strim
        </button>
        <a href="<?= BASE_URL?>/admin/stream" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>Anile
        </a>
    </div>
</form>

<script>
// Génération automatique du stream key
function generateNewKey() {
    // Utilisation de la fonction de génération de clé du backend
    fetch('<?= BASE_URL?>/api/livestream/generate-key', {
        method: 'POST',
        headers: {
            Authorization: `Bearer ${sessionStorage.getItem('token')}`
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('stream_key').value = data.stream_key;
            updateStreamUrl(data.stream_key);
        } else {
            // Fallback: génération locale
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
        // Fallback local
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
    document.getElementById('stream_url').value = url;
    document.getElementById('stream_url_display').style.display = 'block';
}

// Gestion de l'image de couverture
document.getElementById('enable_cover_image').addEventListener('change', function() {
    const container = document.getElementById('cover_image_container');
    if (this.checked) {
        container.style.display = 'block';
        document.getElementById('cover_image').disabled = false;
    } else {
        container.style.display = 'none';
        document.getElementById('cover_image').value = '';
        document.getElementById('cover_image').disabled = true;
        document.getElementById('image_preview').style.display = 'none';
    }
});

// Preview image
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

function removeImage() {
    document.getElementById('cover_image').value = '';
    document.getElementById('image_preview').style.display = 'none';
    document.getElementById('preview_img').src = '#';
}

// Générer une clé au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    generateNewKey();
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
.form-check-input:disabled {
    opacity: 0.5;
}
</style>