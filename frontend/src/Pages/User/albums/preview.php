<?php //require_once ADMIN_PATH . '/views/layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL . '/static/css/album-preview-style.css'?>"/>

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
                    <div class="grid" style="grid-template-columns: 1fr 1fr;">
                        <div class="stat-item">
                            <i class="fas fa-user"></i>
                            <span><?= htmlspecialchars($album['artist_name'] ?? 'Artiste inconnu') ?></span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-music"></i>
                            <span><?= htmlspecialchars($album['name'] ?? 'Non spécifié') ?></span>
                        </div>
                        
                    </div>
                    <div class="flex row" style="justify-content: center;">
                        <div class="stat-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?= date('Y', strtotime($album['release_year'] ?? 'now')) ?></span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-list"></i>
                            <span><?= count($album['tracks'] ?? []) ?> </span>
                        </div>
                    </div>
                    <div class="flex row" style="justify-content: center;">
                        <div class="stat-item">
                            <i class="fas fa-eye"></i>
                            <span class="a-view-count"><?= number_format($album['views'] ?? 0) ?> </span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-share-alt"></i>
                            <span class="a-share-count"><?= number_format($album['shares'] ?? 0) ?> </span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-download"></i>
                            <span class="a-download-count"><?= number_format($album['downloads'] ?? 0) ?> </span>
                        </div>
                    </div>
                </div>
                
                <div class="album-actions mt-4">
                    <button class="btn btn-primary btn-block mb-2" id="downloadAlbumBtn">
                        <i class="fas fa-download me-2"></i>Télécharger tout l'album
                    </button>
                    <!-- <button class="btn btn-secondary btn-block mb-2" id="viewTracksBtn">
                        <i class="fas fa-headphones me-2"></i>Voir les pistes
                    </button> -->
                    <button class="btn btn-success btn-block mb-2" id="shareAlbumBtn">
                        <i class="fas fa-share-alt me-2"></i>Partager
                    </button>
                    <!-- <button class="btn btn-danger btn-block like-btn" id="likeAlbumBtn" data-album-id="">
                        <i class="fas fa-heart me-2"></i>
                        <span class="like-count"></span>
                        <span class="like-text"></span>
                    </button> -->
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
                    <div class="expand-controller">
                        <i class="fa-solid fa-chevron-down"></i><span>we plis</span>
                    </div>
                    <script>
                        let descriptionField = document.querySelector('.description-content .lead');
                        let expandController = document.querySelector('.expand-controller');
                        expandController.addEventListener('click', ()=>{
                            descriptionField.classList.toggle('show');
                        });
                    </script>
                </div>
                <?php else: ?>
                <div class="description-content mt-3">
                    <p class="text-muted">Aucune description disponible pour cet album.</p>
                </div>
                <?php endif; ?>
                
                <!-- Section des pistes (cachée par défaut, affichée quand on clique sur "Voir les pistes") -->
                <div id="tracksSection" style="display: block;" class="mt-4">
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
                                                <strong><?= htmlspecialchars($track['title']) ?></strong>
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
                    <input type="text" class="form-control" id="shareLink" value="<?= 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.album = <?= json_encode($album)?>;
</script>
<script src="/konektem/dist/apps.7c1234126e356e74d78b_2.js" defer></script>


<?php //require_once ADMIN_PATH . '/views/layout/footer.php'; ?>