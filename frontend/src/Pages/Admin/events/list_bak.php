<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edite eveneman</h2>
    <a href="<?= BASE_URL ?>/admin/events/create" class="btn btn-primary">
        <i class="fas fa-plus d-none d-md-inline"></i> Ajoute eveneman
    </a>
</div>

<div class="row">
    <?php foreach ($events as $event): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <?php if ($event['image_url']): ?>
            <img src="<?= $event['image_url'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" loading="lazy">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($event['title']) ?></h5>
                <p class="card-text">
                    <strong>Dat:</strong> <?= $event['event_date'] ?><br>
                    <strong>Kibo> <?= htmlspecialchars($event['location']) ?></strong><br>
                    <strong>Pri:</strong> <?= $event['price'] ?> руб.
                </p>
            </div>
            <div class="card-footer">
                <a href="<?= BASE_URL ?>/admin/events/<?= $event['id'] ?>/edit" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <a 
                   class="btn btn-sm btn-danger del-btn" data-id="<?= $event['id']?>">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<script>
window.item = 'events';
window.BASE_URL = '<?= BASE_URL?>';
window.role = 'admin';
</script>