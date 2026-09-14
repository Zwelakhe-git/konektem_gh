const user = JSON.parse(atob(localStorage.getItem('token')?.split('.')[1]) ?? '{}');
const hasActiveSubscription = Object.entries(JSON.parse(user.subscription )).length > 0;

const modalHtml = () => {
    return `<div class="posting-modal" id="postingModal">
        <button class="modal-close-btn" id="modalCloseBtn">&times;</button>

        <!-- header -->
        <div class="modal-header-custom">
            <h2><i class="fas fa-th-list" style="color:#d32f2f; margin-right: 10px;"></i>Select items</h2>
            <span class="badge-count" id="selectedBadge">
            <i class="fas fa-check-circle" style="color:#1976d2;"></i>
            <span id="selectedCountDisplay">0</span> selected
            </span>
        </div>

        <!-- section tabs -->
        <div class="section-tabs" id="sectionTabs">
            <button class="section-tab active" data-section="music">
                <i class="fas fa-music tab-icon"></i> Music
            </button>
            <button class="section-tab" data-section="events">
                <i class="fas fa-calendar-alt tab-icon"></i> Events
            </button>
            <button class="section-tab" data-section="books">
                <i class="fas fa-book tab-icon"></i> Books
            </button>
            <!-- future sections will be added here -->
        </div>

        <!-- items grid -->
        <div class="items-grid" id="itemsGrid">
            <div class="section-loading"><i class="fas fa-spinner fa-spin"></i> loading...</div>
        </div>

        <!-- footer -->
        <div class="modal-footer-custom">
            ${!hasActiveSubscription ? `
            <div id="premiumBtn" onclick="location.href='/premium-subscription'">
                <button class="btn-secondary-outline inner border-0" >
                <i class="fas fa-crown"></i> Premium
                </button>
            </div>
            ` : ''
            }
            <button class="btn-secondary-outline" id="clearSelectionBtn">
                <i class="fas fa-times"></i> Clear
            </button>
            <button class="btn-publish" id="publishBtn" disabled>
                <i class="fas fa-rocket"></i> Publish
            </button>
        </div>
    </div>`;
}

