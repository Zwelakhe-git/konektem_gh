import {InfFreeFetch} from './InfFreeUtils.js';

document.addEventListener('DOMContentLoaded', function() {
    // Variables
    const albumId = window.album['id'];
    const albumName = window.album['id'];
    
    // 1. Voir les pistes
    const viewTracksBtn = document.getElementById('viewTracksBtn');
    const tracksSection = document.getElementById('tracksSection');
    
    if (viewTracksBtn) {
        viewTracksBtn.addEventListener('click', function() {
            if (tracksSection.style.display === 'none') {
                tracksSection.style.display = 'block';
                viewTracksBtn.innerHTML = '<i class="fas fa-eye-slash me-2"></i>Masquer les pistes';
            } else {
                tracksSection.style.display = 'none';
                viewTracksBtn.innerHTML = '<i class="fas fa-headphones me-2"></i>Voir les pistes';
            }
        });
    }
    
    // 2. Like album
    const likeAlbumBtn = document.getElementById('likeAlbumBtn');
    if (likeAlbumBtn) {
        likeAlbumBtn.addEventListener('click', function() {
            allow();
            const btn = this;
            const likeCountSpan = btn.querySelector('.a-like-count');
            const currentLikes = parseInt(likeCountSpan.textContent.replace(/[^0-9]/g, ''));
            
            // Appel AJAX pour le like
            fetch(`/konektem/api.service/like`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: {
                    item: 'album',
                    id: albumId
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if(response.status === 401){
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    location.href = '/konektem/auth/logout';
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    likeCountSpan.textContent = result.data.likes.toLocaleString();
                    
                    // Animation du bouton
                    btn.classList.add('liked');
                    setTimeout(() => btn.classList.remove('liked'), 300);
                } else {
                    alert('Erreur: ' + (data.error || 'Impossible de liker cet album'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
            });
        });
    }
    
    // 3. Partager
    const shareAlbumBtn = document.getElementById('shareAlbumBtn');
    if (shareAlbumBtn) {
        shareAlbumBtn.addEventListener('click', function() {
            const shareCountSpan = document.querySelector('.a-share-count');
            // Enregistrer le partage
            InfFreeFetch(`/konektem/api.service/share`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: {
                    item: 'album',
                    id: albumId
                }
            })
            .then(response => {
                if(response.status === 401){
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    location.href = '/konektem/auth/logout';
                }
                return response.json();
            })
            .then(result => {
                if(result.success){
                    const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
                    shareModal.show();
                    shareCountSpan.textContent = result.data.shares.toLocaleString();
                }
            })
            .catch(error => console.error('Error recording share:', error));
        });
    }
    
    // 4. Télécharger tout l'album (à implémenter plus tard)
    const downloadAlbumBtn = document.getElementById('downloadAlbumBtn');
    if (downloadAlbumBtn) {
        downloadAlbumBtn.addEventListener('click', async function() {
            // TODO: Implémenter le téléchargement de tout l'album
            // alert('Fonctionnalité de téléchargement complet à venir bientôt !');
            allow();
            const downloadCountSpan = document.querySelector('.a-download-count');
            try {
                // Log du téléchargement
                InfFreeFetch(`/konektem/api.service/download`,{
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    'body': {
                        item: 'album',
                        id: albumId
                    }
                })
                .then(response => {
                    if(response.status === 401){
                        localStorage.removeItem('token');
                        localStorage.removeItem('user');
                        location.href = '/konektem/auth/logout';
                    }
                    return response.json();
                })
                .then(result => {
                    if(result.success){
                        //console.log("downloading file: ", result.data.file_name, result.data.file_url)
                        let a = document.createElement('a');
                        a.download = result.data.file_name;
                        a.href = result.data.file_url;
                        a.click();
                        downloadCountSpan.textContent = result.data.downloads.toLocaleString();
                    } else {
                        console.error("failed to fetch download url:", result.message);
                        showError(result.message);
                    }
                })
                .catch(error => {
                    console.error(error.message);
                })
            } catch(error){
                console.error("Error during album download: ", error);
            }
        });
    }
    
    // 5. Play track
    document.querySelectorAll('.play-track').forEach(btn => {
        btn.addEventListener('click', function() {
            //const trackUrl = this.getAttribute('data-track-url');
            const trackId = this.getAttribute('data-track-id');  
            let audioPlayer = document.getElementById('audioPlayer');
            if (!audioPlayer) {
                audioPlayer = document.createElement('audio');
                audioPlayer.id = 'audioPlayer';
                document.body.appendChild(audioPlayer);
            }
            // Enregistrer la lecture
            InfFreeFetch(`/konektem/api.service/play-track`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
                body: {
                    id: trackId
                }
            })
            .then(response => {
                if(response.status === 401){
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    location.href = '/konektem/auth/logout';
                }
                return response.json();
            })
            .then(result => {
                if(result.success){
                    const trackUrl = result.data.file_url;
                    if (trackUrl) {
                        // Créer ou réutiliser un lecteur audio
                        if (window.currentTrackId && window.currentTrackId === trackId) {
                            if(!audioPlayer.paused && !audioPlayer.ended && window._audioPlaying){
                                audioPlayer.pause();
                                window._audioPlaying = false;
                                this.innerHTML = '<i class="fas fa-play"></i>';
                            } else {
                                audioPlayer.play();
                                window._audioPlaying = true;
                                this.innerHTML = '<i class="fas fa-pause"></i>';
                            }
                        } else {
                            audioPlayer.src = trackUrl;
                            audioPlayer.play();
                            window._audioPlaying = true;
                            this.innerHTML = '<i class="fas fa-pause"></i>';
                            window.currentTrackId = trackId;

                            document.querySelectorAll('.play-track').forEach(otherBtn => {
                                if (otherBtn !== btn) {
                                    otherBtn.innerHTML = '<i class="fas fa-play"></i>';
                                }
                            });
                        }
                       
                        // Arrêter les autres lecteurs
                        
                        // Quand la piste se termine
                        audioPlayer.onended = () => {
                            window._audioPlaying = false;
                            this.innerHTML = '<i class="fas fa-play"></i>';
                        };
                    }
                } else {
                    showError(result.message);
                }
            })
            .catch(error => console.error('Error recording play:', error));
        });
    });
    
    // 6. Download track
    document.querySelectorAll('.download-track').forEach(btn => {
        btn.addEventListener('click', async function() {
            // edit so that the token is also required
            allow();
            
            //const trackUrl = this.getAttribute('data-track-url');
            const trackId = this.getAttribute('data-track-id');
            let headers = {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                //'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };
            InfFreeFetch(`/konektem/api.service/download`, {
                method: 'POST',
                headers: headers,
                body: {
                    'item': 'track',
                    'id': trackId
                }
            })
            .then(response => {
                if(response.status === 401){
                    showError('Session expired');
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    location.href = '/konektem/auth/logout';
                }
                return response.json();
            })
            .then(result => {
                if(result.success){
                    /*const link = document.createElement('a');
                    link.href = result.data.file_url;
                    link.download = result.data.file_name;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);*/
                } else {
                    console.error("failed to download track", result.message);
                    // showAlert(result.message, 'error', document.body);
                    showError(result.message);
                }
            })
            .catch(error => {
                console.error('Error recording download:', error)
                // showAlert('Error recording download:', 'error', document.body);
            });
            
        });
    });
    
    // 7. Like track
    document.querySelectorAll('.like-track').forEach(btn => {
        btn.addEventListener('click', function() {
            allow();
            const trackId = this.getAttribute('data-track-id');
            const likeCountSpan = this.querySelector('.track-like-count');
            const currentLikes = parseInt(likeCountSpan.textContent.replace(/[^0-9]/g, ''));
            
            InfFreeFetch(`/konektem/api.service/like`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: {
                    'item': 'track',
                    'id': trackId
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if(response.status === 401){
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    location.href = '/konektem/auth/login';
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    likeCountSpan.textContent = result.data.likes.toLocaleString();
                    this.classList.add('liked');
                    setTimeout(() => this.classList.remove('liked'), 300);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    
    // 8. Partager sur les réseaux sociaux
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const platform = this.getAttribute('data-platform');
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(`Découvrez l'album "${albumName}" !`);
            
            let shareUrl = '';
            switch(platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?text=${text}&url=${url}`;
                    break;
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=${text}%20${url}`;
                    break;
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        });
    });
});

function allow(){
    if(!localStorage.getItem('token')){
        location.href = '/konektem/auth/login';
    }
}