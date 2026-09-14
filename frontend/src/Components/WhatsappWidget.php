<div class="whatsapp-widget">
    <div class="whatsapp-button" id="whatsappToggle">
        <i class="fab fa-whatsapp"></i>
    </div>
    
    <div class="whatsapp-popup" id="whatsappPopup">
        <div class="whatsapp-header">
            <h3>Contact Us on WhatsApp</h3>
            <button class="close-whatsapp" id="closeWhatsapp">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="whatsapp-content">
            <p>Send us a message directly on WhatsApp. We typically respond within minutes.</p>
            
            <div class="whatsapp-number">
                <select id="countryCode">
                    <?php foreach($whatsappData['countryCodes'] as $code){?>
                        <option value="<?= $code['code']?>" <?= $code['country'] === "US" ? 'selected' : ''?>>
                            <?= $code['code']?> (<?= $code['country']?>)
                        </option>
                    <?php } ?>
                </select>
                <input type="text" id="phoneNumber" placeholder="Phone number" value="<?= $whatsappData['phoneNumber'] ?>">
            </div>
            
            <div class="whatsapp-actions">
                <button class="whatsapp-btn whatsapp-primary" id="sendWhatsapp">
                    <i class="fab fa-whatsapp"></i> Send Message
                </button>
                <button class="whatsapp-btn whatsapp-secondary" id="cancelWhatsapp">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    /**
     * whatsapp handler
     * from initWhatsappWidget
     */
    let whatsappData = <?= json_encode($whatsappData) ?>;
    // Get elements
    const whatsappToggle = document.getElementById('whatsappToggle');
    const whatsappPopup = document.getElementById('whatsappPopup');
    const closeWhatsapp = document.getElementById('closeWhatsapp');
    const sendWhatsapp = document.getElementById('sendWhatsapp');
    const cancelWhatsapp = document.getElementById('cancelWhatsapp');
    const countryCode = document.getElementById('countryCode');
    const phoneNumber = document.getElementById('phoneNumber');
    
    // Toggle WhatsApp popup
    if (whatsappToggle) {
        whatsappToggle.addEventListener('click', function() {
            whatsappPopup.style.display = 'flex';
        });
    }
    
    // Close WhatsApp popup
    if (closeWhatsapp) {
        closeWhatsapp.addEventListener('click', function() {
            whatsappPopup.style.display = 'none';
        });
    }
    
    // Cancel button
    if (cancelWhatsapp) {
        cancelWhatsapp.addEventListener('click', function() {
            whatsappPopup.style.display = 'none';
        });
    }
    
    // Send WhatsApp message
    if (sendWhatsapp) {
        sendWhatsapp.addEventListener('click', function() {
            const fullNumber = countryCode.value + phoneNumber.value.replace(/\D/g, '');
            const message = encodeURIComponent(whatsappData.welcomeMessage);
            const whatsappUrl = `https://wa.me/${fullNumber}?text=${message}`;
            
            // Open WhatsApp in a new tab
            window.open(whatsappUrl, '_blank');
            
            // Close the popup
            whatsappPopup.style.display = 'none';
        });
    }
</script>