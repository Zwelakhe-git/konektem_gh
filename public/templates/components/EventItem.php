<div class="event-card" data-itemid="<?= $event['id'] ?>">
    <div class="ev-container flx-disp col">
        <img src="<?= $event['image_location'] ?>" alt="<?= $event['title'] ?>" class="event-image grow" />
        <div class="event-content pad-5">
            <div class="ticket-info">
                <span class="ticket-price">Pri Tikè: \$<?= $event['price'] ?></span>
            </div>
            <a href="/?p=buytickets&f=event&id=<?= $event['id'] ?>" class="buy-btn">buy tickets</a>
        </div>
    </div>
    <div class="event-info flx-disp col full-h space-btwn events-description">
        <div class="no-ovrflw" style="height: 80%;">
            <?php if($event['description']){
                $event['description'];
            } else {?>
                <p class="elipsis txt-lines-12 break-word pad-r5 pad-t20">no description</p>
            <?php }?>
        </div>
        <div class="intrvws-media-stats f1-s f6-b pad-5 flx-disp row bdr-top solid-bdr" style="background-color:#ededed;">
            <div class="gap-x">
                <ion-icon class="" name="time-outline"></ion-icon>
                <span><?= $event['eventDate'] ?></span>
            </div>
            <div class="like-btn gap-x">
                <i class="fa-regular fa-heart media-ico"
                data-itemname="events"
                data-itemid="<?= $event['id'] ?>"></i>
                <span><?= $event['likes'] ?? 0 ?></span>
            </div>
            <div class="share-btn">
                <i class="fa-regular fa-paper-plane media-ico"
                data-itemname="events"
                data-itemid="<?= $event['id'] ?>"></i>
                <span><?= $event['shares'] ?? 0 ?></span>
            </div>
        </div>
    </div>
</div>