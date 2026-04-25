# 🗳️ EventVote - Plateforme Professionnelle de Votes Événementiels

**EventVote** est une solution complète de gestion de votes et de sondages pour événements, développée avec Laravel. Elle permet aux organisateurs de créer des campagnes, aux candidats de se présenter et aux votants de s'exprimer de manière sécurisée et contrôlée.

---

## 📸 Aperçus de la Plateforme

<p align="center">
  <img src="Demo/1.png" width="30%" />
  <img src="Demo/2.png" width="30%" />
  <img src="Demo/3.png" width="30%" />
  <br />
  <img src="Demo/4.png" width="30%" />
  <img src="Demo/5.png" width="30%" />
  <img src="Demo/6.png" width="30%" />
  <br />
  <img src="Demo/7.png" width="30%" />
  <img src="Demo/8.png" width="30%" />
  <img src="Demo/9.png" width="30%" />
  <br />
  <img src="Demo/10.png" width="30%" />
  <img src="Demo/11.png" width="30%" />
  <img src="Demo/12.png" width="30%" />
  <br />
  <img src="Demo/13.png" width="30%" />
  <img src="Demo/14.png" width="30%" />
  <img src="Demo/15.png" width="30%" />
  <br />
  <img src="Demo/16.png" width="30%" />
  <img src="Demo/17.png" width="30%" />
  <img src="Demo/18.png" width="30%" />
  <br />
  <img src="Demo/19.png" width="30%" />
  <img src="Demo/20.png" width="30%" />
  <img src="Demo/21.png" width="30%" />
  <br />
  <img src="Demo/22.png" width="30%" />
  <img src="Demo/23.png" width="30%" />
  <img src="Demo/24.png" width="30%" />
  <br />
  <img src="Demo/25.png" width="30%" />
</p>

---

## 🎭 Acteurs du Système

1.  **🦸 Super Admin** : Gestion globale, création de campagnes et supervision des comptes promoteurs.
2.  **👨‍💼 Organisateur (Promoteur)** : Création et gestion de ses campagnes, ajout de candidats et suivi des statistiques en temps réel.
3.  **👤 Candidat** : Profil public, présentation et réception des votes.
4.  **🗳️ Votant** : Participation sécurisée aux campagnes avec restrictions de vote.

---

## 🛡️ Règles de Vote & Sécurité

Pour garantir l'intégrité des résultats, le système impose des règles strictes :
*   **⏳ Cooldown de 1 minute** : Un délai d'une minute est imposé entre chaque vote pour un même utilisateur/session.
*   **🚫 Limite de 100 Votes** : Un utilisateur (identifié par session/fingerprint) est limité à un maximum de **100 votes par campagne**.
*   **💳 Paiement Intégré** : Possibilité de coupler les votes à un système de paiement.

---

## 🛠️ Stack Technique

*   **Backend** : Laravel 11 (PHP 8.2+)
*   **Infrastructure** : Système de migrations robuste, Policies d'accès et Gates de sécurité.
*   **UI** : Blade Templates avec composants responsives.

---

## 🚀 Installation Express

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

---

## 📄 Licence
Ce projet est sous licence MIT. Développé par **BELLOX**.
