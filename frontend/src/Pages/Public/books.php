<header>
    <h1>📚 Bibliyotèk Liv</h1>
    <p>Navige, li, ak telechaje koleksyon liv nou an nan fòma PDF. Prop, vit, epi fasil.</p>
</header>

<div class="container">
    <div class="search-bar-books">
        <div class="search-wrapper">
            <i class="fa-solid fa-search search-icon"></i>
            <input type="text" id="searchBooks" placeholder="Search books by title, author, or genre…" />
            <button class="search-clear-btn" id="clearSearch" style="display: none;">
                <i class="fa-solid fa-times-circle"></i>
            </button>
        </div>
    </div>

    <?php if(empty($books)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-book-open" style="font-size: 3rem; color: var(--muted);"></i>
            <h3 style="color: var(--muted); margin-top: 1rem;">No books available at the moment.</h3>
            <p style="color: var(--muted);">Check back later for new additions to our library.</p>
        </div>
    <?php else:
        $groupedBooks = array_reduce($books, function($group, $book){
            if(!array_key_exists($book['genre'], $group)){
                $group[$book['genre']] = [];
            }
            $group[$book['genre']][] = $book;
            return $group;
        }, []);
    ?>
        <div id="booksContainer">
            <?php foreach($groupedBooks as $groupName => $books): ?>
                <div class="section accordion-item closed" data-genre="<?= htmlspecialchars($groupName) ?>">
                    <div class="genre-header" data-genre="<?= htmlspecialchars($groupName) ?>">
                        <h3><?= htmlspecialchars($groupName) ?></h3>
                        <span class="book-count"><?= count($books) ?> books</span>
                    </div>
                    <section class="books-grid" data-genre="<?= htmlspecialchars($groupName) ?>">
                        <?php foreach($books as $book): ?>
                            <article class="book-card" data-id="<?= $book['id'] ?>">
                                <div class="book-cover">
                                    <img 
                                        class="full-wh" 
                                        style="border-radius: 10px; object-fit: cover;" 
                                        src="<?= htmlspecialchars($book['image_url'] ?? '/konektem/static/images/default-book.jpg') ?>" 
                                        alt="<?= htmlspecialchars($book['title']) ?>" 
                                        loading="lazy"
                                        onerror="this.src='/konektem/static/images/default-book.jpg'"
                                    />
                                    <?php if(!$book['image_url']): ?>
                                        <div class="book-cover-placeholder">
                                            <i class="fa-solid fa-book"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="book-info">
                                    <h3 class="book-title ellipsis" title="<?= htmlspecialchars($book['title']) ?>">
                                        <?= htmlspecialchars($book['title']) ?>
                                    </h3>
                                    <div class="book-author ellipsis">
                                        <i class="fa-solid fa-user-pen" style="font-size: 0.7rem;"></i>
                                        <?= htmlspecialchars($book['author']) ?>
                                    </div>
                                    <div class="book-description ellipsis" title="<?= htmlspecialchars($book['description'] ?? '') ?>">
                                        <?= htmlspecialchars($book['description'] ?? 'No description available') ?>
                                    </div>
                                </div>
                                <div class="book-actions">
                                    <button class="btn btn-view" data-itemid="<?= $book['id'] ?>">
                                        <i class="fa-regular fa-eye"></i> View
                                    </button>
                                    <button class="btn download-btn">
                                        <i class="fa-solid fa-download media-ico" data-itemid="<?= $book['id'] ?>" data-itemname="book"></i>
                                        <span class="hidden"></span>
                                    </button>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </section>
                    <div class="accordion-item-controller">
                        <span></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>