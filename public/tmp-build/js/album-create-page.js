// Prévisualisation cover album
//import "../css/album-create-page.css";
import showAlert from './alert-modal.js';

document.getElementById('album_image').addEventListener('change', function(e) {
    const preview = document.getElementById('album-preview-img');
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            preview.src = ev.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    } else {
        preview.style.display = 'none';
    }
});

// Gestion du champ "nouvel artiste"
document.getElementById('artist_name').addEventListener('change', function() {
    const newArtistField = document.getElementById('new_artist_field');
    if (this.value === 'new') {
        newArtistField.style.display = 'block';
        document.getElementById('new_artist_name').required = true;
    } else {
        newArtistField.style.display = 'none';
        document.getElementById('new_artist_name').required = false;
    }
});

// Gestion des tracks
let tracks = [];

function updateTracksList() {
    const container = document.getElementById('tracksList');
    const countSpan = document.getElementById('trackCount');
    
    if (tracks.length === 0) {
        container.innerHTML = '<div class="text-muted text-center">Aucune piste ajoutée pour le moment</div>';
        countSpan.textContent = '0';
        return;
    }
    
    countSpan.textContent = tracks.length;
    
    let html = '<div class="list-group">';
    tracks.forEach((track, index) => {
        html += `
            <div class="list-group-item" data-index="${index}">
                <div class="d-flex align-items-center">
                    <div class="track-preview me-3">
                        ${track.imagePreview ? `<img src="${track.imagePreview}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">` : '<div style="width: 50px; height: 50px; background: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-music"></i></div>'}
                    </div>
                    <div class="flex-grow-1">
                        <strong>${escapeHtml(track.name)}</strong><br>
                        <small class="text-muted">${track.audioName || 'Fichier audio'}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger remove-track" data-index="${index}"><i class="fa-solid fa-x"></i></button>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
    
    // Ajouter les écouteurs pour les boutons supprimer
    document.querySelectorAll('.remove-track').forEach(btn => {
        btn.addEventListener('click', function() {
            const idx = parseInt(this.getAttribute('data-index'));
            tracks.splice(idx, 1);
            updateTracksList();
            updateHiddenFields();
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

function updateHiddenFields() {
    // Supprimer les anciens champs cachés
    document.querySelectorAll('.track-data-field').forEach(field => field.remove());
    
    // Ajouter les nouveaux champs cachés pour chaque track
    tracks.forEach((track, index) => {
        if (track.name) {
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = `tracks[${index}][name]`;
            nameInput.value = track.name;
            nameInput.className = 'track-data-field';
            document.getElementById('albumForm').appendChild(nameInput);
        }
        
        if (track.imageFile instanceof File) {
            // Pour les fichiers, on utilise un DataTransfer pour les conserver
            // Alternative: on stocke dans un objet global et on les envoie via FormData
        }
        
        if (track.audioFile instanceof File) {
            // Pour les fichiers, on utilise un DataTransfer pour les conserver
        }
    });
}

document.getElementById('addTrackBtn').addEventListener('click', function() {

    // multiple upload
    const audioFiles = document.getElementById('audio_files').files;
    
    if (!audioFiles) {
        alert('Veuillez sélectionner un fichier audio');
        return;
    }
    Array.from(audioFiles).forEach(file => {
        tracks.push({
            name: file.name,
            audioFile: file,
            imageFile: null,
            imagePreview: null,
            audioName: file.name
        });
    });
    updateTracksList();
    updateHiddenFields();
    clearTrackForm();
 
});

function clearTrackForm() {
    //document.getElementById('track_name').value = '';
    //document.getElementById('track_image').value = '';
    document.getElementById('audio_files').value = '';
}

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

// Soumission du formulaire - Utilisation de FormData pour envoyer les fichiers
document.getElementById('albumForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validation de base
    const albumName = document.getElementById('album_name').value.trim();
    const genre = document.getElementById('genre').value.trim();
    const releaseYear = document.getElementById('release_year').value;
    const albumImage = document.getElementById('album_image').files[0];
    const ownerName = document.getElementById('owner-name').value.trim();
    const copyright = document.getElementById('copyright').checked;
    const consent = document.getElementById('consent').checked;
    
    if (!albumName) {
        alert('Veuillez entrer le nom de l\'album');
        return;
    }
    if (!genre) {
        alert('Veuillez entrer le genre');
        return;
    }
    if (!releaseYear) {
        alert('Veuillez entrer l\'année de sortie');
        return;
    }
    if (!albumImage) {
        alert('Veuillez sélectionner une cover pour l\'album');
        return;
    }
    if (tracks.length === 0) {
        alert('Veuillez ajouter au moins une piste à l\'album');
        return;
    }
    if (!ownerName) {
        alert('Veuillez entrer votre nom pour la signature');
        return;
    }
    if (!copyright || !consent) {
        alert('Veuillez accepter les conditions');
        return;
    }
    
    const formData = new FormData();
    
    // Informations album
    formData.append('album_name', albumName);
    formData.append('genre', genre);
    formData.append('release_year', releaseYear);
    formData.append('album_description', document.getElementById('album_description').value);
    formData.append('album_image', albumImage);
    formData.append('owner_name', ownerName);
    formData.append('copyright', copyright ? '1' : '0');
    formData.append('consent', consent ? '1' : '0');
    
    // Artiste
    const artistSelect = document.getElementById('artist_name');
    if (artistSelect.value === 'new') {
        const newArtistName = document.getElementById('new_artist_name').value.trim();
        if (!newArtistName) {
            alert('Veuillez entrer le nom du nouvel artiste');
            return;
        }
        formData.append('artist_type', 'new');
        formData.append('new_artist_name', newArtistName);
    } else if (artistSelect.value) {
        formData.append('artist_type', 'existing');
        formData.append('artist_id', artistSelect.value);
    } else {
        alert('Veuillez sélectionner un artiste');
        return;
    }
    
    // Tracks
    let valid = true;
    tracks.forEach((track, index) => {
        if (!track.audioFile) {
            alert(`Piste ${index + 1}: Fichier audio manquant`);
            valid = false;
            return;
        }
        formData.append(`tracks[${index}][name]`, track.name);
        formData.append(`tracks[${index}][audio]`, track.audioFile);
        // remove the track image
        if (track.imageFile) {
            formData.append(`tracks[${index}][image]`, track.imageFile);
        }
    });
    
    if (!valid){
        showAlert("Invalid form", 'danger');
        return;
    }
    
    // Désactiver le bouton pour éviter double soumission
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = '';
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin-pulse"></i>';
    
    // Envoyer via fetch ou soumission standard
    // Ici on utilise fetch pour mieux gérer la réponse
    
    fetch('/konektem/api/albums/create', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${sessionStorage.getItem('token')}`
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if(data.qrcode_base64){
                showQRCode(data.qrcode_base64, data.album_id);
            }
            showSuccess((data.message || 'Successfully created album'));
            //alert('Success: ' + (data.message || 'Successfully created album'));
            //window.location.href = '/konektem/user/me/albums';
        } else {
            showError('Erreur: ' + (data.message || 'Une erreur est survenue'));
            //alert('Erreur: ' + (data.error || 'Une erreur est survenue'));
            
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = '';
        submitBtn.textContent = 'Créer l\'album';
    })
    .catch(error => {
        console.log(error);
        showError('Erreur lors de l\'envoi: ');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Créer l\'album';
    });
});