<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
        <meta name="theme-color" content="black"/>
        <meta http-equiv="Cache-Control" content="max-age=31536000">
        <?php if(isset($og_params)){
            foreach($og_params as $k => $v){
        ?>
        <meta property="og:<?= $k?>" content="<?= $v?>" />
        <?php
            }
        }?>
        <title><?= $title ?? 'Konektem'?></title>
        <link rel="shortcut icon" type="image/png" href="/assets/favicon.png"/>
        
        <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://kit.fontawesome.com/6f0be4257f.js" crossorigin="anonymous"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" nomodule></script>
        <link rel="stylesheet" href="<?= BASE_URL?>/static/css/footerStyle.css"/>
        <link rel="stylesheet" href="<?= BASE_URL?>/static/css/topBar.css"/>
        <link rel="stylesheet" href="<?= BASE_URL?>/static/css/profile-settings-modal.css"/>
        <link rel="stylesheet" type="text/css" href="<?= BASE_URL?>/static/css/navpanelstyle.css"/>
        <link rel="stylesheet" type="text/css" href="<?= BASE_URL?>/static/css/globalStyle.css"/>
        <link rel="stylesheet" type="text/css" href="<?= BASE_URL?>/static/css/global.css"/>
        <link rel="stylesheet" type="text/css" href="<?= BASE_URL?>/static/css/adaptive-theme.css"/>
        <link rel="stylesheet" type="text/css" href="<?= BASE_URL?>/static/css/toast-notification.css"/>
        <link rel="stylesheet" type="text/css" href="<?= BASE_URL?>/static/css/auth-form.css"/>
        <link rel="stylesheet" type="text/css" href="<?= BASE_URL?>/static/css/layout.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
		<script src="<?= BASE_URL?>/static/js/vanish-on-scroll-simple.js"></script>
        <script src="<?= BASE_URL?>/static/js/toast-notification.js" defer></script>
        <script src="<?= BASE_URL?>/static/js/main-page-script.js" type="module" defer></script>
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
    <body data-theme="light">
        <div id="top-bar" class="no-margin flxDisp">
            <div class="top-bar-container">
                
                <div id="top-bar-content" class="flxDisp full-h">
                    <div id="logo">
                        <img alt="logo" type="image/jpg" src="/media/images/Konektem1.png"/>
                    </div>
                    <div class="container links search flxDisp">
                        <div class="search-bar flxDisp">
                            <input id="tb-inp-el" class="input no-outln no-bdr full-w" name='search' placeholder='Chache...' />
                            <div id="tb-inp-div" class="input search-inp no-outln no-bdr full-w"><span>Chache</span></div>
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <div class="social evenFlx">
                        </div>
                    </div>
                    <div class="icon-container opts">
                        <i class="fa-solid fa-bars icon opts-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div id="nav-panel" class="grey-clr overflow-hidden">
            <div id="nav" class="overflow-hidden flex-grow-1">
                <a tabindex="0" class="bdr-10 d-inline-flex nav-link <?= $page === 'main' ? 'active' : '' ?>" href="<?= BASE_URL?>/">
                    <i class="fa-solid fa-house"></i>
                    <span>Akèy</span>
                </a>
                <a tabindex="0" class="bdr-10 d-inline-flex nav-link <?= $page === 'news' ? 'active' : '' ?>" href="<?= BASE_URL?>/actuality">
                    <ion-icon name="calendar-clear-outline"></ion-icon>
                <span>Aktyalite</span>
                </a>
                <a tabindex="0" class="bdr-10 d-inline-flex nav-link <?= $page === 'interviews' ? 'active' : '' ?>" href="<?= BASE_URL?>/interviews">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Entèvyou</span>
                </a>
                <a tabindex="0" class="bdr-10 d-inline-flex nav-link <?= $page === 'events' ? 'active' : '' ?>" href="<?= BASE_URL?>/events">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <span>Evenman</span>
                </a>
                <a tabindex="0" class="bdr-10 d-inline-flex nav-link <?= $page === 'music' ? 'active' : '' ?>" href="<?= BASE_URL?>/music">
                    <ion-icon name="musical-notes-outline"></ion-icon>
                    <span>Mizik</span>
                </a>
                <a tabindex="0" class="bdr-10 d-inline-flex nav-link <?= $page === 'books' ? 'active' : '' ?>" href="<?= BASE_URL?>/books">
                    <i class="fa-brands fa-readme"></i>
                    <span>Bibliyotèk</span>
                </a>
                <a tabindex="0" class="bdr-10 d-inline-flex nav-link <?= $page === 'services' ? 'active' : '' ?>" href="<?= BASE_URL?>/services">
                    <i class="fa-solid fa-satellite-dish"></i>
                    <span>Sèvis</span>
                </a>
                <a tabindex="0" class="bdr-10 d-inline-flex nav-link <?= $page === 'stream' ? 'active' : '' ?>" href="<?= BASE_URL?>/stream">
                    <ion-icon name="radio-outline"></ion-icon>
                    <span>Layv</span>
                </a>
                <a tabindex="0" class="bdr-10 d-inline-flex nav-link <?= $page === 'contacts' ? 'active' : '' ?>" href="<?= BASE_URL?>/contacts">
                    <i class="fa-solid fa-phone"></i>
                    <span>Kontak</span>
                </a>
                <a id='prof-link-' tabindex="0" class="bdr-10 d-inline-flex nav-link" href="<?= BASE_URL?>/user/me">
                    <i class="fa-solid user-prof fa-circle-user"></i>
                    <span class='user-id'>login/profile</span>
                </a>
                <a class="nav-link close-btn d-md-none">
                    <i class="fa-regular fa-x"></i>
                    <span>Fèmen</span>
                </a>
            </div>
        </div>

        <script>
            const navLinks = document.querySelectorAll('#nav-panel .nav-link');
            let navPanel = document.querySelector('#nav-panel');
            
            navLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    // For internal navigation links, close the panel after click
                    if (link.getAttribute('href') && link.getAttribute('href') !== '#') {
                        toggleNavPanel();
                    }
                    
                    // Handle profile link separately
                    if (link.id === 'prof-link') {
                        e.preventDefault();
                        // Profile link handling is done in profLinkClickHandle
                        return;
                    }
                });
            });
            async function setTopBar(){
                let optsIcon = document.querySelector(".opts-icon");
                let closeIcon = document.querySelector("#nav-panel .close-btn");
                const navPanel = document.querySelector("#nav-panel");
                if(!optsIcon){
                    return;
                }
                
                closeIcon.addEventListener('click', toggleNavPanel);

                optsIcon.addEventListener("click", toggleNavPanel);
                const initPos = navPanel.style.top ?? window.getComputedStyle(navPanel).getPropertyValue('top');
                
                function toggleNavPanel(event = null){
                    const navPanel = document.querySelector("#nav-panel");
                    if( !navPanel ){
                        return;
                    }
                    
                    const isOpen = navPanel.classList.toggle("open");
                    if (isOpen) {
                        navPanel.style.display = 'block';
                        navPanel.style.maxHeight = 'none';

                        document.querySelector('#top-bar')?.classList.add('fixed');
                        requestAnimationFrame(() => {
                            const h = navPanel.scrollHeight;
                            navPanel.style.maxHeight = '';
                            navPanel.style.setProperty('--nav-panel-height', `${h}px`);
                        });
                    } else {
                        navPanel.style.removeProperty('--nav-panel-height');
                        setTimeout(() => {
                            navPanel.style.display = '';
                            document.querySelector('#top-bar')?.classList.remove('fixed');
                        }, 350);
                    }
                    
                }
                
                return;
            }
            setTopBar();
        </script>
        <script src="<?= BASE_URL . '/static/js/search-engine.js'?>"></script>
        <script>
            // Search functionality
            function initSearchFunctionality() {
                const searchInput = document.getElementById('tb-inp-el');
                const searchButton = document.querySelector('.fa-magnifying-glass');
                const navSearchLink = document.getElementById('nav-search-link');
                
                // Top bar search functionality
                if (searchInput && searchButton) {
                    searchButton.addEventListener('click', performSearch);
                    
                    searchInput.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') {
                            performSearch();
                        }
                    });
                }
                
                // Navigation panel search link functionality
                if (navSearchLink) {
                    navSearchLink.addEventListener('click', (e) => {
                        e.preventDefault();
                        
                        // Close mobile navigation panel if open
                        const navPanel = document.querySelector('#nav-panel');
                        if (navPanel && navPanel.classList.contains('open')) {
                            navPanel.classList.remove('open');
                            navPanel.style.maxHeight = '0px';
                            setTimeout(() => {
                                navPanel.style.display = 'none';
                            }, 350);
                        }
                        
                        // Focus on search input
                        if (searchInput) {
                            searchInput.focus();
                            // Scroll to top to ensure search input is visible
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    });
                }
            }
            var currentDisplay = window.innerWidth;
            var numberRegex = /[0-9]+[.]?[0-9]+/;   
            async function responsiveWindow(){
                const topBar = document.querySelector("#top-bar");
                const navPanel = document.querySelector("#nav-panel");
                var topBarStyles = window.getComputedStyle(topBar);
                let initNpRec = navPanel.getBoundingClientRect();
                navPanel.style.top = `${topBar.clientHeight + Number(numberRegex.exec(topBarStyles.marginBottom))}px`;

                window.addEventListener('resize', ()=>{
                    if((window.innerWidth > 768 && currentDisplay < 768)
                    ){
                        navPanel.style.top = `${topBar.clientHeight + Number(numberRegex.exec(topBarStyles.marginBottom))}px`;
                        navPanel.style.display = 'flex';
                        currentDisplay = window.innerWidth;
                    } else if(window.innerWidth < 768 && currentDisplay > 768){
                        initNpRec = navPanel.getBoundingClientRect();
                        navPanel.style.top = `${topBarStyles.height}`;
                        currentDisplay = window.innerWidth;
                    }
                });

            }
            initSearchFunctionality();
            responsiveWindow();
        </script>
        <div id="root">
            <?php if(isset($template) && (file_exists( __DIR__ . "/$template") || file_exists($template))){
    				require_once $template;
			}?>
        </div>
        <div id="nav-panel-bottom" class="grey-clr overflow-hidden">
            <div class="nav" class="overflow-hidden flex-grow-1">
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'main' ? 'active' : '' ?>" href="<?= BASE_URL?>/">
                    <i class="fa-solid fa-house"></i> 
                    
                </a>
                <a tabindex="0" class="profile bdr-10 nav-link p-modal-control">
                    <i class="fa-regular fa-user"></i>
                
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'cart' ? 'active' : '' ?>" href="/user/me/orders">
                    <i class="fa-solid fa-cart-arrow-down"></i>
                    <?php if(isset($_SESSION['user']) && !empty($_SESSION['user']['orders'])){
                        $pendingOrders = array_values(array_filter($_SESSION['user']['orders'], fn($order) => $order['order_status'] === 'pending'));
                        if(count($pendingOrders) > 0){?>
                        <span class="cart-count position-absolute top-0" style="right: 0px;background-color: red;color:white;font-size: 12px;width: 16px;height: 16px;border-radius: 50%;"><?= count($pendingOrders) ?></span>
                    <?php } }?>
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'music' ? 'active' : '' ?>" href="<?= BASE_URL?>/music">
                    <i class="fa-solid fa-music"></i>
                    
                </a>
                
                <a tabindex="0" class="profile bdr-10 nav-link" href="<?= BASE_URL?>/user/me">
                    <i class="fa-solid fa-gauge"></i>
                    
                </a>
            </div>
        </div>
        <?php require_once 'footer.php';?>
    </body>
</html>
    