<h2>Redije Trak</h2>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="edit-form">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="title" class="form-label">Non trak *</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= htmlspecialchars($track['title'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="artist_id" class="form-label">Аtis *</label>
                <select class="form-control" id="artist_id" name="artist_id" required>
                    <option value="new">Chwazi atis</option>
                    <option value="<?= $track['artist_id'] ?? $_SESSION['user']['id'] ?>" selected>
                        <?= htmlspecialchars($track['artist_name'] ?? $_SESSION['user']['name']) ?>
                    </option>
                    <option value="new">+ Ajoute nouvo atis</option>
                </select>
            </div>
            
            <div class="mb-3" id="new_artist_field" style="display: none;">
                <label for="new_artist_name" class="form-label">Non nouvo atis *</label>
                <input type="text" class="form-control" id="new_artist_name" name="new_artist_name">
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">genre *</label>
                <input type="text" class="form-control" id="genre" placeholder="music genre" name="genre" value="<?= htmlspecialchars($track['genre'] ?? '')?>" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="track_image" class="form-label">Cover trak</label>
                <input type="file" class="form-control" id="track_image" name="track_image" 
                       accept="image/*" data-preview="music-track-prev-img">
                <img id="music-track-prev-img" style="display:none;margin-top: 10px; width: 150px; height: 150px"/>
                <?php if ($track['image_url']): ?>
                <div class="mt-2">
                    <p>Aktyel Cover:</p>
                    <img src="<?= $track['image_url'] ?>" width="200" class="img-thumbnail">
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
                        <i class="fas fa-music fa-2x mb-2"></i>
                        <br>Cover non ajou
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="audio_file" class="form-label">Odyo li *</label>
                <input type="file" class="form-control" id="audio_file" name="audio_file" accept="audio/mp3">
                <div class="form-text">Foma aksepte: MP3, WAV</div>
            </div>
            
            <div class="card mt-3">
                <div class="card-body">
                    <h6>Enfomasyon fichye</h6>
                    <?php if ($track['url']): ?>
                    <p><strong>fichye:</strong> <?= basename($track['url']) ?></p>
                    <p><strong>Tip:</strong> <?= $track['mime_type'] ?></p>
                    <?php else: ?>
                    <p class="text-muted">Fichye non telechaje</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <button type="button" id="submitBtn" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Renouvle trak
        </button>
        <a href="<?= BASE_URL?>/user/me/music" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>Refize
        </a>
    </div>
</form>

