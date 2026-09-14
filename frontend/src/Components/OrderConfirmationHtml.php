<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f8f9fa; padding: 30px 20px; border-radius: 0 0 10px 10px; }
            .order-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
            .total { font-size: 24px; font-weight: bold; color: #764ba2; text-align: center; padding: 15px; background: #f0f0f0; border-radius: 5px; }
            .footer { text-align: center; font-size: 12px; color: #999; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>📋 Commande confirmée</h1>
            <p style='margin: 0; opacity: 0.9;'>#<?= $orderNumber?></p>
        </div>
        
        <div class='content'>
            <h2>Bonjour  <?= htmlspecialchars($userName)?> </h2>
            
            <p>Votre commande a été confirmée avec succès !</p>
            
            <div class='order-details'>
                <p><strong>📅 Date :</strong> <?= $orderDate?></p>
                <p><strong>🔢 Numéro de commande :</strong> #{<?= $orderNumber?>}</p>
                <p><strong>📦 Articles :</strong> <?= $orderInfo['items_count']?></p>
            </div>
            
            <div class='total'>
                Total : <?= $totalAmount?> <?= $currency?>
            </div>
            
            <p style='text-align: center; margin-top: 20px;'>
                <small>Un email de confirmation vous a été envoyé. Consultez votre espace client pour suivre votre commande.</small>
            </p>
            
            <div class='footer'>
                <p>© <?= date('Y')?> Konektem - Tous droits réservés</p>
            </div>
        </div>
    </body>
</html>