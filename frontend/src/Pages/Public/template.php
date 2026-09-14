<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MP3 Cover Art</title>
    <script>
        /* ========================================
   TRACK ITEM - KONEKTEM
   Professional, responsive, and polished
   ======================================== */

.track-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 1rem;
    background: rgba(247, 249, 252, 0.85);
    border-radius: 0.75rem;
    margin-bottom: 0.75rem;
    border: 1px solid transparent;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    width: 100%;
    backdrop-filter: blur(4px);
}

.track-item:hover {
    background: rgba(241, 243, 245, 0.95);
    border-color: #e2e8f0;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

/* ------ Image ------ */
.track-item__image {
    flex: 0 0 60px;
    width: 60px;
    height: 60px;
    border-radius: 0.5rem;
    overflow: hidden;
    position: relative;
    background: #e9ecef;
}

.track-item__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.track-item__fallback {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #e9ecef, #dee2e6);
    color: #adb5bd;
    font-size: 1.5rem;
}

/* ------ Info ------ */
.track-item__info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.track-item__header {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.track-item__title {
    font-weight: 600;
    font-size: 0.95rem;
    color: #0b1a2f;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}

.track-item__artist {
    font-size: 0.8rem;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ------ Controls ------ */
.track-item__controls {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.track-item__play {
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    background: #1976d2;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.75rem;
    box-shadow: 0 2px 8px rgba(25, 118, 210, 0.25);
}

.track-item__play:hover {
    background: #1565c0;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(25, 118, 210, 0.35);
}

.track-item__play:active {
    transform: scale(0.95);
}

.track-item__play.playing {
    background: #d32f2f;
    box-shadow: 0 2px 8px rgba(211, 47, 47, 0.25);
}

.track-item__play.playing:hover {
    background: #c62828;
    box-shadow: 0 4px 12px rgba(211, 47, 47, 0.35);
}

.track-item__progress-wrap {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 80px;
}

.track-item__progress-bar {
    flex: 1;
    height: 4px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    transition: height 0.2s ease;
}

.track-item__progress-bar:hover {
    height: 6px;
}

.track-item__progress-fill {
    height: 100%;
    background: #1976d2;
    border-radius: 4px;
    width: 0%;
    transition: width 0.1s linear;
}

.track-item__time {
    font-size: 0.7rem;
    color: #94a3b8;
    font-variant-numeric: tabular-nums;
    min-width: 36px;
    text-align: right;
}

/* ------ Actions ------ */
.track-item__actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.25rem;
    flex-wrap: wrap;
}

.track-item__action {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.4rem;
    border: none;
    background: transparent;
    color: #94a3b8;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border-radius: 0.25rem;
    font-weight: 500;
}

.track-item__action:hover {
    color: #0b1a2f;
    background: rgba(0, 0, 0, 0.04);
}

.track-item__action i {
    font-size: 0.7rem;
    transition: all 0.2s ease;
}

.track-item__action--download:hover {
    color: #1976d2;
}

.track-item__action--download:hover i {
    transform: translateY(2px);
}

.track-item__action--like:hover {
    color: #d32f2f;
}

.track-item__action--like:hover i {
    transform: scale(1.15);
}

.track-item__action--like.liked {
    color: #d32f2f;
}

.track-item__action--like.liked i {
    font-weight: 900;
}

.track-item__action--share:hover {
    color: #0ea5e9;
}

.track-item__action--share:hover i {
    transform: rotate(-15deg);
}

.track-item__action span {
    min-width: 12px;
}

/* ------ Owner ------ */
.track-item__owner {
    position: absolute;
    right: 0.5rem;
    bottom: 0.25rem;
    font-size: 0.5rem;
    color: #adb5bd;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    opacity: 0.5;
}

/* ========================================
   RESPONSIVE
   ======================================== */

/* Tablet */
@media (max-width: 768px) {
    .track-item {
        padding: 0.6rem 0.75rem;
        gap: 0.75rem;
        border-radius: 0.5rem;
    }

    .track-item__image {
        flex: 0 0 50px;
        width: 50px;
        height: 50px;
    }

    .track-item__title {
        font-size: 0.85rem;
    }

    .track-item__artist {
        font-size: 0.7rem;
    }

    .track-item__play {
        flex: 0 0 28px;
        width: 28px;
        height: 28px;
        font-size: 0.65rem;
    }

    .track-item__progress-wrap {
        min-width: 60px;
    }

    .track-item__actions {
        gap: 0.3rem;
    }

    .track-item__action {
        font-size: 0.65rem;
        padding: 0.15rem 0.3rem;
    }

    .track-item__action i {
        font-size: 0.6rem;
    }
}

/* Mobile */
@media (max-width: 480px) {
    .track-item {
        flex-direction: column;
        align-items: stretch;
        padding: 0.5rem;
        gap: 0.5rem;
        border-radius: 0.5rem;
    }

    .track-item__image {
        flex: 0 0 auto;
        width: 100%;
        height: 140px;
        border-radius: 0.5rem;
    }

    .track-item__image img {
        border-radius: 0.5rem;
    }

    .track-item__info {
        gap: 0.3rem;
    }

    .track-item__header {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .track-item__title {
        font-size: 0.8rem;
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .track-item__artist {
        font-size: 0.65rem;
        white-space: nowrap;
    }

    .track-item__controls {
        gap: 0.5rem;
    }

    .track-item__play {
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        font-size: 0.8rem;
    }

    .track-item__progress-wrap {
        min-width: 50px;
    }

    .track-item__actions {
        justify-content: space-around;
        margin-top: 0.15rem;
        padding-top: 0.3rem;
        border-top: 1px solid #e9ecef;
    }

    .track-item__action {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
    }

    .track-item__owner {
        position: static;
        font-size: 0.45rem;
        text-align: right;
        opacity: 0.4;
        margin-top: 0.15rem;
    }
}

/* ========================================
   DARK THEME
   ======================================== */

[data-theme="dark"] .track-item {
    background: rgba(30, 41, 59, 0.85);
}

[data-theme="dark"] .track-item:hover {
    background: rgba(51, 65, 85, 0.95);
    border-color: #334155;
}

[data-theme="dark"] .track-item__title {
    color: #e2e8f0;
}

[data-theme="dark"] .track-item__artist {
    color: #94a3b8;
}

[data-theme="dark"] .track-item__progress-bar {
    background: #334155;
}

[data-theme="dark"] .track-item__action {
    color: #64748b;
}

[data-theme="dark"] .track-item__action:hover {
    color: #e2e8f0;
    background: rgba(255, 255, 255, 0.05);
}

[data-theme="dark"] .track-item__time {
    color: #64748b;
}

[data-theme="dark"] .track-item__owner {
    color: #475569;
}

[data-theme="dark"] .track-item__image {
    background: #1e293b;
}

[data-theme="dark"] .track-item__fallback {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    color: #475569;
}

[data-theme="dark"] .track-item__actions {
    border-color: #334155;
}

/* ========================================
   ANIMATIONS
   ======================================== */

@keyframes trackItemPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.track-item__play.playing i {
    animation: trackItemPulse 1.5s ease-in-out infinite;
}

/* Like button animation */
@keyframes likePop {
    0% { transform: scale(1); }
    50% { transform: scale(1.3); }
    100% { transform: scale(1); }
}

.track-item__action--like.liked i {
    animation: likePop 0.3s ease;
}

/* Download button animation */
@keyframes downloadBounce {
    0% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
    100% { transform: translateY(0); }
}

.track-item__action--download:active i {
    animation: downloadBounce 0.3s ease;
}
    </script>
</head>
<body>
    <script>

/**
 * Sends a mock MonCash webhook for testing purposes
 * @param {string} orderId - The order ID
 * @param {number|string} amount - The payment amount
 * @param {boolean} success - Whether the payment should be successful (default: true)
 * @returns {Promise<Object>} - Response object with details
 */
async function sendMockMonCashWebhook(orderId, amount, success = true) {
    try {
        // Generate a random transaction ID
        const transactionId = generateMonCashTransactionId();
        
        // Build the mock webhook payload
        const payload = {
            transactionId: transactionId,
            orderId: orderId,
            payment: {
                transaction_id: transactionId,
                cost: amount,
                message: success ? 'successful' : 'failed',
                payer: {
                    phone: '50912345678',
                    email: 'test@example.com',
                    name: 'Test User'
                },
                status: success ? 'COMPLETED' : 'FAILED'
            },
            timestamp: new Date().toISOString().replace('T', ' ').slice(0, 19)
        };
        
        // Add more realistic MonCash response fields for successful payments
        if (success) {
            payload.payment.transaction_reference = 'REF_' + generateUniqueId();
            payload.payment.payment_method = 'MonCash';
        }
        
        console.log('Sending mock MonCash webhook:', JSON.stringify(payload, null, 2));
        
        // Get the webhook URL from environment or construct it
        const webhookUrl = 
                          `/api/payment/moncash/webhook`;
        
        // Send the webhook
        const response = await fetch(webhookUrl, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Webhook-Secret': 'test-secret-key'
            },
            body: JSON.stringify(payload),
        });
        
        console.log(`Mock webhook response: HTTP ${response.status}`, response.data);
        
        return {
            success: true,
            http_code: response.status,
            response: response.data,
            payload: payload,
            transaction_id: transactionId
        };
        
    } catch (error) {
        if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.error('Mock webhook error response:', error.response.status, error.response.data);
            return {
                success: false,
                http_code: error.response.status,
                response: error.response.data,
                message: `Server responded with ${error.response.status}: ${error.response.statusText}`
            };
        } else if (error.request) {
            // The request was made but no response was received
            console.error('Mock webhook no response:', error.request);
            return {
                success: false,
                message: 'No response received from webhook endpoint',
                error: error.message
            };
        } else {
            // Something happened in setting up the request that triggered an Error
            console.error('Error sending mock MonCash webhook:', error.message);
            return {
                success: false,
                message: `Error: ${error.message}`
            };
        }
    }
}

/**
 * Generates a random MonCash transaction ID
 * @returns {string} - Format: MC + timestamp + 6 random chars
 */
function generateMonCashTransactionId() {
    const timestamp = Date.now().toString(36).toUpperCase();
    const random = Math.random().toString(36).substring(2, 8).toUpperCase();
    return `MC${timestamp}${random}`;
}

/**
 * Generates a unique reference ID
 * @returns {string} - Format: 10 character alphanumeric
 */
function generateUniqueId() {
    return Math.random().toString(36).substring(2, 12).toUpperCase();
}

(async () => {
        const orderId = (new URLSearchParams(window.location.search)).get('order_id');
        const result = await sendMockMonCashWebhook(orderId, 150.00, true);
        console.log('Result:', result);
    })();
</script>
</body>
</html>