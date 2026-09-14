<?php
/**
 * header slides
 */
?>

<div id="head" class="section container-outer pad-20 white-bg bdr-box">
    <div id="news-slides" data-itempage="actuality">
<?php
$maxNewsSlides = 10;
$i = 0;
$slides = array_filter($news, fn($item) => $item['position'] === 'mpnews_slide');
$slides = array_slice($slides, 0, $maxNewsSlides);
if (!$slides || empty($slides)){
?>
    <div>
        <h1>News Coming Soon</h1>
        <p>Оставайтесь на связи на спбВКурсе</p>
    </div>
<?php
} else {
foreach($slides as $article){
    require "components/MainArticleItem.php";
}
?>
    </div>
    <div class="scroll-btn right">
        <ion-icon name='arrow-forward-outline' class='icon'></ion-icon>
    </div>
    <div class="scroll-btn left">
        <ion-icon name='arrow-back-outline' class='icon'></ion-icon>
    </div>
    <a href="/?p=actuality&id=<?= array_slice($slides, 0, 1, false)[0]['id'] ?>" id="slide-link" class="link no-dec slide-link">
        <div class="more-actions beep-anim">
            <span>Wè Plis</span>
            <i className="fa-solid fa-chevron-right"></i>
        </div>
    </a>
</div>
<?php }?>
<?php
/**
 * fading news
 */
$maxFadeNews = 20;
$articles = array_filter($news, fn($item) => $item['position'] === 'mpnews_fade');
$articles = array_slice($articles, 0, $maxFadeNews);
?>
<div id="last-news" class="section shelf-anim margin-rl-1 container-outer pad-20 white-bg bdr-box">
    <div class="section-info">
      <a href="/?p=actuality">
        <h1>DÈNYE NOUVÈL</h1>
      </a>
    </div>
    <div class="container-inner full-w" style="padding-top: 0px;padding-left:0px;padding-right:0px">
    	<div id="fade-articles-list" class="articles white-bg bdr-box">
