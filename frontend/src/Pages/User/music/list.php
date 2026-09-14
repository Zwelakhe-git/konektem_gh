
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kontrol Trak</h2>
    <div class="actions-nav flex">
        <a href="<?= BASE_URL ?>/user/me/music/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajoute trak
        </a>
        <a href="<?= BASE_URL ?>/user/me/albums/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajoute album
        </a>
    </div>
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
                    <th>Cover trak</th>
                    <th>Non trak</th>
                    <th>Аtis</th>
                    <?php if($_SESSION['user']['role'] == 'admin'){?>
                    <th>Ekout</th>
                    <th>J'aimes</th>
                    <th>on main page/order</th>
                    <?php }?>
                    <th>Aksyon</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tracks as $track): ?>
                <tr>
                    <td><?= $track['id'] ?></td>
                    <td>
                        <?php if ($track['image_url']): ?>
                        <img src="<?= $track['image_url'] ?>" width="50" height="50" style="object-fit: cover;" class="rounded">
                        <?php else: ?>
                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-music"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($track['title']) ?></td>
                    <td><?= htmlspecialchars($track['artist_name'] ?? $_SESSION['user']['name']) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/user/me/music/<?= $track['id'] ?>/edit" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a 
                           class="btn btn-sm btn-danger del-btn" data-id="<?= $track['id'] ?>">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>