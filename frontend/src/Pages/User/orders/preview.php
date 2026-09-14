<?php
/**
 * Order Detail Page
 * Konektem - View single order
 */
?>

<div class="order-detail-container" id="orderDetailContainer">
    <!-- Loading State -->
    <div class="order-detail-loading" id="orderLoading">
        <i class="fas fa-spinner fa-spin"></i>
        <span>Chajman lòd...</span>
    </div>

    <!-- Order Content -->
    <div class="order-detail-content" id="orderContent" style="display: none;">
        <!-- Back Button -->
        <a class="back-link" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
            <span>Retounen nan lòd</span>
        </a>

        <!-- Order Card -->
        <div class="order-detail-card">
            <!-- Header -->
            <div class="order-detail-header">
                <div class="order-info">
                    <div class="order-number-group">
                        <span class="order-label">N° Lòd</span>
                        <span class="order-number" id="orderNumber">ORD-2025-001234</span>
                    </div>
                    <div class="order-date-group">
                        <i class="far fa-calendar-alt"></i>
                        <span id="orderDate">15 Jan 2025</span>
                    </div>
                </div>
                <div class="order-status-group">
                    <span class="status-badge" id="orderStatus">An tretman</span>
                </div>
            </div>

            <!-- Summary -->
            <div class="order-summary">
                <div class="summary-item">
                    <span class="summary-label">Total Atik</span>
                    <span class="summary-value" id="itemCount">3 atik</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Total</span>
                    <span class="summary-value total-amount" id="orderTotal">$45.99</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Metòd Peman</span>
                    <span class="summary-value" id="paymentMethod">Moncash</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="order-actions" id="orderActions">
                <!-- Dynamic buttons rendered by JS -->
            </div>

            <!-- Items Accordion -->
            <div class="order-items-section">
                <button class="accordion-toggle" id="itemsToggle">
                    <div class="toggle-left">
                        <i class="fas fa-boxes"></i>
                        <span>Atik lòd</span>
                        <span class="item-count-badge" id="itemsBadge">3</span>
                    </div>
                    <div class="toggle-right">
                        <span class="toggle-label">Wè atik yo</span>
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </div>
                </button>
                
                <div class="accordion-content" id="itemsContent">
                    <div class="items-list" id="itemsList">
                        <!-- Items rendered by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---------- Get Order ID from URL ----------
    const pathParts = window.location.pathname.split('/');
    const orderId = pathParts[pathParts.length - 2];
    // ---------- DOM Refs ----------
    const loading = document.getElementById('orderLoading');
    const content = document.getElementById('orderContent');
    const itemsList = document.getElementById('itemsList');
    const itemsToggle = document.getElementById('itemsToggle');
    const itemsContent = document.getElementById('itemsContent');

    // ---------- Status Config ----------
    const statusConfig = {
        'pending': { class: 'status-pending', label: 'An reta' },
        'processing': { class: 'status-processing', label: 'An tretman' },
        'completed': { class: 'status-completed', label: 'Konplete' },
        'cancelled': { class: 'status-cancelled', label: 'Anile' }
    };

    const paymentStatusConfig = {
        'pending': { class: 'payment-pending', label: 'An reta' },
        'paid': { class: 'payment-paid', label: 'Pe' },
        'failed': { class: 'payment-failed', label: 'Echwe' },
        'refunded': { class: 'payment-refunded', label: 'Ranbouse' }
    };

    // ---------- Fetch Order ----------
    async function fetchOrder() {
        try {
            renderOrder(JSON.parse(`<?= json_encode($order)?>` ?? "{}"));
            /*const response = await fetch(`/api/orders/${orderId}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.status === 401) {
                showError('Tanpri konekte ou anvan w gade lòd sa.');
                setTimeout(() => window.location.href = '/auth/login', 1500);
                return;
            }

            if (response.status === 404) {
                showError('Lòd sa pa egziste.');
                setTimeout(() => window.location.href = '/orders', 1500);
                return;
            }

            const result = await response.json();

            if (result.success) {
                renderOrder(result.data.order);
            } else {
                showError(result.message || 'Failed to load order');
            }*/
        } catch (error) {
            console.error('Error fetching order:', error);
            showError(error.message);
        } finally {
            loading.style.display = 'none';
            content.style.display = 'block';
        }
    }

    // ---------- Render Order ----------
    function renderOrder(order) {
        // Basic info
        document.getElementById('orderNumber').textContent = order.order_number;
        document.getElementById('orderDate').textContent = new Date(order.order_date).toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });

        // Status
        const status = order.order_status || 'pending';
        const statusInfo = statusConfig[status] || statusConfig['pending'];
        const statusBadge = document.getElementById('orderStatus');
        statusBadge.textContent = statusInfo.label;
        statusBadge.className = `status-badge ${statusInfo.class}`;

        // Summary
        document.getElementById('itemCount').textContent = `${order.items?.length || 0} atik`;
        document.getElementById('orderTotal').textContent = `${order.total_amount} ${order.currency || 'USD'}`;
        document.getElementById('paymentMethod').textContent = order.payment_method || 'N/A';

        // Render actions
        renderActions(order);

        // Render items
        renderItems(order.items || []);
        //showSuccess(JSON.stringify(order.items));

        // Accordion
        setupAccordion();
    }

    // ---------- Render Actions ----------
    function renderActions(order) {
        const actionsContainer = document.getElementById('orderActions');
        const status = order.order_status;
        const paymentStatus = order.payment_status;

        let actions = [];

        // Show pay button for pending orders
        if (status === 'pending' && paymentStatus === 'pending') {
            actions.push({
                type: 'pay',
                label: '<i class="fas fa-credit-card"></i> Peye kounye a',
                class: 'btn-pay',
                action: 'payOrder'
            });
        }

        // Show cancel button for pending orders
        if (status === 'pending' || status === 'processing') {
            actions.push({
                type: 'cancel',
                label: '<i class="fas fa-times"></i> Anile lòd',
                class: 'btn-cancel',
                action: 'cancelOrder'
            });
        }

        // Show reorder button for completed orders
        if (status === 'completed') {
            actions.push({
                type: 'reorder',
                label: '<i class="fas fa-redo"></i> Rekòmande',
                class: 'btn-reorder',
                action: 'reorder'
            });
        }

        if (actions.length === 0) {
            actionsContainer.innerHTML = `
                <div class="order-actions-empty">
                    <i class="fas fa-check-circle"></i>
                    <span>Lòd sa a finalize</span>
                </div>
            `;
            return;
        }

        actionsContainer.innerHTML = actions.map(action => `
            <button class="order-action-btn ${action.class}" data-action="${action.action}">
                ${action.label}
            </button>
        `).join('');

        // Attach event listeners
        actionsContainer.querySelectorAll('.order-action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.dataset.action;
                if (action === 'payOrder') handlePay(order.id);
                else if (action === 'cancelOrder') handleCancel(order.id);
                else if (action === 'reorder') handleReorder(order);
            });
        });
    }

    // ---------- Render Items ----------
    function renderItems(items) {
        if (!items || items.length === 0) {
            itemsList.innerHTML = `
                <div class="empty-items">
                    <i class="fas fa-box"></i>
                    <p>Pa gen atik nan lòd sa.</p>
                </div>
            `;
            document.getElementById('itemsBadge').textContent = '0';
            return;
        }

        document.getElementById('itemsBadge').textContent = items.length;

        itemsList.innerHTML = items.map((item, index) => `
            <div class="order-item">
                <div class="item-image">
                    ${item.image_url ? 
                        `<img src="${item.image_url}" alt="${item.product_name}" />` :
                        `<div class="item-placeholder"><i class="fas fa-box"></i></div>`
                    }
                </div>
                <div class="item-details">
                    <div class="item-name">${item.product_name}</div>
                    <div class="item-meta">
                        <span class="item-type">${item.product_type}</span>
                        <span class="item-quantity">× ${item.quantity}</span>
                    </div>
                </div>
                <div class="item-price">
                    <span class="item-subtotal">${item.subtotal} ${item.currency || 'USD'}</span>
                    <span class="item-unit-price">${item.price_at_time} chak</span>
                </div>
            </div>
        `).join('');
    }

    // ---------- Accordion ----------
    function setupAccordion() {
        let isOpen = false;

        itemsToggle.addEventListener('click', function() {
            isOpen = !isOpen;
            
            if (isOpen) {
                itemsContent.style.maxHeight = itemsContent.scrollHeight + 'px';
                this.querySelector('.toggle-icon').style.transform = 'rotate(180deg)';
                this.querySelector('.toggle-label').textContent = 'Mache atik yo';
            } else {
                itemsContent.style.maxHeight = '0';
                this.querySelector('.toggle-icon').style.transform = 'rotate(0)';
                this.querySelector('.toggle-label').textContent = 'Wè atik yo';
            }
        });
    }

    // ---------- Action Handlers ----------
    async function handlePay(orderId) {
        if (!confirm('Èske ou vle kontinye ak peman an?')) return;

        const btn = document.querySelector('.btn-pay');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Chargement...</span>';
        btn.disabled = true;

        try {
            const response = await fetch(`/api/orders/${orderId}/pay`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                if (result.redirect_url) {
                    window.location.href = result.redirect_url;
                } else {
                    showSuccess('Peman an te kòmanse. Ou pral resevwa yon konfimasyon.');
                    setTimeout(() => window.location.reload(), 2000);
                }
            } else {
                showError(result.message || 'Peman an echwe.');
            }
        } catch (error) {
            console.error('Payment error:', error);
            showError('Yon erè te rive. Tanpri eseye ankò.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async function handleCancel(orderId) {
        const reason = prompt('Rezon pou anile (opsyonèl):');
        
        if (!confirm('Èske ou sèten ou vle anile lòd sa?')) return;

        const btn = document.querySelector('.btn-cancel');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Chargement...</span>';
        btn.disabled = true;

        try {
            const response = await fetch(`/api/orders/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ reason: reason || '' })
            });

            const result = await response.json();

            if (result.success) {
                showSuccess(result.message ?? 'Lòd ou an anile.');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showError(result.message || 'Echwe pou anile lòd.');
            }
        } catch (error) {
            console.error('Cancel error:', error);
            showError('Yon erè te rive. Tanpri eseye ankò.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    function handleReorder(order) {
        // Redirect to store with order items
        const itemIds = (order.items || []).map(item => item.product_id).join(',');
        window.location.href = `/store/reorder?items=${itemIds}`;
    }

    // ---------- Helper Functions ----------
    function showError(message) {
        if (typeof window.showError === 'function') {
            window.showError(message);
        } else {
            alert('❌ ' + message);
        }
    }

    function showSuccess(message) {
        if (typeof window.showSuccess === 'function') {
            window.showSuccess(message);
        } else {
            alert('✅ ' + message);
        }
    }

    // ---------- Init ----------
    if (orderId && !isNaN(orderId)) {
        fetchOrder();
    } else {
        showError('Lòd pa valab.');
        setTimeout(() => window.location.href = '/orders', 1500);
    }
});
</script>