<div class="book-item no-shrink full-wh flx-disp space-btwn" data-itemid="<?= $book['id'] ?>">
    <div class="book-img" style="width: 48%;">
        <div class="full-wh">
            <img class="full-wh" alt="book-image" src="<?= $book['image_location'] ?>"
            style="border-radius: 10px;"/>
        </div>
    </div>
    <div class="book-info flx-disp col full-h space-btwn no-ovrflw events-description" style="width: 50%;">
        <div class="no-ovrflw" style="height: 80%">
            <div class="book-title" style="margin-bottom: 10px;">
                <h3><?= $book['title'] ?></h3>
            </div>
            <div class="book-desc" style="overflow-y: auto; height: 100%">
                <?= $book['description'] ?>
            </div>
        </div>
        <div class="book-media-stats f1-s f6-b flx-disp pad-5 row bdr-top solid-bdr">
            <div class="book-read">
                <i class="fa-regular fa-eye" data-itemid="<?= $book['id'] ?>" ></i>
                <span><?= $book['reads'] ?? 0 ?></span>
            </div>
            <div class="book-like -btn">
                <!--<i class="fa-sharp-duotone fa-thin fa-link"></i>-->
                <i class="fa-solid fa-link media-ico" data-itemname="books"
                    data-itemid="<?= $book['id'] ?>"></i>
            </div>
            <div class="book-share share-btn">
                <i class="fa-regular fa-paper-plane media-ico" data-itemname="books"
                    data-itemid="<?= $book['id'] ?>"></i>
                <span><?= $book['shares'] ?? 0 ?></span>
            </div>
        </div>
    </div>
</div>