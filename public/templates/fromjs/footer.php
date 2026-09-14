        </div>
        <div id="nav-panel-bottom" class="grey-clr no-ovrflw no-margin">
            <div class="nav" class="no-ovrflw">
                <a tabindex="0" class="bdr-10 nav-link" href="/">
                    <i class="fa-solid fa-house"></i>
                    <span>Akèy</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link" href="/?p=actuality">
                    <i class="fa-regular fa-calendar"></i>
                <span>Aktyalite</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link" href="/?p=interviews">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Entèvyou</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link" href="/?p=music">
                    <i class="fa-solid fa-music"></i>
                    <span>Mizik</span>
                </a>
                <a tabindex="0" class="bdr-10 nav-link" href="/?p=books">
                    <i class="fa-brands fa-readme"></i>
                    <span>Bibliyotèk</span>
                </a>
                <a tabindex="0" class="profile bdr-10 nav-link" href="/account/me/index.php">
                    <i class="fa-solid fa-circle-user prof-icon"></i>
                    <span></span>
                </a>
            </div>
        </div>
        <footer>
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>${settings.footer.site_title}</h3>
                        <p>${settings.footer.site_description}</p>
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
                            <a href="${settings.social.facebook}"><i class="fab fa-facebook"></i></a>
                            <a href="${settings.social.twitter}"></i></a>
                            <a href="${settings.social.instagram}"><i class="fab fa-instagram"></i></a>
                        </div>
                        <div class="contacts&location" style="margin-top: 10px;">
                            <ul>
                                <li>
                                    <i class="fas fa-phone"></i>
                                    <span>${settings.contact.phone}</span>
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
                        <p>${settings.footer.subscription_invitation}</p>
                        <form method="POST" action="/php/dbReader.php?q=emailsub&content=news">
                            <input type="email" placeholder="konektemtv@gmail.com" style="padding:10px; width:100%; margin-top:10px; border-radius:4px; border:none;">
                            <button type="submit" style="background:#ffcc00; color:#1a4b8c; border:none; padding:10px 15px; margin-top:10px; border-radius:4px; font-weight:bold; cursor:pointer;">Abone</button>
                        </form>
                    </div>
                </div>
                <div class="copyright">
                    <p>&copy; ${settings.footer.copyright}.</p>
                </div>
            </div>
        </footer>
    </body>
</html>