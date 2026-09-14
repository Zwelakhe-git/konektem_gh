<div class="order-card" id="orderCard">
    <!-- Header -->
    <div class="order-header">
        <div class="icon-circle">
        <i class="fas fa-shopping-cart"></i>
        </div>
        <h1>Complete Order</h1>
        <span class="badge-item-type" id="itemTypeBadge">Event</span>
    </div>

    <!-- Alert Messages -->
    <div class="alert-custom" id="alertMessage">
        <span id="alertText"></span>
    </div>

    <!-- Item Preview -->
    <div class="item-preview" id="itemPreview">
        <div id="itemImageContainer">
        <img class="item-image" id="itemImage" src="" alt="Item image" style="display:none;">
        <div class="item-image-placeholder" id="itemImagePlaceholder">
            <i class="fas fa-image"></i>
        </div>
        </div>
        <div class="item-details">
        <div class="item-title" id="itemTitle">Loading...</div>
        <div class="item-meta">
            <span id="itemMeta"><i class="far fa-calendar-alt"></i> Loading...</span>
        </div>
        <div class="item-price" id="itemPrice">$0.00</div>
        </div>
    </div>

    <!-- Order Form -->
    <form id="orderForm" novalidate>
        <!-- Hidden fields for item data -->
        <input type="hidden" id="itemId" name="item_id">
        <input type="hidden" id="itemType" name="item_type">
        <input type="hidden" id="orderEndpoint" name="order_endpoint" value="/store/api/create-order">
        <input type="hidden" id="price" name="price">

        <!-- Name -->
        <div class="form-group">
        <label for="customerName">Full Name <span class="required">*</span></label>
        <input type="text" class="form-control" id="customerName" name="customer_name" placeholder="Enter your full name" required>
        </div>

        <!-- Email -->
        <div class="form-group">
        <label for="customerEmail">Email Address <span class="required">*</span></label>
        <input type="email" class="form-control" id="customerEmail" name="customer_email" placeholder="you@example.com" required>
        </div>

        <!-- Phone -->
        <div class="form-group">
        <label for="customerPhone">Phone Number <span class="required">*</span></label>
        <input type="tel" class="form-control" id="customerPhone" name="customer_phone" placeholder="+1 234 567 8900" required>
        </div>

        <!-- Quantity -->
        <div class="form-group">
        <label>Quantity <span class="required">*</span></label>
        <div class="quantity-selector">
            <button type="button" class="qty-btn" id="qtyDecrease">−</button>
            <input type="number" class="qty-input" id="qtyInput" name="quantity" value="1" min="1" max="999">
            <button type="button" class="qty-btn" id="qtyIncrease">+</button>
        </div>
        </div>

        <!-- Special Instructions -->
        <div class="form-group">
        <label for="specialInstructions">Special Instructions <span style="color: var(--text-muted); font-weight: 400;">(optional)</span></label>
        <textarea class="form-control" id="specialInstructions" name="special_instructions" placeholder="Any special requests or notes..."></textarea>
        </div>

        <!-- Actions -->
        <div class="form-actions">
        <button type="button" class="btn-secondary-custom" id="cancelBtn">
            <i class="fas fa-times"></i> Cancel
        </button>
        <button type="submit" class="btn-primary-custom" id="submitBtn">
            <i class="fas fa-lock"></i> Place Order
        </button>
        </div>
    </form>

    <!-- Hidden JWT debug (for development) -->
    <!-- <div class="jwt-debug" id="jwtDebug"></div> -->
</div>

<script>
    window.production = true;
