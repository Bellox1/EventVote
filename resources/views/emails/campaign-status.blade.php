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
        .status-badge { display: inline-block; padding: 8px 20px; font-weight: bold; text-transform: uppercase; font-size: 12px; margin-top: 20px; }
        .status-active { background-color: #003229; color: #d4ae6d; }
        .status-rejected { background-color: #ef4444; color: #ffffff; }
        .btn { display: inline-block; background-color: #d4ae6d; color: #ffffff; padding: 15px 35px; text-decoration: none; border-radius: 0; font-weight: bold; text-transform: uppercase; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-family: 'Times New Roman', serif; letter-spacing: 3px; font-weight: normal; text-transform: uppercase;">Mise à Jour de Session</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $campaign->creator->name }}</strong>,</p>
            
            <p>Nous avons examiné votre demande pour le scrutin <strong>"{{ $campaign->name }}"</strong>.</p>
            
            @if($status === 'active')
                <div class="status-badge status-active">APPORUVÉE & ACTIVÉE</div>
                <p style="margin-top: 30px;">Nous sommes ravis de vous informer que votre session est désormais **active**. Elle est visible par les votants et les participations sont ouvertes.</p>
                <div style="text-align: center;">
                    <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn">Accéder à la Session</a>
                </div>
            @else
                <div class="status-badge status-rejected">DEMANDE REFUSÉE</div>
                <p style="margin-top: 30px;">Votre demande n'a pas été retenue pour le moment. Nous vous invitons à revoir les détails de votre projet ou à nous contacter pour plus d'informations.</p>
                
                @if($campaign->rejection_reason)
                    <div style="background-color: #fef2f2; padding: 20px; border-left: 3px solid #ef4444; margin: 25px 0; color: #991b1b; font-size: 13px; text-align: left;">
                        <strong style="text-transform: uppercase; font-size: 11px; letter-spacing: 0.1em;">Motif du refus :</strong><br>
                        <span style="font-style: italic; margin-top: 8px; display: inline-block;">{{ $campaign->rejection_reason }}</span>
                    </div>
                @endif
                
                <div style="text-align: center;">
                    <a href="{{ route('dashboard') }}" class="btn">Voir mes demandes</a>
                </div>
            @endif

            <p style="margin-top: 40px; font-style: italic; opacity: 0.8;">Merci de votre confiance.</p>
        </div>
        <div class="footer">
            © 2026 {{ strtoupper(config('app.name')) }} • EXCELLENCE & TRANSPARENCE
        </div>
    </div>
</body>
</html>