<?php
if (!$articles || empty($articles)){
?>
    <div>
        <h1>News Coming Soon</h1>
        <p>Оставайтесь на связи на спбВКурсе</p>
    </div>
<?php
} else {
foreach($articles as $article){
    require "components/MainFadeArticleItem.php";
}?>
            <div id="shareMenu" class="share-menu">
                <a id="shareVK" class="share-item">ВКонтакте</a>
                <a id="shareTG" class="share-item">Telegram</a>
                <a id="shareWA" class="share-item">WhatsApp</a>
            </div>
        </div>
        <a id='fade-news-link' href="/?p=actuality&id=<?= array_slice($articles, 0, 1, false)[0]['id'] ?>" class='fade-link' style="padding-left: 15px">
            <div class="more-actions beep-anim">
                <span>Wè Plis</span>
                <i className="fa-solid fa-chevron-right"></i>
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
<div class="section intrvw-cont shelf-anim margin-rl-1 container-outer pad-20 white-bg bdr-box">
    <div class="section-info">
      <a>
      	<h1>ENTÈVYOU</h1>
      </a>
    </div>
    <div class="container-inner full-wh" style="padding-top: 0px;padding-left:0px;padding-right:0px">
    	<div id="intrvws-list" data-itempage="interviews" class="intrvws no-ovrflw white-bg flx-disp row bdr-box">
<?php
if (!$interviews || empty($interviews)){?>
    <div>
        <h1>News Coming Soon</h1>
        <p>Оставайтесь на связи на спбВКурсе</p>
    </div>
<?php
} else {
foreach($interviews as $interview){
    require "components/InterviewItem.php";
}?>
        </div>
        <div id="shareMenu" class="share-menu">
            <a id="shareVK" class="share-item">ВКонтакте</a>
        	<a id="shareTG" class="share-item">Telegram</a>
            <a id="shareWA" class="share-item">WhatsApp</a>
        </div>
        <a href="/?p=interviews&id=<?= $interviews[0]['id'] ?>" style="padding-left: 15px" class="slide-link">
          <div class="more-actions beep-anim">
            <span>Wè Plis</span>
            <i className="fa-solid fa-chevron-right"></i>
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
<div id="events-section" class="section shelf-anim margin-rl-1 colDir container-outer pad-20 white-bg bdr-box">
    <div id="events-section-bg" class="full-h"></div>
    <div class="section-info">
        <a href="/?p=events"><h1>EVENEMAN</h1></a>
    </div>
    <div id="events-list" data-itempage="events" class="container-inner">
<?php
if (!$events || empty($events)){
?>
    <div>
        <h1>News Coming Soon</h1>
        <p>Оставайтесь на связи на спбВКурсе</p>
    </div>
<?php
} else {
foreach($events as $event){
    require "components/EventItem.php";
}
?>
    </div>
    <a href="/?p=events" class='fade-link'>
        <div class="more-actions beep-anim">
            <span>Wè Plis</span>
            <i className="fa-solid fa-chevron-right"></i>
        </div>
    </a>
</div>
<?php }?>
<?php
/**
 * music section
 */
$maxMusicContent = 5;
$tracks = array_filter($music, fn($item) => $item['position'] === 'mainpage');
$tracks = array_slice($tracks, 0, $maxMusicContent);
?>
<div id="middle-panel" class="section shelf-anim margin-rl-1 colDir container-outer pad-20 white-bg bdr-box">
    <div class="section-info">
        <a href="/?p=music">
        <h1>
            PLEYLIS
        </h1>
        </a>
    </div>
    <div id="music-charts-video" class="flxDisp colDir container-inner">
    
    <div class="media-row-content charts">
        <!-- this space should be automatically filled in JS -->
        <div class="section-info charts-title">
            <h2>TOP CHARTS</h2>
        </div>
        <div class="audio-charts flxDisp">
<?php
if (!$tracks || empty($tracks)){
?>
    <div>
        <h1>News Coming Soon</h1>
        <p>Оставайтесь на связи на спбВКурсе</p>
    </div>
<?php
} else {
foreach($tracks as $track){
    require "components/MainTrackItem.php";
}
?>
            </div>
            <a href="/?p=music">
                <div class="more-actions beep-anim">
                    <span>Wè Plis</span>
                    <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>
            </a>
        </div>
    </div>
    <audio id="audio-player"></audio>
</div>
<?php }?>
<?php
/**
 * informative video
 */

require "components/KonektemVideoItem.php";
/**
 * services panel. decided to exclude
 */
$addServicesToMainPage = false;
if($addServicesToMainPage){
?>
<div id="services-panel" class="section shelf-anim margin-rl-1 container-outer pad-20 white-bg bdr-box">
    <div class="section-info">
        <!-- /?p=services -->
        <a href=""><h1>SEVIC</h1></a>
    </div>
    <!-- preparing containers for pasting -->
    <div class='services-container container-inner'>
        <div id="services-list" data-itempage="services" class="no-ovrflw flx-disp row pad-5">
<?php
if (!$services || empty($services)){
?>
    <div>
        <h1>News Coming Soon</h1>
        <p>Оставайтесь на связи на спбВКурсе</p>
    </div>
<?php
} else {
foreach($services as $service){
    require "components/MainServiceItem.php";
}?>
        </div>
        <button class='svc-scroll-btn left' type='button'>
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button class='svc-scroll-btn right' type='button'>
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>
    <a href="/?p=services&id=<?= $services[0]['id'] ?>" class="slide-link">
        <div class="more-actions beep-anim">
            <span>Wè Plis</span>
            <ion-icon name="arrow-forward-outline"></ion-icon>
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
<div id="services-panel" class="section shelf-anim margin-rl-1 container-outer pad-20 white-bg bdr-box">
    <div class="section-info">
    <!-- /?p=services -->
    <a href=""><h1>LIV</h1></a>
    </div>
    <!-- preparing containers for pasting -->
    <div class='books-container container-inner'>
        <div id="books-list" data-itempage="books" class="no-ovrflw  full-w flx-disp row">
<?php
foreach($books as $book){
    require "components/MainBookItem.php";
} }
?>
        </div>
        <button class='scroll-btn left' type='button'>
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button class='scroll-btn right' type='button'>
            <i class="fa-solid fa-chevron-right"></i>
        </button>
        <a href="/?p=books&id=<?= $books[0]['id'] ?>" class="slide-link">
            <div class="more-actions beep-anim">
            <span>Wè Plis</span>
            <i className="fa-solid fa-chevron-right"></i>
            </div>
        </a>
    </div>
</div>

<?php
/**
 * partners panel
 */
?>
<div id="partners-panel" class="section shelf-anim margin-rl-1 container-outer pad-20 white-bg bdr-box">
    <div class="section-info">
        <h1>PATNÈ NOU YO</h1>
    </div>
    <div id="partners-list" class="partners">
<?php
if (!$partners || empty($partners)){
?>
    <div>
        <h1>News Coming Soon</h1>
        <p>Оставайтесь на связи на спбВКурсе</p>
    </div>
<?php
} else {
foreach($partners as $partner){
?>
    <div class='partner-img-cont <?= $i < 2 && 'small' ?>'>
        <img src="<?= $partner['image_location'] ?>" <?= $i == 1 && "style='width: 63%; height: 63%'" ?>/>
    </div>
<?php
}
?>
    </div>
</div>

<div class="bottom-slide container-outer" style="padding: 0px">
<?php
/**
 * bottom slides
 */
$imgUrls = [
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
foreach($imgUrls as $slide){
?>
    <div class="slide-container">
        <img type="<?= $slide['mime_type'] ?>" src="<?= $slide['src'] ?>"/>
    </div>
<?php
}
?>
</div>
<?php }?>
<?php
/**
 * cookie form, though it shouldn't reside in the static file, but dynamicall added
 */
?>
<div class='cookie-form-contnent'>
    <div class='cookie-msg'>
        Nou pran angajman pou nou respekte vi prive w avèk pèmisyon w, nou itilize COOKIES ak lòt trasè ankò nan lide pou kontwole odyans nou yo, pataje sou rezo sosyal yo, pèsonalize kontni yo ak piblisite pèsonalize sou sèvis nou yo.
        <a class='wht-clr' href='https://www.konektem.net/privacyandlegalinfo'>Politique de confidentialité</a>
    </div>
    <div class='btns'>
        <button type='button' class='wht-bg' id='cookie-allow'>Accept</button>
        <button type='button' class='transp' id='cookie-deny'>Reject</button>
    </div>
</div>

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

require "components/WhatsappWidget.php";
?>

<!-- END OF DOM -->
<!-- SCRIPTS -->
<?php
require_once __DIR__ . '/footer.php';
?>