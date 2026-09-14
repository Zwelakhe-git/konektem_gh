<div class="intrvw-itm no-shrink full-wh" data-lk="/konektem/interviews/id/<?= $interview['id']?>" data-itemid="<?= $interview['id'] ?>">
    <div class='full-wh overflow-hidden'>
        <div class='full-wh d-flex flex-row bdr-8' style="background-color:#ededed">
            <a class=''>
                <div class='left-img white-bg pad-5 abs-img-cont'>
                    <img alt='image' class="full-wh bdr-8" src='<?= $interview['image_url'] ?>'/>
                </div>
            </a>
            <div class="d-flex flex-column justify-content-between p-3 pb-0 overflow-hidden" >
                <div class="elipsis">
                    <b class="break-word"><?= $interview['title']?></b>
                    <?= $interview['description'] ?>
                </div>
                <div class="intrvws-media-stats f1-s f6-b pad-5 d-flex flex-row justify-content-between bdr-top solid-bdr" style="background-color:#ededed;">
                    <div class="gap-x">
                        <i class="fa-regular fa-clock"></i>
                        <span><?= str_split($interview['created_at'] ?? $interview['interview_date'])[0] ?></span>
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