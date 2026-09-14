<?php
//require_once ADMIN_PATH . '/views/layout/header.php'; ?>

<div class="grid">
    <!-- albums -->
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fas fa-cogs fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/user/me/albums/create'?>" class="text-white text-nowrap">Ajoute Album</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fas fa-compact-disc fa-4x text-muted"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/user/me/albums'?>" class="text-white text-nowrap">Jere Album</a>
            </div>
        </div>
    </div>
    <!-- tracks -->
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fas fa-cogs fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/user/me/music/create'?>" class="text-white text-nowrap">Ajoute Mizik</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="icon">
                <i class="fas fa-music fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/user/me/music'?>" class="text-white text-nowrap">Jere Mizik</a>
            </div>
        </div>
    </div>
    <!-- events -->
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fas fa-calendar-alt fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/user/me/events/create'?>" class="text-white text-nowrap">Ajoute Evenman</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fas fa-calendar-alt fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/user/me/events'?>" class="text-white text-nowrap">Jere Evenman</a>
            </div>
        </div>
    </div>
    <!-- books -->
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/user/me/books/create'?>" class="text-white text-nowrap">Ajoute Liv</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/user/me/books'?>" class="text-white text-nowrap">Jere Liv</a>
            </div>
        </div>
    </div>
</div>
</div>
<?php //require_once ADMIN_PATH . '/views/layout/footer.php'; ?>