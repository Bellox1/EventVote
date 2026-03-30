<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #fff8e7; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { background-color: #003229; color: #d4ae6d; padding: 40px; text-align: center; }
        .content { padding: 40px; color: #00332B; line-height: 1.6; }
        .footer { background-color: #f9f6f0; padding: 20px; text-align: center; font-size: 11px; color: #6B7A77; letter-spacing: 1px; }
        .status-badge { display: inline-block; padding: 8px 20px; font-weight: bold; text-transform: uppercase; font-size: 12px; margin-top: 20px; background-color: #f59e0b; color: #ffffff; }
        .info-box { background-color: #f9f6f0; padding: 20px; border-left: 3px solid #d4ae6d; margin: 25px 0; color: #00332B; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-family: 'Times New Roman', serif; letter-spacing: 3px; font-weight: normal; text-transform: uppercase;">Candidature Reçue</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $candidate->name }}</strong>,</p>
            
            <p>Nous avons bien reçu votre demande de participation pour le scrutin : <strong>"{{ $campaign->name }}"</strong>.</p>
            
            <div class="status-badge">Traitement en cours</div>
            
            <p style="margin-top: 30px;">Votre candidature est actuellement en cours d'examen par l'organisateur. Vous recevrez un e-mail dès qu'une décision sera prise (acceptation ou refus).</p>
            
            <div class="info-box">
                <strong>Assistance & Suivi :</strong><br>
                Si vous n'obtenez pas de réponse dans un délai raisonnable, vous pouvez contacter directement l'organisateur de cet événement :
                <br><br>
                <span style="font-weight: bold; color: #003229;">{{ $campaign->creator->name }}</span><br>
                📞 {{ $campaign->creator->phone ?? 'Non renseigné' }}
            </div>

            <p style="margin-top: 40px; font-style: italic; opacity: 0.8;">Merci de votre patience et de votre participation.</p>
        </div>
        <div class="footer">
            © 2026 {{ strtoupper(config('app.name')) }} • EXCELLENCE & TRANSPARENCE
        </div>
    </div>
</body>
</html>
