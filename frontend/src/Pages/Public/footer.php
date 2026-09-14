<?php
/*$settings = ['footer' => [],'social' => [],'contact' => []];
foreach($settings as $setting => $pairs){
    $group = array_filter($sitesettings, fn($st) => $st['setting_group'] === $setting);
    foreach($group as $row){
        $settings[$setting][$row['setting_key']] = $row['setting_value'];
    }
}*/
require_once __DIR__ . '/../../Utils/ajax.php';
$response = fetch('/api/v1/site-settings/all');
if($response){
    $response = json_decode($response, true);
    $siteSettings = $response['data']['settings'];
}
?>

<footer id="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?= $siteSettings['footer']['site_title'] ?></h3>
                <p><?= $siteSettings['footer']['site_description'] ?></p>
            </div>
            <div class="footer-section">
                <h3>Kategori</h3>
                <ul>
                    <li><a href="/?p=actuality">Politik</a></li>
                    <li><a href="/?p=actuality">Biznis</a></li>
                    <li><a href="/?p=actuality">Teknoloji</a></li>
                    <li><a href="/?p=actuality">Spo</a></li>
                    <li><a href="/?p=actuality">Divetisman</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Konekte m</h3>
                <div class="social-icons">
                    <a href="<?= $siteSettings['social']['facebook'] ?>"><i class="fab fa-facebook"></i></a>
                    <a href="<?= $siteSettings['social']['twitter'] ?>"></a>
                    <a href="<?= $siteSettings['social']['instagram'] ?>"><i class="fab fa-instagram"></i></a>
                </div>
                <div class="contacts&location" style="margin-top: 10px;">
                    <ul>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span><?= $siteSettings['contact']['phone'] ?></span>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>14, Delmas 79, Village Daniel Roy., Delmas,HT6120 Haiti</span>
                        </li>
                        <li>
                            <i class="fas fa-globe"></i>
                            <a href="/">https://konektem.net</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-section">
                <p><?= $siteSettings['footer']['subscription_invitation'] ?></p>
                <form method="POST" action="/php/dbReader.php?q=emailsub&content=news">
                    <input type="email" placeholder="konektemtv@gmail.com" style="padding:10px; width:100%; margin-top:10px; border-radius:4px; border:none;">
                    <button type="submit" style="background:#ffcc00; color:#1a4b8c; border:none; padding:10px 15px; margin-top:10px; border-radius:4px; font-weight:bold; cursor:pointer;">Abone</button>
                </form>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; <?= $siteSettings['footer']['copyright'] ?>.</p>
        </div>
    </div>
</footer>