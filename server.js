const express = require('express');
const axios = require('axios');
require('dotenv').config();

const app = express();

console.log("SERVER ACTIF OK");
console.log("🔥 SERVER AGROTRACEBTC DEMARRÉ");

app.use(express.json());
app.use(express.static('.'));
app.use((req,res,next)=>{
  console.log("URL:", req.url);
  next();
});

let projets = [
  {
    id: "1",
    titre: "Semences certifiées",
    porteur: "Coopérative Agri",
    region: "Glazoué",
    objectif: 500000,
    collecte: 0,
    beneficiaires: 47,
    description: "Achat de semences certifiées de maïs pour 47 agriculteurs.",
    jalons: [
      { id: "j1", titre: "Achat semences", montant: 150000, statut: "en_attente", preuve: null },
      { id: "j2", titre: "Formation agriculteurs", montant: 200000, statut: "en_attente", preuve: null },
      { id: "j3", titre: "Rapport final", montant: 150000, statut: "en_attente", preuve: null }
    ],
    transactions: []
  },
  {
    id: "2",
    titre: "Irrigation coopérative Parakou",
    porteur: "Groupement Agri-Nord",
    region: "Parakou",
    objectif: 750000,
    collecte: 0,
    beneficiaires: 32,
    description: "Installation d'un système d'irrigation pour cultiver pendant la saison sèche.",
    jalons: [
      { id: "j1", titre: "Achat équipement", montant: 300000, statut: "en_attente", preuve: null },
      { id: "j2", titre: "Installation", montant: 250000, statut: "en_attente", preuve: null },
      { id: "j3", titre: "Rapport final", montant: 200000, statut: "en_attente", preuve: null }
    ],
    transactions: []
  },
  {
    id: "3",
    titre: "Formation agroécologie jeunes",
    porteur: "Association Terre Verte Bénin",
    region: "Abomey-Calavi",
    objectif: 300000,
    collecte: 0,
    beneficiaires: 25,
    description: "Programme de formation de 3 mois sur les techniques agroécologiques.",
    jalons: [
      { id: "j1", titre: "Matériel formation", montant: 100000, statut: "en_attente", preuve: null },
      { id: "j2", titre: "Sessions formation", montant: 150000, statut: "en_attente", preuve: null },
      { id: "j3", titre: "Certification", montant: 50000, statut: "en_attente", preuve: null }
    ],
    transactions: []
  }
];

app.get('/', (req, res) => {
  res.send("🚀 AgrotraceBTC backend OK");
});

app.get('/api/projets', (req, res) => {
  console.log("API PROJETS appelée");
  res.json(projets);
});

app.get('/api/projets/:id', (req, res) => {
  console.log("API PROJET ID appelée :", req.params.id);
  const projet = projets.find(p => p.id === req.params.id);
  if (!projet) return res.status(404).json({ error: "Projet non trouvé" });
  res.json(projet);
});

app.post('/api/invoice', async (req, res) => {
  try {
    const { projetId, montant, investisseur } = req.body;
    const response = await axios.post(
      `${process.env.LNBITS_URL}/api/v1/payments`,
      { out: false, amount: montant, memo: `AgrotraceBTC - ${projetId} - ${investisseur}` },
      { headers: { 'X-Api-Key': process.env.LNBITS_API_KEY, 'Content-Type': 'application/json' } }
    );
    res.json({ success: true, invoice: response.data.payment_request, hash: response.data.payment_hash });
  } catch (error) {
    console.error("ERREUR LNbits :", error.message);
    res.json({ success: false, error: error.message });
  }
});

app.get('/api/check/:hash', async (req, res) => {
  try {
    const { hash } = req.params;
    const { projetId, montant } = req.query;
    const response = await axios.get(
      `${process.env.LNBITS_URL}/api/v1/payments/${hash}`,
      { headers: { 'X-Api-Key': process.env.LNBITS_API_KEY } }
    );
    const paid = response.data.paid;
    if (paid && projetId && montant) {
      const projet = projets.find(p => p.id === projetId);
      if (projet) {
        projet.collecte += parseInt(montant);
        projet.transactions.push({ hash, montant: parseInt(montant), date: new Date().toISOString() });
      }
    }
    res.json({ paid });
  } catch (error) {
    res.json({ paid: false, error: error.message });
  }
});

app.post('/api/jalon/soumettre', (req, res) => {
  const { projetId, jalonId, description } = req.body;
  const projet = projets.find(p => p.id === projetId);
  if (!projet) return res.status(404).json({ error: "Projet non trouvé" });
  const jalon = projet.jalons.find(j => j.id === jalonId);
  if (!jalon) return res.status(404).json({ error: "Jalon non trouvé" });
  jalon.statut = "en_validation";
  jalon.preuve = { description, date: new Date().toISOString() };
  res.json({ success: true, message: "Preuve soumise" });
});

app.post('/api/jalon/valider', (req, res) => {
  const { projetId, jalonId } = req.body;
  const projet = projets.find(p => p.id === projetId);
  if (!projet) return res.status(404).json({ error: "Projet non trouvé" });
  const jalon = projet.jalons.find(j => j.id === jalonId);
  if (!jalon) return res.status(404).json({ error: "Jalon non trouvé" });
  jalon.statut = "valide";
  jalon.dateValidation = new Date().toISOString();
  const hashBitcoin = `btc_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
  jalon.hashBitcoin = hashBitcoin;
  res.json({ success: true, message: "Jalon validé", hashBitcoin, lienMempool: `https://mempool.space/testnet/tx/${hashBitcoin}` });
});

app.listen(process.env.PORT || 3000, () => {
  console.log(`AgrotraceBTC lancé sur http://localhost:${process.env.PORT || 3000}`);
});