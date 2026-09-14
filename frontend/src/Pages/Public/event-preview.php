<div class="event-item-page" style="color: black;">
    <div class="event-header">
        <h1 class="event-title"><?= $event['title']?></h1>
        <div class="event-meta">
            <span class="event-date">
                <i class="fas fa-calendar-alt"></i>
                <?= $event['event_date']?>
            </span>
        </div>
    </div>
    
    <div class="event-image-container">
        <div class="blurred-bg" style="background-image: url('<?= "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}{$event['image_url']}"?>');"></div>
        <img src="<?= $event['image_url']?>" 
                alt="image" 
                class="event-main-image" loading="lazy">
    </div>
    <div class="event-content" style="max-height: 250px; overflow-y: scroll;">
        <?= $event['description']?>
    </div>

    <div class="event-actions flex-column">
        <div class="event-stats">
            <div class="stat-item share-btn">
                <i class="fas fa-share-alt media-ico" data-itemname="event" data-itemid="<?= $event['id']?>"></i>
                <span><?= $event['shares']?></span>
            </div>
            <div class="stat-item like-btn">
                <i class="fas fa-heart media-ico" data-itemname="event" data-itemid="<?= $event['id']?>"></i>
                <span><?= $event['likes']?></span>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center gap-3">
            <span class="text-danger font-bold">$<?= $event['price'] ?></span>
            <button type="button" data-itemid="<?= $event['id']?>"
                class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition flex-grow-0 buy-btn">
                Peye
            </button>
        </div>
    </div>
</div>
