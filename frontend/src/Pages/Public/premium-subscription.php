<?php
/**
 * Premium Subscription Page
 * Konektem - Subscribe to premium features
 */
?>

<div class="premium-container">
    <div class="premium-card">
        <!-- Header -->
        <div class="premium-header">
            <div class="premium-header-icon">
                <i class="fas fa-crown"></i>
            </div>
            <h1 class="premium-title">Abònman Premium</h1>
            <p class="premium-subtitle">Deploge tout potansyèl Konektem ak plan an premium nou an</p>
        </div>

        <!-- Plan Information -->
        <div class="premium-plan">
            <div class="plan-badge">
                <span class="badge-text">PREMIUM</span>
            </div>
            <div class="plan-price">
                <span class="price-amount">$9.99</span>
                <span class="price-period">/ mwa</span>
            </div>
            <div class="plan-features">
                <div class="feature-item">
                    <i class="fas fa-check-circle feature-icon"></i>
                    <span>Fè kontni w parèt nan paj prensipal la</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle feature-icon"></i>
                    <span>Piblikasyon san limit</span>
                </div>
                <div class="feature-item feature-coming">
                    <i class="fas fa-clock feature-icon"></i>
                    <span>Plis opsyon ap vini... <span class="coming-badge">Bientòt</span></span>
                </div>
            </div>
        </div>

        <!-- Subscription Form -->
        <form class="premium-form" id="premiumForm" method="POST">
            <div class="form-group">
                <label for="fullName">
                    <i class="fas fa-user"></i> Non konplè
                </label>
                <input 
                    type="text" 
                    id="fullName" 
                    name="full_name" 
                    placeholder="Antre non w" 
                    required
                />
            </div>

            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Adrès imèl
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="exèmple@konektem.net" 
                    required
                />
            </div>

            <div class="form-group">
                <label for="phoneNumber">
                    <i class="fas fa-phone"></i> Nimewo telefòn
                </label>
                <input 
                    type="tel" 
                    id="phoneNumber" 
                    name="phone_number" 
                    placeholder="+509 1234 5678" 
                    required
                />
            </div>

            <!-- Payment Method Toggle -->
            <div class="payment-method">
                <label class="payment-label">Metòd Peman</label>
                <div class="toggle-container">
                    <div class="toggle-options">
                        <button type="button" class="toggle-btn active" data-method="moncash">
                            <img src="/media/images/moncash-logo.png" alt="Moncash" class="payment-logo" onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-mobile-alt\'></i> Moncash';" />
                            <span>Moncash</span>
                        </button>
                        <button type="button" class="toggle-btn" data-method="visa">
                            <i class="fas fa-credit-card"></i>
                            <span>Visa</span>
                        </button>
                    </div>
                    <input type="hidden" id="paymentMethod" name="payment_method" value="moncash" />
                </div>
            </div>

            <!-- Moncash Payment Details -->
            <div class="payment-details" id="moncashDetails">
                <div class="payment-info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Ou pral resevwa yon nimewo sou telefòn ou pou konplete peman an via Moncash.</p>
                </div>
            </div>

            <!-- Visa Payment Details -->
            <div class="payment-details" id="visaDetails" style="display: none;">
                <div class="form-group">
                    <label for="cardNumber">Nimewo kat</label>
                    <input 
                        type="text" 
                        id="cardNumber" 
                        name="card_number" 
                        placeholder="1234 5678 9012 3456"
                    />
                </div>
                <div class="form-row">
                    <div class="form-group half">
                        <label for="expiryDate">Dat ekspirasyon</label>
                        <input 
                            type="text" 
                            id="expiryDate" 
                            name="expiry_date" 
                            placeholder="MM/YY"
                        />
                    </div>
                    <div class="form-group half">
                        <label for="cvv">CVV</label>
                        <input 
                            type="text" 
                            id="cvv" 
                            name="cvv" 
                            placeholder="123"
                            maxlength="4"
                        />
                    </div>
                </div>
                <div class="payment-info-box">
                    <i class="fas fa-lock"></i>
                    <p>Peman ou an se an sekirite ak chifreman SSL.</p>
                </div>
            </div>
            <input type="hidden" name="product_id" value="27"/>
            <input type="hidden" name="amount" value="9.99"/>

            <!-- Terms -->
            <div class="terms-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="termsCheck" required />
                    <span class="checkmark"></span>
                    <span class="terms-text">
                        Mwen dakò ak <a href="<?= BASE_URL ?>/terms" target="_blank">kondisyon itilizasyon</a> yo
                    </span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-rocket"></i>
                <span>Abònman kounye a</span>
            </button>

            <p class="form-footnote">
                <i class="fas fa-shield-alt"></i>
                Peman ou an an sekirite. Ou ka anile nenpòt moman.
            </p>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---------- Payment Method Toggle ----------
    const toggleBtns = document.querySelectorAll('.toggle-btn');
    const moncashDetails = document.getElementById('moncashDetails');
    const visaDetails = document.getElementById('visaDetails');
    const paymentMethodInput = document.getElementById('paymentMethod');

    const nameInp = document.getElementById('fullName');
    const emailInp = document.getElementById('email');
    const phoneInp = document.getElementById('phoneNumber');
    const termsInp = document.getElementById('termsCheck');

    const user = JSON.parse(atob(localStorage.getItem('token')?.split('.')[1]) ?? '{}');

    if(user){
        nameInp.value = user.full_name ?? user.name;
        emailInp.value = user.email;
    }
    
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            toggleBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const method = this.dataset.method;
            paymentMethodInput.value = method;

            // Show/hide payment details
            if (method === 'moncash') {
                moncashDetails.style.display = 'block';
                visaDetails.style.display = 'none';
            } else {
                moncashDetails.style.display = 'none';
                visaDetails.style.display = 'block';
            }
        });
    });

    // ---------- Form Submission ----------
    const form = document.getElementById('premiumForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        if(!user){
            showError("Login required");
            return;
        }

        // Validate required fields
        const name = nameInp.value.trim();
        const email = emailInp.value.trim();
        const phone = phoneInp.value.trim();
        const terms = termsInp.checked;

        if (!name || !email || !phone) {
            showError('Tanpri ranpli tout champs ki obligatwa yo.');
            return;
        }

        if (!terms) {
            showError('Tanpri aksepte kondisyon itilizasyon yo.');
            return;
        }

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError('Tanpri antre yon adrès imèl valab.');
            return;
        }

        // Validate phone (basic)
        const phoneRegex = /^[\+\d\s\-\(\)]{8,20}$/;
        if (!phoneRegex.test(phone)) {
            showError('Tanpri antre yon nimewo telefòn valab.');
            return;
        }

        // Disable button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Chargement...</span>';

        // Collect form data
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        data.user_id = user.id;

        // Add payment method
        let payment_method = document.getElementById('paymentMethod').value;

        if(payment_method === 'moncash'){
            data.action = 'moncash_create_payment';
        } else {
            data.action = 'create_payment_intent';
        }

        try {
            const response = await fetch('/api/payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });

            if (response.status === 401) {
                showError('Tanpri konekte ou anvan ou abònman.');
                setTimeout(() => window.location.href = '/auth/login', 1500);
                return;
            }

            const result = await response.json();

            if (result.success) {
                showSuccess(result.message || 'Redirecting...');
                // Redirect to payment processing or dashboard
                if (result.payment_url) {
                    setTimeout(() => window.location.href = result.payment_url, 2000);
                } else {
                    setTimeout(() => window.location.href = '/user/me', 2000);
                }
            } else {
                showError(result.message || 'Failed to create payment');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-rocket"></i> <span>Abònman kounye a</span>';
            }
        } catch (err) {
            console.error('Subscription error:', err);
            showError('unexpected error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-rocket"></i> <span>Abònman kounye a</span>';
        }
    });

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
});
</script>