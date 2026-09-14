<?php
//require_once ADMIN_PATH . '/views/layout/header.php'; ?>

<div class="grid">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="icon">
                <i class="fas fa-newspaper fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL?>/admin/news/create" class="text-white text-nowrap">Ajoute Nouvel</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="icon">
                <i class="fas fa-newspaper fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL?>/admin/news" class="text-white text-nowrap">Jere Nouvel</a>
            </div>
        </div>
    </div>
    <!-- albums -->
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fas fa-cogs fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/admin/albums/create'?>" class="text-white text-nowrap">Ajoute Album</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fas fa-compact-disc fa-4x text-muted"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/admin/albums'?>" class="text-white text-nowrap">Jere Album</a>
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
                <a href="<?= BASE_URL . '/admin/music/create'?>" class="text-white text-nowrap">Ajoute Mizik</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="icon">
                <i class="fas fa-music fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/admin/music'?>" class="text-white text-nowrap">Jere Mizik</a>
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
                <a href="<?= BASE_URL . '/admin/events/create'?>" class="text-white text-nowrap">Ajoute Evenman</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fas fa-calendar-alt fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/admin/events'?>" class="text-white text-nowrap">Jere Evenman</a>
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
                <a href="<?= BASE_URL . '/admin/books/create'?>" class="text-white text-nowrap">Ajoute Liv</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/admin/books'?>" class="text-white text-nowrap">Jere Liv</a>
            </div>
        </div>
    </div>
    
    <!-- streams -->
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="icon">
                <i class="fas fa-video fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/admin/stream/create'?>" class="text-white text-nowrap">strimin</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="icon">
                <i class="fas fa-video fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/admin/stream'?>" class="text-white text-nowrap">Kreye strimin</a>
            </div>
        </div>
    </div>
    <!-- services -->
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fas fa-cogs fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/admin/services/create'?>" class="text-white text-nowrap">Ajoute Sevis</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="icon">
                <i class="fas fa-cogs fa-2x"></i>
            </div>
            <div class="label">
                <a href="<?= BASE_URL . '/admin/services'?>" class="text-white text-nowrap">Jere Sevis</a>
            </div>
        </div>
    </div>
    
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Aksyon rapid</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    
                    <a href="<?= BASE_URL . '/admin/news/create'?>" class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i> Ajoute nouvel
                    </a>
                    <a href="<?= BASE_URL . '/admin/music/create'?>" class="btn btn-outline-success">
                        <i class="fas fa-plus"></i> Ajoute trak
                    </a>
                    <a href="<?= BASE_URL . '/admin/stream/create'?>" class="btn btn-outline-warning">
                        <i class="fas fa-plus"></i> Kreye strim
                    </a>
                    <a href="<?= BASE_URL . '/admin/events/create'?>" class="btn btn-outline-info">
                        <i class="fas fa-plus"></i> Ajoute eveneman
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
