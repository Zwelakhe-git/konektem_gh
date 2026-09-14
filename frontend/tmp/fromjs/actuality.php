<?php
// news data
?>

<div class='container' id='current-news-root'></div>
    <div class="container all-N">
    <div class="breaking-news">
        <h2><i class="fas fa-bolt"></i> BREAKING NEWS</h2>
        <a href='#'><p class='wht-clr'>TOP STORY</p></a>
    </div>

    <div class="section-nav">
        <div class="container">
            <ul>
                <li><a href="#politics" class="active">Politik</a></li>
                <li><a href="#business">Biznis</a></li>
                <li><a href="#technology">Teknoloji</a></li>
                <li><a href="#sports">Spo</a></li>
                <li><a href="#entertainment">Amizman</a></li>
                <li><a href="#health">Sante</a></li>
                <li><a href="#science">Syans</a></li>
                <li><a href="#security">Sekirite</a></li>
                <li><a href="#society">Sosyete</a></li>
                <li><a href="#diplomatie">Diplomasi</a></li>
                <li><a href="#international">Entènasyonal</a></li>
                <li><a href="#economy">Ekonomi</a></li>
                <li><a href="#family">Fanmi</a></li>
                <li><a href="#culture">Kilti</a></li>
                <li><a href="#Music & Video">Mizik & Videyo</a></li>
                <li><a href="#cinema">Sinema</a></li>
                <li><a href="#mode">Mòd & Estil</a></li>
                <li><a href="#personality">Pèsonalite</a></li>
                <li><a href="#religion">Relijyon</a></li>
                <li><a href="#kitchen">Kizin & Resèt</a></li>
                <li><a href="#trip">Vwayaj</a></li>
                <li><a href="#education">Edikasyon</a></li>
            </ul>
        </div>
    </div>
    <?php
        $newsCategories = [
            [
                'name' => 'technology',
                'id' => 'technology',
                'text' => 'technology'
            ],
            [
                'name' => 'business',
                'id' => 'business',
                'text' => 'business'
            ],
            [
                'name' => 'politics',
                'id' => 'politics',
                'text' => 'politics'
            ],
            [
                'name' => 'entertainment',
                'id' => 'entertainment',
                'text' => 'entertainment'
            ],
            [
                'name' => 'sports',
                'id' => 'sports',
                'text' => 'sports'
            ],
            [
                'name' => 'health',
                'id' => 'health',
                'text' => 'health'
            ],
            [
                'name' => 'science',
                'id' => 'science',
                'text' => 'science'
            ]
        ];

        foreach($newsCategories as $cat){
            $targetArticles = array_filter($news, function($article){
                return $article['newsCategory'] === $cat['name'];
            });
            
    ?>
    <section id="<?= $cat['id']?>" class="news-section">
        <div class="section-header">
            <h2 class="section-title"><?= $cat['text']?></h2>
            <a href="#" class="view-all">View All</a>
        </div>
        <div class="news-grid">
            <?php
                foreach($targetArticles as $article){
            ?>
            <div class="news-card" id="${newsData[i].id}">
                <div class="news-img">
                    <img src="${newsData[i].image_location}" alt="Politics news">
                </div>
                <div class="news-content">
                    <div class="news-date">${newsData[i].newsDate}</div>
                    <h3 class="news-title" data-newsid=${newsData[i].id}>${newsData[i].newsTitle}</h3>
                    <p class="news-excerpt">${newsData[i].newsHeadline}</p>
                    <div class="full-content">
                        ${newsData[i].fullContent}
                    </div>
                    <a class="read-more" data-state="more">Read More <i class="fas fa-chevron-down"></i></a>
                </div>
            </div>
            <?php }?>
        </div>
    </section>
    <?php }?>
</div>
<script>
    // Smooth scrolling for section navigation
    document.querySelectorAll('.section-nav a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            window.scrollTo({
                top: targetSection.offsetTop - 70,
                behavior: 'smooth'
            });
            
            // Update active class
            document.querySelectorAll('.section-nav a').forEach(a => a.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Update active nav link based on scroll position
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('.news-section');
        const navLinks = document.querySelectorAll('.section-nav a');
        
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (pageYOffset >= (sectionTop - 100)) {
                currentSection = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + currentSection) {
                link.classList.add('active');
            }
        });
    });
    
    // Read More functionality
    document.querySelectorAll('.read-more').forEach(button => {
        button.addEventListener('click', function() {
            const fullContent = this.previousElementSibling;
            const excerpt = fullContent.previousElementSibling;
            
            if (this.getAttribute('data-state') === 'more') {
                // Expand content
                fullContent.classList.add('expanded');
                excerpt.classList.add('expanded');
                this.innerHTML = 'Read Less <i class="fas fa-chevron-up"></i>';
                this.setAttribute('data-state', 'less');
            } else {
                // Collapse content
                fullContent.classList.remove('expanded');
                excerpt.classList.remove('expanded');
                this.innerHTML = 'Read More <i class="fas fa-chevron-down"></i>';
                this.setAttribute('data-state', 'more');
            }
        });
    });
</script>
<!-- one signal initialization -->
<script src="/konektem/static/js/onesignal-init.js"></script>
    