<div class="book-item no-shrink h-100 w-100 d-flex space-btwn" data-lk="/konektem/books?id=<?= $book['id']?>" data-itemid="<?= $book['id'] ?>">
    <div class="book-img" style="width: 48%;">
        <div class="w-100 h-100">
            <img class="w-100 h-100" alt="book-image" src="<?= $book['image_url'] ?>"
            style="border-radius: 10px;"/>
        </div>
    </div>
    <div class="book-info d-flex flex-column h-100 justify-content-between overflow-hidden w-50">
        <div class="overflow-hidden" style="height: 80%">
            <div class="book-title" style="margin-bottom: 10px;">
                <b><?= $book['title'] ?></b>
            </div>
            <div class="book-desc h-100 overflow-y-auto">
                <?= $book['description'] ?>
            </div>
        </div>
        <div class="book-media-stats justify-content-between f1-s f6-b d-flex flex-row pad-5 bdr-top solid-bdr">
            <div class="book-read">
                <i class="fa-regular fa-eye" data-itemid="<?= $book['id'] ?>" ></i>
                <span><?= $book['reads'] ?? 0 ?></span>
            </div>
            <div class="book-like like-btn">
                <!--<i class="fa-sharp-duotone fa-thin fa-link"></i>-->
                <i class="fa-solid fa-link media-ico" data-itemname="book"
                    data-itemid="<?= $book['id'] ?>"></i>
            </div>
            <div class="book-share share-btn">
                <i class="fa-regular fa-paper-plane media-ico" data-itemname="book"
                    data-itemid="<?= $book['id'] ?>"></i>
                <span><?= $book['shares'] ?? 0 ?></span>
            </div>
        </div>
    </div>
</div>