<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <h2 class="mb-0">Lis Entèvyou</h2>
        
        <?php require_once __DIR__ . '/../../../Components/ProfileListActions.php' ?>
    </div>
    
    <a href="<?= BASE_URL ?>/admin/interviews/create" class="btn btn-primary">
        <i class="fas fa-plus d-none d-md-inline"></i> Ajoute Entèvyou
    </a>
</div>

<?php require_once __DIR__ . '/../../../Components/SelectionModeStickyBar.php'?>

<?php require_once __DIR__ . '/../../../Components/ProfileFilterPanel.php'?>

<!-- Interviews Container -->
<div id="interviewsContainer">
    <div class="card">
        <div class="card-body">
            <!-- Grid View -->
            <div id="gridView" class="row">
                <?php foreach ($interviews as $interview): ?>
                <div class="col-md-6 col-lg-4 mb-4 item-container interview-item" 
                    data-id="<?= $interview['id'] ?>"
                    data-category="<?= htmlspecialchars($interview['category'] ?? '') ?>"
                    data-status="<?= $interview['status'] ?? 'active' ?>"
                    data-position="<?= $interview['position'] ?? 'no_pos' ?>">
                    <div class="card h-100">
                        <!-- Selection Checkbox -->
                        <div class="selection-checkbox" style="display: none; position: absolute; top: 10px; right: 10px; z-index: 5;">
                            <input type="checkbox" class="item-select form-check-input" data-id="<?= $interview['id'] ?>" style="width: 20px; height: 20px;">
                        </div>
                        
                        <?php if ($interview['image_url']): ?>
                        <img src="<?= $interview['image_url'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" loading="lazy" onerror="this.classList.add('d-none'); this.parentElement.querySelector('.fallback-image').classList.remove('d-none');">
                        <?php endif; ?>
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center <?= $interview['image_url'] ? 'd-none' : '' ?> fallback-image" style="height: 200px;">
                            <i class="fas fa-microphone text-white" style="font-size: 3rem;"></i>
                        </div>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0"><?= htmlspecialchars($interview['title']) ?></h6>
                                <?php if ($interview['position'] === 'mainpage'): ?>
                                <span class="badge bg-primary">
                                    <i class="fas fa-star"></i> Main
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($interview['category'])): ?>
                            <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($interview['category']) ?></span>
                            <?php endif; ?>
                            <p class="card-text small text-muted">
                                <?= substr($interview['description'] ?? '', 0, 80) ?>...
                            </p>
                            <div class="d-flex justify-content-between text-muted small mt-2">
                                <span><i class="fas fa-eye"></i> <?= $interview['views'] ?? 0 ?></span>
                                <span><i class="fas fa-share-alt"></i> <?= $interview['shares'] ?? 0 ?></span>
                                <span><i class="fas fa-heart"></i> <?= $interview['likes'] ?? 0 ?></span>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                Kreye: <?= date('d.m.Y H:i', strtotime($interview['created_at'])) ?>
                            </small>
                            <div class="mt-2 item-actions">
                                <a href="<?= BASE_URL ?>/admin/interviews/<?= $interview['id'] ?>/edit" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-danger del-btn" data-id="<?= $interview['id']?>">
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
                            <th>Imaj</th>
                            <th>Tit</th>
                            <th>Category</th>
                            <th>Views</th>
                            <th>Shares</th>
                            <th>Likes</th>
                            <th>Main Page</th>
                            <th>Dat</th>
                            <th>Aksyon</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($interviews as $interview): ?>
                        <tr class="interview-item" 
                            data-id="<?= $interview['id'] ?>"
                            data-category="<?= htmlspecialchars($interview['category'] ?? '') ?>"
                            data-status="<?= $interview['status'] ?? 'active' ?>"
                            data-position="<?= $interview['position'] ?? 'no_pos' ?>">
                            <td>
                                <input type="checkbox" class="article-select form-check-input" data-id="<?= $interview['id'] ?>" style="display: none;">
                            </td>
                            <td><?= $interview['id'] ?></td>
                            <td>
                                <?php if ($interview['image_url']): ?>
                                <img src="<?= $interview['image_url'] ?>" width="50" height="50" style="object-fit: cover;" class="rounded">
                                <?php else: ?>
                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-microphone"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($interview['title']) ?></td>
                            <td><?= htmlspecialchars($interview['category'] ?? '-') ?></td>
                            <td><?= $interview['views'] ?? 0 ?></td>
                            <td><?= $interview['shares'] ?? 0 ?></td>
                            <td><?= $interview['likes'] ?? 0 ?></td>
                            <td>
                                <?php if ($interview['position'] === 'mainpage'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Yes
                                </span>
                                <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d.m.Y', strtotime($interview['created_at'])) ?></td>
                            <td>
                                <div class="item-actions">
                                    <a href="<?= BASE_URL ?>/admin/interviews/<?= $interview['id'] ?>/edit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-danger del-btn" data-id="<?= $interview['id']?>">
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
