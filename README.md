# 🗳️ EventVote - Système de Vote Événementiel & Paiement Intégré

**EventVote** est une plateforme haut de gamme spécialisée dans la gestion de concours et de votes événementiels (Miss, Awards, Compétitions). Elle est conçue pour supporter un engagement massif tout en régulant les flux de votes pour garantir une compétition équitable.

---

## 👥 Les Acteurs du Système

La plateforme orchestre 4 types de profils avec des privilèges dédiés :

1.  **🛡️ Super Administrateur** : Modère la plateforme, valide les nouvelles campagnes et dispose d'une vue d'ensemble sur toutes les statistiques.
2.  **🏢 Organisateur (Promoteur)** : Crée et personnalise ses propres campagnes, gère ses candidats et suit le chiffre d'affaires généré par ses événements.
3.  **🌟 Candidat** : Participe aux événements, dispose d'un profil multimédia et accède à ses statistiques de votes personnelles pour suivre sa popularité.
4.  **🗳️ Votant (Public)** : Le cœur du système. Il soutient ses candidats favoris via des votes gratuits ou payants.

---

## 🌟 Régulation & Équité des Votes

Le système applique des règles de vote intelligentes pour maximiser l'engagement tout en évitant les abus :
*   **🔋 Soutien Fidèle** : Chaque fan peut voter jusqu'à un **maximum de 100 fois** par campagne.
*   **⏱️ Délai de Courtoisie** : Un intervalle de **60 secondes (1 minute)** est requis entre chaque vote pour garantir une compétition saine.
*   **💳 Votes Premium** : Système monétisé avec validation automatique pour un comptage instantané des points.

---

## ✨ Fonctionnalités Avancées

*   **🏆 CMS de Campagnes** : Cycle complet de gestion (Slug unique, Codes de participation, Tracking des visites).
*   **👨‍💼 Workflow de Candidature** : Modération fluide des dossiers (En attente / Accepté / Rejeté).
*   **🎥 Galerie Immersive** : Profils enrichis avec vidéos (YouTube/Vimeo) et photos HD.
*   **📈 Dashboard Analytics** : Graphiques en temps réel sur 12h, top ranking et breakdown de l'activité des votants.

---

## 🛠️ Stack Technique

*   **Framework** : [Laravel 12](https://laravel.com/)
*   **Database** : MySQL / SQLite
*   **UI/UX** : Tailwind CSS & Blade.
*   **Notifications** : Système de mail transactionnel pour le suivi des statuts.

---

## 🚀 Installation & Configuration

```bash
# 1. Installation
composer install
cp .env.example .env
php artisan key:generate

# 2. Base de données
php artisan migrate --seed

# 3. Lancement
php artisan serve
```

---

## 📄 Licence
Ce projet est sous licence MIT. Développé par **BELLOX**.
