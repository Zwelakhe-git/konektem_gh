document.addEventListener('DOMContentLoaded', function() {
    // Load saved theme
    const savedTheme = localStorage.getItem('books-theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    // // Add theme toggle button if it doesn't exist
    // if (!document.querySelector('.theme-toggle-books')) {
    //     const toggleBtn = document.createElement('button');
    //     toggleBtn.className = 'theme-toggle-books';
    //     toggleBtn.innerHTML = savedTheme === 'light' ? '🌙' : '☀️';
    //     toggleBtn.setAttribute('aria-label', 'Toggle theme');
    //     toggleBtn.addEventListener('click', function() {
    //         const current = document.documentElement.getAttribute('data-theme');
    //         const next = current === 'light' ? 'dark' : 'light';
    //         document.documentElement.setAttribute('data-theme', next);
    //         localStorage.setItem('books-theme', next);
    //         this.innerHTML = next === 'light' ? '🌙' : '☀️';
    //     });
    //     document.body.appendChild(toggleBtn);
    // }
    // ----- ACCORDION TOGGLE -----
    document.querySelectorAll(".accordion-item-controller").forEach(ctrl => {
        ctrl.addEventListener('click', function(e) {
            e.stopPropagation();
            let targetItem = this.closest('.accordion-item');
            if (!targetItem) {
                console.warn("Accordion controller outside of accordion-item");
                return;
            }
            
            // Close all other accordion items (optional - comment out to keep independent)
            // document.querySelectorAll('.accordion-item').forEach(item => {
            //     if (item !== targetItem && item.classList.contains('open')) {
            //         item.classList.remove('open');
            //         item.classList.add('closed');
            //     }
            // });
            
            targetItem.classList.toggle('open');
            targetItem.classList.toggle('closed');
            
            // Update icon rotation
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('rotated');
            }
        });
    });

    // ----- VIEW BOOK (open PDF) -----
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            if (!localStorage.getItem('token')) {
                showError("Please login to view books");
                return;
            }
            
            const btnElement = this;
            const originalText = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';
            btnElement.disabled = true;
            
            try {
                const response = await fetch('/api/v1/books/all', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();

                if (!result.success) {
                    showError(result.message || 'Failed to load book');
                    btnElement.innerHTML = originalText;
                    btnElement.disabled = false;
                    return;
                }
                
                let target = result.data.books.find(book => Number(book.id) === Number(btn.dataset.itemid));
                if (target) {
                    if (!target.pdfUrl) {
                        showError("PDF not available for this book");
                        btnElement.innerHTML = originalText;
                        btnElement.disabled = false;
                        return;
                    }
                    window.open(target.pdfUrl, '_blank');
                } else {
                    showError("Book not found");
                }
            } catch (err) {
                console.error(err);
                showError(err.message || 'An error occurred');
            } finally {
                btnElement.innerHTML = originalText;
                btnElement.disabled = false;
            }
        });
    });

    // ----- SEARCH FUNCTIONALITY -----
    const searchInput = document.getElementById('searchBooks');
    const clearBtn = document.getElementById('clearSearch');
    const bookCards = document.querySelectorAll('.book-card');
    const accordionItems = document.querySelectorAll('.accordion-item');
    const genreHeaders = document.querySelectorAll('.genre-header');
    
    function filterBooks(query) {
        const searchTerm = query.toLowerCase().trim();
        let visibleCount = 0;
        
        // Show/hide clear button
        if (searchTerm.length > 0) {
            clearBtn.style.display = 'block';
        } else {
            clearBtn.style.display = 'none';
        }
        
        // If search is empty, show all books and expand all sections
        if (!searchTerm) {
            bookCards.forEach(card => {
                card.style.display = '';
            });
            accordionItems.forEach(item => {
                item.classList.remove('closed');
                item.classList.add('open');
            });
            return;
        }
        
        // Filter books
        bookCards.forEach(card => {
            const title = card.querySelector('.book-title')?.textContent?.toLowerCase() || '';
            const author = card.querySelector('.book-author')?.textContent?.toLowerCase() || '';
            const description = card.querySelector('.book-description')?.textContent?.toLowerCase() || '';
            
            const matches = title.includes(searchTerm) || 
                            author.includes(searchTerm) || 
                            description.includes(searchTerm);
            
            card.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });
        
        // Show/hide genre sections based on visible books
        accordionItems.forEach(item => {
            const grid = item.querySelector('.books-grid');
            const cards = grid?.querySelectorAll('.book-card') || [];
            let hasVisible = false;
            
            cards.forEach(card => {
                if (card.style.display !== 'none') {
                    hasVisible = true;
                }
            });
            
            if (hasVisible) {
                item.style.display = '';
                item.classList.remove('closed');
                item.classList.add('open');
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show empty state if no results
        const noResults = document.getElementById('noResults');
        if (visibleCount === 0) {
            if (!noResults) {
                const emptyDiv = document.createElement('div');
                emptyDiv.id = 'noResults';
                emptyDiv.className = 'empty-state';
                emptyDiv.innerHTML = `
                    <i class="fa-solid fa-search" style="font-size: 3rem; color: var(--muted);"></i>
                    <h3 style="color: var(--muted); margin-top: 1rem;">No books found</h3>
                    <p style="color: var(--muted);">Try adjusting your search terms</p>
                `;
                document.getElementById('booksContainer').appendChild(emptyDiv);
            }
        } else {
            const noResultsEl = document.getElementById('noResults');
            if (noResultsEl) noResultsEl.remove();
        }
    }
    
    // Debounce search input
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterBooks(this.value);
        }, 300);
    });
    
    // Clear search
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        filterBooks('');
        searchInput.focus();
    });
    
    // Keyboard shortcut: Ctrl+Shift+F to focus search
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'F') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
    });

    // ----- INITIAL STATE: All sections open by default -----
    accordionItems.forEach(item => {
        item.classList.remove('closed');
        item.classList.add('open');
    });
});