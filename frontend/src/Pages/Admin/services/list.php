<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kontwol sevis</h2>
    <a href="<?= BASE_URL?>/admin/services/create" class="btn btn-primary">
        <i class="fas fa-plus d-none d-md-inline"></i> Ajoute sevis
    </a>
</div>

<div class="row">
    <?php if(empty($services)) :?>
    	<h2>No Added Services</h2>
    	<div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= BASE_URL?>/admin/services/create" class="btn btn-primary">
            <i class="fas fa-plus d-none d-md-block"></i> Ajoute sevis
        </a>
    </div>
    <?php else: foreach ($services as $service): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <?php if ($service['image_url']): ?>
            <img src="<?= $service['image_url'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($service['name']) ?></h5>
                <p class="card-text"><?= nl2br((substr($service['description'], 0, 100))) ?>...</p>
            </div>
            <div class="card-footer">
                <a href="<?= BASE_URL?>/admin/services/<?= $service['id'] ?>/edit" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <a data-id="<?= $service['id']?>"
                   class="btn btn-sm btn-danger del-btn" >
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; endif;?>
</div>