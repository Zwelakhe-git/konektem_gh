<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="/assets/favicon.png"/>
        <title><?= $_SESSION['user']['name'] . ' - konektem' ?></title>
        <!-- В секции head добавить: -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="<?= BASE_URL?>/static/css/toast-notification.css"/>
        <link rel="stylesheet" href="<?= BASE_URL?>/static/css/adaptive_theme_v1.css"/>
        <link rel="stylesheet" href="<?= BASE_URL ?>/static/css/profile-edit.css"/>
        <link rel="stylesheet" href="<?= BASE_URL ?>/static/css/content-publishing-modal.css"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- TinyMCE -->
        <script src="https://cdn.tiny.cloud/1/<?= TINY_API?>/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
        <script src="<?= BASE_URL?>/static/js/toast-notification.js" defer></script>
        <script src="<?= BASE_URL?>/static/admin/js/profile-modal.js" type="module"></script>
        <script src="<?= BASE_URL?>/static/js/content-publishing-modal.js" type="module"></script>
        
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
            <div class="container">
                <div class="header">
                    <div class="info">Menu</div>
                    <div class="opts-container settings-icon"><i class="fas fa-cog me-2"></i></div>
                </div>
                <div class="user-info profile">
                    <div class="menu-panel"></div>
                    <div class="user-avatar">
                        <?php if(!empty($_SESSION['user']['avatar_url'])): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['user']['avatar_url']); ?>" alt="User Avatar">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <?php echo strtoupper(substr($_SESSION['user']['name'] ?? 'G', 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                        <?php if(!empty($_SESSION['user']['email'])): ?>
                            <span class="user-email"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!--collapse-->
                <div class="navbar-collapse collapse nav-panel nav-panel-bottom" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL?>/">
                                <i class="fa-solid fa-house"></i><span class='link-text'>konektem</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>/user/me">
                                <i class="fas fa-tachometer-alt me-1"></i><span class='link-text'>Dachbod</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'music' ? 'active' : '' ?>" href="<?= BASE_URL ?>/user/me/music">
                                <i class="fas fa-music me-1"></i><span class='link-text'>Mizik</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'events' ? 'active' : '' ?>" href="<?= BASE_URL ?>/user/me/events">
                                <i class="fas fa-calendar-alt me-1"></i><span class='link-text'>Eveneman</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') == 'books' ? 'active' : '' ?>" href="<?= BASE_URL ?>/user/me/books">
                                <i class="fa-sharp fa-solid fa-book-open"></i><span class='link-text'>liv</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link logout-link logout-btn" href="<?= BASE_URL ?>/auth/logout" style='display: none'>
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
                                <li><a class="dropdown-item settings-icon"><i class="fas fa-cog me-2"></i>Paramet</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item logout-btn" href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Soti</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <button class="theme-toggle" id="themeToggle">
                                    <i class="fas fa-moon"></i>
                                </button>
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
        
        <!-- Profile Edit Modal -->
        
        <div class="container mt-4 pb-5" id="root">
            <!-- do require on template from context -->
            <?php if(file_exists($template)){
                require_once $template;
            } else { header('Location: /konektem/error/404'); }?>
        </div>
        <?php
        preg_match('/(konektem)?\/user\/me\/?(\w+)?\/?(\d+)?\/?(\w+)?/', $_SERVER['REQUEST_URI'], $matches);
        if( count($matches) <= 3 ){?>
        <div class="flex-disp flex-row-reverse my-5 px-5">
            <div id="openPostingBtn" class="poster inline-flex items-center justify-center px-4 py-2 h-10 rounded-md bg-blue-600 text-white text-lg font-medium border border-blue-700 shadow-sm hover:bg-blue-700 active:bg-blue-800 transition-colors duration-150">
                Boost post
            </div>
        </div>
        <?php }?>
        <!-- end of body content -->
         <script>
            // Universal theme toggle
            document.addEventListener('DOMContentLoaded', () => {
                const toggleBtn = document.getElementById('themeToggle');
                const savedTheme = localStorage.getItem('theme') || 'dark';
                document.documentElement.setAttribute('data-theme', savedTheme);
                updateIcon(savedTheme);

                toggleBtn.addEventListener('click', () => {
                    const current = document.documentElement.getAttribute('data-theme');
                    const next = current === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next);
                    localStorage.setItem('theme', next);
                    updateIcon(next);
                });

                function updateIcon(theme) {
                    const icon = toggleBtn.querySelector('i');
                    icon.className = theme === 'light' ? 'fas fa-sun' : 'fas fa-moon';
                }
            });
        </script>
        
    </body>
</html>
