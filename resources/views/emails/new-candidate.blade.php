<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #fff8e7; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { background-color: #003229; color: #d4ae6d; padding: 40px; text-align: center; }
        .content { padding: 40px; color: #00332B; line-height: 1.6; }
        .candidate-card { background-color: #f9f6f0; border-left: 4px solid #d4ae6d; padding: 25px; margin: 25px 0; }
        .btn { display: inline-block; padding: 15px 30px; background-color: #003229; color: #ffffff !important; text-decoration: none; font-weight: bold; font-size: 13px; text-transform: uppercase; letter-spacing: 2px; }
        .footer { background-color: #f9f6f0; padding: 20px; text-align: center; font-size: 11px; color: #6B7A77; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-family: 'Times New Roman', serif; letter-spacing: 2px; font-weight: normal; text-transform: uppercase;">Alerte Inscription</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $campaign->creator->name }}</strong>,</p>
            
            <p>Une nouvelle demande de participation vient d'être déposée pour votre scrutin : <strong>"{{ $campaign->name }}"</strong>.</p>
            
            <div class="candidate-card">
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #d4ae6d; margin-bottom: 5px;">Identité du candidat</div>
                <div style="font-size: 20px; color: #003229; font-weight: bold;">{{ $candidate->name }}</div>
                <div style="margin-top: 15px; font-style: italic;">"{{ Str::limit($candidate->description, 100) }}"</div>
            </div>

            <p>Vous pouvez dès à présent examiner cette candidature sur votre tableau de bord de gestion pour l'approuver ou la décliner.</p>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ route('campaigns.manage', $campaign->slug) }}" class="btn">Gérer les candidatures</a>
            </div>
        </div>
        <div class="footer">
            © 2026 {{ strtoupper(config('app.name')) }} • EXCELLENCE & TRANSPARENCE
        </div>
    </div>
</body>
</html>