(function() {
    "use strict";

    // ---------- DOM refs ----------
    const form = document.getElementById('orderForm');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const alertMessage = document.getElementById('alertMessage');
    const alertText = document.getElementById('alertText');

    // Item display elements
    const itemTypeBadge = document.getElementById('itemTypeBadge');
    const itemImage = document.getElementById('itemImage');
    const itemImagePlaceholder = document.getElementById('itemImagePlaceholder');
    const itemTitle = document.getElementById('itemTitle');
    const itemMeta = document.getElementById('itemMeta');
    const itemPrice = document.getElementById('itemPrice');

    // Form fields
    const itemId = document.getElementById('itemId');
    const itemType = document.getElementById('itemType');
    const orderEndpoint = document.getElementById('orderEndpoint');
    const customerName = document.getElementById('customerName');
    const customerEmail = document.getElementById('customerEmail');
    const customerPhone = document.getElementById('customerPhone');
    const qtyInput = document.getElementById('qtyInput');
    const priceInput = document.querySelector('input#price');
    const specialInstructions = document.getElementById('specialInstructions');

    // ---------- Helper: show alert ----------
    function showAlert(message, type = 'info') {
    alertMessage.className = `alert-custom show alert-${type}`;
    alertText.textContent = message;
    // Auto-hide after 5 seconds for success/info
    if (type === 'success' || type === 'info') {
        setTimeout(() => {
        alertMessage.classList.remove('show');
        }, 5000);
    }
    }

    function hideAlert() {
    alertMessage.classList.remove('show');
    }

    // ---------- Helper: format currency ----------
    function formatPrice(price) {
        const num = parseFloat(price);
        if (isNaN(num)) return '$0.00';
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(num);
    }

    // ---------- Parse JWT from URL ----------
    function parseJwtFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token') || urlParams.get('jwt');

        if (!token) {
            console.warn('No JWT token found in URL');
            return null;
        }

        try {
            // Decode JWT payload (base64url)
            const parts = token.split('.');
            if (parts.length !== 3) {
                throw new Error('Invalid JWT format');
            }
            const payload = JSON.parse(atob(parts[1].replace(/-/g, '+').replace(/_/g, '/')));
            return payload;
        } catch (err) {
            console.error('Failed to parse JWT:', err);
            return null;
        }
    }

    // ---------- Render item from payload ----------
    function renderItemFromPayload(payload) {
        if (!payload) {
            showAlert('No item data found. Please check the link.', 'error');
            return;
        }

        // Determine item type
        let type = payload['product_type'] ?? 'item';
        let title = payload.title || payload.name || 'Untitled';
        let price = payload.price || 0;
        let imageUrl = payload.image_url || null;
        let metaText = payload['description'] ?? '';

        if (payload.event_date) {
            type = 'event';
            metaText = `<i class="far fa-calendar-alt"></i> ${new Date(payload.event_date).toLocaleDateString()}`;
            if (payload.location) {
                metaText += ` · <i class="fas fa-map-marker-alt"></i> ${payload.location}`;
            }
            itemTypeBadge.textContent = 'Event';
        } else if (payload.product_type === 'stream') {
            type = 'stream';
            metaText = `<i class="fas fa-video"></i> Live Stream Access`;
            itemTypeBadge.textContent = 'Livestream';
        } else if (payload.artist_name) {
            type = 'music';
            metaText = `<i class="fas fa-user"></i> ${payload.artist_name}`;
            itemTypeBadge.textContent = 'Music';
        } else {
            //type = type;
            metaText = `<i class="fas fa-tag"></i> ${metaText}`;
            itemTypeBadge.textContent = type;
        }

        // Set form values
        itemId.value = payload.id || '';
        itemType.value = type;

        // Display item
        itemTitle.textContent = title;

        // Price
        const priceNum = parseFloat(price);
        itemPrice.textContent = formatPrice(priceNum);

        // Meta
        itemMeta.innerHTML = metaText;

        // Image
        if (imageUrl) {
            itemImage.src = imageUrl;
            itemImage.style.display = 'block';
            itemImagePlaceholder.style.display = 'none';
        } else {
            itemImage.style.display = 'none';
            itemImagePlaceholder.style.display = 'flex';
        }

        // Debug info
        /*const debugEl = document.getElementById('jwtDebug');
        debugEl.textContent = `JWT Payload: ${JSON.stringify(payload, null, 2)}`;
        debugEl.style.display = 'block';*/
    }

    // ---------- Quantity controls ----------
    function updateQuantity(value) {
        let qty = parseInt(value);
        if (isNaN(qty) || qty < 1) qty = 1;
        if (qty > 999) qty = 999;
        qtyInput.value = qty;
    }

    document.getElementById('qtyDecrease').addEventListener('click', () => {
        updateQuantity(parseInt(qtyInput.value) - 1);
    });

    document.getElementById('qtyIncrease').addEventListener('click', () => {
        updateQuantity(parseInt(qtyInput.value) + 1);
    });

    qtyInput.addEventListener('change', () => {
        updateQuantity(qtyInput.value);
    });

    // ---------- Form submission ----------
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideAlert();

        if(!window.production){
            showError("We're under development. Please try again later");
            return;
        }
        // Validate required fields
        if (!customerName.value.trim()) {
            showAlert('Please enter your full name.', 'error');
            customerName.focus();
            return;
        }

        if (!customerEmail.value.trim() || !customerEmail.value.includes('@')) {
            showAlert('Please enter a valid email address.', 'error');
            customerEmail.focus();
            return;
        }

        if (!customerPhone.value.trim()) {
            showAlert('Please enter your phone number.', 'error');
            customerPhone.focus();
            return;
        }

        // Prepare order data
        const orderData = {
            customer_name: customerName.value.trim(),
            customer_email: customerEmail.value.trim(),
            customer_phone: customerPhone.value.trim(),
            special_instructions: specialInstructions.value.trim(),
            items: [
                {
                    item_id: parseInt(itemId.value),
                    item_type: itemType.value,
                    quantity: parseInt(qtyInput.value),
                }
            ]
            // Additional fields from JWT payload can be included
        };

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

        try {
            // Get endpoint from hidden field (configurable)
            const endpoint = orderEndpoint.value || '/store/api/create-order';

            // Simulate API call (replace with actual fetch)
            //console.log('📦 Order Data:', orderData);

            // For demo: simulate API response
            //await new Promise(resolve => setTimeout(resolve, 1500));

            // Uncomment for real API:
            
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    order_token: btoa(JSON.stringify(orderData))
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Order failed');
            }

            const result = await response.json();
            
            if(result.redirect_url){
                showSuccess("Order created successfully");
                setTimeout(() => window.location.href = result.redirect_url, 3000);
            } else {
                showError(result.message);
            }

            // Demo success
            //showAlert('✅ Order placed successfully! You will receive a confirmation email shortly.', 'success');
            form.reset();
            qtyInput.value = 1;

            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-lock"></i> Place Order';

            // Optionally close/redirect after success
            // window.location.href = '/thank-you';

        } catch (err) {
            console.error('Order error:', err);
            showAlert(`❌ ${err.message || 'Failed to place order. Please try again.'}`, 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-lock"></i> Place Order';
        }
    });

    // ---------- Cancel button ----------
    cancelBtn.addEventListener('click', () => {
    if (confirm('Are you sure you want to cancel this order?')) {
        // Optionally redirect or close modal
        window.history.back();
    }
    });

    // ---------- Initialize ----------
    function init() {
        // Parse JWT from URL
        const payload = parseJwtFromUrl();

        if (payload) {
            renderItemFromPayload(payload);
        } else {
            // Fallback: use mock data for demo
            const mockPayload = {
                id: 19,
                title: 'Konektem Festival 2026',
                event_date: '2026-07-30',
                description: 'Annual gospel music festival',
                price: 25.00,
                location: 'Port-au-Prince, Haiti',
                image_url: 'https://picsum.photos/seed/festival/400/400'
            };
            renderItemFromPayload(mockPayload);
            //showAlert('ℹ️ Demo mode: Using sample event data. Add ?token=YOUR_JWT to the URL for real data.', 'info');
        }
    }

    init();

    //console.log('Order form ready. JWT parser active.');
    //console.log('To use: add ?token=YOUR_JWT_TOKEN to the URL');

})();
</script>