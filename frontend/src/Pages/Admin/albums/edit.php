<h2>Redije Album</h2>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (isset($success)): ?>
<div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" id="albumForm" class="edit-form">
    <div class="row">
        <!-- Colonne gauche - Informations album -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="album_name" class="form-label">Non album *</label>
                <input type="text" class="form-control" id="album_name" name="album_name" 
                       value="<?= htmlspecialchars($album['name'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="artist_id" class="form-label">Atis *</label>
                <select class="form-control" id="artist_id" name="artist_id" required>
                    <option value="">Chwazi atis</option>
                    <option value="<?= $album['artist_id'] ?>" selected>
                        <?= htmlspecialchars($album['artist_name']) ?>
                    </option>
                    <option value="new">+ Ajoute nouvo atis</option>
                </select>
            </div>
            
            <div class="mb-3" id="new_artist_field" style="display: none;">
                <label for="new_artist_name" class="form-label">Non nouvo atis *</label>
                <input type="text" class="form-control" id="new_artist_name" name="new_artist_name"
                       value="<?= htmlspecialchars($new_artist_name ?? '') ?>">
            </div>
            
            <div class="mb-3">
                <label for="genre" class="form-label">Genre *</label>
                <input type="text" class="form-control" id="genre" name="genre" 
                       value="<?= htmlspecialchars($album['genre'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="release_year" class="form-label">Ane soti *</label>
                <input type="date" class="form-control" id="release_year" name="release_year" 
                       value="<?= htmlspecialchars($album['release_year'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsyon</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($album['description'] ?? '') ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="likes" class="form-label">J'aimes</label>
                        <input type="number" class="form-control" id="likes" name="likes" 
                               value="<?= $album['likes'] ?? 0 ?>" min="0" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="downloads" class="form-label">Telechajman</label>
                        <input type="number" class="form-control" id="downloads" name="downloads" 
                               value="<?= $album['downloads'] ?? 0 ?>" min="0" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="shares" class="form-label">Pataje</label>
                        <input type="number" class="form-control" id="shares" name="shares" 
                               value="<?= $album['shares'] ?? 0 ?>" min="0" readonly>
                    </div>
                </div>
            </div>
            <!-- qrcode -->
            <div class="" id="qrcode-modal">
                <div class="qr-code-container card box">
                    <div class="qr-code">
                        <img id="qr-code-image" class="qr-code-image" src="data:image/png;base64,<?= $qrcode_base64?>"/>
                    </div>
                    <a class="btn btn-primary qrcode-img-download-link" href="data:image/png;base64,<?= $qrcode_base64?>" download="qrcode.png">
                        <i class="fas fa-download"></i>Download</a>
                    <div class="btn btn-success qrcode-share-btn"><i class="fa-solid fa-share-nodes"></i>share</div>
                    <div class="qr-code-info">
                        Download album
                    </div>
                </div>
                <script>
                	document.querySelector(".qrcode-share-btn").addEventListener('click', async (e)=>{
                        e.stopPropagation();
                        if(navigator.share){
                            await navigator.share({
                                title: "Album",
                                text: "Download the latest album",
                                url: `https://konektem.net/konektem/albums/<?= $album['id']?>/download`
                            });
                        } else {
                            alert('Your browser doesnt support sharing. Please download the code and share on your favorite platforms');
                        }
                    });
                </script>
            </div>
        </div>
        
        <!-- Colonne droite - Cover et gestion des pistes -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="album_image" class="form-label">Cover album</label>
                <input type="file" class="form-control" id="album_image" name="album_image" 
                       accept="image/*">
                
                <?php if (!empty($album['image_url'])): ?>
                <div class="mt-2">
                    <p>Aktyel Cover:</p>
                    <img src="<?= htmlspecialchars($album['image_url']) ?>" width="200" class="img-thumbnail" id="currentCover">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                        <label class="form-check-label" for="remove_image">
                            Efase aktyel Cover
                        </label>
                    </div>
                </div>
                <?php else: ?>
                <div class="mt-2 text-center">
                    <div class="text-muted border rounded p-4">
                        <i class="fas fa-images fa-2x mb-2"></i>
                        <br>Cover non ajoute
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Section des pistes existantes -->
            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <strong>Pistes nan album (<span id="trackCount"><?= count($album['tracks'] ?? []) ?></span>)</strong>
                </div>
                <div class="card-body" id="tracksList" style="max-height: 300px; overflow-y: auto;">
                    <?php if (empty($album['tracks'])): ?>
                    <div class="text-muted text-center">Pa gen piste nan album sa a</div>
                    <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($album['tracks'] as $index => $track): ?>
                        <div class="list-group-item" data-track-id="<?= $track['id'] ?>">
                            <div class="d-flex align-items-center">
                                <div class="track-preview me-3">
                                    <?php if (!empty($track['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($track['image_url']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                    <div style="width: 50px; height: 50px; background: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                        🎵
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <strong><?= htmlspecialchars($track['track_name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($track['artist_name'] ?? '') ?></small>
                                </div>
                                <div class="btn-group">
                                    <a href="<?= BASE_URL?>/admin/albums/<?= $track['id'] ?>/edit" class="btn btn-sm btn-warning" title="Modifye piste">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger remove-track" data-track-id="<?= $track['id'] ?>" title="Efase piste">
                                        <i class="fa-solid fa-bin"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Section pour ajouter de nouvelles pistes -->
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <strong>Ajoute nouvo piste</strong>
                </div>
                <div class="card-body">
                    <!-- <div class="mb-2">
                        <label for="new_track_name" class="form-label">Non piste *</label>
                        <input type="text" class="form-control" id="new_track_name" placeholder="Ex: Intro">
                    </div> -->
                    
                    <!-- <div class="mb-2">
                        <label for="new_track_image" class="form-label">Image piste</label>
                        <input type="file" class="form-control" id="new_track_image" accept="image/*">
                    </div> -->
                    
                    <div class="mb-2">
                        <label for="new_audio_files" class="form-label">Fichye audio *</label>
                        <input type="file" class="form-control" id="new_audio_files" accept="audio/mp3,audio/wav" multiple>
                        <div class="form-text">Foma aksepte: MP3, WAV</div>
                    </div>
                    
                    <button type="button" class="btn btn-success btn-sm" id="addTrackBtn">
                        <i class="fa-solid fa-plus"></i> Ajoute piste sa a
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Renouvle album
        </button>
        <a href="<?= BASE_URL?>/admin/albums" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>Anile
        </a>
    </div>
</form>
<script>
    window.albumId = <?= $album['id']?>;
</script>
<script defer src="<?= BASE_URL . '/dist/apse.7c1234126e356e74d78b.js'?>"></script>
<style>
    :root{
        --qr-container-w: 300px;
    }
    @media (max-width: 480px){
        :root{
            --qr-container-w: 200px;
        }
    }
    #qrcode-modal{
        display: block;
    }
    .qr-code-container{
        width: var(--qr-container-w);
        border-radius: 9px;
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        box-shadow: 0px 0px 5px grey;
        background: white;
        margin: auto;
        top: 25%;
    }
    .qrcode-img-download-link{
        background: #2da2d1;
    }
    .qr-code{
        width: 90%;
        margin: auto;
    }
    .qr-code img{
        width: 100%;
    }
    .qrcode-share-btn{
        background: #06cb4e;
    }
    .close-qrcode-modal-btn{
        background: #ff0000;
    }
    .qr-code-container .btn{
        font-weight: bold;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        flex: 0 0 auto;
        width: fit-content;
        padding: 5px;
    }
</style>
<style>
.card {
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 20px;
}
.card-header {
    padding: 10px 15px;
    border-bottom: 1px solid #ddd;
    border-radius: 8px 8px 0 0;
}
.bg-primary {
    background-color: #007bff;
}
.bg-secondary {
    background-color: #6c757d;
}
.text-white {
    color: white;
}
.list-group-item {
    padding: 8px 12px;
    border-bottom: 1px solid #eee;
}
.d-flex {
    display: flex;
}
.align-items-center {
    align-items: center;
}
.me-2, .me-3 {
    margin-right: 10px;
}
.flex-grow-1 {
    flex: 1;
}
.btn-group {
    display: flex;
    gap: 5px;
}
.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-warning {
    background-color: #ffc107;
    color: #212529;
}
.btn-danger {
    background-color: #dc3545;
    color: white;
}
.btn-success {
    background-color: #28a745;
    color: white;
}
.btn-primary {
    background-color: #007bff;
    color: white;
}
.btn-secondary {
    background-color: #6c757d;
    color: white;
}
.mt-2, .mt-3, .mt-4 {
    margin-top: 15px;
}
.mb-2, .mb-3 {
    margin-bottom: 15px;
}
.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
textarea.form-control {
    resize: vertical;
}
.img-thumbnail {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 2px;
}
.alert {
    padding: 12px 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}
.col-md-4 {
    flex: 0 0 33.33%;
    max-width: 33.33%;
    padding: 0 15px;
}
.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding: 0 15px;
}
.text-muted {
    color: #6c757d;
}
.text-center {
    text-align: center;
}
</style>

<?php //require_once ADMIN_PATH . '/views/layout/footer.php'; ?>