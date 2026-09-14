<div class="artcl-itm full-wh <?= isset($idx) && $idx === 0 ? 'selected' : ''?>" data-lk="/konektem/actuality/<?= $article['title_hash']?>">
    <div class='artcl-cont full-wh'>
        <div class='artcl-info full-wh'>
        <a href="<?= BASE_URL?>/actuality/<?= $article['title_hash'] ?>" class='img-link'>
            <div class="left-img">
            <img alt='article img' class="full-wh" src='<?= $article['image_url'] ?>'/>
            </div>
        </a>
        <div class="p-3 pb-0 d-flex flex-column h-100 justify-content-between">
            <div class="artcl-text-content elipsis overflow-hidden">
            <span class="line">
            <b class="break-word"><?= $article['title'] ?></b> <?= $article['headline'] ?>
            </span>
            </div>
            <div class="artcl-media-stats f1-s f6-b d-flex flex-row justify-content-between">
                <div class="artcl-date">
                    <i class="fa-regular fa-clock"></i>
                    <span><?= explode(' ',$article['published_at'] ?? $article['created_at'])[0] ?></span>
                </div>
                <div class="artcl-like like-btn">
                    <i class="fa-regular fa-heart media-ico" data-itemname="article" data-itemid="<?= $article['id'] ?>"></i>
                    <span><?= $article['likes'] ?? 0 ?></span>
                </div>
                <div class="artcl-share share-btn">
                    <i class="fa-regular fa-paper-plane media-ico" data-itemname="article" data-itemid="<?= $article['id'] ?>"></i>
                    <span><?= $article['shares'] ?? 0 ?></span>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>