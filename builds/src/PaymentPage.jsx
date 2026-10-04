import React, { useEffect, useMemo, useState } from 'react';

const DEFAULT_STRIPE_KEY = 'pk_live_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

function decodeTokenPayload(token) {
  if (!token || typeof token !== 'string') return null;

  try {
    const [, payload] = token.split('.');
    if (!payload) return null;

    const normalized = payload.replace(/-/g, '+').replace(/_/g, '/');
    const padded = normalized + '='.repeat((4 - (normalized.length % 4)) % 4);
    const decoded = atob(padded);
    return JSON.parse(decoded);
  } catch (error) {
    console.error('Failed to decode payment token:', error);
    return null;
  }
}

export default function PaymentPage({
  stripeKey = DEFAULT_STRIPE_KEY,
  paypalClientId = '',
  paymentApiUrl = '/konektem/api/payment',
  orderApiUrl = '/php/dbReader.php',
  accessApiUrl = '/paymentApplication/generate-access.php',
  receiptApiUrl = '/php/payment.php',
  autoRedirectOnInvalidToken = true,
}) {
  const params = useMemo(() => new URLSearchParams(window.location.search), []);

  const [cardName, setCardName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [activeMethod, setActiveMethod] = useState('card');
  const [isProcessing, setIsProcessing] = useState(false);
  const [paymentStatus, setPaymentStatus] = useState({ type: '', message: '' });
  const [cardElement, setCardElement] = useState(null);
  const [amount, setAmount] = useState(0);
  const [showSuccessForm, setShowSuccessForm] = useState(false);
  const [accessData, setAccessData] = useState(null);
  const [ticketData, setTicketData] = useState(null);
  const [serviceData, setServiceData] = useState(null);
  const [emailSent, setEmailSent] = useState(false);
  const [transactionInfo, setTransactionInfo] = useState({});
  const [user, setUser] = useState(() => {
    try {
      return decodeTokenPayload(localStorage.token) || "{}";
    } catch {
      return {};
    }
  });

  // deprecated
  useEffect(() => {
    const paymentStatusParam = params.get('p');
    const orderId = params.get('orderId');
    const paymentMethod = params.get('method');

    if (paymentStatusParam === 'payment-success') {
      setPaymentStatus({ type: 'success', message: '✅ Payment completed successfully!' });

      const storedEmail = localStorage.getItem('payment_email');
      const storedPhone = localStorage.getItem('payment_phone');

      if (storedEmail) setEmail(storedEmail);
      if (storedPhone) setPhone(storedPhone);

      if (orderId) {
        fetchOrderDetails(orderId).catch(console.error);
      }

      handleSuccessfulPayment(orderId, paymentMethod).catch(console.error);

      localStorage.removeItem('payment_email');
      localStorage.removeItem('payment_phone');
    } else if (paymentStatusParam === 'payment-cancel') {
      setPaymentStatus({ type: 'error', message: '❌ Payment was cancelled' });
    }
  }, [params]);

  useEffect(() => {
    const token = params.get('token');

    if (!token) {
      if (autoRedirectOnInvalidToken) {
        window.history.back();
      }
      return;
    }

    const payload = decodeTokenPayload(token);
    if (!payload) {
      if (autoRedirectOnInvalidToken) {
        window.history.back();
      }
      return;
    }

    const parsedAmount = Number(payload.amount || 0);
    setAmount(parsedAmount);
    setEmail(payload.email || '');
    setTransactionInfo((prev) => ({ ...prev, email: payload.email || prev.email || '' }));
  }, [params, autoRedirectOnInvalidToken]);

  useEffect(() => {
    if (activeMethod !== 'card' || !window.Stripe || !stripeKey || !document.getElementById('card-element')) {
      return;
    }

    const stripe = window.Stripe(stripeKey);
    const elements = stripe.elements();
    const newCard = elements.create('card');
    newCard.mount('#card-element');
    setCardElement(newCard);

    return () => {
      if (newCard && newCard.unmount) {
        newCard.unmount();
      }
    };
  }, [activeMethod, stripeKey]);

  useEffect(() => {
    if (activeMethod !== 'paypal' || !paypalClientId || !window.paypal) {
      return;
    }

    const paypalContainer = document.getElementById('paypal-button-container');
    if (!paypalContainer) return;

    paypalContainer.innerHTML = '';
    window.paypal.Buttons({
      createOrder: async () => {
        const res = await fetch(paymentApiUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'paypal_create_order',
            amount: (amount / 100).toString(),
            currency: 'USD',
            email,
          }),
        });

        const data = await res.json();
        return data.id;
      },
      onApprove: async (data) => {
        const res = await fetch(paymentApiUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'paypal_capture_order', orderID: data.orderID }),
        });

        if (res.ok) {
          setPaymentStatus({ type: 'success', message: '✅ PayPal payment completed! Processing...' });
          await handleSuccessfulPayment(data.orderID, 'paypal');
        } else {
          setPaymentStatus({ type: 'error', message: 'PayPal capture failed' });
        }
      },
      onError: () => {
        setPaymentStatus({ type: 'error', message: 'PayPal payment failed' });
      },
      onCancel: () => {
        setPaymentStatus({ type: 'error', message: 'User cancelled PayPal payment' });
      },
    }).render('#paypal-button-container');
  }, [activeMethod, amount, email, paymentApiUrl, paypalClientId]);

  useEffect(() => {
    if (activeMethod !== 'paypal' || !paypalClientId || window.paypal) {
      return;
    }

    const script = document.createElement('script');
    script.src = `https://www.paypal.com/sdk/js?client-id=${paypalClientId}&currency=USD`;
    script.async = true;
    document.body.appendChild(script);

    return () => {
      if (script.parentNode) script.parentNode.removeChild(script);
    };
  }, [activeMethod, paypalClientId]);

  // remove
  const fetchOrderDetails = async (orderId) => {
    try {
      const res = await fetch(`${orderApiUrl}?q=order&id=${orderId}`);
      const json = await res.json();
      if (json) setTransactionInfo(json);
    } catch (error) {
      console.error('Error fetching order details:', error);
    }
  };

  const handleSuccessfulPayment = async (orderId, paymentMethod) => {
    const serviceType = params.get('f');

    try {
      if (serviceType === 'stream') {
        const accessResponse = await fetch(accessApiUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            payment_id: `${paymentMethod}_${orderId || Date.now()}`,
            email: email || transactionInfo.email || 'customer@example.com',
          }),
        });

        const accessResult = await accessResponse.json();

        if (accessResult.success) {
          setAccessData(accessResult.access_data);
          setShowSuccessForm(true);
          await sendReceipt(accessResult.access_data);
        }
      } else if (serviceType === 'event' && transactionInfo) {
        const ticketInfo = {
          tickets: Number(transactionInfo.total_amount) / Number(transactionInfo.price || 1),
          title: transactionInfo.title,
          eventDate: transactionInfo.eventDate,
          location: transactionInfo.location,
          transaction_id: `${paymentMethod}_${Date.now()}`,
          'price(usd)': amount / 100,
          image_location: transactionInfo.image_location,
          order_id: orderId || params.get('orderid'),
        };

        setTicketData(ticketInfo);
        setShowSuccessForm(true);
        await sendReceipt(ticketInfo);
      } else if (serviceType === 'service' && transactionInfo) {
        const serviceInfo = {
          name: transactionInfo.name || 'Service',
          description: transactionInfo.description || 'Service description',
          'price(usd)': amount / 100,
          date: new Date().toISOString().split('T')[0],
          order_id: orderId || params.get('orderid'),
        };

        setServiceData(serviceInfo);
        setShowSuccessForm(true);
        await sendReceipt(serviceInfo);
      }
    } catch (error) {
      console.error('Error handling successful payment:', error);
    }
  };

  const sendReceipt = async (payload) => {
    try {
      const response = await fetch(`${receiptApiUrl}?q=sendreceipt&f=${params.get('f') || 'service'}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          customer_name: `${transactionInfo.first_name || ''} ${transactionInfo.last_name || ''}`.trim() || 'Customer',
          customer_email: email || transactionInfo.email,
          service: payload,
          payment_id: `payment_${Date.now()}`,
          date: new Date().toISOString().slice(0, 19).replace('T', ' '),
        }),
      });

      const data = await response.json();
      if (data.status === 'success') {
        setEmailSent(true);
      }
    } catch (error) {
      console.error('Error sending receipt:', error);
    }
  };

  const processCardPayment = async () => {
    if (!window.Stripe) {
      throw new Error('Stripe is not loaded');
    }

    const res = await fetch(paymentApiUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'create_payment_intent', amount, currency: 'usd', email }),
    });

    const result = await res.json();
    if (!res.ok || !result.success) {
      throw new Error(result.error || 'Failed to create payment intent');
    }

    const { clientSecret } = result;
    const stripe = window.Stripe(stripeKey);

    const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
      payment_method: {
        card: cardElement,
        billing_details: { name: cardName, email },
      },
    });

    if (error) throw new Error(error.message);

    setPaymentStatus({ type: 'success', message: '✅ Payment succeeded! Processing your access...' });

    if (params.get('f') === 'stream') {
      const accessResponse = await fetch(accessApiUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ payment_id: paymentIntent.id, email }),
      });

      const accessDataResult = await accessResponse.json();
      if (accessDataResult.success) {
        setAccessData(accessDataResult.access_data);
        setShowSuccessForm(true);
        await sendReceipt(accessDataResult.access_data);
      }
    } else {
      await handleSuccessfulPayment(paymentIntent.id, 'stripe');
    }
  };

  const processMonCashPayment = async () => {
    try {
      localStorage.setItem('payment_email', email);
      localStorage.setItem('payment_phone', phone);

      if (!user || !user.id) {
        throw new Error('Login required');
      }

      const res = await fetch(paymentApiUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'moncash_create_payment',
          user_id: user.id,
          product_id: params.get('id'),
          amount: amount / 100,
          phone: phone.replace(/\D/g, ''),
          email: user.email || email,
        }),
      });

      if (res.status >= 200 && res.status < 300) {
        const data = await res.json();
        if (!data.success) {
          throw new Error(data.message || 'MonCash request failed');
        }
        window.location.href = data.payment_url;
        return;
      }

      throw new Error('MonCash request failed');
    } catch (error) {
      console.error(error);
      setPaymentStatus({ type: 'error', message: error.message || 'MonCash payment failed' });
      setIsProcessing(false);
    }
  };

  const processApplePayPayment = async () => {
    if (!window.ApplePaySession || !window.ApplePaySession.canMakePayments()) {
      throw new Error('Apple Pay not available');
    }

    const res = await fetch(paymentApiUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'create_payment_intent', amount, currency: 'usd', email }),
    });

    const result = await res.json();
    if (!res.ok || !result.success) {
      throw new Error(result.error || 'Failed to create payment intent');
    }

    const { clientSecret } = result;
    const stripe = window.Stripe(stripeKey);
    const { error, paymentIntent } = await stripe.confirmApplePayPayment(clientSecret, {
      paymentMethodData: { billingDetails: { email } },
    });

    if (error) throw new Error(error.message);

    setPaymentStatus({ type: 'success', message: '✅ Apple Pay succeeded! Processing...' });
    await handleSuccessfulPayment(paymentIntent.id, 'applepay');
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    if (activeMethod === 'card' && (!cardName || !email)) {
      setPaymentStatus({ type: 'error', message: 'Please fill in all required fields' });
      return;
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

    const shouldProceed = window.confirm(`Proceed with payment for ${params.get('f') || 'order'}: $${(amount / 100).toFixed(2)}?`);
    if (!shouldProceed) return;

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
    } finally {
      setIsProcessing(false);
    }
  };

  const processPaypalPayment = async () => {
    if (!window.paypal) {
      throw new Error('PayPal SDK is not loaded');
    }

    localStorage.setItem('payment_email', email);

    const paypalContainer = document.getElementById('paypal-button-container');
    if (paypalContainer) paypalContainer.innerHTML = '';

    const newContainer = document.createElement('div');
    newContainer.id = `paypal-button-container-${Date.now()}`;
    document.body.appendChild(newContainer);

    await new Promise((resolve, reject) => {
      window.paypal.Buttons({
        createOrder: async () => {
          const res = await fetch(paymentApiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              action: 'paypal_create_order',
              amount: (amount / 100).toString(),
              currency: 'USD',
              email,
            }),
          });

          const data = await res.json();
          return data.id;
        },
        onApprove: async (data) => {
          const res = await fetch(paymentApiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'paypal_capture_order', orderID: data.orderID }),
          });

          if (res.ok) {
            setPaymentStatus({ type: 'success', message: '✅ PayPal payment completed! Processing...' });
            await handleSuccessfulPayment(data.orderID, 'paypal');
            resolve();
          } else {
            reject(new Error('PayPal capture failed'));
          }
        },
        onError: (error) => {
          console.error('PayPal error:', error);
          reject(new Error('PayPal payment failed'));
        },
        onCancel: () => reject(new Error('User canceled PayPal')),
      }).render(`#${newContainer.id}`);
    });
  };

  if (showSuccessForm && accessData) {
    return <StreamingAccessForm accessData={accessData} emailSent={emailSent} />;
  }

  if (showSuccessForm && ticketData) {
    return <EventTicketSuccess ticketData={ticketData} emailSent={emailSent} />;
  }

  if (showSuccessForm && serviceData) {
    return <ServiceOrderSuccess serviceData={serviceData} emailSent={emailSent} />;
  }

  return (
    <div className="payment-container">
      <div className="header">
        <h1>Secure Online Payments</h1>
        <p>Fast and secure payment processing for your purchases</p>
      </div>

      {paymentStatus.message && (
        <div className={`payment-status ${paymentStatus.type}`} style={{ margin: '20px auto', maxWidth: '800px' }}>
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
              <span className="name">Mon Cash</span>
            </div>
            <div className={`payment-method ${activeMethod === 'applepay' ? 'active' : ''}`} onClick={() => setActiveMethod('applepay')}>
              <span className="icon">🍎</span>
              <span className="name">Apple Pay</span>
            </div>
          </div>

          <form onSubmit={handleSubmit}>
            {activeMethod === 'paypal' && (
              <div className="form-group">
                <label>PayPal Payment</label>
                <div id="paypal-button-container"></div>
              </div>
            )}

            {activeMethod === 'card' && (
              <>
                <div className="form-group">
                  <label>Card Details</label>
                  <div id="card-element" style={{ padding: '10px', border: '1px solid #ddd', borderRadius: '4px' }}></div>
                </div>

                <div className="form-group">
                  <label>Cardholder Name</label>
                  <input type="text" className="form-control" placeholder="John Doe" value={cardName} onChange={(e) => setCardName(e.target.value)} required />
                </div>

                <div className="form-group">
                  <label>Email for receipt</label>
                  <input type="email" className="form-control" placeholder="email@example.com" value={email} onChange={(e) => setEmail(e.target.value)} required />
                </div>

                <div className="form-group">
                  <label>Amount ($)</label>
                  <input type="number" className="form-control" min="0" step="0.01" value={(amount / 100).toFixed(2)} disabled required />
                </div>
              </>
            )}

            {activeMethod === 'moncash' && (
              <>
                <div className="form-group">
                  <label>Phone Number</label>
                  <input type="tel" className="form-control" placeholder="509XXXXXXX" value={phone} onChange={(e) => setPhone(e.target.value)} required />
                </div>

                <div className="form-group">
                  <label>Email for receipt</label>
                  <input type="email" className="form-control" placeholder="email@example.com" value={email} onChange={(e) => setEmail(e.target.value)} required />
                </div>

                <div className="form-group">
                  <label>Amount ($)</label>
                  <input type="number" className="form-control" min="0" step="0.01" value={(amount / 100).toFixed(2)} disabled required />
                </div>

                <p style={{ marginTop: '10px', fontSize: '14px', color: '#666' }}>
                  Enter your Mon Cash registered phone number. You will be redirected to MonCash to complete the payment.
                </p>
              </>
            )}

            {activeMethod === 'applepay' && (
              <div className="form-group">
                <p>After clicking "Pay Now", the Apple Pay window will open to complete your payment.</p>
              </div>
            )}

            {activeMethod !== 'paypal' && (
              <button type="submit" className="btn" disabled={isProcessing}>
                {isProcessing ? <span className="loading"></span> : 'Pay Now'}
              </button>
            )}

            <div className="secure-notice">🔒 Your data is securely protected</div>
          </form>
        </div>

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
}

function StreamingAccessForm({ accessData, emailSent }) {
  return (
    <div className="success-container">
      <h2>Access Granted</h2>
      <pre>{JSON.stringify(accessData, null, 2)}</pre>
      {emailSent && <p>Receipt sent successfully.</p>}
    </div>
  );
}

function EventTicketSuccess({ ticketData, emailSent }) {
  return (
    <div className="success-container">
      <h2>Ticket Purchased</h2>
      <p>{ticketData.title}</p>
      {emailSent && <p>Receipt sent successfully.</p>}
    </div>
  );
}

function ServiceOrderSuccess({ serviceData, emailSent }) {
  useEffect(() => {
    if (!serviceData) return;

    fetch('/php/dbReader.php?q=updatePaymentStatus', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ service: serviceData }),
    }).catch(console.error);
  }, [serviceData]);

  return (
    <div className="success-container">
      <h2>Service Order Complete</h2>
      <p>{serviceData.name}</p>
      {emailSent && <p>Receipt sent successfully.</p>}
    </div>
  );
}
