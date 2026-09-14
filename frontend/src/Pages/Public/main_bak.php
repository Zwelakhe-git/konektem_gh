<?php
/**
 * header slides
 */
?>

<div id="head" class="section container-outer pad-20 white-bg bdr-box">
    <div id="news-slides" class="d-flex flex-row overflow-hidden" data-itempage="actuality">
<?php
$maxNewsSlides = 10;
$i = 0;
$slides = array_values(array_filter($news, fn($item) => $item['position'] === 'mpnews_slide'));
$slides = array_slice($slides, 0, $maxNewsSlides);
if (!$slides || empty($slides)){
?>
        <div>
            <h1>News not available</h1>
            <p></p>
        </div>
    </div>
</div>
<?php
} else {
foreach($slides as $article){
    require __DIR__ . "/../../Components/MainArticleItem.php";
}
?>
    </div>
    <div class="scroll-btn right">
        <ion-icon name='arrow-forward-outline' class='icon'></ion-icon>
    </div>
    <div class="scroll-btn left">
        <ion-icon name='arrow-back-outline' class='icon'></ion-icon>
    </div>
    <a href="<?= BASE_URL?>/actuality/<?= $slides[0]['title_hash'] ?>" id="slide-link" class="slide-link d-md-none">
        <div class="ps-3 pt-3 beep-anim">
            <span>Wè Plis</span>
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </a>
</div>
<?php }?>
<div id="main" class="d-md-grid m-md-auto py-md-3 px-md-3 white-bg">
    <?php
    /**
     * fading news
     */
    $maxFadeNews = 20;
    $articles = array_values(array_filter($news, fn($item) => $item['position'] === 'mpnews_fade'));
    $articles = array_slice($articles, 0, $maxFadeNews);
    ?>
    <div id="newsFadeSection" class="section shelf-anim container-outer mt-md-0 mb-sm-max-3 pad-20 white-bg bdr-box">
        <div class="section-info">
        <a href="<?= BASE_URL?>/actuality">
            <h1>DÈNYE NOUVÈL</h1>
        </a>
        </div>
        <div class="container-inner w-100 px-0 pt-0">
            <div id="fade-articles-list" class="articles white-bg bdr-box">
    <?php
    if (!$articles || empty($articles)){
    ?>
                <div>
                    <h1>News not available</h1>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <?php
    } else {
    foreach($articles as $idx => $article){
        require __DIR__ . "/../../Components/MainFadeArticleItem.php";
    }?>
                <div id="shareMenu" class="share-menu">
                    <a id="shareVK" class="share-item">ВКонтакте</a>
                    <a id="shareTG" class="share-item">Telegram</a>
                    <a id="shareWA" class="share-item">WhatsApp</a>
                </div>
            </div>
            <a class="fade-link" href="<?= BASE_URL?>/actuality/<?= $articles[0]['title_hash'] ?>">
                <div class="beep-anim ps-3 pt-3">
                    <span>Wè Plis</span>
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
        </div>
    </div>
    <?php }?>
    <?php
    /**
     * interviews section
     */
    $maxInterviews = 20;
    ?>
    <div id="interviewsSection" class="section intrvw-cont shelf-anim container-outer mt-md-0 mb-sm-max-3 pad-20 white-bg bdr-box">
        <div class="section-info">
        <a href="<?= BASE_URL?>/interviews">
            <h1>ENTÈVYOU</h1>
        </a>
        </div>
        <div class="container-inner px-0 pt-0">
            <div id="intrvws-list" data-itempage="interviews" class="intrvws overflow-hidden white-bg flx-disp bdr-box">
    <?php
    if (!$interviews || empty($interviews)){?>
                <div>
                    <h1>Interviews not available</h1>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <?php
    } else {
    foreach($interviews as $interview){
        require __DIR__ . "/../../Components/InterviewItem.php";
    }?>
            </div>
            <div id="shareMenu" class="share-menu">
                <a id="shareVK" class="share-item">ВКонтакте</a>
                <a id="shareTG" class="share-item">Telegram</a>
                <a id="shareWA" class="share-item">WhatsApp</a>
            </div>
            <a data-linkpre="/konektem/interviews/" href="<?= BASE_URL ?>/interviews/id/<?= $interviews[0]['id'] ?>" class="slide-link">
            <div class="beep-anim ps-3 pt-3">
                <span>Wè Plis</span>
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            </a>
        </div>
    </div>
    <?php }?>
    <?php
    /**
     * events section
     */
    ?>
    <div id="events-section" class="section shelf-anim mt-md-0 mb-sm-max-3 colDir container-outer pad-20 white-bg bdr-box">
        <div id="events-section-bg" class="full-h"></div>
        <div class="section-info">
            <a href="<?= BASE_URL?>/events"><h1>EVENEMAN</h1></a>
        </div>
        <div id="events-list" data-itempage="events" class="container-inner">
    <?php
    if (!$events || empty($events)){
    ?>
            <div>
                <h1>Events not available</h1>
                <p></p>
            </div>
        </div>
    </div>
    <?php
    } else {
    foreach($events as $idx => $event){
        require __DIR__ . "/../../Components/EventItem.php";
    }
    ?>
        </div>
        <a href="<?= BASE_URL?>/events" class="fade-link">
            <div class="beep-anim ps-3 pt-3">
                <span>Wè Plis</span>
                <i class="fa-solid fa-chevron-right"></i>
            </div>
        </a>
    </div>
    <script>
        function base64UrlDecode(str){
            let base64 = str.replace('/-/g', '+').replace('/_/g', '/');
            while (base64.length % 4){
                base64 += '=';
            }
            return atob(base64);
        }
        let buyBtns = document.querySelectorAll('.event-card .buy-btn');
        buyBtns.forEach(btn => {
            btn.addEventListener('click', async(e)=>{
                try {
                    console.log('target', e.target.closest('.event-card'));
                    body = {
                            id: e.target.closest('.event-card').dataset.itemid,
                            type: 'event'
                        };
                        console.log(body);
                    const response = await fetch('/store/api/create-order-token', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(body)
                    });
                    const result = await response.json();
                    if(!response.ok || !result.success){
                        showError(result.message ?? 'Server error');
                        return;
                    }
                    //showSuccess(base64UrlDecode(result.token.split('.')[1]));
                    if(!result.token){
                        showError('Failed to create order token');
                        return;
                    }
                    window.location.href = `/store/counter?token=${result.token}`;

                } catch(err){
                    console.error(err);
                    showError(err);
                }
            })
        })
    </script>
    <?php }?>
    <?php
    /**
     * music section
     */
    $maxMusicContent = 5;
    $tracks = array_filter($music, fn($item) => $item['position'] === 'mainpage');
    $tracks = array_slice($tracks, 0, $maxMusicContent);
    ?>
    <div id="musicSection" class="section w-100 shelf-anim mt-md-0 mb-sm-max-3 colDir container-outer pad-20 white-bg bdr-box">
        <div class="section-info">
            <a href="<?= BASE_URL?>/music">
            <h1>
                PLEYLIS
            </h1>
            </a>
        </div>
        <div id="music-charts-video" class="d-flex flex-column m-md-0 p-md-2 container-inner">
        
            <div class="media-row-content charts">
                <div class="audio-charts d-flex">
    <?php
    if (!$tracks || empty($tracks)){
    ?>
                <div>
                    <h1>Music not available</h1>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <?php
    } else {
    foreach($tracks as $track){
        require __DIR__ . "/../../Components/MainTrackItem.php";
    }
    ?>
                </div>
                <a href="<?= BASE_URL?>/music">
                    <div class="beep-anim ps-3 pt-3">
                        <span>Wè Plis</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>
        <audio id="audioPlayer"></audio>
    </div>
    <?php }
    /**
     * services panel. decided to exclude
     */
    $addServicesToMainPage = false;
    if($addServicesToMainPage){
    ?>
    <div id="services-panel" class="section shelf-anim mt-md-0 mb-sm-max-3 container-outer pad-20 white-bg bdr-box">
        <div class="section-info">
            <!-- /?p=services -->
            <a href="<?= BASE_URL?>/services"><h1>SEVIC</h1></a>
        </div>
        <!-- preparing containers for pasting -->
        <div class='services-container container-inner'>
            <div id="services-list" data-itempage="services" class="no-ovrflw flx-disp row pad-5">
    <?php
    if (!$services || empty($services)){
    ?>
                <div>
                    <h1>Services not available</h1>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <?php
    } else {
    foreach($services as $service){
        require __DIR__ . "/../../Components/MainServiceItem.php";
    }?>
            </div>
            <button class='svc-scroll-btn left' type='button'>
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class='svc-scroll-btn right' type='button'>
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
        <a href="<?= BASE_URL?>/services?id=<?= $services[0]['id'] ?>" class="slide-link">
            <div class="beep-anim ps-3 pt-3">
                <span>Wè Plis</span>
                <i class="fa-solid fa-chevron-right"></i>
            </div>
        </a>
    </div>
    <?php
    }
    }
    /**
     * books section
     */
    if (!$books || empty($books)){
    ?>
        <div>
            <h1>News Coming Soon</h1>
            <p>Оставайтесь на связи на спбВКурсе</p>
        </div>
    <?php
    } else {
    ?>
    <div id="books-panel" class="section shelf-anim container-outer mt-md-0 mb-sm-max-3 pad-20 white-bg bdr-box">
        <div class="section-info">
        <a href="<?= BASE_URL?>/books"><h1>LIV</h1></a>
        </div>
        <!-- preparing containers for pasting -->
        <div class="books-container container-inner pb-md-0 pt-md-3 px-md-3">
            <div id="books-list" class="overflow-hidden w-100 d-flex flex-row">
    <?php
    foreach($books as $book){
        require __DIR__ . "/../../Components/MainBookItem.php";
    } }
    ?>
            </div>
            <button class='scroll-btn left' type='button'>
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class='scroll-btn right' type='button'>
                <i class="fa-solid fa-chevron-right"></i>
            </button>
            <a href="<?= BASE_URL?>/books?id=<?= $books[0]['id'] ?>" class="slide-link">
                <div class="beep-anim ps-3 pt-3">
                    <span>Wè Plis</span>
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="container-outer section margin-rl-1 white-bg p-md-3">
    <div class="section-info p-md-3">
        <h1>
            VIDEO
        </h1>
    </div>
