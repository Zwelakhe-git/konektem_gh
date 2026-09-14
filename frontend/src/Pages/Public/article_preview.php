<div class='container main-news' style='color: black'>
    <div class="news-content">
        <h3 class="news-title" style="
                color: black;
                text-align: center;
                font-size: 20px;
            "><?= $article['title']?></h3>
        <div class="news-img" style="
                margin-bottom: 10px;
                border-radius: 8px;
            ">
            <img src="<?= $article['image_url']?>" alt="image">
        </div>
        <div class="news-date"><?= explode(' ', $article['published_at'])[0]?></div>
        <p class="news-excerpt" style="color: inherit;
            max-height: fit-content;
            "><?= $article['headline']?></p>
        <div class="main-Ncontent">
        <?= $article['content']?>
        </div>
    </div>
</div>