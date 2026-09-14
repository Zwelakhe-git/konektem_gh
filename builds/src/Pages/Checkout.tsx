import { useState, useEffect } from 'react';
import { StripeCheckout } from '../Components/StripeCheckout';
import { MoncashCheckout } from '../Components/MoncashCheckout';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { byPrefixName } from '@awesome.me/kit-6f0be4257f/icons';

// REAL KEYS
const STRIPE_PUBLISHABLE_KEY = 'pk_live_51S5ug9DtuiWMVJmJbv8rxKPGvRpz9WPbnKTPLiPaDqf3JRFkmSfZUawO51HQf4mE2ZP9J0K4nQDIu3tamUzoT00700M2PpG7U8';
const PAYPAL_CLIENT_ID = '';

interface OrderToken{
    amount: string,
    order_id: string | Number,
    order_number: string | undefined,
    email: string | undefined,
};

function Index(){
    const [paymentToken, setPaymentToken] = useState('');
    const [tokenPayload, setTokenPayload] = useState<OrderToken|undefined>();
    const [cardName, setCardName] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [activeMethod, setActiveMethod] = useState('card');
    const [isProcessing, setIsProcessing] = useState(false);
    const [paymentStatus, setPaymentStatus] = useState({ type: '', message: '' });
    const [cardElement, setCardElement] = useState(null);
    const [amount, setAmount] = useState(0.00);
    const [showSuccessForm, setShowSuccessForm] = useState(false);
    const [accessData, setAccessData] = useState(null);
    const [ticketData, setTicketData] = useState(null);
    const [serviceData, setServiceData] = useState(null);
    const [emailSent, setEmailSent] = useState(false);
    const [transactionInfo, setTransactionInfo] = useState({});

    const [searchParams] = useState(new URLSearchParams(window.location.search));
        
    // Handle return from payment gateway (MonCash, PayPal, etc.)
    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const paymentStatus = urlParams.get('p');
        const orderId = urlParams.get('orderId');
        const paymentMethod = urlParams.get('method');
        
        if (paymentStatus === 'payment-success') {
            setPaymentStatus({ type: 'success', message: '✅ Payment completed successfully!' });
            
            // Get stored user data if available
            const storedEmail = localStorage.getItem('payment_email');
            const storedPhone = localStorage.getItem('payment_phone');
            
            if (storedEmail) setEmail(storedEmail);
            if (storedPhone) setPhone(storedPhone);
            
            // Fetch order details if needed
            if (orderId) {
                fetchOrderDetails(orderId);
            }
            
            // Generate access based on payment method and order type
            handleSuccessfulPayment(orderId, paymentMethod);
            
            // Clear stored data
            localStorage.removeItem('payment_email');
            localStorage.removeItem('payment_phone');
            
        } else if (paymentStatus === 'payment-cancel') {
            setPaymentStatus({ type: 'error', message: '❌ Payment was cancelled' });
        }
    }, []);
    
    // Load payment details based on URL parameters
    useEffect(() => {
        if(!searchParams.get('token')){
            window.history.back();
        }
        /*if(!searchParams.get('f')){
            window.history.back();
        }*/
        loadPaymentDetails();
    }, [searchParams]);

    const base64UrlDecode = (str: string) : string | null => {
        try {
            let base64 = str.replace(/-/g, '+').replace(/_/g, '/');
            while(base64.length % 4){
                base64 += '=';
            }
            return atob(base64);
        } catch(e){
            return null;
        }
        return null;
    }

    // Function to fetch order details
    const fetchOrderDetails = async (orderId) => {
        try {
            const response = await fetch('/api/data/order' + orderId);
            const orderData = await response.json();
            if (orderData) {
                setTransactionInfo(orderData);
            }
        } catch (error) {
            console.error('Error fetching order details:', error);
        }
    };

    // Handle successful payment for all methods
    const handleSuccessfulPayment = async (orderId, paymentMethod) => {
        const serviceType = searchParams.get('f');
        
        try {
            let accessResult = null;
            
            if (serviceType === 'stream') {
                // Generate streaming access
                const accessResponse = await fetch('/paymentApplication/generate-access.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        payment_id: paymentMethod + '_' + (orderId || Date.now()),
                        email: email || transactionInfo.email || 'customer@example.com'
                    })
                });

                accessResult = await accessResponse.json();

                if (accessResult.success) {
                    setAccessData(accessResult.access_data);
                    setShowSuccessForm(true);
                    
                    // Send receipt
                    await sendReceipt(accessResult.access_data);
                }
            } else if (serviceType === 'event' && transactionInfo) {
                // Create ticket data
                const ticketInfo = {
                    "tickets": Number(transactionInfo.total_amount) / Number(transactionInfo.price),
                    "title": transactionInfo.title,
                    "eventDate": transactionInfo.eventDate,
                    "location": transactionInfo.location,
                    "transaction_id": paymentMethod + '_' + Date.now(),
                    "price(usd)": amount / 100,
                    "image_location": transactionInfo.image_location,
                    "order_id": orderId || searchParams.get('orderid')
                };

                setTicketData(ticketInfo);
                setShowSuccessForm(true);
                await sendReceipt(ticketInfo);
                
            } else if (serviceType === 'service' && transactionInfo) {
                // Create service data
                const serviceInfo = {
                    "name": transactionInfo.name || "Service",
                    "description": transactionInfo.description || "Service description",
                    "price(usd)": amount / 100,
                    "date": new Date().toISOString().split('T')[0],
                    "order_id": orderId || searchParams.get('orderid')
                };
                
                setServiceData(serviceInfo);
                setShowSuccessForm(true);
                await sendReceipt(serviceInfo);
            }
        } catch (error) {
            console.error('Error handling successful payment:', error);
        }
    };
    
    // Load payment details
    const loadPaymentDetails = async () => {
        setPaymentToken(searchParams.get('token') ?? '');
        if(!paymentToken){
            //window.showError("Invalid token");
        }
        setTokenPayload(JSON.parse(base64UrlDecode(paymentToken.split('.')[1]) || '{}') as OrderToken);
        if(!tokenPayload){
            return;
        }
        setAmount(parseFloat(tokenPayload.amount));
        setEmail(tokenPayload.email ?? '');
        /*const serviceType = searchParams.get('f');

        if(serviceType === 'stream'){
            setAmount(100); // $1.00 in cents
            setTransactionInfo({
                "email": "",
            });
        } else {
            let response = await fetch('/php/dbReader.php?q=orders');
            let orders = await response.json();
            const order = orders.find(imt => imt.id == searchParams.get('orderid'));
            
            if(order){
                const item_name = order.item_name;
                
                if(item_name === 'event' && searchParams.get('f') === 'event'){
                    response = await fetch('/php/dbReader.php?r=events');
                }
                else if(item_name === 'service' && searchParams.get('f') === 'service'){
                    response = await fetch('/php/dbReader.php?r=services');
                } else {
                    alert('Unable to determine order item');
                    window.location.href = '/';
                    return;
                }
                
                let data = await response.json();
                const item = data.find(itm => itm.id == order.item_id);
                
                if (item) {
                    setAmount(Number(25) * 100); // Price in cents

                    setTransactionInfo(prev => {
                        const next = { ...(prev || {}), ...(order || {}), ...(item || {}) };
                        return next;
                    });
                    
                    // Pre-fill email if available
                    if (order.email) setEmail(order.email);
                    
                } else {
                    alert("Order item not found");
                    window.location.href = "/?p=events";
                }
            }
        }*/
    };

    // Handle form submission
    const handleSubmit = async (e) => {
        e.preventDefault();
        
        // Validate form based on payment method
        if (activeMethod === 'card') {
            if (!cardName || !email) {
                setPaymentStatus({ type: 'error', message: 'Please fill in all required fields' });
                return;
            }
        }
        
        if (activeMethod === 'moncash') {
            if (!phone || phone.replace(/\D/g, '').length < 8) {
                setPaymentStatus({ type: 'error', message: 'Please enter a valid phone number' });
                return;
            }
            if (!email) {
                setPaymentStatus({ type: 'error', message: 'Please enter your email for receipt' });
                return;
            }
        }
        
        if (amount <= 0) {
            setPaymentStatus({ type: 'error', message: 'Invalid amount' });
            return;
        }
        
        let proceed = window.confirm(`Proceed with payment for ${searchParams.get('f')}: $${(amount / 100).toFixed(2)}?`);
        if(!proceed) return;
        
        setIsProcessing(true);
        setPaymentStatus({ type: '', message: '' });

        try {
            if (activeMethod === 'card') {
                await processCardPayment();
            } else if (activeMethod === 'paypal') {
                await processPaypalPayment();
            } else if (activeMethod === 'moncash') {
                await processMonCashPayment();
            } else if (activeMethod === 'applepay') {
                await processApplePayPayment();
            }
        } catch (error) {
            console.error('Payment error:', error);
            setPaymentStatus({ type: 'error', message: `Payment failed: ${error.message || 'Unknown error'}` });
            setIsProcessing(false);
        }
    };

    const sendReceipt = async (serviceData) => {
        try {
            let response = await fetch('/php/payment.php?q=sendreceipt&f=' + searchParams.get('f'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    customer_name: transactionInfo.first_name + " " + transactionInfo.last_name,
                    customer_email: email || transactionInfo.email,
                    service: serviceData,
                    payment_id: 'payment_' + Date.now(),
                    date: new Date().toISOString().slice(0, 19).replace('T', ' ')
                })
            });
            let data = await response.json();
            if(data.status === 'success'){
                setEmailSent(true);
                console.log('Receipt sent successfully');
            }
        } catch (error) {
            console.log('Error sending receipt:', error);
        }
    };
    return (
        <>
        <div className="payment-container" >
            <div className="header">
                <h1>Secure Online Payments</h1>
                <p>Fast and secure payment processing for your purchases</p>
            </div>
            
            {/* Payment status message */}
            {paymentStatus.message && (
                <div className={`payment-status ${paymentStatus.type}`} style={{margin: '20px auto', maxWidth: '800px'}}>
                    {paymentStatus.message}
                </div>
            )}
            
            <div className="payment-content">
                <div className="payment-form">
                    <h2 className="section-title">Payment Method</h2>
                    <div className="payment-methods">
                        <div className={`payment-method ${activeMethod === 'card' ? 'active' : ''}`} onClick={() => setActiveMethod('card')}>
                            <span className="icon">💳</span>
                            <span className="name">Credit Card</span>
                        </div>
                        <div className={`payment-method ${activeMethod === 'paypal' ? 'active' : ''}`} onClick={() => setActiveMethod('paypal')}>
                            <span className="icon">📱</span>
                            <span className="name">PayPal</span>
                        </div>
                        <div className={`payment-method ${activeMethod === 'moncash' ? 'active' : ''}`} onClick={() => setActiveMethod('moncash')}>
                            <span className="icon">💸</span>
                            <span className="name"><span className="moncash-logo">Mon Cash</span></span>
                        </div>
                        <div className={`payment-method ${activeMethod === 'applepay' ? 'active' : ''}`} onClick={() => setActiveMethod('applepay')}>
                            <i className="fa-brands fa-apple icon"></i>
                            <span className="name">Apple Pay</span>
                        </div>
                    </div>
                    
                    <form onSubmit={handleSubmit}>
                        {/* PayPal container */}
                        {activeMethod === 'paypal' && (
                            <div className="form-group">
                                <label>PayPal Payment</label>
                                <div id="paypal-button-container"></div>
                            </div>
                        )}
                        
                        {/* Credit Card Form */}
                        {activeMethod === 'card' && <StripeCheckout />}
                        
                        {/* MonCash Form */}
                        {activeMethod === 'moncash' && <MoncashCheckout />}
                        
                        {/* Apple Pay Info */}
                        {activeMethod === 'applepay' && (
                            <div className="form-group">
                                <p>After clicking "Pay Now", the Apple Pay window will open to complete your payment.</p>
                            </div>
                        )}
                        
                        {/* Submit Button (not shown for PayPal as it has its own) */}
                        {activeMethod !== 'paypal' && (
                            <button type="submit" className="btn" disabled={isProcessing}>
                                {isProcessing ? <span className="loading"></span> : 'Pay Now'}
                            </button>
                        )}
                        
                        <div className="secure-notice">🔒 Your data is securely protected</div>
                    </form>
                </div>
                
                {/* Order Summary */}
                <div className="payment-summary">
                    <h2 className="section-title">Order Summary</h2>
                    <div className="summary-total">
                        <span>Total</span>
                        <span>${(amount / 100).toFixed(2)}</span>
                    </div>
                    <div className="info-box">
                        <h3>Customer Support</h3>
                        <p>Phone: (509) 3122 3337</p>
                        <p>Email: konektemtv@gmail.com</p>
                        <p>Hours: Lendi - Dimanch, 9am-6pm</p>
                    </div>
                </div>
            </div>
        </div>
        </>
    );
}