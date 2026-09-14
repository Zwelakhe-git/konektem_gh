<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <h2 class="mb-0">Edite eveneman</h2>
        
        <?php require_once __DIR__ . '/../../../Components/ProfileListActions.php' ?>
    </div>
    
    <a href="<?= BASE_URL ?>/admin/events/create" class="btn btn-primary">
        <i class="fas fa-plus d-none d-md-inline"></i> Ajoute eveneman
    </a>
</div>

<?php require_once __DIR__ . '/../../../Components/SelectionModeStickyBar.php'?>

<?php require_once __DIR__ . '/../../../Components/ProfileFilterPanel.php'?>

<!-- Events Container -->
<div id="eventsContainer">
    <div class="card">
        <div class="card-body">
            <!-- Grid View -->
            <div id="gridView" class="row">
                <?php foreach ($events as $event): ?>
                <div class="col-md-4 col-lg-3 mb-4 item-container event-item" 
                    data-id="<?= $event['id'] ?>"
                    data-category="<?= htmlspecialchars($event['category'] ?? '') ?>"
                    data-status="<?= $event['status'] ?? 'active' ?>"
                    data-position="<?= $event['position'] ?? 'no_pos' ?>"
                    data-date="<?= $event['event_date'] ?? '' ?>">
                    <div class="card h-100">
                        <!-- Selection Checkbox -->
                        <div class="selection-checkbox" style="display: none; position: absolute; top: 10px; right: 10px; z-index: 5;">
                            <input type="checkbox" class="item-select form-check-input" data-id="<?= $event['id'] ?>" style="width: 20px; height: 20px;">
                        </div>
                        
                        <?php if ($event['image_url']): ?>
                        <img src="<?= $event['image_url'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" loading="lazy" onerror="this.classList.add('d-none'); this.parentElement.querySelector('.fallback-image').classList.remove('d-none');">
                        <?php endif; ?>
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center <?= $event['image_url'] ? 'd-none' : '' ?> fallback-image" style="height: 200px;">
                            <i class="fas fa-calendar-alt text-white" style="font-size: 3rem;"></i>
                        </div>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0"><?= htmlspecialchars($event['title']) ?></h6>
                                <?php if ($event['position'] === 'mainpage'): ?>
                                <span class="badge bg-primary">
                                    <i class="fas fa-star"></i> Main
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted small mb-2">
                                <i class="fas fa-calendar-alt"></i> <?= date('d.m.Y', strtotime($event['event_date'])) ?><br>
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['location'] ?? 'No location') ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success" style="font-size: 0.9rem;">
                                    <i class="fas fa-tag"></i> <?= number_format($event['price'], 0, ',', ' ') ?> usd.
                                </span>
                                <?php if (!empty($event['category'])): ?>
                                <span class="badge bg-info text-dark"><?= htmlspecialchars($event['category']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="card-footer bg-transparent">
                            <div class="item-actions">
                                <a href="<?= BASE_URL ?>/admin/events/<?= $event['id'] ?>/edit" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-danger del-btn" data-id="<?= $event['id']?>">
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
                            <th>Image</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Main Page</th>
                            <th>Aksyon</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                        <tr class="event-item" 
                            data-id="<?= $event['id'] ?>"
                            data-category="<?= htmlspecialchars($event['category'] ?? '') ?>"
                            data-status="<?= $event['status'] ?? 'active' ?>"
                            data-position="<?= $event['position'] ?? 'no_pos' ?>"
                            data-date="<?= $event['event_date'] ?? '' ?>">
                            <td>
                                <input type="checkbox" class="article-select form-check-input" data-id="<?= $event['id'] ?>" style="display: none;">
                            </td>
                            <td><?= $event['id'] ?></td>
                            <td>
                                <?php if ($event['image_url']): ?>
                                <img src="<?= $event['image_url'] ?>" width="50" height="50" style="object-fit: cover;" class="rounded">
                                <?php else: ?>
                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($event['title']) ?></td>
                            <td><?= date('d.m.Y', strtotime($event['event_date'])) ?></td>
                            <td><?= htmlspecialchars($event['location'] ?? '-') ?></td>
                            <td><span class="badge bg-success"><?= number_format($event['price'], 0, ',', ' ') ?> руб.</span></td>
                            <td>
                                <?php if ($event['position'] === 'mainpage'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Yes
                                </span>
                                <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="item-actions">
                                    <a href="<?= BASE_URL ?>/admin/events/<?= $event['id'] ?>/edit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-danger del-btn" data-id="<?= $event['id']?>">
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
