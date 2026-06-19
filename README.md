# AgroTraceBTC

Plateforme web de demo pour financer des projets agricoles avec Bitcoin Lightning et tracer les investissements dans un registre interne de type pseudo-blockchain.

## Fonctionnalites

- Paiements Bitcoin Lightning via LNbits si `LNBITS_URL` et `LNBITS_API_KEY` sont configures.
- Mode simulation integre pour les demos hackathon sans wallet reel.
- Creation d'invoices, QR code et verification de paiement par polling.
- Registre interne chaine par hashes SHA-256.
- Suivi des projets, fonds collectes, investisseurs et jalons agricoles.
- Back-office de validation des jalons avec inscription dans le registre.
- Interface HTML/CSS/JavaScript vanilla + Bootstrap.

## Installation

```bash
npm install
npm start
```

Ouvrir ensuite:

```text
http://localhost:3000
```

## Configuration

Copier `.env.example` vers `.env` pour personnaliser le comportement local.

```bash
cp .env.example .env
```

Variables utiles:

```text
PORT=3000
SIMULATION_ENABLED=true
SATS_PER_FCFA=1
LNBITS_URL=https://your-lnbits-instance.com
LNBITS_API_KEY=your_invoice_read_key
```

Si LNbits n'est pas configure, l'application bascule en mode simulation.

## Demo conseillee

1. Ouvrir la page d'accueil.
2. Choisir un projet agricole.
3. Cliquer sur `Investir`.
4. Generer une invoice Lightning.
5. Cliquer sur `Simuler le paiement recu`.
6. Ouvrir l'explorer pour voir le bloc de paiement.
7. Aller dans l'admin, soumettre ou valider un jalon, puis verifier le nouveau bloc.
