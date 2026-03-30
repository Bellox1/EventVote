<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #003229; background-color: #f9f6f0; }
        .container { max-width: 600px; margin: 40px auto; background: white; padding: 40px; border-radius: 8px; border-top: 5px solid #d4ae6d; }
        .code { font-size: 32px; font-weight: bold; color: #d4ae6d; letter-spacing: 5px; text-align: center; margin: 30px 0; padding: 20px; background: #003229; border-radius: 4px; }
        .footer { font-size: 11px; color: #666; margin-top: 30px; text-align: center; border-top: 1px solid #eee; padding-top: 20px; text-transform: uppercase; letter-spacing: 2px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center; font-weight: 300; text-transform: uppercase; letter-spacing: 3px;">Sécurité du Compte</h2>
        <p>Bonjour,</p>
        <p>Vous avez initié une demande de suppression définitive de votre compte sur notre plateforme de vote.</p>
        <p>Pour confirmer cette action irréversible, veuillez saisir le code de sécurité suivant dans l'interface de validation :</p>
        
        <div class="code">{{ $otp }}</div>
        
        <p style="color: #ef4444; font-weight: bold; text-align: center;">Attention : La suppression de votre compte entraînera la perte définitive de toutes vos données (campagnes créées, participations, historique de votes).</p>
        
        <p>Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet e-mail. Votre compte reste parfaitement sécurisé.</p>
        
        <div class="footer">
            © 2026 {{ config('app.name') }} • SERVICE DE SÉCURITÉ
        </div>
    </div>
</body>
</html>
