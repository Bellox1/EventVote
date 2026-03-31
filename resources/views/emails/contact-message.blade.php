<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #fff8e7; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #E8E2D9; }
        .header { background-color: #003229; color: #d4ae6d; padding: 50px 40px; text-align: center; }
        .content { padding: 40px; color: #00332B; line-height: 1.8; }
        .footer { background-color: #f9f6f0; padding: 25px; text-align: center; font-size: 11px; color: #6B7A77; letter-spacing: 2px; text-transform: uppercase; border-top: 1px solid #E8E2D9; }
        .info-box { background-color: #f9f6f0; padding: 25px; border-left: 4px solid #d4ae6d; margin: 30px 0; }
        .label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #6B7A77; font-weight: bold; margin-bottom: 5px; display: block; }
        .value { font-size: 15px; color: #003229; font-weight: 600; margin-bottom: 15px; display: block; }
        .message-body { background-color: white; padding: 20px; border: 1px solid #E8E2D9; margin-top: 10px; font-style: italic; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-family: 'Times New Roman', serif; letter-spacing: 5px; font-weight: normal; text-transform: uppercase; font-size: 22px;">Nouveau Contact</h1>
            <div style="width: 40px; height: 1px; background: #d4ae6d; margin: 20px auto;"></div>
            <p style="margin: 0; font-size: 12px; letter-spacing: 2px; opacity: 0.8;">DEMANDE D'ACCOMPAGNEMENT</p>
        </div>
        
        <div class="content">
            <p style="font-size: 16px;">Une nouvelle demande a été soumise via le formulaire de tarification :</p>
            
            <div class="info-box">
                <span class="label">Expéditeur</span>
                <span class="value">{{ $name }}</span>
                
                <span class="label">Email de réponse</span>
                <span class="value">{{ $email }}</span>
                
                <span class="label">Sujet</span>
                <span class="value">{{ $subject }}</span>

                <span class="label">Message</span>
                <div class="message-body">{{ $contactMessage }}</div>
            </div>

            <p style="margin-top: 40px; font-size: 13px; color: #6B7A77; border-top: 1px solid #f0f0f0; padding-top: 20px;">
                Ceci est une notification automatique générée par le système EventVote.
            </p>
        </div>
        
        <div class="footer">
            © 2026 {{ strtoupper(config('app.name')) }} • EXCELLENCE & TRANSPARENCE
        </div>
    </div>
</body>
</html>
