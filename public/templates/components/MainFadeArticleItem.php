<div class='artcl-itm full-wh' data-newsid="<?= $article['id'] ?>">
    <div class='artcl-cont full-wh'>
        <div class='artcl-info full-wh'>
        <a href="/?p=actuality&id=<?= $article['id'] ?>" class='img-link'>
            <div class='artcl-img'>
            <img alt='article img' class="full-wh" src='<?= $article['image_location'] ?>'/>
            </div>
        </a>
        <div class="artcl-text flx-disp col space-btwn">
            <div class="artcl-text-content elipsis no-ovrflw">
            <span class="line">
            <b class="break-word"><?= $article['newsTitle'] ?></b> <?= $article['newsHeadline'] ?>
            </span>
            </div>
            <div class="artcl-media-stats f1-s f6-b flx-disp row">
                <div class="artcl-date">
                    <i class="fa-regular fa-clock"></i>
                    <span><?= $article['newsDate'] ?></span>
                </div>
                <!--<div class="artcl-read">
                    <i class="fa-regular fa-eye" data-itemid="<?= $article['id'] ?>" ></i>
                    <span><?= $article['reads'] ?? 0 ?></span>
                </div>-->
                <div class="artcl-like like-btn">
                    <i class="fa-regular fa-heart media-ico" data-itemname="actuality" data-itemid="<?= $article['id'] ?>"></i>
                    <span><?= $article['likes'] ?? 0 ?></span>
                </div>
                <div class="artcl-share share-btn">
                    <i class="fa-regular fa-paper-plane media-ico" data-itemname="actuality" data-itemid="<?= $article['id'] ?>"></i>
                    <span><?= $article['shares'] ?? 0 ?></span>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>