<?php
/**
 * informative video
 */

require __DIR__ . "/../../Components/KonektemVideoItem.php";
?>
</div>
<?php
/**
 * partners panel
 */
?>

<?php
if ($partners && !empty($partners)){?>
<div id="partners-panel" class="section shelf-anim container-outer px-md-3 pad-20 white-bg bdr-box">
    <div class="section-info p-md-3 border-0">
        <h1>PATNÈ NOU YO</h1>
    </div>
    <div id="partners-list" class="partners">
        <?php
        foreach($partners as $partner){?>
        <div class='partner-img-cont <?= $i < 2 && 'small' ?>'>
            <img src="<?= $partner['image_url'] ?>" <?= $i == 1 && "style='width: 63%; height: 63%'" ?>/>
        </div>
        <?php }?>
    </div>
</div>
<?php }?>


<div class="bottom-slide container-outer mq-wrap" style="padding: 0px">
    <div class="mq-track full-w">
<?php
/**
 * bottom slides
 */
$slides = [
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_4657.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_4658.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_4659.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_4656.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_4660.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_4661.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_4662.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_4664.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_4665.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_5227.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_5244.JPG.jpg"
    ],
    [
    "mime_type" => "image/jpeg",
    "src" => "/media/images/bottom/IMG_5245.JPG.jpg"
    ]
];
foreach([...$slides, ...$slides] as $slide){
?>
        <div class="slide-container">
            <img type="<?= $slide['mime_type'] ?>" src="<?= $slide['src'] ?>"/>
        </div>
<?php }?>
    </div>
</div>
<?php
/**
 * cookie form, though it shouldn't reside in the static file, but dynamicall added
 */
?>
<script>
    if(sessionStorage.getItem('cookies-allowed') && JSON.parse(sessionStorage.getItem('cookies-allowed'))){
        <?php //require_once __DIR__ . '/../../Components/CookieConsentForm.php'; ?>
    }
</script>
<?php
/**
 * whatsapp widget
 */

$whatsappData = [
    "phoneNumber" => "7282143510",
    "welcomeMessage" => "Hello! I'm interested in your services.",
    "countryCodes" => [
        [ "code"  => "+1", "country" => "US" ],
        [ "code" => "+44", "country" => "UK" ],
        [ "code" => "+91", "country" => "IN" ],
        [ "code" => "+33", "country" => "FR" ],
        [ "code" => "+49", "country" => "DE" ],
        [ "code" => "+7",  "country" => "RU" ]
        // Add more country codes as needed
    ]
];

require __DIR__ . "/../../Components/WhatsappWidget.php";
?>

<!-- END OF DOM -->
<!-- SCRIPTS -->
<?php
require_once __DIR__ . '/footer.php';
?>