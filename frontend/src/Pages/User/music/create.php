<h2>Ajoute trak</h2>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

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
                    <option value="">Chwazi atis</option>
                    <option value="<?= $_SESSION['user']['id'] ?>" selected>
                        <?= htmlspecialchars($_SESSION['user']['name']) ?>
                    </option>
                    
                </select>
            </div>
            
            <div class="mb-3" id="new_artist_field" style="display: none;">
                <label for="new_artist_name" class="form-label">Non nouvo atis *</label>
                <input type="text" class="form-control" id="new_artist_name" name="new_artist_name" disabled>
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
        <!-- replace with: $_SESSION['role'] == 'ADMIN'-->
    <div class="row copyright">
        <div class="col-md-6">
            <div class="agreement-text scrollable" style="padding: 8px;height: 150px;">
                <!-- paste text here -->
                <?php require __DIR__ . '/../../../Components/userAgreementText.php'; ?>
            </div>
            <style>
                .scrollable{
                    overflow-y: auto;
                    overflow-x: hidden;
                }
                .scrollable::-webkit-scrollbar {
                    border-radius: 8px;
                    width: 5px;
                }
                .scrollable::-webkit-scrollbar-track {
                    background-color: transparent;
                }
                .scrollable::-webkit-scrollbar-thumb {
                    border-radius: 10px;
                    background-color: #b1afaf;
                    color: red;
                }
            </style>
            <div class="mb-3">
                <input type="checkbox" id="consent" name="consent" disabled required>
                <label for="consent">M dakò ak 
                    <a href="/terms" class="text-purple-600 hover:underline">Kondisyon tèm</a>
                    ak 
                    <a href="/privacy" class="text-purple-600 hover:underline">Politik konfidansyalite</a>
                </label>
            </div>
            <div class="mb-3">
                <input type="checkbox" name="copyright" id="copyright" disabled required />
                <label for="copyright">&copy; I accept copyright conditions and give consent to publish my content
                </label>
            </div>
            <script>
                let agreementTextArea = document.querySelector('.agreement-text');
                let currentScroll = agreementTextArea.scrollTop;
                let maxScrollTop = agreementTextArea.scrollHeight - agreementTextArea.clientHeight;

                if(agreementTextArea.scrollTop >= maxScrollTop - 0.5){
                    document.querySelector('input#consent').disabled = false;
                    document.querySelector('input#copyright').disabled = false;
                }

                agreementTextArea.addEventListener('scroll', ()=>{
                    if(agreementTextArea.scrollTop >= maxScrollTop - 0.5){
                        document.querySelector('input#consent').disabled = false;
                        document.querySelector('input#copyright').disabled = false;
                    }
                });
            </script>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="owner-name" class="box form-label">
                    We observe rights of ownership of Content.
                    Please enter your name to sign your published content to reserve your rights.
                </label>
                <input type="text" class="form-control" id="owner-name" name="owner-name"
                       placeholder="ownership signature (name)" value="<?= htmlspecialchars($_SESSION['user']['name']) ?>" required/>
            </div>
        </div>
        <div class="d-flex gap-1 items-center">
            <div class="checkbox">
                <div class="checkbox-fill"></div>
            </div>
            <input type="hidden" min="0" max="1" name="public" id="publish-inp" value="0"/>
            <div>
                <label for="publish-inp">publish</label>
                <p class="input-hint bold">(make available to the public)</p>
            </div>
        </div>
    </div>
    
    <button type="button" id="submitBtn" class="btn btn-primary">Ajoute trak</button>
    <a href="<?= BASE_URL?>/user/me/music" class="btn btn-secondary cancel">Anile</a>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const userConsentCB = document.querySelector('input#consent');
        const copyrightCB = document.querySelector('input#copyright');
        if(userConsentCB || copyrightCB){
            document.getElementById('submitBtn').disabled = true;

            userConsentCB.onchange = function(){
                document.getElementById('submitBtn').disabled = !this.checked || !copyrightCB.checked;
            }
            copyrightCB.onchange = function(){
                document.getElementById('submitBtn').disabled = !this.checked || !userConsentCB.checked;
            }
        }
    })
   
</script>