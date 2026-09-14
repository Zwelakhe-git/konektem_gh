<script>
    const axios = require('axios'); // Make sure to install: npm install axios

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
        const response = await fetch(webhookUrl, payload, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Webhook-Secret': 'test-secret-key'
            },
            timeout: 30000 // 30 seconds
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
        const result = await sendMockMonCashWebhook('ORDER-12345', 150.00, true);
        console.log('Result:', result);
    })();
</script>