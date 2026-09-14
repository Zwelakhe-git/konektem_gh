document.addEventListener('DOMContentLoaded', function() {
    // ---------- DOM refs ----------
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const positionFilter = document.getElementById('positionFilter');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    const filterCount = document.getElementById('filterCount');
    
    let currentView = 'grid';
    let activeFilters = {
        category: '',
        status: '',
        position: ''
    };
    
    // ---------- Apply Filters Function ----------
    function applyFilters() {
        // Make sure all filter elements exist
        if (!categoryFilter || !statusFilter || !positionFilter) {
            console.warn('Filter elements not found');
            return;
        }
        
        const category = categoryFilter.value.toLowerCase();
        const status = statusFilter.value;
        const position = positionFilter.value;
        
        const items = document.querySelectorAll('.article-item');
        let visibleCount = 0;
        
        items.forEach(item => {
            let show = true;
            
            // Category filter
            if (category) {
                const itemCategory = (item.dataset.category || '').toLowerCase();
                if (itemCategory !== category) show = false;
            }
            
            // Status filter
            if (status && show) {
                const itemStatus = item.dataset.status || '';
                if (itemStatus !== status) show = false;
            }
            
            // Position filter
            if (position && show) {
                const itemPosition = item.dataset.position || '';
                if (itemPosition !== position) show = false;
            }
            
            // Show/hide based on current view
            if (show) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Update filter count badge
        const totalItems = items.length;
        const activeFilterCount = Object.values(activeFilters).filter(v => v !== '').length;
        
        if (filterCount) {
            if (activeFilterCount > 0 && visibleCount < totalItems) {
                filterCount.textContent = visibleCount;
                filterCount.style.display = '';
            } else if (activeFilterCount > 0 && visibleCount === totalItems) {
                filterCount.textContent = activeFilterCount;
                filterCount.style.display = '';
            } else {
                filterCount.style.display = 'none';
            }
        }
        
        // Show message if no items visible
        let noResultsMsg = document.getElementById('noResultsMsg');
        if (visibleCount === 0 && items.length > 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'noResultsMsg';
                noResultsMsg.className = 'text-center text-muted py-5';
                noResultsMsg.innerHTML = '<i class="fas fa-search" style="font-size: 2rem;"></i><p class="mt-3">No articles match your filters</p>';
                const container = document.getElementById('articlesContainer');
                if (container) {
                    container.appendChild(noResultsMsg);
                }
            }
            noResultsMsg.style.display = '';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }
    
    // ---------- Set View Function ----------
    function setView(view) {
        currentView = view;
        
        if (view === 'grid') {
            if (gridView) gridView.style.display = '';
            if (listView) listView.style.display = 'none';
            if (gridViewBtn) gridViewBtn.classList.add('active');
            if (listViewBtn) listViewBtn.classList.remove('active');
            localStorage.setItem('newsView', 'grid');
        } else {
            if (gridView) gridView.style.display = 'none';
            if (listView) listView.style.display = '';
            if (listViewBtn) listViewBtn.classList.add('active');
            if (gridViewBtn) gridViewBtn.classList.remove('active');
            localStorage.setItem('newsView', 'list');
        }
        
        // Re-apply filters after view change
        applyFilters();
    }
    
    // ---------- Update Filters Function ----------
    function updateFilters() {
        if (categoryFilter) activeFilters.category = categoryFilter.value;
        if (statusFilter) activeFilters.status = statusFilter.value;
        if (positionFilter) activeFilters.position = positionFilter.value;
        applyFilters();
    }
    
    // ---------- Initialize ----------
    // Load saved view preference
    const savedView = localStorage.getItem('newsView') || 'grid';
    setView(savedView);
    
    // Event listeners for display toggle
    if (gridViewBtn) {
        gridViewBtn.addEventListener('click', () => setView('grid'));
    }
    if (listViewBtn) {
        listViewBtn.addEventListener('click', () => setView('list'));
    }
    
    // Event listeners for filters
    if (categoryFilter) {
        categoryFilter.addEventListener('change', updateFilters);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', updateFilters);
    }
    if (positionFilter) {
        positionFilter.addEventListener('change', updateFilters);
    }
    
    // Clear filters
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            if (categoryFilter) categoryFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            if (positionFilter) positionFilter.value = '';
            updateFilters();
        });
    }
    
});