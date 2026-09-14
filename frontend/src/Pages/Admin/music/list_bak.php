
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <h2 class="mb-0">Kontrol Trak</h2>
        
        <?php require_once __DIR__ . '/../../../Components/ProfileListActions.php' ?>
    </div>
    <div class="actions-nav flex">
        <a href="<?= BASE_URL ?>/admin/music/create" class="btn btn-primary">
            <i class="fas fa-plus d-none d-md-inline"></i> Ajoute trak
        </a>
        <a href="<?= BASE_URL ?>/admin/albums/create" class="btn btn-primary">
            <i class="fas fa-plus d-none d-md-inline"></i> Ajoute album
        </a>
    </div>
</div>

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
                    <td><?= htmlspecialchars($track['artist_name']) ?></td>
                    <td><?= $track['plays'] ?? 0 ?></td>
                    <td><?= $track['likes'] ?? 0 ?></td>
                    <td>
                        <input type='checkbox' class='music-pos filter' id="track-<?= $track['id'] ?>"
                               <?= $track['position'] === 'mainpage' ? 'checked' : '' ?> readonly disabled/>/
                        <input type="number" name="order_no" value="<?= $track['order_no']?>" style="border:none; margin-left: 5px;" readonly disabled/>
            		</td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/music/<?= $track['id'] ?>/edit" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a 
                           class="btn btn-sm btn-danger del-btn" data-id="<?= $track['id']?>">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>