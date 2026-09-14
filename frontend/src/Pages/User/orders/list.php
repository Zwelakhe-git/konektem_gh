<?php
/**
 * Orders List Page
 * Konektem - User's order history
 */
?>

<div class="orders-container">
    <div class="orders-header">
        <h1 class="orders-title">
            <i class="fas fa-shopping-bag"></i> Mwen Lòd Mwen
        </h1>
        <p class="orders-subtitle">Gere tout lòd ou yo yon sel kote</p>
    </div>

    <!-- Filter/Search Bar -->
    <div class="orders-toolbar">
        <div class="orders-search">
            <i class="fas fa-search search-icon"></i>
            <input 
                type="text" 
                id="orderSearch" 
                placeholder="Chèche pa nimewo lòd..." 
                class="search-input"
            />
        </div>
        <div class="orders-filter">
            <select id="statusFilter" class="filter-select">
                <option value="">Tout lòd</option>
                <option value="pending">An reta</option>
                <option value="processing">An tretman</option>
                <option value="completed">Konplete</option>
                <option value="cancelled">Anile</option>
            </select>
        </div>
    </div>

    <!-- Orders Grid -->
    <div class="orders-grid" id="ordersGrid">
        <!-- Orders will be rendered here by JavaScript -->
        <div class="orders-loading">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Chajman lòd...</span>
        </div>
    </div>

    <!-- Empty State -->
    <div class="orders-empty" id="ordersEmpty" style="display: none;">
        <i class="fas fa-shopping-bag empty-icon"></i>
        <h3>Pa gen lòd</h3>
        <p>Ou poko fè okenn lòd. Kòmanse achte kounye a!</p>
    </div>

    <!-- Pagination -->
    <div class="orders-pagination" id="ordersPagination" style="display: none;">
        <button class="page-btn" id="prevPage" disabled>
            <i class="fas fa-chevron-left"></i>
        </button>
        <span class="page-info" id="pageInfo">Paj 1</span>
        <button class="page-btn" id="nextPage">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---------- State ----------
    let orders = [];
    let filteredOrders = [];
    let currentPage = 1;
    const perPage = 9;

    // ---------- DOM Refs ----------
    const grid = document.getElementById('ordersGrid');
    const emptyState = document.getElementById('ordersEmpty');
    const loadingState = document.querySelector('.orders-loading');
    const pagination = document.getElementById('ordersPagination');
    const pageInfo = document.getElementById('pageInfo');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const searchInput = document.getElementById('orderSearch');
    const statusFilter = document.getElementById('statusFilter');

    // ---------- Fetch Orders ----------
    async function fetchOrders() {
        try {
            loadingState.style.display = 'flex';
            orders = JSON.parse(`<?= json_encode($orders)?>` ?? "[]");
            filteredOrders = [...orders];
            renderOrders();
            /*const response = await fetch('/api/orders', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.status === 401) {
                showError('Tanpri konekte ou anvan w gade lòd ou yo.');
                setTimeout(() => window.location.href = '/auth/login', 1500);
                return;
            }

            const result = await response.json();

            if (result.success) {
                orders = result.data.orders || [];
                filteredOrders = [...orders];
                renderOrders();
            } else {
                showError(result.message || 'Failed to load orders');
            }*/
        } catch (error) {
            console.error('Error fetching orders:', error);
            showError(error.message);
        } finally {
            loadingState.style.display = 'none';
        }
    }

    // ---------- Render Orders ----------
    function renderOrders() {
        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const pageOrders = filteredOrders.slice(start, end);

        if (filteredOrders.length === 0) {
            grid.innerHTML = '';
            emptyState.style.display = 'block';
            pagination.style.display = 'none';
            return;
        }

        emptyState.style.display = 'none';
        pagination.style.display = 'flex';

        // Update pagination
        const totalPages = Math.ceil(filteredOrders.length / perPage);
        pageInfo.textContent = `Paj ${currentPage} / ${totalPages}`;
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages;

        // Render grid
        grid.innerHTML = pageOrders.map(order => createOrderCard(order)).join('');
    }

    // ---------- Create Order Card ----------
    function createOrderCard(order) {
        const statusColors = {
            'pending': 'status-pending',
            'processing': 'status-processing',
            'completed': 'status-completed',
            'cancelled': 'status-cancelled'
        };

        const statusLabels = {
            'pending': 'An reta',
            'processing': 'An tretman',
            'completed': 'Konplete',
            'cancelled': 'Anile'
        };

        const status = order.order_status || 'pending';
        const statusClass = statusColors[status] || 'status-pending';
        const statusLabel = statusLabels[status] || status;

        // Get first two items for images
        const items = order.items || [];
        const displayItems = items.slice(0, 2);
        const hasMultiple = items.length > 1;
        const itemCount = items.length;

        // Format date
        const orderDate = new Date(order.order_date);
        const formattedDate = orderDate.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        return `
            <div class="order-card" data-order-id="${order.id}" onclick="window.location.href='/user/me/orders/${order.id}/preview'">
                <div class="order-card__header">
                    <div class="order-card__number">
                        <span class="order-label">N° Lòd</span>
                        <span class="order-number">${order.order_number}</span>
                    </div>
                    <div class="order-card__status">
                        <span class="status-badge ${statusClass}">${statusLabel}</span>
                    </div>
                </div>
                
                <div class="order-card__date">
                    <i class="far fa-calendar-alt"></i>
                    <span>${formattedDate}</span>
                </div>
                
                <div class="order-card__items">
                    <div class="order-items-preview">
                        ${displayItems.map((item, index) => `
                            <div class="order-item-preview ${index === 0 && hasMultiple ? 'half' : ''}">
                                ${item.image_url ? 
                                    `<img src="${item.image_url}" alt="${item.product_name}" />` :
                                    `<div class="item-placeholder"><i class="fas fa-box"></i></div>`
                                }
                                ${hasMultiple && index === 0 ? `<div class="item-divider"></div>` : ''}
                            </div>
                        `).join('')}
                        ${items.length === 0 ? `
                            <div class="order-item-preview full">
                                <div class="item-placeholder"><i class="fas fa-box"></i></div>
                            </div>
                        ` : ''}
                    </div>
                    <div class="order-items-count">
                        <i class="fas fa-box"></i>
                        <span>${itemCount} atik${itemCount > 1 ? 's' : ''}</span>
                    </div>
                </div>
                
                <div class="order-card__footer">
                    <div class="order-total">
                        <span class="total-label">Total</span>
                        <span class="total-amount">${order.total_amount} ${order.currency || 'USD'}</span>
                    </div>
                    <div class="order-view-btn">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        `;
    }

    // ---------- Filter Orders ----------
    function filterOrders() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value;

        filteredOrders = orders.filter(order => {
            // Search filter
            const matchesSearch = !searchTerm || 
                order.order_number.toLowerCase().includes(searchTerm);

            // Status filter
            const matchesStatus = !status || order.order_status === status;

            return matchesSearch && matchesStatus;
        });

        currentPage = 1;
        renderOrders();
    }

    // ---------- Event Listeners ----------
    searchInput.addEventListener('input', filterOrders);
    statusFilter.addEventListener('change', filterOrders);

    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderOrders();
        }
    });

    nextPageBtn.addEventListener('click', () => {
        const totalPages = Math.ceil(filteredOrders.length / perPage);
        if (currentPage < totalPages) {
            currentPage++;
            renderOrders();
        }
    });

    // ---------- Init ----------
    fetchOrders();

    // ---------- Helper Functions ----------
    function showError(message) {
        if (typeof window.showError === 'function') {
            window.showError(message);
        } else {
            alert('❌ ' + message);
        }
    }
});
</script>