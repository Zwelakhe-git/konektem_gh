import { useEffect, useState } from "react";

interface StripeParams {
    email: string;
    amount: Number;
    cardElement: null;
}
export function StripeCheckout({params: StripeParams}){
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

    return (<>
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
    </>);
}