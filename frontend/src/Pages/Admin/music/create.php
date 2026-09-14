<h2>Ajoute trak</h2>

<form method="POST" id="main-form" enctype="multipart/form-data" class="create-form">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="track_name" class="form-label">Non trak *</label>
                <input type="text" class="form-control" id="track_name" name="title" required>
            </div>
            
            <div class="mb-3">
                <label for="artist_name" class="form-label">Аtis *</label>
                <select class="form-control" id="artist_name" name="artist_name" required>
                    <option value="new">+ Ajoute nouvo atis</option>
                    <option value="<?= $_SESSION['user']['id'] ?>" selected><?= htmlspecialchars($_SESSION['user']['name']) ?></option>
                </select>
            </div>
            
            <div class="mb-3" id="new_artist_field" style="display: none;">
                <label for="new_artist_name" class="form-label">Non nouvo atis *</label>
                <input type="text" class="form-control" id="new_artist_name" name="new_artist_name">
            </div>
            
            <div class="mb-3">
                <label for="genre" class="form-label">genre *</label>
                <input type="text" class="form-control" id="genre" name="genre" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="track_image" class="form-label">Cover trak</label>
                <input type="file" class="form-control" data-preview="music-track-prev-img" id="track_image" name="track_image" accept="image/*">
                <img id="music-track-prev-img" style="display:none;margin-top: 10px; width: 150px; height: 150px"/>
            </div>
            
            <div class="mb-3">
                <label for="audio_file" class="form-label">Odyo li *</label>
                <input type="file" class="form-control" id="audio_file" name="audio_file" accept="audio/mp3" required>
                <div class="form-text">Foma aksepte: MP3, WAV</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        	<div class="mb-3">
                <input type="checkbox" id="cb-filter" onchange="document.querySelector('input#position').disabled = !this.checked; this.parentElement.querySelector('#position-hint').classList.toggle('d-none', !this.checked);"/>
                <label for="cb-filter" class="form-label">ajoute nan paj prensipal</label>
                <input type="hidden" id="position" name="position" value="mainpage" disabled>
                <div class="p-3 mb-3 text-warning bg-warning-subtle rounded-4 d-none" id="position-hint">
                    Tracks appearing on the mainpage are limited to 5. Tracks added beyond this limit will not appear on the main page.
                    Please remove tracks to free space for new ones
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="order_no">Plasman nan paj prensipal la</label>
                <input type="number" min="0" name="order_no" id="order_no"/>
            </div>
        </div>
    </div>
    
    <button type="button" id="submitBtn" class="btn btn-primary">Ajoute trak</button>
    <a href="<?= BASE_URL?>/admin/music" class="btn btn-secondary cancel">Anile</a>
</form>

