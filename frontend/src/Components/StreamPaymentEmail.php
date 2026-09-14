<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f8f9fa; padding: 30px 20px; border-radius: 0 0 10px 10px; }
            .token-box { background: white; border: 2px dashed #667eea; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center; }
            .token-code { font-family: 'Courier New', monospace; font-size: 18px; font-weight: bold; color: #764ba2; word-break: break-all; padding: 10px; background: #f0f0f0; border-radius: 5px; }
            .btn { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 15px 0; }
            .btn:hover { background: #5a67d8; }
            .info-item { margin: 10px 0; padding: 10px; background: white; border-radius: 5px; border-left: 4px solid #667eea; }
            .footer { text-align: center; font-size: 12px; color: #999; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>🎬 Accès à la diffusion</h1>
            <p style='margin: 0; opacity: 0.9;'><strong><?= $productName?></strong></p>
        </div>
        
        <div class='content'>
            <h2>Bonjour " . <?= htmlspecialchars($userName)?> . ",</h2>
            
            <p>Votre accès à la diffusion en direct a été activé avec succès !</p>
            
            <div class='info-item'>
                <strong>📺 Diffusion :</strong> <?= $productName?>
            </div>
            
            <div class='info-item'>
                <strong>🕐 Date et heure :</strong> <?= $startTime?>
            </div>
            
            <div class='info-item'>
                <strong>⏳ Validité :</strong> Jusqu'au <?= $formattedDate?>
            </div>
            
            <div style='text-align: center;'>
                <a href='{$streamUrl}' class='btn'>🎥 Regarder la diffusion</a>
            </div>
            
            <div class='token-box'>
                <p style='margin-bottom: 10px;'><strong>🔑 Votre token d'accès personnel :</strong></p>
                <div class='token-code'><?= $accessToken?></div>
                <p style='font-size: 12px; color: #666; margin-top: 10px;'>
                    Ce token est unique et vous permet d'accéder à la diffusion.
                    Ne le partagez pas avec d'autres personnes.
                </p>
            </div>
            
            <div style='background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0;'>
                <p style='margin: 0; font-size: 14px;'>
                    <strong>💡 Conseil :</strong> Utilisez ce token pour vous connecter à la diffusion. 
                    Si vous rencontrez des problèmes, copiez le token et collez-le dans le champ prévu à cet effet.
                </p>
            </div>
            
            <div class='footer'>
                <p>© <?= date('Y')?> Konektem - Tous droits réservés</p>
                <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
            </div>
        </div>
    </body>
    </html>