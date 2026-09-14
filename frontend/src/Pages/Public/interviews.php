<div id="interviews-section" class="col pad-20 white-bg bdr-box">
    <div id="interviews-section-bg" class="full-h"></div>
    <div class="section-info m-3" style="text-align: center;margin-bottom:15px;">
        <h2>Entèvyou</h2>
    </div>
    <div id="interviews-list" class="" style="display: flex; justify-content: space-evenly; flex-wrap: wrap">
        <?php foreach($interviews as $interview){
        ?>
<div class="container-outer mb-3">
        <div class="interview-card">
            <div class="iv-container">
                <img src="<?= $interview['image_url']?>" alt="image" class="interview-image" />
                <div class="interview-content">
                    <div class="person-info">
                        <h3 class="person-name"><?= $interview['guest_name']?></h3>
                        <p class="person-title"><?= $interview['guest_title']?></p>
                        <div class="interview-meta">
                            <span class="date">
                                <i class="fas fa-calendar"></i>
                                <?= $interview['interview_date']?>
                            </span>
                            <span class="read-time d-none">
                                <i class="fas fa-clock"></i>
                                <?= $interview['duration']?>
                            </span>
                        </div>
                    </div>
                    <div class="interview-description"><?= $interview['description']?></div>
                    <a class="read-more" href="<?= BASE_URL?>/interviews/id/<?= $interview['id']?>">
                        read more
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                    <div class="interview-actions">
                        <a href="<?= BASE_URL?>/interviews/id/<?= $interview['id']?>" class="watch-btn">
                            <i class="fas fa-play"></i>
                        </a>
                        <button class="share-btn">
                            <i class="fas fa-share-alt media-ico" data-itemname="interview" data-itemid="<?= $interview['id']?>"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
</div>
        <?php
        }?>
    </div>
    <a href="<?= BASE_URL?>/interviews" style="text-decoration: none;">
        <div class="more-actions">
            <span>Wè Plis</span>
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </a>
</div>