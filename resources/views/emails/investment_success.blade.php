<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation d'Investissement</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 30px; border-radius: 8px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #063b27; margin: 0; }
        .content { background: white; padding: 20px; border-radius: 8px; border: 1px solid #eee; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #888; }
        .btn { display: inline-block; padding: 10px 20px; background: #063b27; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AgroTrace</h1>
        </div>
        <div class="content">
            <h2>Merci pour votre investissement !</h2>
            <p>Bonjour {{ $investment->user->name }},</p>
            <p>Nous vous confirmons la réception de votre investissement de <strong>{{ number_format($investment->amount_fcfa) }} FCFA</strong> ({{ number_format($investment->amount_sats) }} Sats) pour le projet <strong>{{ $investment->project->title }}</strong>.</p>
            <p>Votre contrat d'investissement a été généré et est disponible sur votre tableau de bord.</p>
            <div style="text-align: center;">
                <a href="{{ url('/dashboard') }}" class="btn">Accéder à mon tableau de bord</a>
            </div>
        </div>
        <div class="footer">
            <p>Ceci est un email automatique, merci de ne pas y répondre.</p>
            <p>&copy; {{ date('Y') }} AgroTrace. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
