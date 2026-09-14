
<div class="album-preview-container">
    <div class="album-preview-grid">
        <!-- Left Column - Album Info -->
        <div class="album-cover-card">
            <div class="album-cover">
                <?php if (!empty($album['image_url'])): ?>
                    <img 
                        src="<?= htmlspecialchars($album['image_url']) ?>" 
                        alt="<?= htmlspecialchars($album['name'] ?? 'Album cover') ?>"
                        onerror="this.classList.add('error'); this.parentElement.querySelector('.fallback-image').style.display='flex';"
                    />
                <?php endif; ?>
                <div class="fallback-image" style="<?= !empty($album['image_url']) ? 'display: none;' : 'display: flex;' ?>">
                    <i class="fas fa-record-vinyl"></i>
                    <span><?= htmlspecialchars($album['name'] ?? 'Album') ?></span>
                </div>
            </div>
            
            <div class="album-stats">
                <div class="stat-item">
                    <i class="fas fa-user"></i>
                    <span><?= htmlspecialchars($album['artist_name'] ?? 'Artiste inconnu') ?></span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-music"></i>
                    <span><?= htmlspecialchars($album['name'] ?? 'Non spécifié') ?></span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span><?= date('Y', strtotime($album['release_year'] ?? 'now')) ?></span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-list"></i>
                    <span><?= count($album['tracks'] ?? []) ?> pistes</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-eye"></i>
                    <span class="stat-value a-view-count"><?= number_format($album['views'] ?? 0) ?></span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-share-alt"></i>
                    <span class="stat-value a-share-count"><?= number_format($album['shares'] ?? 0) ?></span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-download"></i>
                    <span class="stat-value a-download-count"><?= number_format($album['downloads'] ?? 0) ?></span>
                </div>
            </div>
            
            <div class="album-actions">
                <button class="btn btn-primary download-btn" id="downloadAlbumBtn">
                    <i class="fas fa-download media-ico" data-itemname="album" data-itemid=<?= $album['id']?>></i> Télécharger tout l'album
                    <span class="hidden"></span>
                </button>
                <button class="btn btn-success share-btn" id="shareAlbumBtn">
                    <i class="fas fa-share-alt media-ico" data-itemname="album" data-itemid=<?= $album['id']?>></i> Partager
                    <span class="hidden"></span>
                </button>
            </div>
        </div>
        
        <!-- Right Column - Description & Tracks -->
        <div class="album-description-card">
            <h2><?= htmlspecialchars($album['name'] ?? 'Album sans titre') ?></h2>
            
            <div class="album-meta">
                <span><i class="fas fa-user"></i> <?= htmlspecialchars($album['artist_name'] ?? 'Artiste inconnu') ?></span>
                <span><i class="fas fa-calendar-alt"></i> <?= date('Y', strtotime($album['release_year'] ?? 'now')) ?></span>
                <span><i class="fas fa-music"></i> <?= count($album['tracks'] ?? []) ?> pistes</span>
            </div>
            
            <?php if (!empty($album['description'])): ?>
            <div class="description-content">
                <h4>Description</h4>
                <p class="lead"><?= nl2br(htmlspecialchars($album['description'])) ?></p>
                <div class="expand-controller">
                    <i class="fas fa-chevron-down"></i>
                    <span class="show-more-text">Voir plus</span>
                    <span class="show-less-text">Voir moins</span>
                </div>
            </div>
            <?php else: ?>
            <div class="description-content">
                <p class="text-muted">Aucune description disponible pour cet album.</p>
            </div>
            <?php endif; ?>
            
            <!-- Tracks Section -->
            <div id="tracksSection">
                <h4>
                    Liste des pistes
                    <span class="track-count"><?= count($album['tracks'] ?? 0) ?></span>
                </h4>
                <div class="tracks-list">
                    <?php if (!empty($album['tracks'])): ?>
                        <?php foreach ($album['tracks'] as $index => $track): ?>
                            <div class="track-item" data-track-id="<?= $track['id'] ?>">
                                <div class="track-info">
                                    <span class="track-number"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                    <div class="track-details">
                                        <div class="track-title"><?= htmlspecialchars($track['title']) ?></div>
                                        <div class="track-artist"><?= htmlspecialchars($track['artist_name'] ?? $album['artist_name'] ?? 'Artiste inconnu') ?></div>
                                    </div>
                                </div>
                                <div class="track-actions">
                                    <button class="btn-sm play-btn play-track" 
                                            data-itemid="<?= $track['id'] ?>"
                                            title="Écouter">
                                        <i class="fas fa-play media-ico" data-itemid="<?= $track['id'] ?>"></i>
                                        <span class="hidden d-none"></span>
                                    </button>
                                    <button class="btn-sm download-btn download-track" 
                                            title="Télécharger">
                                        <i class="fas fa-download media-ico" data-itemname="track" data-itemid="<?= $track['id']?>"></i>
                                        <span class="hidden d-none"></span>
                                    </button>
                                    <button class="btn-sm like-btn like-track" 
                                            title="J'aime">
                                        <i class="fas fa-heart media-ico" data-itemname="track" data-itemid="<?= $track['id']?>"></i>
                                        <span class="hidden d-none"></span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-tracks">
                            <i class="fas fa-music"></i>
                            <p>Aucune piste disponible pour cet album.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="share-modal-overlay" id="shareModal">
    <div class="share-modal">
        <div class="modal-header">
            <h5><i class="fas fa-share-alt" style="color: var(--accent-blue); margin-right: 0.5rem;"></i> Partager cet album</h5>
            <button class="modal-close" id="closeShareModal">&times;</button>
        </div>
        <div class="modal-body">
            <p style="color: var(--text-secondary); margin-bottom: 1rem;">Partagez cet album avec vos amis !</p>
            <div class="share-buttons">
                <button class="share-btn facebook" data-platform="facebook">
                    <i class="fab fa-facebook"></i> Facebook
                </button>
                <button class="share-btn twitter" data-platform="twitter">
                    <i class="fab fa-twitter"></i> Twitter
                </button>
                <button class="share-btn whatsapp" data-platform="whatsapp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </button>
            </div>
            <div class="share-link-container">
                <label for="shareLink">Lien direct :</label>
                <input type="text" class="share-input" id="shareLink" 
                       value="<?= 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>" 
                       readonly onclick="this.select(); document.execCommand('copy');">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-close-modal" data-bs-dismiss="modal">Fermer</button>
        </div>
    </div>
