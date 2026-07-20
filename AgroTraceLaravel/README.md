🌾 AgroTrace BTC

La vérité du champ à la Blockchain.

AgroTrace BTC est une plateforme de financement participatif agricole qui brise
le plafond de verre de la méfiance en Afrique. En fusionnant l'agronomie
béninoise avec la puissance de Bitcoin Lightning, nous permettons à la diaspora
de financer la terre natale avec une certitude mathématique : chaque Satoshi
investi produit un impact réel, vérifié et immuable.

🧭 Partie 1 : L'Expérience AgroTrace (Guide Investisseur)

Notre mission est de transformer l'envoi d'argent informel en un investissement
productif, transparent et rentable.

1. Sélectionner l'Impact

Explorez notre cartographie interactive couvrant les 12 départements du Bénin.
Qu'il s'agisse de Maïs dans le Borgou ou de Coton dans l'Alibori, chaque projet
est audité et présente des objectifs clairs et un ROI (Retour sur
Investissement) cible attractif.

2. Investissement "Frictionless"

Oubliez les délais SWIFT et les frais de 10%. Grâce au Lightning Network,
investissez instantanément.

  - Rapidité : Paiement en 3 secondes via QR Code.
  - Coût : Frais de transfert quasi-nuls.
  - Accessibilité : Micro-investissement possible dès quelques milliers de
    Satoshis.

3. La Preuve de Travail Agricole (Le Cœur du Système)

L'argent n'est jamais libéré en une seule fois. Le projet est découpé en Jalons
(Milestones).

  - Action Terrain : Pour débloquer les fonds d'un jalon, la coopérative doit
    fournir une preuve (Photo + Coordonnées GPS).
  - Ancrage Blockchain : Une fois validée, l'empreinte numérique (Hash) de cette
    preuve est gravée sur la blockchain Bitcoin via la fonction OP_RETURN.
  - Transparence : L'investisseur suit l'évolution de son champ en temps réel
    sur son dashboard.

4. Partage de Récolte Automatisé

À la fin du cycle, la répartition des richesses est régie par un contrat
numérique transparent :

  - Récolte : L'agriculteur déclare le rendement et le prix de vente.
  - Répartition 70/30 : 70% des revenus sont conservés par la coopérative (frais
    de production + revenus fermiers).
  - Routage Lightning : Les 30% restants (bénéfices) sont redistribués
    automatiquement aux portefeuilles Bitcoin des investisseurs, au prorata de
    leur participation.

🛡️ Confiance Numérique & Garanties

AgroTrace sécurise l'écosystème grâce à un cadre juridique et technique innovant
:

  - Pacte d'Intégrité : Un contrat d'engagement généré automatiquement pour
    chaque projet, liant juridiquement la coopérative à la plateforme.
  - Signature Cryptographique : Chaque validation de jalon est signée par un
    Hash unique, faisant office de preuve irrévocable devant les parties.
  - Inclusion Totale : Grâce à notre passerelle SMS/USSD, même les producteurs
    en zone blanche (sans internet) peuvent soumettre des preuves et participer
    à l'économie Bitcoin.

🛠️ Partie 2 : Guide Technique (Développeurs & Juges)

Ce guide permet de déployer l'infrastructure GreenBolt Collective en local.

1. Prérequis

  - Environnement : PHP 8.2+, Node.js 18+, MySQL 8.0.
  - Outils : Composer, NPM.

2. Installation Rapide

# 1. Cloner le projet
git clone <URL_DU_DEPOT>
cd AgroTraceLaravel

# 2. Dépendances Back-end & Front-end
composer install
npm install && npm run build

# 3. Environnement
cp .env.example .env
php artisan key:generate

3. Base de Données & Seeding

Configurez votre .env avec vos identifiants MySQL, puis initialisez l'écosystème
béninois :

# Création des tables et injection des 12 régions et comptes de test
php artisan migrate:fresh --seed

4. Intégration Lightning (LNbits)

Pour activer les vrais paiements, renseignez vos clés dans le .env :

LNBITS_URL=https://legend.lnbits.com
LNBITS_API_KEY=votre_cle_invoice_read

5. Lancement

php artisan serve

Accédez à l'interface : http://localhost:8000

🧪 Scénarios de Test (Comptes Démo)

Utilisez ces profils pour explorer les trois facettes de la plateforme :

| Rôle               | Email                    | Mot de passe | Action Clé                                    |
| :----------------- | :----------------------- | :----------- | :-------------------------------------------- |
| **Administrateur** | `admin@agrotrace.com`    | `password`   | Valider les jalons et ancrer sur Bitcoin.     |
| **Investisseur**   | `investor@agrotrace.com` | `password`   | Financer un projet via Lightning QR.          |
| **Coopérative**    | `coop@agrotrace.com`     | `password`   | Créer un projet et soumettre des preuves GPS. |

AgroTrace BTC : Parce que la confiance est le premier engrais de l'agriculture.
🚀🇧🇯⚡️
