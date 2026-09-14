

<div class='container main-news' style='color: black'>
    <div class="news-content">
        <h3 class="news-title" data-newsid=<?= $article['id'] ?> style="
                color: black;
                text-align: center;
                font-size: 20px;
            "><?= $article['newsTitle'] ?></h3>
        <div class="news-img" style="
                margin-bottom: 10px;
                border-radius: 8px;
            ">
            <img src="<?= $article['image_location'] ?>" alt="Politics news">
        </div>
        <div class="news-date"><?= $article['newsDate'] ?></div>
        <p class="news-excerpt" style="color: inherit;
            max-height: fit-content;
            "><?= $article['newsHeadline'] ?></p>
        <div class="main-Ncontent">
        <?= $article['fullContent'] ?>
        </div>
    </div>
</div>
<!-- more recommended reads -->
 <!-- top 10 articles in a slide -->
<div class="recommended-reads-slide">
    <div class="slide-container" id="recommended-reads">
        <?php
            // 10 news items of the same category as the current news item
        ?>
        <div class="card">
            <div class="slide-card-image"></div>
            <div class="slide-card-body"></div>
            <div class="slide-card-footer"></div>
        </div>
    </div>
</div>