</div>

<script>
    // Album data for JavaScript
    window.album = <?= json_encode($album) ?>;
    
    // Share Modal
    document.addEventListener('DOMContentLoaded', function() {
        const shareBtn = document.getElementById('shareAlbumBtn');
        const shareModal = document.getElementById('shareModal');
        const closeShareBtn = document.getElementById('closeShareModal');
        const shareLink = document.getElementById('shareLink');
        
        function openShareModal() {
            shareModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeShareModal() {
            shareModal.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        if (shareBtn) {
            shareBtn.addEventListener('click', function(){
                if(!localStorage.getItem('token')) return;
                openShareModal();
            });
        }
        
        if (closeShareBtn) {
            closeShareBtn.addEventListener('click', closeShareModal);
        }
        
        shareModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeShareModal();
            }
        });
        
        // Copy link on click
        if (shareLink) {
            shareLink.addEventListener('click', function() {
                this.select();
                document.execCommand('copy');
                // Show feedback
                const originalValue = this.value;
                this.value = '✅ Lien copié !';
                setTimeout(() => {
                    this.value = originalValue;
                }, 2000);
            });
        }
        
        // Share buttons
        document.querySelectorAll('.share-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const platform = this.dataset.platform;
                const url = encodeURIComponent(window.location.href);
                const title = encodeURIComponent('<?= htmlspecialchars($album['name'] ?? 'Album') ?>');
                let shareUrl = '';
                
                switch(platform) {
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                        break;
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                        break;
                    case 'whatsapp':
                        shareUrl = `https://api.whatsapp.com/send?text=${title}%20${url}`;
                        break;
                }
                
                if (shareUrl) {
                    window.open(shareUrl, '_blank', 'width=600,height=400');
                }
            });
        });
        
        // Description expand/collapse
        const expandController = document.querySelector('.expand-controller');
        if (expandController) {
            expandController.addEventListener('click', function() {
                const lead = this.parentElement.querySelector('.lead');
                if (lead) {
                    lead.classList.toggle('show');
                }
            });
        }
    });
</script>
