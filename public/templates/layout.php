<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
        <meta name="theme-color" content="black"/>
        <meta http-equiv="Cache-Control" content="max-age=31536000">
        <title><?= $title ?? 'Konektem'?></title>
        <link rel="shortcut icon" type="image/png" href="/media/images/favicon.png"/>
        <link rel="stylesheet" href="/konektem/static/css/footerStyle.css"/>
        <link rel="stylesheet" href="/konektem/static/css/topBar.css"/>
        <link rel="stylesheet" href="/konektem/static/css/profile-settings-modal.css"/>
        <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://kit.fontawesome.com/6f0be4257f.js" crossorigin="anonymous"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" nomodule></script>
        
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
        <link rel="stylesheet" type="text/css" href="/konektem/static/css/navpanelstyle.css"/>
        <link rel="stylesheet" type="text/css" href="/konektem/static/css/globalStyle.css"/>
		<script src="/konektem/static/js/vanish-on-scroll-simple.js"></script>
        <style>
            body{
                width: 100%;
                margin: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            @media (min-width: 768px){
                .close-btn{
                    display: none;
                }
            }
            #root{
                min-height: 100vh;
                width: 100%;
            }
            #loading-logo{
                position: relative;
                margin: auto;
                margin-top: 35%;
                animation: 1s linear infinite rotate-ani;
                border-radius: 50%;
                width: fit-content;
                height: fit-content;
            }

            @keyframes rotate-ani{
                from { transform: rotate(0deg) }
                to { transform: rotate(360deg) }
            }
            
            /* search bar styles */
            .search-bar {
                background: white;
                border-radius: 9px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                border: 2px solid transparent;
                transition: all 0.3s ease;
            }
            
            .search-bar:focus-within {
                border-color: #3498db;
                box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            }
            
            #tb-inp-el {
                border: none;
                padding: 12px 20px;
                font-size: 1em;
                background: transparent;
                width: 100%;
                color: #2c3e50;
            }
            
            #tb-inp-el:focus {
                border: none;
                outline: none;
                box-shadow: none;
            }
            
            #tb-inp-el::placeholder {
                color: #95a5a6;
            }
            
            .search-bar .fa-magnifying-glass:hover {
                background: #2980b9;
                transform: scale(1.05);
            }
        </style>
    </head>
    <body>
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
        <div id="nav-panel" class="grey-clr no-ovrflw no-margin">
            <div id="nav" class="no-ovrflw">
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'main' ? 'active' : '' ?>" href="/">
                    <i class="fa-solid fa-house"></i>
                    <span>Akèy</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'news' ? 'active' : '' ?>" href="/?p=actuality">
                    <ion-icon name="calendar-clear-outline"></ion-icon>
                <span>Aktyalite</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'interviews' ? 'active' : '' ?>" href="/?p=interviews">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Entèvyou</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'events' ? 'active' : '' ?>" href="/?p=events">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <span>Evenman</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'music' ? 'active' : '' ?>" href="/?p=music">
                    <ion-icon name="musical-notes-outline"></ion-icon>
                    <span>Mizik</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'books' ? 'active' : '' ?>" href="/konektem/books">
                    <i class="fa-brands fa-readme"></i>
                    <span>Bibliyotèk</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'services' ? 'active' : '' ?>" href="/?p=services">
                    <i class="fa-solid fa-satellite-dish"></i>
                    <span>Sèvis</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'stream' ? 'active' : '' ?>" href="/?p=streaming">
                    <ion-icon name="radio-outline"></ion-icon>
                    <span>Layv</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'main' ? 'contacts' : '' ?>" href="/?p=contacts">
                    <i class="fa-solid fa-phone"></i>
                    <span>Kontak</span>
                </a>
                <a id='prof-link-' tabindex="0" class="bdr-10 nav-link" href="/account/me/index.php">
                    <i class="fa-solid user-prof fa-circle-user"></i>
                    <span class='user-id'>login/profile</span>
                </a>
                <a class="nav-link close-btn">
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
                    console.log("Options icon not found");
                    return;
                }
                
                closeIcon.addEventListener('click', toggleNavPanel);

                optsIcon.addEventListener("click", toggleNavPanel);
                const initPos = navPanel.style.top ?? window.getComputedStyle(navPanel).getPropertyValue('top');
                
                function toggleNavPanel(event = null){
                    const navPanel = document.querySelector("#nav-panel");
                    if( !navPanel ){
                        console.log("nav-panel not found");
                        return;
                    }
                    
                    navPanel.classList.toggle("open");
                    if(navPanel.classList.contains("open")){
                        navPanel.style.display = "block";
                        navPanel.style.maxHeight = `${navPanel.scrollHeight}px`;
                        document.querySelector('#top-bar')?.style.setProperty('transform', 'translate(0%, 0%)');
                    }
                    else{
                        navPanel.style.maxHeight = "0px";
                        setTimeout(() => {
                            navPanel.style.display = "none";
                            document.querySelector('#top-bar')?.style.removeProperty('transform');
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
        <div id="nav-panel-bottom" class="grey-clr no-ovrflw no-margin">
            <div class="nav" class="no-ovrflw">
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'main' ? 'active' : '' ?>" href="/">
                    <i class="fa-solid fa-house"></i> 
                    
                </a>
                <a tabindex="0" class="profile bdr-10 nav-link p-modal-control">
                    <i class="fa-regular fa-user"></i>
                
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'cart' ? 'active' : '' ?>" href="/?">
                    <i class="fa-solid fa-cart-arrow-down"></i>
                    
                </a>
                <a tabindex="0" class="bdr-10 nav-link <?= $page === 'music' ? 'active' : '' ?>" href="/?p=music">
                    <i class="fa-solid fa-music"></i>
                    
                </a>
                
                <a tabindex="0" class="profile bdr-10 nav-link" href="/account/me/index.php">
                    <i class="fa-solid fa-gauge"></i>
                    
                </a>
            </div>
        </div>
        <?php require_once 'footer.php';?>
        <?php require_once 'profile-settings-modal.php';?>
    </body>
</html>
    