<div id="nav-panel-bottom" class="grey-clr no-ovrflw no-margin">
    <div class="nav" class="no-ovrflw">
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'main' ? 'active' : '' ?>" href="<?= BASE_URL ?>">
            <i class="fa-solid fa-house"></i>

        </a>
        <a tabindex="0" class="profile bdr-10 nav-link p-modal-control">
            <i class="fa-regular fa-user"></i>

        </a>
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'cart' ? 'active' : '' ?>" href="/?">
            <i class="fa-solid fa-cart-arrow-down"></i>

        </a>
        <a tabindex="0" class="bdr-10 nav-link <?= $page === 'music' ? 'active' : '' ?>" href="<?= BASE_URL ?>/music">
            <i class="fa-solid fa-music"></i>

        </a>

        <a tabindex="0" class="profile bdr-10 nav-link" href="<?= BASE_URL ?>/user/me">
            <i class="fa-solid fa-gauge"></i>

        </a>
    </div>
</div>