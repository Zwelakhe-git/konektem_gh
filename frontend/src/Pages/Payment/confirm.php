<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de paiement - Konektem</title>
    <link rel="shortcut icon" type="image/png" href="/media/images/favicon.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Styles pour la page de confirmation */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Conteneur principal */
        .payment-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            min-height: 100vh;
        }

        /* Carte de confirmation avec animation fadeIn */
        .payment-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.8s ease-in-out;
            position: relative;
        }

        /* Animation fadeIn */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Bouton maison en haut à gauche */
        .home-btn {
            position: fixed;
            top: 25px;
            left: 25px;
            z-index: 1000;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 12px;
            padding: 12px 18px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .home-btn:hover {
            background: rgba(255,255,255,0.35);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            transform: scale(1.05);
            color: white;
            text-decoration: none;
        }

        .home-btn i {
            font-size: 18px;
        }

        /* Icône de statut */
        .status-icon {
            font-size: 72px;
            display: block;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Titre */
        .payment-title {
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 12px;
            color: #2d3748;
        }

        /* Message */
        .payment-message {
            text-align: center;
            color: #4a5568;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .payment-submessage {
            text-align: center;
            color: #718096;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        /* Badge de plateforme */
        .platform-badge {
            display: inline-block;
            background: #edf2f7;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 20px;
        }

        /* Détails du paiement */
        .payment-details {
            background: #f7fafc;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
            display: <?= $paymentStatus === 'success' ? 'block' : 'none' ?>;
        }

        .payment-details .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            color: #4a5568;
            border-bottom: 1px solid #e2e8f0;
        }

        .payment-details .detail-row:last-child {
            border-bottom: none;
        }

        .payment-details .label {
            font-weight: 500;
        }

        .payment-details .value {
            font-weight: 600;
            color: #2d3748;
        }

        /* Boutons d'action */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 14px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: #edf2f7;
            border: none;
            color: #4a5568;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-secondary-custom:hover {
            background: #e2e8f0;
            color: #2d3748;
        }

        /* Alertes de statut */
        .alert-success {
            border-left: 4px solid #48bb78;
        }

        .alert-warning {
            border-left: 4px solid #ed8936;
        }

        .alert-info {
            border-left: 4px solid #4299e1;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .payment-card {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .payment-title {
                font-size: 22px;
            }
            
            .status-icon {
                font-size: 56px;
            }
            
            .home-btn {
                top: 15px;
                left: 15px;
                padding: 10px 14px;
                font-size: 12px;
            }
            
            .home-btn span {
                display: none;
            }
        }

        /* Effet de particules (optionnel) */
        .particle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            background: rgba(255,255,255,0.1);
            animation: float 15s infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-100px) rotate(180deg); opacity: 1; }
        }
    </style>
