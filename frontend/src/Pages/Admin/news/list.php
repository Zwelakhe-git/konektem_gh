<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <h2 class="mb-0">News control</h2>
        
        <?php require_once __DIR__ . '/../../../Components/ProfileListActions.php' ?>
    </div>
    
    <a href="<?= BASE_URL?>/admin/news/create" class="btn btn-primary">
        <i class="fas fa-plus d-none d-md-inline"></i> Ajoute nouvel
    </a>
</div>

<div class="p-3 mb-3 text-warning bg-warning-subtle rounded-4">
    Under development
</div>

<?php require_once __DIR__ . '/../../../Components/SelectionModeStickyBar.php'?>

<?php require_once __DIR__ . '/../../../Components/ProfileFilterPanel.php'?>

<!-- Articles Container -->
<div id="articlesContainer">
    <div class="card">
        <div class="card-body">
            <!-- Grid View -->
            <div id="gridView" class="row">
                <?php foreach ($articles as $article): ?>
                <div class="col-md-4 col-lg-3 mb-4 item-container article-item" 
                    data-id="<?= $article['id'] ?>"
                    data-category="<?= htmlspecialchars($article['category'] ?? '') ?>"
                    data-status="<?= $article['published_at'] ? 'published' : 'draft' ?>"
                    data-position="<?= htmlspecialchars($article['position'] ?? 'no_pos') ?>">
                    <div class="card h-100">
                        <!-- Selection Checkbox -->
                        <div class="selection-checkbox" style="display: none; position: absolute; top: 10px; right: 10px; z-index: 5;">
                            <input type="checkbox" class="item-select form-check-input" data-id="<?= $article['id'] ?>" style="width: 20px; height: 20px;">
                        </div>
                        
                        <?php //if ($article['image_url']): ?>
                        <img src="<?= $article['image_url'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.classList.add('d-none'); this.parentElement.querySelector('.fallback-image').classList.remove('d-none');">
                        <?php //else: ?>
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center d-none fallback-image" style="height: 200px;">
                            <i class="fas fa-newspaper text-white" style="font-size: 3rem;"></i>
                        </div>
                        <?php //endif; ?>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0"><?= htmlspecialchars($article['title']) ?></h6>
                                <?php if ($article['published_at']): ?>
                                <span class="badge bg-success">Published</span>
                                <?php else: ?>
                                <span class="badge bg-warning text-dark">Draft</span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($article['category'])): ?>
                            <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($article['category']) ?></span>
                            <?php endif; ?>
                            <p class="card-text small text-muted">
                                <?= htmlspecialchars(substr($article['description'] ?? '', 0, 80)) ?>...
                            </p>
                            <?php if ($article['position'] && $article['position'] !== 'no_pos'): ?>
                            <span class="badge bg-primary">
                                <i class="fas fa-star"></i> Main Page
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Actions -->
                        <div class="card-footer bg-transparent">
                            <div class="mt-2 item-actions">
                            <small class="text-muted">
                                <?= $article['published_at'] ? date('d.m.Y H:i', strtotime($article['published_at'])) : 'Not published' ?>
                            </small>
                            <div class="mt-2 article-actions">
                                <a href="<?= BASE_URL ?>/admin/news/<?= $article['id'] ?>/edit" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-danger del-btn" data-id="<?= $article['id']?>">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
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
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>Position</th>
                            <th>Dat</th>
                            <th>Aksyon</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                        <tr class="article-item" 
                            data-id="<?= $article['id'] ?>"
                            data-category="<?= htmlspecialchars($article['category'] ?? '') ?>"
                            data-status="<?= $article['published_at'] ? 'published' : 'draft' ?>"
                            data-position="<?= htmlspecialchars($article['position'] ?? 'no_pos') ?>">
                            <td>
                                <input type="checkbox" class="article-select form-check-input" data-id="<?= $article['id'] ?>" style="display: none;">
                            </td>
                            <td><?= $article['id'] ?></td>
                            <td>
                                <?php if ($article['image_url']): ?>
                                <img src="<?= $article['image_url'] ?>" width="50" height="50" style="object-fit: cover;" class="rounded">
                                <?php else: ?>
                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($article['title']) ?></td>
                            <td><?= htmlspecialchars($article['category'] ?? '-') ?></td>
                            <td>
                                <?php if ($article['published_at']): ?>
                                <span class="badge bg-success">Published</span>
                                <?php else: ?>
                                <span class="badge bg-warning text-dark">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($article['position'] && $article['position'] !== 'no_pos'): ?>
                                <span class="badge bg-primary">
                                    <i class="fas fa-star"></i> Main
                                </span>
                                <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $article['published_at'] ?? '-' ?></td>
                            <td>
                                <div class="item-actions">
                                    <a href="<?= BASE_URL ?>/admin/news/<?= $article['id'] ?>/edit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-danger del-btn" data-id="<?= $article['id']?>">
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