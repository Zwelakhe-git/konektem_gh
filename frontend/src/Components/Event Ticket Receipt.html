<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Order Confirmation</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Fraunces:opsz,wght@9..144,400;9..144,600&display=swap" rel="stylesheet">
<style>
  :root{
    --paper:#fbf8f2; --ink:#1f2420; --line:#d8d2c4; --accent:#2f6b4f;
    --muted:#8a8577; --stub:#f1ede2;
  }
  :root:not([data-theme="light"]){}
  @media (prefers-color-scheme: dark){
    :root:not([data-theme="light"]){ --paper:#fbf8f2; --ink:#1f2420; }
  }
  *{box-sizing:border-box;}
  html,body{height:100%;}
  body{
    margin:0; background:#e8e4da; color:var(--ink);
    font-family:'Space Mono', monospace;
    padding: max(24px, env(safe-area-inset-top)) 16px max(24px, env(safe-area-inset-bottom));
    display:flex; justify-content:center;
  }
  .receipt{
    width:100%; max-width:420px; background:var(--paper);
    box-shadow:0 12px 30px rgba(0,0,0,.15);
    position:relative;
  }
  .receipt::before, .receipt::after{
    content:""; position:absolute; left:0; right:0; height:14px;
    background-image: radial-gradient(circle at 8px 7px, #e8e4da 6px, transparent 7px);
    background-size:16px 14px; background-repeat:repeat-x;
  }
  .receipt::before{ top:-7px; }
  .receipt::after{ bottom:-7px; transform:rotate(180deg); }
  .pad{ padding:32px 28px 24px; }
  .brand{ text-align:center; margin-bottom:18px; }
  .brand .mark{ font-family:'Fraunces', serif; font-size:26px; font-weight:600; letter-spacing:.5px; }
  .brand .tag{ font-size:10px; color:var(--muted); margin-top:4px; }
  .rule{ border:none; border-top:1px dashed var(--line); margin:18px 0; }
  .paid{
    display:flex; align-items:center; gap:8px; justify-content:center;
    color:var(--accent); font-size:12px; font-weight:700; margin-bottom:20px;
  }
  .paid .dot{ width:6px; height:6px; border-radius:50%; background:var(--accent); }
  .event-name{ font-family:'Fraunces', serif; font-size:20px; line-height:1.3; margin:0 0 4px; }
  .event-meta{ font-size:11px; color:var(--muted); line-height:1.8; }
  .row{ display:flex; justify-content:space-between; font-size:12px; padding:6px 0; }
  .row .label{ color:var(--muted); }
  .items .row{ padding:4px 0; }
  .total-row{ display:flex; justify-content:space-between; font-size:15px; font-weight:700; padding-top:10px; }
  .qr-wrap{ display:flex; justify-content:center; margin:20px 0 8px; }
  .qr{ width:96px; height:96px; }
  .order-no{ text-align:center; font-size:10px; letter-spacing:1.5px; color:var(--muted); margin-top:2px; }
  .foot{ text-align:center; font-size:10px; color:var(--muted); margin-top:20px; line-height:1.8; }
</style>
</head>
<body>
  <div class="receipt">
    <div class="pad">
      <div class="brand">
        <div class="mark">Starlight Sessions</div>
        <div class="tag">EVENT TICKETING</div>
      </div>

      <div class="paid"><span class="dot"></span>PAYMENT CONFIRMED</div>

      <h1 class="event-name"><?= htmlspecialchars($eventName)?></h1>
      <div class="event-meta">
          <?= nl2br(htmlspecialchars($eventMeta))?>
      </div>

      <hr class="rule">

      <div class="items">
        <div class="row"><span class="label">General Admission ×2</span><span>$90.00</span></div>
        <?php if(isset($serviceFee)): ?>
        <div class="row"><span class="label">Service fee</span><span><?= $serviceFee ?></span></div>
        <?php endif; ?>
      </div>

      <hr class="rule">

      <div class="total-row"><span>TOTAL PAID</span><span><?= $totalAmount?></span></div>
      <div class="row"><span class="label">Paid with</span><span><?= $paymentMethod ?></span></div>
      <div class="row"><span class="label">Date</span><span><?= $paymentDate ?></span></div>

      <div class="qr-wrap">
        <svg class="qr" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <rect width="100" height="100" fill="var(--paper)"/>
          <g fill="var(--ink)">
            <rect x="4" y="4" width="24" height="24"/><rect x="10" y="10" width="12" height="12" fill="var(--paper)"/>
            <rect x="72" y="4" width="24" height="24"/><rect x="78" y="10" width="12" height="12" fill="var(--paper)"/>
            <rect x="4" y="72" width="24" height="24"/><rect x="10" y="78" width="12" height="12" fill="var(--paper)"/>
            <rect x="36" y="8" width="6" height="6"/><rect x="48" y="8" width="6" height="6"/><rect x="60" y="16" width="6" height="6"/>
            <rect x="36" y="36" width="6" height="6"/><rect x="48" y="42" width="6" height="6"/><rect x="60" y="36" width="6" height="6"/>
            <rect x="36" y="52" width="6" height="6"/><rect x="44" y="60" width="6" height="6"/><rect x="56" y="64" width="6" height="6"/>
            <rect x="68" y="52" width="6" height="6"/><rect x="80" y="44" width="6" height="6"/><rect x="88" y="60" width="6" height="6"/>
            <rect x="68" y="72" width="6" height="6"/><rect x="80" y="80" width="6" height="6"/><rect x="88" y="88" width="6" height="6"/>
            <rect x="36" y="80" width="6" height="6"/><rect x="48" y="88" width="6" height="6"/>
          </g>
        </svg>
      </div>
      <div class="order-no"><?= $orderNumber?></div>

      <div class="foot">
        Bring this confirmation and a photo ID to entry.<br>
        Questions? konektemtv@gmail.com
      </div>
    </div>
  </div>
</body>
</html>
