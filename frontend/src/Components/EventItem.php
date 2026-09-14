<div class="event-card clickable <?= isset($idx) && $idx === 0 ? 'selected' : ''?>" data-lk="/konektem/events/id/<?= $event['id']?>" data-itemid="<?= $event['id'] ?>">
    <div class="ev-container d-flex flex-column">
        <img src="<?= $event['image_url'] ?>" alt="<?= $event['title'] ?>" class="event-image grow" loading="lazy" />
        <div class="event-content pad-5">
            <div class="ticket-info">
                <span class="ticket-price">Pri Tikè: $<?= $event['price'] ?></span>
            </div>
            <a class="buy-btn">buy tickets</a>
        </div>
    </div>
    <div class="event-info d-flex flex-column h-100 justify-content-between events-description">
        <div class="overflow-hidden" style="height: 80%;">
            <?= $event['description'] ?? '<p class="elipsis txt-lines-12 break-word pad-r5 pad-t20">no description</p>'?>
        </div>
        <div class="intrvws-media-stats justify-content-between f1-s f6-b pad-5 d-flex flex-row bdr-top solid-bdr" style="background-color:#ededed;">
            <div class="gap-x">
                <ion-icon class="" name="time-outline"></ion-icon>
                <span><?= $event['event_date'] ?></span>
            </div>
            <div class="like-btn gap-x">
                <i class="fa-regular fa-heart media-ico"
                data-itemname="event"
                data-itemid="<?= $event['id'] ?>"></i>
                <span><?= $event['likes'] ?? 0 ?></span>
            </div>
            <div class="share-btn">
                <i class="fa-regular fa-paper-plane media-ico"
                data-itemname="event"
                data-itemid="<?= $event['id'] ?>"></i>
                <span><?= $event['shares'] ?? 0 ?></span>
            </div>
        </div>
    </div>
</div>