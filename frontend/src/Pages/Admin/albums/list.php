<?php //require_once ADMIN_PATH . '/views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Albums</h2>
    <a href="<?= BASE_URL ?>/admin/albums/create" class="btn btn-primary">
        <i class="fas fa-plus d-none d-md-inline"></i> Ajoute album
    </a>
</div>

<div class="row">
    <?php if(empty($albums)): ?>
    <div class="col-12 text-center py-5">
        <div class="empty-state">
            <i class="fas fa-compact-disc fa-4x mb-3 text-muted"></i>
            <h3>Pa gen album ajoute</h3>
            <p class="text-muted">Klike sou bouton an ba a pou kreye premye album ou</p>
            <a href="<?= BASE_URL ?>/admin/albums/create" class="btn btn-primary mt-3">
                <i class="fas fa-plus d-none d-md-inline"></i> Ajoute album
            </a>
        </div>
    </div>
    <?php else: 
        foreach ($albums as $album): 
            // Récupérer le nombre de pistes et l'artiste principal
            $tracksCount = $album['tracks_count'] ?? $album['actual_tracks_count'] ?? 0;
            $artistName = $album['artist_name'] ?? 'Atis inconnu';
    ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 album-card" data-album-id="<?= $album['id'] ?>">
            <?php if (!empty($album['image_url'])): ?>
            <img src="<?= htmlspecialchars($album['image_url']) ?>" 
                 class="card-img-top album-cover" 
                 alt="<?= htmlspecialchars($album['name']) ?>"
                 style="height: 250px; object-fit: cover;">
            <?php else: ?>
            <div class="card-img-top album-cover-placeholder" style="height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-music fa-4x text-white"></i>
            </div>
            <?php endif; ?>
            
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($album['name']) ?></h5>
                <p class="card-text text-muted small">
                    <i class="fas fa-user"></i> <?= htmlspecialchars($artistName) ?>
                </p>
                <p class="card-text text-muted small">
                    <i class="fas fa-calendar-alt"></i> <?= date('Y', strtotime($album['release_year'])) ?>
                    <span class="ms-2"><i class="fas fa-music"></i> <?= $tracksCount ?> pistes</span>
                </p>
                <?php if (!empty($album['description'])): ?>
                <p class="card-text"><?= nl2br(htmlspecialchars(substr($album['description'], 0, 80))) ?>...</p>
                <?php else: ?>
                <p class="card-text text-muted"><em>Pa gen deskripsyon</em></p>
                <?php endif; ?>
                
                <!-- Stats rapides -->
                <div class="album-stats mt-2">
                    <span class="badge bg-secondary">
                        <i class="fas fa-download"></i> <?= number_format($album['downloads'] ?? 0) ?>
                    </span>
                    <span class="badge bg-danger">
                        <i class="fas fa-heart"></i> <?= number_format($album['likes'] ?? 0) ?>
                    </span>
                    <span class="badge bg-success">
                        <i class="fas fa-share-alt"></i> <?= number_format($album['shares'] ?? 0) ?>
                    </span>
                </div>
            </div>
            
            <div class="card-footer bg-transparent">
                <a href="<?= BASE_URL ?>/admin/albums/<?= $album['id'] ?>/preview" class="btn btn-sm btn-info" title="Voir details">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="<?= BASE_URL ?>/admin/albums/<?= $album['id'] ?>/edit" class="btn btn-sm btn-warning" title="Modifye album">
                    <i class="fas fa-edit"></i>
                </a>
                <button type="button" class="btn btn-sm btn-danger delete-album del-btn" 
                        data-album-id="<?= $album['id'] ?>"  data-id="<?= $album['id']?>"
                        data-album-name="<?= htmlspecialchars($album['name']) ?>"
                        title="Efase album">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; endif; ?>
</div>

<!-- Modal confirmation suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfimasyon efasman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Èske ou sèten ou vle efase album <strong id="deleteAlbumName"></strong>?</p>
                <p class="text-danger small">Aksyon sa a pap ka anile. Tout pistes nan album sa a pral efase tou.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anile</button>
                <button type="button" class="btn btn-danger del-btn" id="confirmDeleteBtn">Efase</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let albumToDelete = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteAlbumNameSpan = document.getElementById('deleteAlbumName');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    // Gestion des clics sur les boutons supprimer
    document.querySelectorAll('.delete-album').forEach(button => {
        button.addEventListener('click', function() {
            albumToDelete = this.getAttribute('data-album-id');
            const albumName = this.getAttribute('data-album-name');
            deleteAlbumNameSpan.textContent = albumName;
            deleteModal.show();
        }, { passive: true});
    });
    
    // Confirmation suppression
    confirmDeleteBtn.addEventListener('click', function() {
        if (albumToDelete) {
            // Envoyer la requête de suppression via fetch
            fetch(`/konektem/admin/albums/${albumToDelete}/delete`, {
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
                    // Supprimer la carte du DOM
                    const albumCard = document.querySelector(`.album-card[data-album-id="${albumToDelete}"]`).closest('.col-md-4');
                    if (albumCard) {
                        albumCard.remove();
                    }
                    
                    // Afficher un message de succès
                    showAlert('Album efase avèk siksè!', 'success');
                    
                    // Si plus d'albums, afficher l'état vide
                    const remainingAlbums = document.querySelectorAll('.album-card').length;
                    if (remainingAlbums === 0) {
                        location.reload();
                    }
                } else {
                    showAlert('Ere: ' + (data.error || 'Impossible efase album'), 'danger');
                }
                deleteModal.hide();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Ere serveur, eseye ankò', 'danger');
                deleteModal.hide();
            });
        }
    });
    
    // Fonction pour afficher les alertes
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        const container = document.querySelector('.d-flex.justify-content-between').parentNode;
        container.insertBefore(alertDiv, container.firstChild.nextSibling);
        
        // Auto fermeture après 3 secondes
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
});
</script>
<script>
window.item = 'albums';
window.BASE_URL = '<?= BASE_URL?>';
window.role = 'admin';
</script>
<style>
.modal{
    z-index: calc(var(--bs-backdrop-zindex, 1050) + 1);
}
.album-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.album-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.album-cover {
    border-radius: 8px 8px 0 0;
}

.album-cover-placeholder {
    border-radius: 8px 8px 0 0;
}

.card-footer {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    border-top: 1px solid #eee;
    padding: 12px;
}

.album-stats {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.album-stats .badge {
    font-size: 0.75rem;
    padding: 5px 8px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.btn-info:hover {
    background-color: #138496;
    border-color: #117a8b;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.card-text {
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .col-md-4 {
        margin-bottom: 20px;
    }
}
</style>