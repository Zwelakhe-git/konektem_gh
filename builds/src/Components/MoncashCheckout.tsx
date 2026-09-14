
export function MoncashCheckout(){
    // =============== MONCASH PAYMENT ===============
    const processMonCashPayment = async () => {
        try {
            // Store user data for after return
            localStorage.setItem('payment_email', email);
            localStorage.setItem('payment_phone', phone);

            const res = await fetch('/konektem/api/payment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    action: 'moncash_create_payment', 
                    amount: amount / 100, // Convert cents to dollars
                    phone: phone.replace(/\D/g, ''), 
                    email: email 
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
    return (<>
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
    </>);
}