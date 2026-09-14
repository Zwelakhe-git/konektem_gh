<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kontwol videyo</h2>
    <a href="<?= BASE_URL?>/admin/videos/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajoute videyo
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success">Operasyon reyisi!</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cover</th>
                    <th>Non</th>
                    <th>Tip</th>
                    <th>Aksyon</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($videos as $video): ?>
                <tr>
                    <td><?= $video['id'] ?></td>
                    <td>
                        <?php if ($video['image_location']): ?>
                        <img src="<?= $video['image_location'] ?>" width="50" height="50" style="object-fit: cover;">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($video['vidTitle']) ?></td>
                    <td><?= $video['mime_type'] ?></td>
                    <td>
                        <a href="<?= BASE_URL?>/admin/videos/<?= $video['id'] ?>/edit" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a 
                           class="btn btn-sm btn-danger del-btn" data-id="<?= $video['id']?>">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>