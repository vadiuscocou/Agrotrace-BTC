#  AgroTrace BTC
### *La vérité du champ à la Blockchain.*

**AgroTrace BTC** est une plateforme de financement participatif agricole qui brise le plafond de verre de la méfiance en Afrique. En fusionnant l'agronomie béninoise avec la puissance de **Bitcoin Lightning**, nous permettons à la diaspora de financer la terre natale avec une certitude mathématique.

---

##  Partie 1 : L'Expérience AgroTrace (Guide Investisseur)

### 1. Sélectionner l'Impact
Explorez notre cartographie interactive couvrant les **12 départements du Bénin**. Chaque projet est audité et régi par un **Contrat d'Engagement Numérique** généré dès la validation du projet.

### 2. Investissement "Frictionless"
- **Rapidité :** Paiement instantané via QR Code (Lightning Network).
- **Sécurité :** Fonds bloqués dans un portefeuille séquestre et libérés uniquement par jalons validés.
- **Micro-investissement :** Accessible à tous les budgets.

### 3. La Preuve de Travail Agricole
L'argent est décaissé progressivement. Pour chaque jalon (ex: semis, fertilisation), la coopérative soumet une preuve (Photo + GPS). Une fois validée, l'empreinte numérique (**Hash OP_RETURN**) est gravée sur la blockchain Bitcoin, rendant le suivi infalsifiable.

### 4. Partage de Revenus (Modèle 70/30)
À l'issue de la vente des récoltes, le chiffre d'affaires est réparti ainsi :
- **70 %** pour la Coopérative (Revenus fermiers et coûts de production).
- **30 %** pour les Investisseurs (Principal + Dividendes).
- **Remboursement :** S'effectue en **3 tranches** via Lightning Network pour garantir la liquidité.

---

## Confiance Numérique & Gestion des Risques

AgroTrace sécurise l'écosystème grâce à un cadre juridique et technique strict :

*   **Signature Cryptographique :** Chaque contrat est signé numériquement par un **Hash SHA-256** unique, liant irrévocablement le porteur de projet à ses obligations.
*   **Structure des Frais (Garantie de 5%) :**
    *   **2% de frais de service** pour l'infrastructure blockchain.
    *   **3% pour le Fonds d'Indemnisation** : Une réserve mutuelle qui sert de garantie. *Ce montant est intégralement restitué à la coopérative si le projet est mené à bien sans sinistre.*
*   **Cas de Force Majeure :** En cas d'aléa climatique majeur (sécheresse, inondation) constaté par l'administration, les fonds non encore utilisés sont restitués aux investisseurs, protégeant ainsi le capital restant.
*   **Score de Confiance :** Tout retard ou fraude entraîne une dégradation publique du score de la coopérative et des poursuites via le contrat numérique.

---

## Partie 2 : Guide Technique (Développeurs & Juges)

### 1. Prérequis
- **Environnement :** PHP 8.2+, Node.js 18+, MySQL 8.0.
- **Outils :** Composer, NPM.

### 2. Installation Rapide
```bash
# Cloner et configurer
git clone <URL_DU_DEPOT>
cd AgroTrace-BTC
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate

# Initialiser l'écosystème (12 régions et comptes tests)
php artisan migrate:fresh --seed
```

### 3. Intégration Bitcoin (LNbits)
Dans votre `.env`, configurez votre nœud Lightning :
```env
LNBITS_URL=https://legend.lnbits.com
LNBITS_API_KEY=votre_cle_invoice_read
```

---

## Scénarios de Test

| Rôle | Email | Action Clé |
| :--- | :--- | :--- |
| **Administrateur** | `admin@agrotrace.com` | Valide les preuves et ancre les hashs sur Bitcoin. |
| **Investisseur** | `investor@agrotrace.com` | Finance un jalon et télécharge son **Contrat CT-ID**. |
| **Coopérative** | `coop@agrotrace.com` | Publie un projet et signe le pacte d'intégrité. |

---
**AgroTrace BTC** : *La technologie Bitcoin au service de la souveraineté alimentaire.* 🚀🇧🇯⚡️
