<x-mail::message>
# Réinitialisation de mot de passe

Bonjour,

Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.

Votre code de vérification est :

<h1 style="text-align: center; color: #1a4d2e; font-size: 32px; letter-spacing: 5px; padding: 20px; border: 2px dashed #d4af37; border-radius: 10px; margin: 20px 0;">{{ $otp }}</h1>

Ce code expirera dans 15 minutes.

Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune autre action n'est requise.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
