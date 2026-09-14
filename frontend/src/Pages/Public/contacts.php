<?php
$settings = [
    [
        'type' => "address",
        'icon' => "fas fa-map-marker-alt",
        'title' => "Adrès:",
        'content' => "14, Delmas 79, Village Daniel Roy., Delmas,HT6120<br>Haiti",
        'link' => "#"
    ],
    [
        'type' => "telefòn",
        'icon' => "fas fa-phone",
        'title' => "telefòn",
        'content' => "+1 (728)2143510",
        'link' => "tel:+1 7282143510"
    ],
    [
        'type' => "imèl",
        'icon' => "fas fa-envelope",
        'title' => "imèl",
        'content' => "konektemtv@gmail.com",
        'link' => "mailto:konektemtv@gmail.com"
    ],
    [
        'type' => "website",
        'icon' => "fas fa-globe",
        'title' => "sit nou",
        'content' => "https://konektem.net",
        'link' => "https://Konektem.net"
    ]
];
?>
<div id="contact-section-container">
    <div id="contact-section" class="margin-rl-1 colDir container-outer pad-20 white-bg bdr-box">
        <div id="contact-section-bg" class="full-h"></div>
        <div class="section-info">
            <h1><?= strtoupper('Kontak')?></h1>
            <p>Kontakte nou nan youn nan fòm sa yo</p>
        </div>
        <div id="contact-cards" class="container-inner">
            <?php foreach($settings as $setting){?>
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="<?= $setting['icon']?>"></i>
                </div>
                <h3><?= $setting['title']?></h3>
                <p><?= $setting['content']?></p>
            </div>
            <?php }?>
        </div>
        <div class="map-container">
            <div class="map-placeholder">
               Interaktif Map - Lokalizasyon nou
            </div>
        </div>
        <a href="<?= BASE_URL?>">
            <div class="more-actions">
                <span>Back to Home</span>
                <ion-icon name="arrow-back-outline"></ion-icon>
            </div>
        </a>
    </div>
</div>