function openModal(){
    const exisitingOverlay = document.getElementById('postingModalOverlay');
    if(exisitingOverlay){
        exisitingOverlay.remove();
    }
    const overlay = document.createElement('div');
    overlay.className = 'posting-modal-overlay';
    overlay.id = 'postingModalOverlay';
    overlay.innerHTML = modalHtml();
    document.body.appendChild(overlay);
    
    overlay.classList.add('active');
    (function() {
        "use strict";

        // ---------- DOM refs ----------
        const modal = document.getElementById('postingModal');
        const closeBtn = document.querySelector('#postingModal .modal-close-btn');
        const itemsGrid = document.getElementById('itemsGrid');
        const sectionTabs = document.getElementById('sectionTabs');
        const publishBtn = document.getElementById('publishBtn');
        const clearBtn = document.getElementById('clearSelectionBtn');
        const selectedCountDisplay = document.getElementById('selectedCountDisplay');

        // ---------- state ----------
        let currentSection = 'music';
        let allItems = {
            music: [],
            events: [],
            books: []
        };
        let selectedIds = new Set(); // store item.id
        let isLoading = false;
        let touchHoldTimer = null;
        let isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);

        // ---------- fetch from API ----------
        async function fetchSection(section) {
            isLoading = true;
            itemsGrid.innerHTML = `<div class="section-loading"><i class="fas fa-spinner fa-spin"></i> loading ${section}...</div>`;

            try {
                const response = await fetch(`/user/api/data/${section}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });

                const result = await response.json();
                if(response.status === 401){
                    if(localStorage.getItem('token')){
                        showError("session expired");
                    } else { showError(result.message); }
                    localStorage.removeItem('token');
                    setTimeout(() => { location.href = '/auth/logout' }, 500);
                    return;
                }
                if (result.success) {
                    allItems[section] = result.data;
                    renderItems(section);
                } else {
                    itemsGrid.innerHTML = `<div class="section-error"><i class="fas fa-exclamation-circle"></i> ${result.message || 'Failed to load'}</div>`;
                }
            } catch (err) {
                itemsGrid.innerHTML = `<div class="section-error"><i class="fas fa-exclamation-circle"></i> Error: ${err.message}</div>`;
            } finally {
                isLoading = false;
            }
        }

        // ---------- render items ----------
        function renderItems(section) {
            const items = allItems[section] || [];
            if (!items.length) {
                itemsGrid.innerHTML = `<div class="section-loading"><i class="fas fa-info-circle"></i> No items in ${section}</div>`;
                return;
            }

            let html = '';
            items.forEach(item => {
                const isSelected = selectedIds.has(item.id);
                const isPublished = item.public === 1 || item.public === true;
                const title = item.title || item.name || 'Untitled';
                const imgUrl = item.image_url || null;
                const imgHtml = imgUrl
                    ? `<img class="card-img" src="${imgUrl}" alt="${title}" loading="lazy">`
                    : `<div class="card-img-placeholder"><i class="fas fa-image"></i></div>`;

                // Build card classes
                let cardClasses = 'item-card';
                if (isSelected) cardClasses += ' selected';
                if (isPublished) cardClasses += ' published';

                html += `
                    <div class="${cardClasses}" data-id="${item.id}" data-section="${section}" data-published="${isPublished}">
                        ${imgHtml}
                        <div class="card-body">
                            <div class="card-title">${title}</div>
                            <div class="card-sub">${item.artist_name || item.location || item.description || ''}</div>
                        </div>
                        ${isPublished ? 
                            `<div class="published-badge"><i class="fas fa-check-circle"></i> Published</div>` :
                            `<div class="check-mark"><i class="fas fa-check"></i></div>`
                        }
                    </div>
                `;
            });

            itemsGrid.innerHTML = html;

            // attach events
            document.querySelectorAll('.item-card').forEach(card => {
                const id = parseInt(card.dataset.id);
                const isPublished = card.dataset.published === 'true';

                // Only allow interaction if not published
                if (!isPublished) {
                    // click to toggle (works for mouse & touch)
                    card.addEventListener('click', (e) => {
                        e.preventDefault();
                        toggleItem(id);
                    });

                    // touch & hold for multi-select (touch devices)
                    if (isTouchDevice) {
                        let holdTimer = null;
                        card.addEventListener('touchstart', (e) => {
                            holdTimer = setTimeout(() => {
                                card.classList.add('touch-held');
                                toggleItem(id);
                                e.preventDefault();
                            }, 500);
                        }, { passive: true });

                        card.addEventListener('touchend', () => {
                            clearTimeout(holdTimer);
                            card.classList.remove('touch-held');
                        });
                        card.addEventListener('touchmove', () => {
                            clearTimeout(holdTimer);
                            card.classList.remove('touch-held');
                        });
                    }
                } else {
                    // Add tooltip for published items
                    card.title = 'This item is already published and cannot be selected';
                }
            });

            updatePublishButton();
        }

        // ---------- toggle item selection ----------
        function toggleItem(id) {
            // Check if item is published before toggling
            const item = findItemById(id);
            if (item && (item.public === 1 || item.public === true)) {
                showError('This item is already published and cannot be selected');
                return;
            }

            if (selectedIds.has(id)) {
                selectedIds.delete(id);
            } else {
                selectedIds.add(id);
            }
            // re-render current section to update UI
            renderItems(currentSection);
            updatePublishButton();
        }

        // ---------- helper: find item by ID ----------
        function findItemById(id) {
            for (const section of ['music', 'events']) {
                const items = allItems[section] || [];
                const found = items.find(item => item.id === id);
                if (found) return found;
            }
            return null;
        }

        // ---------- update publish button & badge ----------
        function updatePublishButton() {
            const count = selectedIds.size;
            selectedCountDisplay.textContent = count;
            publishBtn.disabled = count === 0;
            if (count > 0) {
                publishBtn.innerHTML = `<i class="fas fa-rocket"></i> Publish (${count})`;
            } else {
                publishBtn.innerHTML = `<i class="fas fa-rocket"></i> Publish`;
            }
        }

        // ---------- clear selection ----------
        function clearSelection() {
            selectedIds.clear();
            renderItems(currentSection);
            updatePublishButton();
        }

        // ---------- switch section ----------
        function switchSection(section) {
            if (section === currentSection) return;
            currentSection = section;

            // update tabs
            document.querySelectorAll('.section-tab').forEach(tab => {
                tab.classList.toggle('active', tab.dataset.section === section);
            });

            // load if not loaded
            if (!allItems[section] || allItems[section].length === 0) {
                fetchSection(section);
            } else {
                renderItems(section);
            }
        }

        document.body.style.overflow = 'hidden';
        // load default section
        if (!allItems.music.length) {
            fetchSection('music');
        } else {
            renderItems('music');
        }
        // reset tabs
        document.querySelectorAll('.section-tab').forEach(tab => {
            tab.classList.toggle('active', tab.dataset.section === 'music');
        });
        currentSection = 'music';
        updatePublishButton();

        function closeModal() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // ---------- event listeners ----------
        closeBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeModal();
        });

        // section tabs
        sectionTabs.addEventListener('click', (e) => {
            const tab = e.target.closest('.section-tab');
            if (!tab) return;
            const section = tab.dataset.section;
            if (section) switchSection(section);
        });

        // clear selection
        clearBtn.addEventListener('click', clearSelection);

        // publish button
        publishBtn.addEventListener('click', async () => {
            if (publishBtn.disabled) return;
            try {
                const selected = [];
                publishBtn.disabled = true;
                const buttonContent = publishBtn.innerHTML;
                publishBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Publishing...';
                
                // gather selected items from all sections
                for (const [section, items] of Object.entries(allItems)) {
                    //const items = allItems[section] || [];
                    items.forEach(item => {
                        if (selectedIds.has(item.id)) {
                            selected.push({ ...item, section });
                        }
                    });
                }

                // Filter out any published items (safety check)
                const validSelected = selected.filter(item => !(item.public === 1 || item.public === true));
                //console.log(selected);
                
                if (validSelected.length === 0) {
                    showError('No valid items to publish. Selected items may already be published.');
                    publishBtn.innerHTML = buttonContent;
                    publishBtn.disabled = false;
                    return;
                }

            
                const response = await fetch('/user/api/publish', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(validSelected)
                });
                
                if(response.status === 401){
                    showError('Session timeout');
                    setTimeout(() => window.location.href = '/auth/logout', 500);
                    return;
                }
                
                const result = await response.json();
                if(result.success){
                    showSuccess(result.message);
                    // Clear selection and refresh
                    selectedIds.clear();
                    // Refresh current section to update published status
                    await fetchSection(currentSection);
                    closeBtn.click();
                } else {
                    showError(result.message);
                }
            } catch(err){
                console.error(err);
                showError('An error occurred while publishing');
            } finally {
                publishBtn.innerHTML = buttonContent;
                publishBtn.disabled = false;
            }
        });

        // keyboard: escape to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && overlay.classList.contains('active')) {
                closeModal();
            }
        });

    })();
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('openPostingBtn')?.addEventListener('click', openModal);
    
});