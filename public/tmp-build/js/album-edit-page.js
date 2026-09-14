import showAlert from './alert-modal.js';

document.addEventListener('DOMContentLoaded', function() {
    // Gestion du select artiste
    const artistSelect = document.getElementById('artist_id');
    const newArtistField = document.getElementById('new_artist_field');
    const newArtistInput = document.getElementById('new_artist_name');
    
    if (artistSelect) {
        artistSelect.addEventListener('change', function() {
            if (this.value === 'new') {
                newArtistField.style.display = 'block';
                newArtistInput.required = true;
            } else {
                newArtistField.style.display = 'none';
                newArtistInput.required = false;
            }
        });
    }
    
    // Preview nouvelle cover
    const coverInput = document.getElementById('album_image');
    if (coverInput) {
        coverInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('coverPreview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'coverPreview';
                        preview.style.width = '200px';
                        preview.style.marginTop = '10px';
                        preview.className = 'img-thumbnail';
                        coverInput.parentNode.appendChild(preview);
                    }
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Gestion des nouvelles pistes
    let newTracks = [];
    
    function showQRCode(base64Data, albumId){
        document.getElementById("qrcode-modal").style.display = 'block';
        let shareBtn = document.querySelector(".qrcode-share-btn");
        let qrCodeImage = document.getElementById('qr-code-image');
        let downloadBtn = document.querySelector('.qrcode-img-download-link');
        let dataUrl = `data:image/png;base64,${base64Data}`;
        qrCodeImage.src = dataUrl;
        downloadBtn.href = dataUrl;

        shareBtn.addEventListener('click', async ()=>{
            if(navigator.share){
                await navigator.share({
                    title: "Album",
                    text: "Download the latest album",
                    url: `https://konektem.net/konektem/albums/${albumId}/download`
                });
            } else {
                alert('Your browser doesnt support sharing. Please download the code and share on your favorite platforms');
            }
        });
    }
    function updateNewTracksPreview() {
        const container = document.getElementById('newTracksPreview');
        const countSpan = document.getElementById('newTrackCount');
        
        if (!container) return;
        
        if (newTracks.length === 0) {
            container.innerHTML = '<div class="text-muted text-center p-2">Pa gen nouvo piste ajoute</div>';
            if (countSpan) countSpan.textContent = '0';
            return;
        }
        
        if (countSpan) countSpan.textContent = newTracks.length;
        
        let html = '<div class="list-group mt-2">';
        newTracks.forEach((track, index) => {
            html += `
                <div class="list-group-item" data-new-index="${index}">
                    <div class="d-flex align-items-center">
                        <div class="track-preview me-2">
                            ${track.imagePreview ? `<img src="${track.imagePreview}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">` : '<div style="width: 40px; height: 40px; background: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 20px;">🎵</div>'}
                        </div>
                        <div class="flex-grow-1">
                            <strong>${escapeHtml(track.name)}</strong><br>
                            <small class="text-muted">${track.audioName || 'Fichye audio'}</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-new-track" data-index="${index}">✖</button>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
        
        // Ajouter les écouteurs pour les boutons supprimer
        document.querySelectorAll('.remove-new-track').forEach(btn => {
            btn.addEventListener('click', function() {
                const idx = parseInt(this.getAttribute('data-index'));
                newTracks.splice(idx, 1);
                updateNewTracksPreview();
                updateHiddenNewTracks();
            });
        });
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
    
    function updateHiddenNewTracks() {
        // Supprimer les anciens champs cachés
        document.querySelectorAll('.new-track-data').forEach(field => field.remove());
        
        // Ajouter les nouveaux champs cachés
        newTracks.forEach((track, index) => {
            if (track.name) {
                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = `new_tracks[${index}][name]`;
                nameInput.value = track.name;
                nameInput.className = 'new-track-data';
                document.querySelector('form').appendChild(nameInput);
            }
            
            if (track.audioFile instanceof File) {
                // Les fichiers seront gérés via FormData à la soumission
            }
        });
    }
    
    // Ajouter un conteneur pour les nouvelles pistes
    const addTrackBtn = document.getElementById('addTrackBtn');
    if (addTrackBtn) {
        // Créer le conteneur pour les nouvelles pistes si pas présent
        let newTracksContainer = document.getElementById('newTracksPreview');
        if (!newTracksContainer) {
            const tracksCard = document.querySelector('.card.mt-3:last-child');
            if (tracksCard) {
                const previewDiv = document.createElement('div');
                previewDiv.id = 'newTracksPreview';
                previewDiv.innerHTML = '<div class="text-muted text-center p-2">Pa gen nouvo piste ajoute</div>';
                tracksCard.appendChild(previewDiv);
            }
        }
        
        addTrackBtn.addEventListener('click', function() {
            const audioFiles = document.getElementById('new_audio_files').files;
            
            
            if (!audioFiles) {
                alert('Veuillez sélectionner un fichier audio');
                return;
            }
            Array.from(audioFiles).forEach(file => {
                newTracks.push({
                    name: file.name,
                    audioFile: file,
                    imageFile: null,
                    imagePreview: null,
                    audioName: file.name
                });
            });
            updateNewTracksPreview();
            updateHiddenNewTracks();
            clearNewTrackForm();
            
        });
    }
    
    function clearNewTrackForm() {
        //document.getElementById('new_track_name').value = '';
        //document.getElementById('new_track_image').value = '';
        document.getElementById('new_audio_files').value = '';
    }
    
    // Gestion suppression des pistes existantes (avec confirmation)
    document.querySelectorAll('.remove-track').forEach(btn => {
        btn.addEventListener('click', function() {
            const trackId = this.getAttribute('data-track-id');
            if (confirm('Èske ou sèten ou vle efase piste sa a? Aksyon sa a pa ka anile.')) {
                // Ajouter un champ caché pour marquer la suppression
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_tracks[]';
                input.value = trackId;
                document.querySelector('form').appendChild(input);
                
                // Cacher visuellement l'élément
                const item = this.closest('.list-group-item');
                if (item) {
                    item.style.display = 'none';
                }
            }
        });
    });
    // Добавь этот код в конец тега <script> в файле edit_album.php

    // Gestion de la soumission du formulaire
    const editForm = document.getElementById('editAlbumForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Récupérer le bouton submit pour le désactiver
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Modification en cours...';
            
            // Créer FormData
            const formData = new FormData(this);
            
            // Ajouter les nouvelles pistes (fichiers)
            // Les nouvelles pistes sont déjà dans newTracks array
            if (typeof newTracks !== 'undefined' && newTracks.length > 0) {
                newTracks.forEach((track, index) => {
                    formData.append(`new_tracks[${index}][name]`, track.name);
                    if (track.audioFile) {
                        formData.append(`new_tracks_audio_${index}`, track.audioFile);
                    }
                    // if (track.imageFile) {
                    //     formData.append(`new_tracks_image_${index}`, track.imageFile);
                    // }
                });
            }
            
            // Envoyer la requête
            let url = `/konektem/albums/edit`;
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': `Bearer ${sessionStorage.getItem('token')}`
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Afficher message de succès
                    showSucess('Album modifié avec succès!');
                    
                    // Rediriger après 1.5 secondes
                    // setTimeout(() => {
                    //     window.location.href = '?action=albums&success=1';
                    // }, 1500);
                } else {
                    showError('Erreur: ' + (data.error || 'Une erreur est survenue'));
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Erreur serveur, veuillez réessayer');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }

});