</head>
<body>

    <!-- Bouton maison en haut à gauche -->
    <a href="<?= BASE_URL ?>/" class="home-btn">
        <i class="fas fa-home"></i>
        <span>Accueil</span>
    </a>

    <!-- Particules de fond (effet visuel) -->
    <?php for ($i = 0; $i < 6; $i++): ?>
    <div class="particle" style="
        width: <?= rand(20, 60) ?>px;
        height: <?= rand(20, 60) ?>px;
        top: <?= rand(5, 95) ?>%;
        left: <?= rand(5, 95) ?>%;
        animation-delay: <?= rand(-15, 0) ?>s;
        animation-duration: <?= rand(12, 20) ?>s;
        opacity: <?= rand(3, 8) / 10 ?>;
    "></div>
    <?php endfor; ?>

    <div class="payment-container">
        <div class="payment-card">

            <!-- Icône de statut -->
            <span class="status-icon">
                <?php if ($paymentStatus === 'success'): ?>
                    ✅
                <?php elseif ($paymentStatus === 'cancel' || $paymentStatus === 'cancelled'): ?>
                    ⏸️
                <?php else: ?>
                    ⏳
                <?php endif; ?>
            </span>

            <!-- Badge plateforme -->
            <div style="text-align: center;">
                <span class="platform-badge">
                    <?= $icon ?> <?= $platformName ?>
                </span>
            </div>

            <!-- Titre -->
            <h1 class="payment-title"><?= $title ?></h1>

            <!-- Message -->
            <p class="payment-message"><?= $message ?></p>
            <p class="payment-submessage"><?= $subMessage ?></p>

            <!-- Détails du paiement (si succès) -->
            <?php if ($paymentStatus === 'success' && ($orderNumber || $transactionId || $amount)): ?>
            <div class="payment-details">
                <?php if ($orderNumber): ?>
                <div class="detail-row">
                    <span class="label">📋 Numéro de commande</span>
                    <span class="value">#<?= htmlspecialchars($orderNumber) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($amount): ?>
                <div class="detail-row">
                    <span class="label">💰 Montant</span>
                    <span class="value"><?= number_format($amount, 2) ?> USD</span>
                </div>
                <?php endif; ?>
                
                <?php if ($transactionId): ?>
                <div class="detail-row">
                    <span class="label">🔑 ID Transaction</span>
                    <span class="value" style="font-size: 12px;"><?= htmlspecialchars($transactionId) ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Boutons d'action -->
            <div class="action-buttons">
                <?php if ($paymentStatus === 'success'): ?>
                    <a href="<?= BASE_URL ?>/<?= ($_SESSION && $_SESSION['user']['role'] === 'admin') ? 'admin' : 'user/me'?>" class="btn-primary-custom">
                        <i class="fas fa-user me-2"></i> Mon tableau de bord
                    </a>
                    <a href="<?= BASE_URL ?>/" class="btn-secondary-custom">
                        <i class="fas fa-home me-2"></i> Retour à l'accueil
                    </a>
                <?php elseif ($paymentStatus === 'cancel' || $paymentStatus === 'cancelled'): ?>
                    <a href="<?= BASE_URL ?>/" class="btn-primary-custom">
                        <i class="fas fa-shopping-cart me-2"></i> Continuer mes achats
                    </a>
                    <a href="javascript:history.back()" class="btn-secondary-custom">
                        <i class="fas fa-arrow-left me-2"></i> Réessayer le paiement
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/<?= ($_SESSION && $_SESSION['user']['role'] === 'admin') ? 'admin' : 'user/me'?>" class="btn-primary-custom">
                        <i class="fas fa-user me-2"></i> Mon tableau de bord
                    </a>
                    <a href="<?= BASE_URL ?>/" class="btn-secondary-custom">
                        <i class="fas fa-home me-2"></i> Retour à l'accueil
                    </a>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #edf2f7;">
                <p style="font-size: 12px; color: #a0aec0; margin: 0;">
                    © <?= date('Y') ?> Konektem. Tous droits réservés.
                </p>
                <p style="font-size: 11px; color: #cbd5e0; margin: 5px 0 0;">
                    <i class="fas fa-shield-alt me-1"></i> Paiement sécurisé
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Animation supplémentaire - effet de confettis pour le succès
        <?php if ($paymentStatus === 'success'): ?>
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter des confettis simples
            const colors = ['#667eea', '#764ba2', '#48bb78', '#ed8936', '#4299e1', '#f687b3'];
            
            for (let i = 0; i < 30; i++) {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                    position: fixed;
                    width: ${Math.random() * 8 + 4}px;
                    height: ${Math.random() * 8 + 4}px;
                    background: ${colors[Math.floor(Math.random() * colors.length)]};
                    top: -10px;
                    left: ${Math.random() * 100}%;
                    border-radius: ${Math.random() > 0.5 ? '50%' : '2px'};
                    pointer-events: none;
                    opacity: ${Math.random() * 0.7 + 0.3};
                    animation: confettiFall ${Math.random() * 4 + 3}s linear ${Math.random() * 2}s forwards;
                    transform: rotate(${Math.random() * 360}deg);
                `;
                document.body.appendChild(confetti);
            }

            // Ajouter l'animation des confettis
            const style = document.createElement('style');
            style.textContent = `
                @keyframes confettiFall {
                    0% {
                        transform: translateY(0) rotate(0deg) scale(1);
                        opacity: 1;
                    }
                    100% {
                        transform: translateY(100vh) rotate(720deg) scale(0.5);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });
        <?php endif; ?>

        // Redirection automatique après 5 secondes (si succès)
        <?php if ($paymentStatus === 'success'): ?>
        setTimeout(function() {
            // Optionnel: rediriger vers le dashboard après 5 secondes
            // window.location.href = '<?= BASE_URL ?>/dashboard';
        }, 5000);
        <?php endif; ?>
    </script>

</body>
</html>