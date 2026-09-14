// ============================================================
// SELECTION MODE - Main Page Management
// ============================================================

document.addEventListener('DOMContentLoaded', function() {
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
    
    let isSelectionMode = false;
    let selectedIds = new Set();
    
    // ---------- Toggle Selection Mode ----------
    function toggleSelectionMode() {
        isSelectionMode = !isSelectionMode;
        
        if (isSelectionMode) {
            selectionBar.style.display = 'block';
            selectionModeBtn.classList.add('active');
            selectionModeBtn.innerHTML = '<i class="fas fa-times"></i><span class="d-none d-sm-inline"> Cancel Select</span>';
            
            // Show checkboxes
            document.querySelectorAll('.article-select').forEach(cb => {
                cb.style.display = 'inline-block';
            });
            
            // Show select all in list view
            if (selectAllList) {
                selectAllList.style.display = 'inline-block';
            }
            
            // Hide edit/delete buttons
            document.querySelectorAll('.article-actions').forEach(actions => {
                actions.style.display = 'none';
            });
            
            // Add selection mode class to cards for styling
            document.querySelectorAll('.article-item .card').forEach(card => {
                card.classList.add('selection-mode');
            });
            
            // Clear selections
            clearSelections();
            
        } else {
            selectionBar.style.display = 'none';
            selectionModeBtn.classList.remove('active');
            selectionModeBtn.innerHTML = '<i class="fas fa-check-double"></i><span class="d-none d-sm-inline">Select</span>';
            
            // Hide checkboxes
            document.querySelectorAll('.article-select').forEach(cb => {
                cb.style.display = 'none';
                cb.checked = false;
            });
            
            // Hide select all in list view
            if (selectAllList) {
                selectAllList.style.display = 'none';
                selectAllList.checked = false;
            }
            
            // Show edit/delete buttons
            document.querySelectorAll('.article-actions').forEach(actions => {
                actions.style.display = '';
            });
            
            // Remove selection mode class
            document.querySelectorAll('.article-item .card').forEach(card => {
                card.classList.remove('selection-mode');
            });
            
            // Clear selections
            clearSelections();
            selectAllCheckbox.checked = false;
        }
    }
    
    // ---------- Clear Selections ----------
    function clearSelections() {
        selectedIds.clear();
        document.querySelectorAll('.article-select').forEach(cb => {
            cb.checked = false;
        });
        if (selectAllCheckbox) selectAllCheckbox.checked = false;
        if (selectAllList) selectAllList.checked = false;
        updateSelectedCount();
    }
    
    // ---------- Update Selected Count ----------
    function updateSelectedCount() {
        const count = selectedIds.size;
        selectedCount.textContent = `${count} selected`;
        
        // Update select all checkbox state
        const totalItems = document.querySelectorAll('.article-item').length;
        const checkedItems = document.querySelectorAll('.article-select:checked').length;
        
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
    }
    
    // ---------- Select All ----------
    function selectAll(checked) {
        document.querySelectorAll('.article-select').forEach(cb => {
            cb.checked = checked;
            const id = parseInt(cb.dataset.id);
            if (checked) {
                selectedIds.add(id);
            } else {
                selectedIds.delete(id);
            }
        });
        updateSelectedCount();
    }
    
    // ---------- API Calls ----------
    async function updateMainPage(action) {
        if (selectedIds.size === 0) {
            showError('Please select at least one article');
            return;
        }
        
        const position = positionSelect.value;
        const ids = Array.from(selectedIds);
        
        // Confirm action
        const actionText = action === 'add' ? 'add to' : 'remove from';
        if (!confirm(`Are you sure you want to ${actionText} the main page for ${selectedIds.size} article(s)?`)) {
            return;
        }
        
        // Disable buttons during request
        addToMainBtn.disabled = true;
        removeFromMainBtn.disabled = true;
        addToMainBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        removeFromMainBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        try {
            const response = await fetch(`/api/mainpage/${position}/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    ids: ids,
                    //position: position
                })
            });
            
            if (response.status === 401) {
                showError('Session expired. Please login again.');
                const adminRole = /admin/.exec(window.location.href);
                setTimeout(() => window.location.href = (adminRole ? '/auth/admin/logout' : '/auth/logout'), 1500);
                return;
            }
            
            const result = await response.json();
            
            if (result.success) {
                showSuccess(`Successfully ${action === 'add' ? 'added' : 'removed'} ${selectedIds.size} article(s) to/from main page`);
                
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
            showError(error.message);
        } finally {
            addToMainBtn.disabled = false;
            removeFromMainBtn.disabled = false;
            addToMainBtn.innerHTML = '<i class="fas fa-plus"></i> Add';
            removeFromMainBtn.innerHTML = '<i class="fas fa-minus"></i> Remove';
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
        if (e.target.classList.contains('article-select')) {
            toggleItemSelection(e.target);
        }
    });
    
    // Select all (grid view)
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            selectAll(this.checked);
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
    
    // ---------- Helper Functions (if not already defined) ----------
    /*function showError(message) {
        // Your existing showError function
        console.error(message);
        alert(message);
    }
    
    function showSuccess(message) {
        // Your existing showSuccess function
        console.log(message);
        alert(message);
    }*/
});