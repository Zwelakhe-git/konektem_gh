<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kontwol liv</h2>
    <a href="<?= BASE_URL ?>/user/me/books/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajoute liv
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success">Operasyon reyisi!</div>
<?php endif; ?>

<div class="row">
    <?php if(empty($books)):?>
    <div style="place-items: center;">
        <h1>no added books</h1>
        <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= BASE_URL ?>/user/me/books/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajoute liv
        </a>
    </div>
    </div>
    <?php else: foreach ($books as $book): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100" style="background: white;">
            <?php if ($book['image_url']): ?>
            <img src="<?= $book['image_url'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                <p class="card-text"><?= nl2br((substr($book['description'], 0, 100))) ?>...</p>
            </div>
            <div class="card-footer">
                <a href="<?= BASE_URL ?>/user/me/books/<?= $book['id'] ?>/edit" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <a 
                   class="btn btn-sm btn-danger del-btn" data-id="<?= $book['id'] ?>">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; endif;?>
</div>
