<?php //require_once ADMIN_PATH . '/views/layout/header.php'; ?>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'></script>
<div class="album-preview-container">
    <div class="row">
        <!-- Colonne gauche - Informations album -->
        <div class="col-md-4">
            <div class="album-cover-card">
                <div class="album-cover">
                    <img src="<?= htmlspecialchars($album['image_url'] ?? '/assets/images/default-album.jpg') ?>" 
                         alt="<?= htmlspecialchars($album['name'] ?? 'Album cover') ?>"
                         class="img-fluid rounded">
                </div>
                
                <div class="album-stats mt-3">
                    <div class="stat-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?= date('Y', strtotime($album['release_year'] ?? 'now')) ?></span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-user"></i>
                        <span><?= htmlspecialchars($album['artist_name'] ?? 'Artiste inconnu') ?></span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-music"></i>
                        <span><?= htmlspecialchars($album['genre'] ?? 'Non spécifié') ?></span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-list"></i>
                        <span><?= count($album['tracks'] ?? []) ?> pistes</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-eye"></i>
                        <span><?= number_format($album['views'] ?? 0) ?> vues</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-share-alt"></i>
                        <span><?= number_format($album['shares'] ?? 0) ?> partages</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-download"></i>
                        <span><?= number_format($album['downloads'] ?? 0) ?> téléchargements</span>
                    </div>
                </div>
                
                <div class="album-actions mt-4">
                    <button class="btn btn-primary btn-block mb-2" id="downloadAlbumBtn">
                        <i class="fas fa-download me-2"></i>Télécharger tout l'album
                    </button>
                    <button class="btn btn-secondary btn-block mb-2" id="viewTracksBtn">
                        <i class="fas fa-headphones me-2"></i>Voir les pistes
                    </button>
                    <button class="btn btn-success btn-block mb-2" id="shareAlbumBtn">
                        <i class="fas fa-share-alt me-2"></i>Partager
                    </button>
                    <button class="btn btn-danger btn-block like-btn" id="likeAlbumBtn" data-album-id="<?= $album['id'] ?>">
                        <i class="fas fa-heart me-2"></i>
                        <span class="like-count"><?= number_format($album['likes'] ?? 0) ?></span>
                        <span class="like-text">J'aime</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Colonne droite - Description -->
        <div class="col-md-8">
            <div class="album-description-card">
                <h2><?= htmlspecialchars($album['name'] ?? 'Album sans titre') ?></h2>
                
                <?php if (!empty($album['description'])): ?>
                <div class="description-content mt-3">
                    <h4>Description</h4>
                    <p class="lead"><?= nl2br(htmlspecialchars($album['description'])) ?></p>
                </div>
                <?php else: ?>
                <div class="description-content mt-3">
                    <p class="text-muted">Aucune description disponible pour cet album.</p>
                </div>
                <?php endif; ?>
                
                <!-- Section des pistes (cachée par défaut, affichée quand on clique sur "Voir les pistes") -->
                <div id="tracksSection" style="display: none;" class="mt-4">
                    <h4>Liste des pistes</h4>
                    <div class="tracks-list">
                        <?php if (!empty($album['tracks'])): ?>
                            <ul class="list-group">
                            <?php foreach ($album['tracks'] as $index => $track): ?>
                                <li class="list-group-item track-item" data-track-id="<?= $track['id'] ?>">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="track-number me-3"><?= $index + 1 ?></div>
                                            <div class="track-info">
                                                <strong><?= htmlspecialchars($track['name']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($track['artist_name'] ?? $album['artist_name']) ?></small>
                                            </div>
                                        </div>
                                        <div class="track-actions">
                                            <button class="btn btn-sm btn-outline-primary play-track" data-track-id="<?= $track['id'] ?>" data-track-url="<?= htmlspecialchars($track['location'] ?? '') ?>">
                                                <i class="fas fa-play"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary download-track" data-track-id="<?= $track['id'] ?>" data-track-url="<?= htmlspecialchars($track['location'] ?? '') ?>">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger like-track" data-track-id="<?= $track['id'] ?>">
                                                <i class="fas fa-heart"></i>
                                                <span class="track-like-count"><?= number_format($track['likes'] ?? 0) ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">Aucune piste disponible pour cet album.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour le partage -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Partager cet album</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Partagez cet album avec vos amis !</p>
                <div class="share-buttons">
                    <button class="btn btn-primary share-btn" data-platform="facebook">
                        <i class="fab fa-facebook"></i> Facebook
                    </button>
                    <button class="btn btn-info share-btn" data-platform="twitter">
                        <i class="fab fa-twitter"></i> Twitter
                    </button>
                    <button class="btn btn-success share-btn" data-platform="whatsapp">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </button>
                </div>
                <div class="mt-3">
                    <label>Lien direct :</label>
                    <input type="text" class="form-control" id="shareLink" value="<?= $_SERVER['REQUEST_URI'] ?>" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables
    const albumId = <?= json_encode($album['id'] ?? null) ?>;
    const albumName = <?= json_encode($album['name'] ?? '') ?>;
    
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
            const btn = this;
            const likeCountSpan = btn.querySelector('.like-count');
            const currentLikes = parseInt(likeCountSpan.textContent.replace(/[^0-9]/g, ''));
            
            // Appel AJAX pour le like
            fetch(`?action=like_album&id=${albumId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    likeCountSpan.textContent = data.likes.toLocaleString();
                    
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
            const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
            shareModal.show();
            
            // Enregistrer le partage
            fetch(`?action=share_album&id=${albumId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .catch(error => console.error('Error recording share:', error));
        });
    }
    
    // 4. Télécharger tout l'album (à implémenter plus tard)
    const downloadAlbumBtn = document.getElementById('downloadAlbumBtn');
    if (downloadAlbumBtn) {
        downloadAlbumBtn.addEventListener('click', function() {
            // TODO: Implémenter le téléchargement de tout l'album
            alert('Fonctionnalité de téléchargement complet à venir bientôt !');
            
            // Log du téléchargement
            fetch(`?action=download_album&id=${albumId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .catch(error => console.error('Error recording download:', error));
        });
    }
    
    // 5. Play track
    document.querySelectorAll('.play-track').forEach(btn => {
        btn.addEventListener('click', function() {
            const trackUrl = this.getAttribute('data-track-url');
            const trackId = this.getAttribute('data-track-id');
            
            if (trackUrl) {
                // Créer ou réutiliser un lecteur audio
                let audioPlayer = document.getElementById('audioPlayer');
                if (!audioPlayer) {
                    audioPlayer = document.createElement('audio');
                    audioPlayer.id = 'audioPlayer';
                    document.body.appendChild(audioPlayer);
                }
                
                if (audioPlayer.src !== trackUrl) {
                    audioPlayer.src = trackUrl;
                }
                
                if (audioPlayer.paused) {
                    audioPlayer.play();
                    this.innerHTML = '<i class="fas fa-pause"></i>';
                } else {
                    audioPlayer.pause();
                    this.innerHTML = '<i class="fas fa-play"></i>';
                }
                
                // Arrêter les autres lecteurs
                document.querySelectorAll('.play-track').forEach(otherBtn => {
                    if (otherBtn !== this) {
                        otherBtn.innerHTML = '<i class="fas fa-play"></i>';
                    }
                });
                
                // Quand la piste se termine
                audioPlayer.onended = () => {
                    this.innerHTML = '<i class="fas fa-play"></i>';
                };
                
                // Enregistrer la lecture
                fetch(`?action=play_track&id=${trackId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .catch(error => console.error('Error recording play:', error));
            }
        });
    });
    
    // 6. Download track
    document.querySelectorAll('.download-track').forEach(btn => {
        btn.addEventListener('click', function() {
            const trackUrl = this.getAttribute('data-track-url');
            const trackId = this.getAttribute('data-track-id');
            
            if (trackUrl) {
                // Créer un lien de téléchargement
                const link = document.createElement('a');
                link.href = trackUrl;
                link.download = '';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Enregistrer le téléchargement
                fetch(`?action=download_track&id=${trackId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .catch(error => console.error('Error recording download:', error));
            }
        });
    });
    
    // 7. Like track
    document.querySelectorAll('.like-track').forEach(btn => {
        btn.addEventListener('click', function() {
            const trackId = this.getAttribute('data-track-id');
            const likeCountSpan = this.querySelector('.track-like-count');
            const currentLikes = parseInt(likeCountSpan.textContent.replace(/[^0-9]/g, ''));
            
            fetch(`?action=like_track&id=${trackId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    likeCountSpan.textContent = data.likes.toLocaleString();
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
</script>

<style>
.album-preview-container {
    padding: 20px;
    background-color: #f8f9fa;
    min-height: calc(100vh - 200px);
}

.album-cover-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.album-cover img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.album-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    background-color: #f8f9fa;
    border-radius: 8px;
    font-size: 14px;
}

.stat-item i {
    width: 20px;
    color: #6c757d;
}

.album-actions {
    margin-top: 20px;
}

.album-actions .btn-block {
    width: 100%;
    margin-bottom: 10px;
}

.album-description-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 100%;
}

.album-description-card h2 {
    color: #333;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.description-content {
    font-size: 16px;
    line-height: 1.6;
    color: #555;
}

.tracks-list {
    max-height: 400px;
    overflow-y: auto;
}

.track-item {
    transition: all 0.3s ease;
}

.track-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.track-number {
    font-weight: bold;
    color: #007bff;
    min-width: 40px;
}

.track-actions {
    display: flex;
    gap: 8px;
}

.like-btn.liked {
    animation: pulse 0.3s ease;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.share-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
}

@media (max-width: 768px) {
    .album-stats {
        grid-template-columns: 1fr;
    }
    
    .track-actions {
        flex-wrap: wrap;
    }
    
    .share-buttons {
        flex-direction: column;
    }
}
</style>

<?php //require_once ADMIN_PATH . '/views/layout/footer.php'; ?>