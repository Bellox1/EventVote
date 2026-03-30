<x-mail::message>
# Nouveau Scrutin à Valider

Un nouvel événement vient d'être créé sur la plateforme par **{{ $campaign->user->name }}**.

**Détails de l'événement :**
- **Nom :** {{ $campaign->name }}
- **Description :** {{ $campaign->description }}
- **Créateur :** {{ $campaign->user->name }} ({{ $campaign->user->email }})
- **Téléphone :** {{ $campaign->user->phone ?? 'Non renseigné' }}

<x-mail::button :url="config('app.url') . '/admin/dashboard'">
Accéder à l'administration
</x-mail::button>

Cordialement,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
