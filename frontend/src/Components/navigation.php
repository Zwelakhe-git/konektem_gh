<div id="nav-panel" class="grey-clr no-ovrflw no-margin">
    <div id="nav" class="no-ovrflw">
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'main' ? 'active' : '' ?>" href="<?= BASE_URL ?>/">
            <i class="fa-solid fa-house"></i>
            <span>Akèy</span>
        </a>
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'news' ? 'active' : '' ?>" href="<?= BASE_URL ?>/actuality">
            <ion-icon name="calendar-clear-outline"></ion-icon>
            <span>Aktyalite</span>
        </a>
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'interviews' ? 'active' : '' ?>"
            href="<?= BASE_URL ?>/interviews">
            <i class="fa-solid fa-user-tie"></i>
            <span>Entèvyou</span>
        </a>
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'events' ? 'active' : '' ?>" href="<?= BASE_URL ?>/events">
            <ion-icon name="calendar-outline"></ion-icon>
            <span>Evenman</span>
        </a>
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'music' ? 'active' : '' ?>" href="<?= BASE_URL ?>/music">
            <ion-icon name="musical-notes-outline"></ion-icon>
            <span>Mizik</span>
        </a>
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'books' ? 'active' : '' ?>" href="<?= BASE_URL ?>/books">
            <i class="fa-brands fa-readme"></i>
            <span>Bibliyotèk</span>
        </a>
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'services' ? 'active' : '' ?>"
            href="<?= BASE_URL ?>/services">
            <i class="fa-solid fa-satellite-dish"></i>
            <span>Sèvis</span>
        </a>
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'stream' ? 'active' : '' ?>" href="<?= BASE_URL ?>/stream">
            <ion-icon name="radio-outline"></ion-icon>
            <span>Layv</span>
        </a>
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'contacts' ? 'active' : '' ?>"
            href="<?= BASE_URL ?>/contacts">
            <i class="fa-solid fa-phone"></i>
            <span>Kontak</span>
        </a>
        <a id='prof-link-' tabindex="0" class="bdr-10 nav-link" href="<?= BASE_URL ?>/user/me">
            <i class="fa-solid user-prof fa-circle-user"></i>
            <span class='user-id'>login/profile</span>
        </a>
        <a class="nav-link close-btn">
            <i class="fa-regular fa-x"></i>
            <span>Fèmen</span>
        </a>
    </div>
</div>