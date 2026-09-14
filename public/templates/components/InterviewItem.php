<div class='intrvw-itm no-shrink full-wh' data-itemid="<?= $interview['id'] ?>">
    <div class='full-wh' style="overflow: hidden">
        <div class='full-wh flx-disp row bdr-8' style="background-color:#ededed">
            <a class=''>
                <div class='left-img bg-white pad-5 abs-img-cont'>
                    <img alt='image' class="full-wh bdr-8" src='<?= $interview['image_location'] ?>'/>
                </div>
            </a>
            <div class="flx-disp space-btwn col intrvw-descrp no-ovrflw" >
                <div class="elipsis">
                    <b class="break-word"><?= $interview['title']?></b>
                    <?= $interview['description'] ?>
                </div>
                <div class="intrvws-media-stats f1-s f6-b pad-5 flx-disp row bdr-top solid-bdr" style="background-color:#ededed;">
                    <div class="gap-x">
                        <i class="fa-regular fa-clock"></i>
                        <span><?= str_split($interview['created_at'])[0] ?></span>
                    </div>
                    <div class="like-btn gap-x">
                        <i class="fa-regular fa-heart media-ico" data-itemname="interviews" data-itemid="<?= $interview['id'] ?>"></i>
                        <span><?= $interview['likes'] ?></span>
                    </div>
                    <div class="share-btn">
                        <i class="fa-regular fa-paper-plane media-ico" data-itemname="interviews" data-itemid="<?= $interview['id'] ?>"></i>
                        <span><?= $interview['shares'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>