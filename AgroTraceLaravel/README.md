# AgroTrace BTC 🌾⚡

**Track Every Satoshi to the Green Fields.**

AgroTrace BTC est une plateforme web développée (MVP) pour le **Hackathon Bitcoin Mastermind 2026**. Elle connecte les investisseurs de la diaspora avec des coopératives agricoles locales en Afrique, en utilisant le **Lightning Network** pour des micropaiements instantanés et des preuves on-chain via **OP_RETURN** pour garantir la transparence ESG (Environnement, Social, Gouvernance).

## 🚀 Fonctionnalités Principales

- **Investissement Lightning** : Simulation de paiements ultra-rapides et sans friction avec calcul de frais de réseau (2%).
- **Jalons (Milestones) On-Chain** : Suivi transparent de l'avancement des projets (ex: "Semences achetées", "Récolte"). Une fois validées, ces étapes sont ancrées de manière immuable sur la blockchain Bitcoin (simulation).
- **Architecture Multi-Rôles** : 3 tableaux de bord (Dashboards) distincts selon votre profil utilisateur.
- **Design Premium** : Interface utilisateur repensée en **Tailwind CSS pur** (Glassmorphism, animations, UI/UX de haute qualité).

## 🌍 Les Pages et Dashboards

### Pages Publiques
- `/` (**Accueil**) : Landing page vitrine expliquant le concept et les avantages du projet.
- `/projects` (**Explorateur**) : Grille des projets agricoles certifiés nécessitant des financements.
- `/verification` (**Preuves en direct**) : Registre public type "Block Explorer" listant les jalons agricoles validés et leur empreinte OP_RETURN.

### Les Rôles et Leurs Tableaux de Bord (Dashboards)
La page `/dashboard` s'adapte automatiquement selon votre rôle.

1. **Investisseur (Diaspora)** :
   - Vue sur l'historique des investissements, le total investi en FCFA et l'équivalent en SATS.
   - Accès rapide aux reçus Blockchain.
2. **Coopérative (Porteur de Projet)** :
   - Gestion des projets agricoles.
   - Soumission de preuves (photos, reçus) pour valider l'avancement des "Jalons" (Milestones).
3. **Administrateur Système** :
   - Back-office de la plateforme.
   - Validation des nouveaux projets et vérification des preuves soumises par les agriculteurs avant l'inscription sur la blockchain.

---

## 🛠️ Installation (Après un `git clone`)

Une fois que vous avez cloné le projet sur votre machine locale, suivez ces étapes pour lancer l'application :

### 1. Prérequis
- **PHP** 8.2 ou supérieur
- **Composer**
- **Node.js** & NPM
- **MySQL** (via XAMPP, WAMP, ou autre)

### 2. Configuration
Ouvrez un terminal à la racine du projet et exécutez :

```bash
# Installer les dépendances PHP
composer install

# Installer les dépendances Frontend (Tailwind, Vite)
npm install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application Laravel
php artisan key:generate
```

### 3. Base de données
1. Créez une base de données MySQL nommée `agrotrace`.
2. Ouvrez le fichier `.env` et vérifiez vos identifiants (généralement `DB_USERNAME=root` et `DB_PASSWORD=` vide).
3. Lancez les migrations :
```bash
php artisan migrate:fresh
```

### 4. Compilation et Lancement
Pour que le design (Tailwind) s'affiche correctement, vous devez compiler les assets :
```bash
npm run build
```
*(Ou `npm run dev` si vous souhaitez modifier le code en direct).*

Enfin, lancez le serveur local Laravel :
```bash
php artisan serve
```
Le projet sera accessible sur **[http://localhost:8000](http://localhost:8000)**.

---

## 🔑 Comment se connecter / Tester

Le système d'authentification utilise **Laravel Breeze**.
Pour tester les différents rôles, nous vous recommandons de **créer des comptes via la page d'inscription** :

1. Allez sur **[http://localhost:8000/register](http://localhost:8000/register)**.
2. Remplissez les champs (Nom, Email, Mot de passe).
3. **Important** : Sélectionnez le "Type de Compte" dans la liste déroulante (Investisseur ou Coopérative Agricole).
4. Une fois l'inscription validée, vous serez automatiquement redirigé vers le Dashboard correspondant à votre rôle.
5. *(Pour tester l'interface Administrateur, vous devrez changer manuellement la valeur `role` à `admin` pour votre utilisateur dans la table `users` de la base de données phpMyAdmin).*

Bon hackathon ! ⚡
