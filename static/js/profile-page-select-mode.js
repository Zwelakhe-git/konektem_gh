// ============================================================
// SELECTION MODE - Adaptive Main Page Management
// ============================================================

document.addEventListener('DOMContentLoaded', function() {
    // ---------- Get current page context from URL ----------
    const url = new URL(location.href);
    const parts = /(konektem)?\/(admin|user\/me)\/?(\w+)?\/?(\d+)?\/?(\w+)?/.exec(url.pathname);
    const BASE_URL = window.BASE_URL || '/konektem';
    
    let currentItem = 'news'; // default
    let currentId = null;
    
    if (parts) {
        currentItem = parts[3] || 'news';
        currentId = parts[4] || null;
    }
    
    // Map URL item to API position
    const positionMap = {
        'news': 'mpnews_slide',
        'interviews': 'interviews',
        'events': 'events',
        'music': 'music',
        'books': 'books'
    };
    
    const defaultPosition = positionMap[currentItem] || currentItem;
    
    // ---------- DOM refs ----------
    const selectionModeBtn = document.getElementById('selectionModeBtn');
    const selectionBar = document.getElementById('selectionBar');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const selectAllList = document.getElementById('selectAllList');
    const selectedCount = document.getElementById('selectedCount');
    const positionSelect = document.getElementById('positionSelect');
    const addToMainBtn = document.getElementById('addToMainBtn');
    const removeFromMainBtn = document.getElementById('removeFromMainBtn');
    const cancelSelectionBtn = document.getElementById('cancelSelectionBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    
    let isSelectionMode = false;
    let selectedIds = new Set();
    let currentView = 'grid'; // track current view
    
    // ---------- Configure position select for current item type ----------
    function configurePositionSelect() {
        if (!positionSelect) return;
        
        // Clear existing options
        positionSelect.innerHTML = '';
        
        // Define options based on item type
        const options = {
            'news': [
                { value: 'newsSlide', label: 'Slide' },
                { value: 'fadeNews', label: 'Fade' }
            ],
            'music': [
                { value: 'music', label: 'Music' }
            ],
            'events': [
                { value: 'events', label: 'Events' }
            ],
            'interviews': [
                { value: 'interviews', label: 'Interviews' }
            ],
            'books': [
                { value: 'books', label: 'Books' }
            ]
        };
        
        const itemOptions = options[currentItem] || options['news'];
        
        itemOptions.forEach(opt => {
            const option = document.createElement('option');
            option.value = opt.value;
            option.textContent = opt.label;
            if (opt.value === defaultPosition) {
                option.selected = true;
            }
            positionSelect.appendChild(option);
        });
        
        // Update label
        const label = document.querySelector('label[for="positionSelect"]');
        if (label) {
            label.textContent = currentItem.charAt(0).toUpperCase() + currentItem.slice(1) + ' Position:';
        }
    }
    
    // ---------- Get all item selectors based on current view ----------
    function getItemSelectors() {
        // Get all checkboxes regardless of view
        return document.querySelectorAll('.item-select, .article-select');
    }
    
    function getVisibleItems() {
        // Get visible items based on current view
        if (currentView === 'grid') {
            return document.querySelectorAll('#gridView .item-container, #gridView .article-item');
        } else {
            return document.querySelectorAll('#listView tbody tr.article-item');
        }
    }
    
    function getItemCheckboxes() {
        if (currentView === 'grid') {
            return document.querySelectorAll('#gridView .item-select');
        } else {
            return document.querySelectorAll('#listView .article-select');
        }
    }
    
    function getItemParent(checkbox) {
        if (currentView === 'grid') {
            return checkbox.closest('.item-container, .article-item');
        } else {
            return checkbox.closest('tr.article-item');
        }
    }
    
    // ---------- Toggle Selection Mode ----------
    function toggleSelectionMode() {
        isSelectionMode = !isSelectionMode;
        
        if (isSelectionMode) {
            selectionBar.style.display = 'block';
            selectionModeBtn.classList.add('active');
            selectionModeBtn.innerHTML = '<i class="fas fa-times"></i><span class="d-none d-sm-inline"> Cancel Select</span>';
            
            // Show checkboxes in both views
            document.querySelectorAll('.item-select, .article-select').forEach(cb => {
                cb.style.display = 'inline-block';
            });
            
            // Show select all in list view
            if (selectAllList) {
                selectAllList.style.display = 'inline-block';
            }
            
            // Hide edit/delete buttons in both views
            document.querySelectorAll('.item-actions, .article-actions').forEach(actions => {
                actions.style.display = 'none';
            });
            
            // Add selection mode class to cards in grid view
            document.querySelectorAll('#gridView .card').forEach(card => {
                card.classList.add('selection-mode');
            });
            
            // Add selection mode class to table rows in list view
            document.querySelectorAll('#listView tbody tr').forEach(row => {
                row.classList.add('selection-mode');
            });
            
            // Clear selections
            clearSelections();
            
        } else {
            selectionBar.style.display = 'none';
            selectionModeBtn.classList.remove('active');
            selectionModeBtn.innerHTML = '<i class="fas fa-check-double"></i><span class="d-none d-sm-inline">Select</span>';
            
            // Hide checkboxes in both views
            document.querySelectorAll('.item-select, .article-select').forEach(cb => {
                cb.style.display = 'none';
                cb.checked = false;
            });
            
            // Hide select all in list view
            if (selectAllList) {
                selectAllList.style.display = 'none';
                selectAllList.checked = false;
            }
            
            // Show edit/delete buttons in both views
            document.querySelectorAll('.item-actions, .article-actions').forEach(actions => {
                actions.style.display = '';
            });
            
            // Remove selection mode class from cards in grid view
            document.querySelectorAll('#gridView .card').forEach(card => {
                card.classList.remove('selection-mode');
            });
            
            // Remove selection mode class from table rows in list view
            document.querySelectorAll('#listView tbody tr').forEach(row => {
                row.classList.remove('selection-mode');
            });
            
            // Clear selections
            clearSelections();
            selectAllCheckbox.checked = false;
            if (selectAllList) selectAllList.checked = false;
        }
    }
    
    // ---------- Clear Selections ----------
    function clearSelections() {
        selectedIds.clear();
        document.querySelectorAll('.item-select, .article-select').forEach(cb => {
            cb.checked = false;
        });
        if (selectAllCheckbox) selectAllCheckbox.checked = false;
        if (selectAllList) selectAllList.checked = false;
        updateSelectedCount();
        
        // Remove selection highlight from all items
        document.querySelectorAll('#gridView .card, #listView tbody tr').forEach(el => {
            el.classList.remove('selected');
        });
    }
    
    // ---------- Update Selected Count ----------
    function updateSelectedCount() {
        const count = selectedIds.size;
        selectedCount.textContent = `${count} selected`;
        
        // Update select all checkbox state
        const totalItems = getVisibleItems().length;
        const checkedItems = document.querySelectorAll('.item-select:checked, .article-select:checked').length;
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedItems === totalItems && totalItems > 0;
            selectAllCheckbox.indeterminate = checkedItems > 0 && checkedItems < totalItems;
        }
        if (selectAllList) {
            selectAllList.checked = checkedItems === totalItems && totalItems > 0;
            selectAllList.indeterminate = checkedItems > 0 && checkedItems < totalItems;
        }
    }
    
    // ---------- Toggle Item Selection ----------
    function toggleItemSelection(checkbox) {
        const id = parseInt(checkbox.dataset.id);
        if (checkbox.checked) {
            selectedIds.add(id);
        } else {
            selectedIds.delete(id);
        }
        updateSelectedCount();
        
        // Highlight selected item
        const parent = getItemParent(checkbox);
        if (parent) {
            if (currentView === 'grid') {
                const card = parent.querySelector('.card') || parent;
                if (checkbox.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            } else {
                // List view - highlight the row
                if (checkbox.checked) {
                    parent.classList.add('selected');
                } else {
                    parent.classList.remove('selected');
                }
            }
        }
    }
    
    // ---------- Select All ----------
    function selectAll(checked) {
        const checkboxes = getItemCheckboxes();
        
        checkboxes.forEach(cb => {
            cb.checked = checked;
            const id = parseInt(cb.dataset.id);
            if (checked) {
                selectedIds.add(id);
            } else {
                selectedIds.delete(id);
            }
            
            // Update card/row highlight
            const parent = getItemParent(cb);
            if (parent) {
                if (currentView === 'grid') {
                    const card = parent.querySelector('.card') || parent;
                    if (checked) {
                        card.classList.add('selected');
                    } else {
                        card.classList.remove('selected');
                    }
                } else {
                    if (checked) {
                        parent.classList.add('selected');
                    } else {
                        parent.classList.remove('selected');
                    }
                }
            }
        });
        updateSelectedCount();
    }
    
    // ---------- API Calls ----------
    async function updateMainPage(action) {
        if (selectedIds.size === 0) {
            showError('Please select at least one item');
            return;
        }
        
        // Get position from select or use default
        let position = positionSelect ? positionSelect.value : defaultPosition;
        
        // If position is 'no_pos' or empty, use the current item type
        if (!position || position === 'no_pos') {
            position = defaultPosition;
        }
        
        const ids = Array.from(selectedIds);
        
        // Confirm action
        const actionText = action === 'add' ? 'add to' : 'remove from';
        if (!confirm(`Are you sure you want to ${actionText} the main page for ${selectedIds.size} item(s)?`)) {
            return;
        }
        
        // Disable buttons during request
        addToMainBtn.disabled = true;
        removeFromMainBtn.disabled = true;
        addToMainBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        removeFromMainBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        try {
            const endpoint = `/api/mainpage/${position}/${action}`;
            
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    ids: ids
                })
            });
            
            if (response.status === 401) {
                showError('Session expired. Please login again.');
                setTimeout(() => window.location.href = '/auth/logout', 1500);
                return;
            }
            
            const result = await response.json();
            
            if (result.success) {
                showSuccess(`Successfully ${action === 'add' ? 'added' : 'removed'} ${selectedIds.size} item(s) to/from main page`);
                
                // Refresh the page after a delay to show updated positions
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showError(result.message || 'Operation failed');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('An error occurred. Please try again.');
        } finally {
            addToMainBtn.disabled = false;
            removeFromMainBtn.disabled = false;
            addToMainBtn.innerHTML = '<i class="fas fa-plus"></i> Add';
            removeFromMainBtn.innerHTML = '<i class="fas fa-minus"></i> Remove';
        }
    }
    
    // ---------- Handle view switching ----------
    function handleViewSwitch() {
        // Update current view
        if (gridView && gridView.style.display !== 'none') {
            currentView = 'grid';
        } else if (listView && listView.style.display !== 'none') {
            currentView = 'list';
        }
        
        // If in selection mode, update visibility
        if (isSelectionMode) {
            // Show checkboxes in the current view
            const checkboxes = getItemCheckboxes();
            checkboxes.forEach(cb => {
                cb.style.display = 'inline-block';
            });
            
            // Update select all visibility
            if (selectAllList) {
                selectAllList.style.display = currentView === 'list' ? 'inline-block' : 'none';
            }
            
            // Update actions visibility
            document.querySelectorAll('.item-actions, .article-actions').forEach(actions => {
                actions.style.display = 'none';
            });
            
            // Re-apply selection highlights
            document.querySelectorAll('.item-select:checked, .article-select:checked').forEach(cb => {
                const parent = getItemParent(cb);
                if (parent) {
                    if (currentView === 'grid') {
                        const card = parent.querySelector('.card') || parent;
                        card.classList.add('selected');
                    } else {
                        parent.classList.add('selected');
                    }
                }
            });
        }
    }
    
    // ---------- Event Listeners ----------
    // Toggle selection mode
    if (selectionModeBtn) {
        selectionModeBtn.addEventListener('click', toggleSelectionMode);
    }
    
    // Cancel selection
    if (cancelSelectionBtn) {
        cancelSelectionBtn.addEventListener('click', toggleSelectionMode);
    }
    
    // Individual item selection (event delegation)
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-select') || e.target.classList.contains('article-select')) {
            toggleItemSelection(e.target);
        }
    });
    
    // Select all (grid view)
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            selectAll(this.checked);
            // Sync with list view select all
            if (selectAllList) {
                selectAllList.checked = this.checked;
            }
        });
    }
    
    // Select all (list view)
    if (selectAllList) {
        selectAllList.addEventListener('change', function() {
            selectAll(this.checked);
            // Sync with grid view select all
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = this.checked;
            }
        });
    }
    
    // Add to main page
    if (addToMainBtn) {
        addToMainBtn.addEventListener('click', () => updateMainPage('add'));
    }
    
    // Remove from main page
    if (removeFromMainBtn) {
        removeFromMainBtn.addEventListener('click', () => updateMainPage('remove'));
    }
    
    // Keyboard shortcut: Escape to cancel selection mode
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isSelectionMode) {
            toggleSelectionMode();
        }
    });
    
    // ---------- Helper Functions ----------
    function showError(message) {
        console.error(message);
        if (typeof window.showError === 'function') {
            window.showError(message);
        } else {
            alert(message);
        }
    }
    
    function showSuccess(message) {
        console.log(message);
        if (typeof window.showSuccess === 'function') {
            window.showSuccess(message);
        } else {
            alert(message);
        }
    }
    
    // ---------- Initialize ----------
    configurePositionSelect();
    
    // Detect initial view
    if (gridView && gridView.style.display !== 'none') {
        currentView = 'grid';
    } else if (listView && listView.style.display !== 'none') {
        currentView = 'list';
    }
    
    // Store current context for debugging
    window.selectionContext = {
        currentItem,
        defaultPosition,
        currentId,
        currentView
    };
    
    console.log('Selection mode initialized for:', currentItem);
});