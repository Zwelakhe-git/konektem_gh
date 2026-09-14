<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestion des streams</h2>
    <a href="<?= BASE_URL?>/admin/stream/create" class="btn btn-primary">
        <i class="fas fa-plus d-none d-md-inline"></i> Nouvo strim
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imaj</th>
                        <th>Non strim</th>
                        <th>Dat kòmansman</th>
                        <th>Dat finisyon</th>
                        <th>Pri</th>
                        <th>Estati</th>
                        <th>Aksyon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($streams as $stream): ?>
                    <tr>
                        <td><?= $stream['id'] ?></td>
                        <td>
                            <?php if (isset($stream['cover_image']) && !empty($stream['cover_image'])): ?>
                                <img src="<?= $stream['cover_image'] ?>" alt="Cover" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($stream['name']) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($stream['start_time'])) ?></td>
                        <td>
                            <?= isset($stream['end_time']) ? date('d.m.Y H:i', strtotime($stream['end_time'])) : '—' ?>
                        </td>
                        <td>
                            <?php if ($stream['price'] > 0): ?>
                                <?= number_format($stream['price'], 2) ?> $
                            <?php else: ?>
                                <span class="badge bg-success">Gratis</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $startTime = strtotime($stream['start_time']);
                            $endTime = isset($stream['end_time']) ? strtotime($stream['end_time']) : null;
                            $now = time();
                            
                            if (!$stream['is_active']) {
                                echo '<span class="badge bg-secondary">Inaktif</span>';
                            } elseif ($startTime > $now) {
                                echo '<span class="badge bg-warning">Planifye</span>';
                            } elseif ($startTime <= $now && (!$endTime || $endTime > $now)) {
                                echo '<span class="badge bg-success">An difizyon</span>';
                            } else {
                                echo '<span class="badge bg-secondary">Fini</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL?>/admin/stream/<?= $stream['id'] ?>/edit" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger delete-stream del-btn" data-id="<?= $stream['id']?>" data-name="<?= htmlspecialchars($stream['name']) ?>" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            <a href="#" class="btn btn-sm btn-info view-stream" data-id="<?= $stream['id']?>" title="View" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($streams)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-video fa-2x text-muted mb-2 d-block"></i>
                            <p class="text-muted">Pa gen okenn strim pou montre</p>
                            <a href="<?= BASE_URL?>/admin/stream/create" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Kreye premye strim
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la suppression avec confirmation
    /*document.querySelectorAll('.delete-stream').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const streamId = this.dataset.id;
            const streamName = this.dataset.name;
            
            if (confirm(`Èske ou sètènman vle efase strim "${streamName}"? Aksyon sa pa ka anile.`)) {
                // Envoyer la requête de suppression via AJAX
                fetch('<?= BASE_URL?>/api/stream/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: streamId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Efase echwe: ' + (data.message || 'Erè sèvè'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Efase echwe. Tanpri eseye ankò.');
                });
            }
        });
    });*/
    
    // Gestion de l'affichage du stream
    document.querySelectorAll('.view-stream').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const streamId = this.dataset.id;
            // Ouvrir le stream dans un nouvel onglet ou modal
            window.open(`<?= BASE_URL?>/stream/${streamId}`, '_blank');
        });
    });
});

window.BASE_URL = '<?= BASE_URL?>';
</script>

<style>
.table td {
    vertical-align: middle;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
}
</style>