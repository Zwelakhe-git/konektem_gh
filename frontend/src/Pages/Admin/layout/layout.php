<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="/assets/favicon.png"/>
        <title>Panel admen</title>
        <!-- В секции head добавить: -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="<?= BASE_URL?>/static/css/toast-notification.css" rel="stylesheet"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- TinyMCE -->
        <script src="https://cdn.tiny.cloud/1/<?= TINY_API?>/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
        <script src="<?= BASE_URL?>/static/js/toast-notification.js" defer></script>

        <?php if(isset($styles)){
            foreach($styles as $url){?>
            <link rel="stylesheet" href="<?= $url?>" />
        <?php } }
        if(isset($scripts)){
            foreach($scripts as $script){?>
            <script src="<?= $script['url']?>"
            <?php if(isset($script['params'])){
                    foreach($script['params'] as $param => $value){?>
                    <?= $param . "=\"$value\""?>
            <?php } }?> ></script><?php }?>
        <?php }?>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg ">
            <div class="container overflow-auto">
                    <a class='navbar-brand' href="<?= BASE_URL?>/admin">
                    <i class='fas fa-cogs me-2'></i>Panel admen</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!--collapse-->
                <div class="navbar-collapse collapse nav-panel" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL?>/">
                                <i class="fa-solid fa-house"></i><span class='link-text'>konektem</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin">
                                <i class="fas fa-tachometer-alt me-1"></i><span class='link-text'>Dachbod</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'news' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/news">
                                <i class="fas fa-newspaper me-1"></i><span class='link-text'>Nouvel</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'music' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/music">
                                <i class="fas fa-music me-1"></i><span class='link-text'>Mizik</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'services' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/services">
                                <i class="fas fa-concierge-bell me-1"></i><span class='link-text'>Sevis</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'events' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/events">
                                <i class="fas fa-calendar-alt me-1"></i><span class='link-text'>Eveneman</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'interview' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/interviews">
                                <i class="fas fa-microphone me-1"></i><span class='link-text'>Entèvyou</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'stream' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/stream">
                                <i class="fas fa-broadcast-tower me-1"></i><span class='link-text'>Strimin</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'orders' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/orders">
                                <i class="fas fa-regular fa-book"></i><span class='link-text'>orders</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'service_orders' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/service_orders">
                                <i class="fas fa-regular fa-book"></i><span class='link-text'>service orders</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'partners' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/partners">
                                <i class="fa-regular fa-handshake"></i><span class='link-text'>Patnè</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'books' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/books">
                                <i class="fa-sharp fa-solid fa-book-open"></i><span class='link-text'>liv</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link logout-link logout-btn" href="<?= BASE_URL ?>/auth/admin/logout" style='display: none'>
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav dropdown-container">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['user']['name']) ?>
                            </a>
                            <ul class="dropdown-menu" style="background-color: black">
                                <li><a class="dropdown-item settings-icon admin" href="<?= BASE_URL ?>/admin/settings"><i class="fas fa-cog me-2"></i>Paramet</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item logout-btn" href="<?= BASE_URL ?>/auth/admin/logout"><i class="fas fa-sign-out-alt me-2"></i>Soti</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <script>
            let logoutBtns = document.querySelectorAll('.logout-btn');
            logoutBtns.forEach(btn => {
                btn.addEventListener('click', ()=>{
                    localStorage.removeItem('user');
                    localStorage.removeItem('token');
                })
            })
        </script>
        
        
        <div class="container mt-4 pb-5" id="root">
            <!-- do require on template from context -->
            <?php if(file_exists($template)){
                require_once $template;
            } else { header('Location: ' . BASE_URL . '/error/404'); }?>
        </div>
    </body>
</html>
    