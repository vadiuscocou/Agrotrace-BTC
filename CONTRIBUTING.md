# Contribuer à AgroTrace BTC

Merci de vouloir contribuer ! Ce guide couvre l'installation locale et les scénarios de test.

## Prérequis

- PHP 8.2+
- Node.js 18+
- MySQL 8.0
- Composer, NPM

## Installation rapide

```bash
git clone <URL_DU_DEPOT>
cd AgroTrace-BTC
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate

# Initialiser l'écosystème (12 régions et comptes tests)
php artisan migrate:fresh --seed
```

## Intégration Bitcoin (LNbits)

Dans votre `.env`, configurez votre nœud Lightning :
```env
LNBITS_URL=https://legend.lnbits.com
LNBITS_API_KEY=votre_cle_invoice_read
```

## Scénarios de test

| Rôle | Email | Action clé |
| :--- | :--- | :--- |
| **Administrateur** | `admin@agrotrace.com` | Valide les preuves et ancre les hashs sur Bitcoin |
| **Investisseur** | `investor@agrotrace.com` | Finance un jalon et télécharge son Contrat CT-ID |
| **Coopérative** | `coop@agrotrace.com` | Publie un projet et signe le pacte d'intégrité |

## Bonnes pratiques avant de contribuer

- Ne jamais commiter de `.env` (déjà dans `.gitignore`)
- Ne jamais commiter `node_modules/` ou `vendor/`
- Ouvrir une Pull Request vers `main` avec une description claire des changements
