<h2>Créer un nouvel album</h2>

<form method="POST" enctype="multipart/form-data" id="albumForm" class="create-form">
    <div class="row">
        <!-- Colonne gauche - Informations album -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="album_name" class="form-label">Nom de l'album *</label>
                <input type="text" class="form-control" id="album_name" name="album_name" required>
            </div>
            
            <div class="mb-3">
                <label for="artist_name" class="form-label">Artiste *</label>
                <select class="form-control" id="artist_name" name="artist_name" required>
                    <option value="new" selected>+ Ajouter nouvel artiste</option>
                    <!-- <option value="" >Choisir artiste</option> -->
                </select>
            </div>
            
            <div class="mb-3" id="new_artist_field" style="display: block;">
                <label for="new_artist_name" class="form-label">Nom du nouvel artiste *</label>
                <input type="text" class="form-control" id="new_artist_name" name="new_artist_name" required>
            </div>
            
            <div class="mb-3">
                <label for="genre" class="form-label">Genre *</label>
                <input type="text" class="form-control" id="genre" name="genre" required>
            </div>
            
            <div class="mb-3">
                <label for="release_year" class="form-label">Année de sortie *</label>
                <input type="date" class="form-control" id="release_year" name="release_year" required>
            </div>
            
            <div class="mb-3">
                <label for="album_description" class="form-label">Description</label>
                <textarea class="form-control" id="album_description" name="album_description" rows="4"></textarea>
            </div>
            
            <div class="mb-3">
                <label for="album_image" class="form-label">Cover de l'album</label>
                <input type="file" class="form-control" id="album_image" name="album_image" accept="image/*" required>
                <img id="album-preview-img" style="display:none; margin-top: 10px; max-width: 200px;"/>
            </div>

            <!-- qrcode -->
            <div class="hidden" id="qrcode-modal">
                <div class="qr-code-container card box">
                    <div class="qr-code">
                        <img id="qr-code-image" class="qr-code-image"/>
                    </div>
                    <a class="btn btn-primary qrcode-img-download-link" download="qrcode.png">
                        <i class="fas fa-download"></i>Download</a>
                    <div class="btn btn-success qrcode-share-btn"><i class="fa-solid fa-share-nodes"></i>share</div>
                    <div class="qr-code-info">
                        Download album
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Colonne droite - Ajout des tracks -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong>Ajouter des pistes</strong>
                </div>
                <div class="card-body">
                    <!-- <div class="mb-3">
                        <label for="track_name" class="form-label">Nom de la piste *</label>
                        <input type="text" class="form-control" id="track_name" placeholder="Ex: Intro">
                    </div> -->
                    
                    <!-- <div class="mb-3">
                        <label for="track_image" class="form-label">Image de la piste</label>
                        <input type="file" class="form-control" id="track_image" accept="image/*">
                    </div> -->
                    <!-- uploading multiple files -->
                    <div class="mb-3">
                        <label for="audio_files" class="form-label">Fichier audio *</label>
                        <input type="file" class="form-control" id="audio_files" accept="audio/mp3,audio/wav" multiple>
                        <div class="form-text">Formats acceptés: MP3, WAV</div>
                    </div>

                    <!-- <div class="mb-3">
                        <label for="audio_file" class="form-label">Fichier audio *</label>
                        <input type="file" class="form-control" id="audio_file" accept="audio/mp3,audio/wav">
                        <div class="form-text">Formats acceptés: MP3, WAV</div>
                    </div> -->
                    
                    <button type="button" class="btn btn-success" id="addTrackBtn">
                        <i class="fa-solid fa-plus"></i> Ajouter cette piste
                    </button>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <strong>Pistes ajoutées (<span id="trackCount">0</span>)</strong>
                </div>
                <div class="card-body" id="tracksList" style="max-height: 400px; overflow-y: auto;">
                    <div class="text-muted text-center">Aucune piste ajoutée pour le moment</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Conditions d'utilisation -->
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="copyright">
                <div class="mb-3">
                    <input type="checkbox" name="copyright" id="copyright" required />
                    <label for="copyright">
                        &copy; J'accepte les conditions de droit d'auteur et donne mon consentement pour publier mon contenu
                    </label>
                </div>
                <div class="mb-3">
                    <label for="owner-name" class="form-label">
                        Nous respectons les droits de propriété du contenu.
                        Veuillez entrer votre nom pour signer votre contenu publié et réserver vos droits.
                    </label>
                    <input type="text" value="<?= $_SESSION['user']['name']?>" class="form-control" id="owner-name" name="owner_name"
                           placeholder="Signature de propriété (nom)" required/>
                </div>
            </div>
            <div class="terms-conds" style="margin-bottom: 10px;">
                <input type="checkbox" id="consent" name="consent" required>
                <label for="consent">Je suis d'accord avec les 
                    <a href="/terms" class="text-purple-600 hover:underline">Conditions d'utilisation</a>
                    et la 
                    <a href="/privacy" class="text-purple-600 hover:underline">Politique de confidentialité</a>
                </label>
            </div>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Créer l'album</button>
    <a href="<?= BASE_URL?>/user/me/albums" class="btn btn-secondary cancel">Annuler</a>
</form>

<script defer src="<?= BASE_URL?>/static/js/album-create-page.js" type="module"></script>