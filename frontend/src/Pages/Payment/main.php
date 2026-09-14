<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Online Payments</title>
    <link rel="shortcut icon" type="image/png" href="/media/images/favicon.png"/>
    <script type="text/javascript" src="/JS/base.js"></script> 
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/static/css/checkout.css" >
    <link rel="stylesheet" href="<?= BASE_URL?>/static/css/toast-notification.css" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" nomodule></script>
    <script src="https://kit.fontawesome.com/6f0be4257f.js" crossorigin="anonymous"></script>
    <script src="<?= BASE_URL?>/static/js/toast-notification.js" defer></script>
    
</head>
<body>
    <!-- Top Bar -->
    <div id="top-bar" class="no-margin flxDisp">
    </div>

    <!-- Search Page -->
    <div id="search-pg">
        <div class="search-pg-bar">
            <input type="text" placeholder="Search for news, topics, or authors...">
            <ion-icon name="search-outline"></ion-icon>
            <ion-icon name="close-outline" class="close-search"></ion-icon>
        </div>
    </div>

    <!-- Navigation Panel -->
    <div id="nav-panel">
        
    </div>

    <!-- Payment Content -->
    <div id="payment-root"></div>

    <!-- REAL API + STRIPE ELEMENTS -->
    <script type="text/babel">
        const { useState, useEffect } = React;

        // REAL KEYS
        const STRIPE_PUBLISHABLE_KEY = 'pk_live_51S5ug9DtuiWMVJmJbv8rxKPGvRpz9WPbnKTPLiPaDqf3JRFkmSfZUawO51HQf4mE2ZP9J0K4nQDIu3tamUzoT00700M2PpG7U8';
        const PAYPAL_CLIENT_ID = '';

        const stripe = Stripe(STRIPE_PUBLISHABLE_KEY);

        const PaymentPage = () => {
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
                if(!searchParams.get('f')){
                    window.history.back();
                }
                loadPaymentDetails();
            }, [searchParams]);
            
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
            const [user, setUser] = useState(()=>{
                return JSON.parse(sessionStorage.getItem('user') ?? '{}');
            });
            
            // Function to fetch order details
            const fetchOrderDetails = async (orderId) => {
                try {
                    const response = await fetch('/php/dbReader.php?q=order&id=' + orderId);
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
                const serviceType = searchParams.get('f');

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
                            setAmount(Number(25/*order.total_amount*/) * 100); // Price in cents

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
                }
            };
            
            // Stripe Elements when card method is selected
            useEffect(() => {
                if (activeMethod === 'card') {
                    const elements = stripe.elements();
                    const card = elements.create('card', {
                        style: {
                            base: {
                                fontSize: '16px',
                                color: '#333',
                                '::placeholder': { color: '#aaa' },
                            },
                            invalid: { color: '#fa755a' },
                        },
                    });
                    card.mount('#card-element');
                    setCardElement(card);
                }
                
                // Cleanup
                return () => {
                    if (cardElement) {
                        cardElement.unmount();
                    }
                };
            }, [activeMethod]);

            // Load PayPal SDK
            useEffect(() => {
                if (activeMethod === 'paypal' && !window.paypal) {
                    const script = document.createElement('script');
                    script.src = `https://www.paypal.com/sdk/js?client-id=${PAYPAL_CLIENT_ID}&currency=USD`;
                    script.async = true;
                    document.body.appendChild(script);
                }
            }, [activeMethod]);

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

            // =============== STRIPE CARD PAYMENT ===============
            const processCardPayment = async () => {
                try {
                    // Create payment intent
                    const res = await fetch('/konektem/api/payment', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ 
                            action: 'create_payment_intent', 
                            amount: amount, 
                            currency: 'usd', 
                            email: email 
                        })
                    });
                    
                    const result = await res.json();
        
                    if (!res.ok || !result.success) {
                        throw new Error(result.error || 'Failed to create payment intent');
                    }

                    const { clientSecret } = result;
                    
                    // Confirm card payment
                    const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: { name: cardName, email: email }
                        }
                    });

                    if (error) throw new Error(error.message);

                    // Payment successful - generate access
                    setPaymentStatus({ type: 'success', message: '✅ Payment succeeded! Processing your access...' });
                    
                    const serviceType = searchParams.get('f');
                    
                    if (serviceType === 'stream') {
                        const accessResponse = await fetch('generate-access.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ 
                                payment_id: paymentIntent.id,
                                email: email 
                            })
                        });

                        const accessData = await accessResponse.json();

                        if (accessData.success) {
                            setAccessData(accessData.access_data);
                            setShowSuccessForm(true);
                            await sendReceipt(accessData.access_data);
                        }
                    } else {
                        // For other service types, handle after payment
                        await handleSuccessfulPayment(paymentIntent.id, 'stripe');
                    }
                    
                } catch (error) {
                    console.error('Card payment error:', error);
                    throw error;
                }
            };

            // =============== PAYPAL PAYMENT ===============
            const processPaypalPayment = async () => {
                return new Promise((resolve, reject) => {
                    // Store user data for after return
                    localStorage.setItem('payment_email', email);
                    
                    const container = document.getElementById('paypal-button-container');
                    if (container) container.innerHTML = '';
                    
                    const newContainer = document.createElement('div');
                    newContainer.id = 'paypal-button-container-' + Date.now();
                    document.body.appendChild(newContainer);

                    window.paypal.Buttons({
                        createOrder: async () => {
                            const res = await fetch('/konektem/api/payment', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ 
                                    action: 'paypal_create_order', 
                                    amount: (amount / 100).toString(), 
                                    currency: 'USD', 
                                    email: email 
                                })
                            });
                            const data = await res.json();
                            return data.id;
                        },
                        onApprove: async (data) => {
                            const res = await fetch('/konektem/api/payment', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ action: 'paypal_capture_order', orderID: data.orderID })
                            });
                            const details = await res.json();
                            
                            if (res.ok) {
                                setPaymentStatus({ type: 'success', message: '✅ PayPal payment completed! Processing...' });
                                
                                // Handle successful payment
                                await handleSuccessfulPayment(data.orderID, 'paypal');
                                resolve();
                            } else {
                                reject(new Error('PayPal capture failed'));
                            }
                        },
                        onError: (err) => {
                            console.error('PayPal error:', err);
                            reject(new Error('PayPal payment failed'));
                        },
                        onCancel: () => reject(new Error('User canceled PayPal'))
                    }).render('#' + newContainer.id);
                });
            };

            // =============== MONCASH PAYMENT ===============
            const processMonCashPayment = async () => {
                try {
                    // Store user data for after return
                    localStorage.setItem('payment_email', email);
                    localStorage.setItem('payment_phone', phone);
                    if(!user){
                        showError("Login required");
                        return;
                    }

                    const res = await fetch('/konektem/api/payment', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ 
                            action: 'moncash_create_payment', 
                            user_id: user.id,
                            product_id: searchParams.get('id'),
                            amount: amount / 100, // Convert cents to dollars
                            phone: phone.replace(/\D/g, ''), 
                            email: user.email ?? email
                        })
                    });
                    if(res.status >= 200 && res.status < 300){
                        const data = await res.json();
                        if(!data.success){
                            showError(data.message);
                            throw new Error('MonCash request failed');
                        }
                        // Redirect to MonCash
                        window.location.href = data.payment_url;
                    } else {
                        console.log("moncash response code: " + res.status);
                        console.log(res.message || res.error);
                    }
                    setIsProcessing(false);
                } catch(e){
                    console.error(e);
                    setIsProcessing(false);
                }
            };

            // =============== APPLE PAY ===============
            const processApplePayPayment = async () => {
                if (!window.ApplePaySession || !ApplePaySession.canMakePayments()) {
                    throw new Error('Apple Pay not available');
                }

                const res = await fetch('/konektem/api/payment', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        action: 'create_payment_intent',
                        amount: amount, 
                        currency: 'usd', 
                        email: email 
                    })
                });
                
                const result = await res.json();
    
                if (!res.ok || !result.success) {
                    throw new Error(result.error || 'Failed to create payment intent');
                }

                const { clientSecret } = result;

                const { error, paymentIntent } = await stripe.confirmApplePayPayment(clientSecret, {
                    paymentMethodData: { billingDetails: { email } }
                });

                if (error) throw new Error(error.message);
                
                setPaymentStatus({ type: 'success', message: '✅ Apple Pay succeeded! Processing...' });
                await handleSuccessfulPayment(paymentIntent.id, 'applepay');
            };
            
            // Send receipt function
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
            
            // Render success forms
            if (showSuccessForm && accessData) {
                return <StreamingAccessForm accessData={accessData} emailSent={emailSent} />;
            } else if (showSuccessForm && ticketData) {
                return <EventTicketSuccess ticketData={ticketData} emailSent={emailSent} />;
            } else if (showSuccessForm && serviceData) {
                return <ServiceOrderSuccess serviceData={serviceData} emailSent={emailSent} />;
            }
            
            // Main payment form
            return (
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
                                    <span className="icon">🍎</span>
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
                                {activeMethod === 'card' && (
                                    <>
                                        <div className="form-group">
                                            <label>Card Details</label>
                                            <div id="card-element" style={{padding: '10px', border: '1px solid #ddd', borderRadius: '4px'}}></div>
                                        </div>
                                        <div className="form-group">
                                            <label>Cardholder Name</label>
                                            <input type="text" className="form-control" placeholder="John Doe"
                                                value={cardName} onChange={(e) => setCardName(e.target.value)} required />
                                        </div>
                                        <div className="form-group">
                                            <label>Email for receipt</label>
                                            <input type="email" className="form-control" placeholder="email@example.com"
                                                value={email} onChange={(e) => setEmail(e.target.value)} required />
                                        </div>
                                        <div className="form-group">
                                            <label>Amount ($)</label>
                                            <input type="number" className="form-control"
                                                min="0" step="0.01" value={(amount / 100).toFixed(2)} disabled required />
                                        </div>
                                    </>
                                )}
                                
                                {/* MonCash Form */}
                                {activeMethod === 'moncash' && (
                                    <>
                                        <div className="form-group">
                                            <label>Phone Number</label>
                                            <input type="tel" className="form-control" placeholder="509XXXXXXX" 
                                                value={phone} onChange={(e) => setPhone(e.target.value)} required />
                                        </div>
                                        <div className="form-group">
                                            <label>Email for receipt</label>
                                            <input type="email" className="form-control" placeholder="email@example.com"
                                                value={email} onChange={(e) => setEmail(e.target.value)} required />
                                        </div>
                                        <div className="form-group">
                                            <label>Amount ($)</label>
                                            <input type="number" className="form-control"
                                                min="0" step="0.01" value={(amount / 100).toFixed(2)} disabled required />
                                        </div>
                                        <p style={{marginTop: '10px', fontSize: '14px', color: '#666'}}>
                                            Enter your Mon Cash registered phone number. You will be redirected to MonCash to complete the payment.
                                        </p>
                                    </>
                                )}
                                
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
            );
        };

        // Service Order Success Component
        const ServiceOrderSuccess = ({ serviceData, emailSent }) => {
            useEffect(() => {
                fetch('/php/dbReader.php?q=updatePaymentStatus', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        service_name: serviceData.name,
                        order_id: serviceData.order_id,
                        status: 'paid'
                    })
                }).catch(error => console.log(error));
            }, [serviceData]);

            return (
                <div style={{ padding: '20px', maxWidth: '600px', margin: '0 auto', background: 'white', borderRadius: '10px' }}>
                    <h1 style={{ textAlign: 'center', color: '#28a745' }}>🎉 Payment Successful!</h1>
                    <h3 style={{ textAlign: 'center', marginBottom: '20px' }}>Your service order details:</h3>
                    {emailSent ? (
                        <div style={{ background: '#d4edda', color: '#155724', padding: '10px', borderRadius: '4px', textAlign: 'center', marginBottom: '20px' }}>
                            ✅ Receipt email sent to your email address!
                        </div>
                    ) : (
                        <div style={{ background: '#fff3cd', color: '#856404', padding: '10px', borderRadius: '4px', textAlign: 'center', marginBottom: '20px' }}>
                            ⚠️ Sending receipt email...
                        </div>
                    )}
                    <div style={{ textAlign: 'center' }}>
                        <h2>{serviceData.name}</h2>
                        <p><strong>Description:</strong> {serviceData.description}</p>
                        <p><strong>Total Paid:</strong> ${serviceData["price(usd)"]}</p>
                        <p><strong>Date:</strong> {serviceData.date}</p>
                    </div>
                </div>
            );
        };
        
        // Event Ticket Success Component
        const EventTicketSuccess = ({ ticketData, emailSent }) => {
            useEffect(() => {
                fetch('/php/dbReader.php?q=updatePaymentStatus', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        service_name: ticketData.title,
                        order_id: ticketData.order_id,
                        status: 'paid'
                    })
                }).catch(error => console.log(error));
            }, [ticketData]);
            
            return (
                <div style={{ padding: '20px', maxWidth: '600px', margin: '0 auto', background: 'white', borderRadius: '10px' }}>
                    <h1 style={{ textAlign: 'center', color: '#28a745' }}>🎉 Payment Successful!</h1>
                    <h3 style={{ textAlign: 'center', marginBottom: '20px' }}>Your event ticket details:</h3>
                    {emailSent ? (
                        <div style={{ background: '#d4edda', color: '#155724', padding: '10px', borderRadius: '4px', textAlign: 'center', marginBottom: '20px' }}>
                            ✅ Receipt email sent to your email address!
                        </div>
                    ) : (
                        <div style={{ background: '#fff3cd', color: '#856404', padding: '10px', borderRadius: '4px', textAlign: 'center', marginBottom: '20px' }}>
                            ⚠️ Sending receipt email...
                        </div>
                    )}
                    <div style={{ textAlign: 'center' }}>
                        <img src={ticketData.image_location} alt={ticketData.title} style={{ maxWidth: '100%', borderRadius: '8px', marginBottom: '20px' }} />
                        <h2>{ticketData.title}</h2>
                        <p><strong>Date:</strong> {ticketData.eventDate}</p>
                        <p><strong>Location:</strong> {ticketData.location}</p>
                        <p><strong>Tickets:</strong> {ticketData.tickets}</p>
                        <p><strong>Total Paid:</strong> ${ticketData["price(usd)"]}</p>
                    </div>
                </div>
            );
        };
        
        // Streaming Access Form Component
        const StreamingAccessForm = ({ accessData, emailSent }) => {
            const [isRegistered, setIsRegistered] = useState(false);
            const [fieldcopied, setFieldCopied] = useState([false, false, false, false]);

            const registerAccess = async () => {
                try {
                    const params = new URLSearchParams();
                    params.append('login', accessData.login);
                    params.append('email', accessData.email);
                    params.append('accessKey', accessData.access_key);
                    params.append('password', accessData.password);
                    
                    const response = await fetch('/php/dbReader.php?q=viewerReg', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: params
                    });

                    const result = await response.json();
                    setIsRegistered(result.success);
                    
                } catch (error) {
                    console.error('Registration error:', error);
                }
            };

            useEffect(() => {
                registerAccess();
            }, []);

            return (
                <div style={{ padding: '20px', maxWidth: '600px', margin: '0 auto', background: 'white', borderRadius: '10px' }}>
                    <h1 style={{ textAlign: 'center', color: '#28a745' }}>🎉 Payment Successful!</h1>
                    <h3 style={{ textAlign: 'center', marginBottom: '20px' }}>Your streaming access details:</h3>
                    
                    {emailSent && (
                        <div style={{ background: '#d4edda', color: '#155724', padding: '10px', borderRadius: '4px', textAlign: 'center', marginBottom: '20px' }}>
                            ✅ Receipt email sent!
                        </div>
                    )}
                    
                    <div style={{ marginBottom: '20px' }}>
                        {Object.entries(accessData).map(([key, value], index) => (
                            <div key={key} style={{ marginBottom: '15px' }}>
                                <label style={{ display: 'block', fontWeight: 'bold', marginBottom: '5px' }}>
                                    {key.charAt(0).toUpperCase() + key.slice(1)}:
                                </label>
                                <div style={{ display: 'flex', gap: '10px' }}>
                                    <input type="text" value={value} readOnly 
                                        style={{ flex: 1, padding: '8px', border: '1px solid #ddd', borderRadius: '4px', background: '#f9f9f9' }} />
                                    <button onClick={() => {
                                        navigator.clipboard.writeText(value);
                                        setFieldCopied(prev => {
                                            const newCopied = [...prev];
                                            newCopied[index] = true;
                                            return newCopied;
                                        });
                                        setTimeout(() => setFieldCopied([false, false, false, false]), 2000);
                                    }} style={{ padding: '8px 12px', background: '#007bff', color: 'white', border: 'none', borderRadius: '4px', cursor: 'pointer' }}>
                                        {fieldcopied[index] ? 'Copied!' : 'Copy'}
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>

                    {!isRegistered && (
                        <div style={{ textAlign: 'center', padding: '10px', background: '#e9ecef', borderRadius: '4px' }}>
                            <i className="fa-solid fa-spinner fa-spin"></i> Activating access...
                        </div>
                    )}

                    {isRegistered && (
                        <div style={{ background: '#d4edda', color: '#155724', padding: '10px', borderRadius: '4px', textAlign: 'center' }}>
                            ✅ Access successfully activated!
                        </div>
                    )}
                </div>
            );
        };
        
        const App = () => <PaymentPage />;
        const root = ReactDOM.createRoot(document.getElementById('payment-root'));
        root.render(<App />);
    </script>
</body>
</html>