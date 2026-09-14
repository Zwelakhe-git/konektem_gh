<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <h2 class="mb-0">Kontrol Trak</h2>
        
        <?php require_once __DIR__ . '/../../../Components/ProfileListActions.php' ?>
    </div>
    <div class="actions-nav d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/music/create" class="btn btn-primary">
            <i class="fas fa-plus d-none d-md-inline"></i> Ajoute trak
        </a>
        <a href="<?= BASE_URL ?>/admin/albums/create" class="btn btn-primary">
            <i class="fas fa-plus d-none d-md-inline"></i> Ajoute album
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../../../Components/SelectionModeStickyBar.php'?>

<?php require_once __DIR__ . '/../../../Components/ProfileFilterPanel.php'?>

<!-- Tracks Container -->
<div id="tracksContainer">
    <div class="card">
        <div class="card-body">
            <!-- Grid View -->
            <div id="gridView" class="row">
                <?php foreach ($tracks as $track): ?>
                <div class="col-md-4 col-lg-3 mb-4 item-container track-item" 
                    data-id="<?= $track['id'] ?>"
                    data-artist="<?= htmlspecialchars($track['artist_name'] ?? '') ?>"
                    data-status="<?= $track['status'] ?? 'active' ?>"
                    data-position="<?= $track['position'] ?? 'no_pos' ?>">
                    <div class="card h-100">
                        <!-- Selection Checkbox -->
                        <div class="selection-checkbox" style="display: none; position: absolute; top: 10px; right: 10px; z-index: 5;">
                            <input type="checkbox" class="item-select form-check-input" data-id="<?= $track['id'] ?>" style="width: 20px; height: 20px;">
                        </div>
                        
                        <?php if ($track['image_url']): ?>
                        <img src="<?= $track['image_url'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.classList.add('d-none'); this.parentElement.querySelector('.fallback-image').classList.remove('d-none');">
                        <?php endif; ?>
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center <?= $track['image_url'] ? 'd-none' : '' ?> fallback-image" style="height: 200px;">
                            <i class="fas fa-music text-white" style="font-size: 3rem;"></i>
                        </div>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0"><?= htmlspecialchars($track['title']) ?></h6>
                                <?php if ($track['position'] === 'mainpage'): ?>
                                <span class="badge bg-primary">
                                    <i class="fas fa-star"></i> Main
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted small mb-2">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($track['artist_name'] ?? 'Unknown') ?>
                            </div>
                            <div class="d-flex justify-content-between text-muted small">
                                <span><i class="fas fa-eye"></i> <?= $track['plays'] ?? 0 ?></span>
                                <span><i class="fas fa-heart"></i> <?= $track['likes'] ?? 0 ?></span>
                                <span><i class="fas fa-download"></i> <?= $track['downloads'] ?? 0 ?></span>
                            </div>
                            <?php if ($track['order_no']): ?>
                            <span class="badge bg-secondary mt-2">Order: <?= $track['order_no'] ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Actions -->
                        <div class="card-footer bg-transparent">
                            <div class="item-actions">
                                <a href="<?= BASE_URL ?>/admin/music/<?= $track['id'] ?>/edit" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-danger del-btn" data-id="<?= $track['id']?>">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- List View -->
            <div id="listView" style="display: none;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" class="form-check-input" id="selectAllList" style="display: none;">
                            </th>
                            <th>ID</th>
                            <th>Cover</th>
                            <th>Non trak</th>
                            <th>Atis</th>
                            <?php if($_SESSION['user']['role'] == 'admin'){?>
                            <th>Ekout</th>
                            <th>J'aimes</th>
                            <th>Main Page</th>
                            <?php }?>
                            <th>Aksyon</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tracks as $track): ?>
                        <tr class="track-item" 
                            data-id="<?= $track['id'] ?>"
                            data-artist="<?= htmlspecialchars($track['artist_name'] ?? '') ?>"
                            data-status="<?= $track['status'] ?? 'active' ?>"
                            data-position="<?= $track['position'] ?? 'no_pos' ?>">
                            <td>
                                <input type="checkbox" class="article-select form-check-input" data-id="<?= $track['id'] ?>" style="display: none;">
                            </td>
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
                            <?php if($_SESSION['user']['role'] == 'admin'){?>
                            <td><?= $track['plays'] ?? 0 ?></td>
                            <td><?= $track['likes'] ?? 0 ?></td>
                            <td>
                                <?php if ($track['position'] === 'mainpage'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Yes
                                    <?php if ($track['order_no']): ?>
                                    <span class="badge bg-light text-dark ms-1">#<?= $track['order_no'] ?></span>
                                    <?php endif; ?>
                                </span>
                                <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                                <?php endif; ?>
                            </td>
                            <?php }?>
                            <td>
                                <div class="item-actions">
                                    <a href="<?= BASE_URL ?>/admin/music/<?= $track['id'] ?>/edit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-danger del-btn" data-id="<?= $track['id']?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include the selection mode JavaScript -->
