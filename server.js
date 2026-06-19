const crypto = require("crypto");
const express = require("express");
const axios = require("axios");
const path = require("path");
const QRCode = require("qrcode");
require("dotenv").config();

const app = express();
const PORT = process.env.PORT || 3000;
const SATS_PER_FCFA = Number(process.env.SATS_PER_FCFA || 1);
const SIMULATION_ENABLED = process.env.SIMULATION_ENABLED !== "false";

app.use(express.json());
app.use(express.static(path.join(__dirname, "public")));

const now = () => new Date().toISOString();
const toNumber = (value) => Number.parseInt(value, 10) || 0;
const clampPercent = (value) => Math.max(0, Math.min(100, Math.round(value)));

const projects = [
  {
    id: "1",
    title: "Semences certifiees pour Glazoue",
    owner: "Cooperative Agri-Etoile",
    region: "Glazoue, Benin",
    category: "Intrants agricoles",
    goal: 500000,
    collected: 185000,
    beneficiaries: 47,
    image: "assets/field-hero.svg",
    summary: "Financer des semences de mais certifiees et un suivi terrain pour ameliorer le rendement de 47 producteurs.",
    impact: ["47 producteurs equipes", "12 hectares suivis", "Revenus attendus +28%"],
    milestones: [
      { id: "j1", title: "Achat des semences", amount: 150000, status: "validated", proof: { description: "Facture fournisseur et photos de livraison", date: now() }, hashBitcoin: "simtx_6b0d1e0f4a" },
      { id: "j2", title: "Distribution aux producteurs", amount: 200000, status: "in_review", proof: { description: "Liste des beneficiaires soumise", date: now() } },
      { id: "j3", title: "Rapport de recolte", amount: 150000, status: "pending", proof: null }
    ],
    transactions: []
  },
  {
    id: "2",
    title: "Irrigation solaire a Parakou",
    owner: "Groupement Agri-Nord",
    region: "Parakou, Benin",
    category: "Eau et energie",
    goal: 750000,
    collected: 310000,
    beneficiaries: 32,
    image: "assets/irrigation.svg",
    summary: "Installer une pompe solaire et un reseau goutte-a-goutte pour maintenir la production pendant la saison seche.",
    impact: ["32 maraichers connectes", "Production hors saison", "Economie d'eau estimee 40%"],
    milestones: [
      { id: "j1", title: "Achat pompe solaire", amount: 300000, status: "validated", proof: { description: "Bon de commande confirme", date: now() }, hashBitcoin: "simtx_9a21cfbd33" },
      { id: "j2", title: "Installation terrain", amount: 250000, status: "pending", proof: null },
      { id: "j3", title: "Audit d'usage", amount: 200000, status: "pending", proof: null }
    ],
    transactions: []
  },
  {
    id: "3",
    title: "Cohorte agroecologie jeunes",
    owner: "Association Terre Verte Benin",
    region: "Abomey-Calavi, Benin",
    category: "Formation",
    goal: 300000,
    collected: 96000,
    beneficiaries: 25,
    image: "assets/training.svg",
    summary: "Former des jeunes aux pratiques agroecologiques, au compostage et a la vente directe locale.",
    impact: ["25 jeunes formes", "3 mois d'accompagnement", "Micro-fermes pilotes"],
    milestones: [
      { id: "j1", title: "Materiel pedagogique", amount: 100000, status: "in_review", proof: { description: "Programme et devis soumis", date: now() } },
      { id: "j2", title: "Sessions pratiques", amount: 150000, status: "pending", proof: null },
      { id: "j3", title: "Certification", amount: 50000, status: "pending", proof: null }
    ],
    transactions: []
  }
];

const invoices = new Map();
const ledger = [];

function blockHash(index, prevHash, timestamp, data) {
  return crypto
    .createHash("sha256")
    .update(JSON.stringify({ index, prevHash, timestamp, data }))
    .digest("hex");
}

function addBlock(type, data) {
  const prevHash = ledger.length ? ledger[ledger.length - 1].hash : "0".repeat(64);
  const index = ledger.length + 1;
  const timestamp = now();
  const payload = { type, ...data };
  const hash = blockHash(index, prevHash, timestamp, payload);
  const block = { index, hash, prevHash, timestamp, data: payload };
  ledger.push(block);
  return block;
}

function publicProject(project) {
  const progress = clampPercent((project.collected / project.goal) * 100);
  return { ...project, progress };
}

function registerPaidInvoice(invoice) {
  if (invoice.paid) return invoice;

  const project = projects.find((item) => item.id === invoice.projectId);
  if (!project) throw new Error("Projet introuvable");

  invoice.paid = true;
  invoice.paidAt = now();
  project.collected += invoice.amountFcfa;
  project.transactions.unshift({
    hash: invoice.hash,
    amountFcfa: invoice.amountFcfa,
    sats: invoice.sats,
    investor: invoice.investor,
    date: invoice.paidAt,
    mode: invoice.mode
  });

  addBlock("PAYMENT_CONFIRMED", {
    projectId: invoice.projectId,
    projectTitle: project.title,
    amountFcfa: invoice.amountFcfa,
    sats: invoice.sats,
    investor: invoice.investor,
    paymentHash: invoice.hash,
    mode: invoice.mode
  });

  return invoice;
}

function createSimulatedInvoice({ project, amountFcfa, investor }) {
  const hash = crypto.randomBytes(24).toString("hex");
  const sats = Math.max(1, Math.round(amountFcfa * SATS_PER_FCFA));
  const invoice = {
    hash,
    projectId: project.id,
    amountFcfa,
    sats,
    investor,
    mode: "simulation",
    paid: false,
    createdAt: now(),
    memo: `AgroTraceBTC | ${project.title} | ${investor}`,
    paymentRequest: `lnbcrt${sats}n1agrotrace${hash.slice(0, 32)}`
  };
  invoices.set(hash, invoice);
  return invoice;
}

async function createLnbitsInvoice({ project, amountFcfa, investor }) {
  const sats = Math.max(1, Math.round(amountFcfa * SATS_PER_FCFA));
  const response = await axios.post(
    `${process.env.LNBITS_URL}/api/v1/payments`,
    {
      out: false,
      amount: sats,
      memo: `AgroTraceBTC | ${project.title} | ${investor}`
    },
    {
      headers: {
        "X-Api-Key": process.env.LNBITS_API_KEY,
        "Content-Type": "application/json"
      },
      timeout: 12000
    }
  );

  const invoice = {
    hash: response.data.payment_hash,
    projectId: project.id,
    amountFcfa,
    sats,
    investor,
    mode: "lnbits",
    paid: false,
    createdAt: now(),
    memo: `AgroTraceBTC | ${project.title} | ${investor}`,
    paymentRequest: response.data.payment_request
  };
  invoices.set(invoice.hash, invoice);
  return invoice;
}

function verifyLedger() {
  return ledger.every((block, index) => {
    const expectedPrev = index === 0 ? "0".repeat(64) : ledger[index - 1].hash;
    const expectedHash = blockHash(block.index, block.prevHash, block.timestamp, block.data);
    return block.prevHash === expectedPrev && block.hash === expectedHash;
  });
}

addBlock("GENESIS", {
  message: "AgroTraceBTC internal traceability ledger",
  hackathon: "Bitcoin Dev Day 2026"
});

projects.forEach((project) => {
  addBlock("PROJECT_REGISTERED", {
    projectId: project.id,
    projectTitle: project.title,
    goal: project.goal,
    owner: project.owner
  });
});

app.get("/", (req, res) => {
  res.sendFile(path.resolve(__dirname, "public", "index.html"));
});

app.get("/api/health", (req, res) => {
  res.json({
    ok: true,
    simulationEnabled: SIMULATION_ENABLED,
    lnbitsConfigured: Boolean(process.env.LNBITS_URL && process.env.LNBITS_API_KEY),
    ledgerValid: verifyLedger()
  });
});

app.get("/api/stats", (req, res) => {
  const totalCollected = projects.reduce((sum, project) => sum + project.collected, 0);
  const totalGoal = projects.reduce((sum, project) => sum + project.goal, 0);
  const beneficiaries = projects.reduce((sum, project) => sum + project.beneficiaries, 0);
  const confirmedPayments = [...invoices.values()].filter((invoice) => invoice.paid).length;

  res.json({
    totalProjects: projects.length,
    totalCollected,
    totalGoal,
    beneficiaries,
    confirmedPayments,
    ledgerBlocks: ledger.length,
    ledgerValid: verifyLedger(),
    simulationEnabled: SIMULATION_ENABLED,
    progress: clampPercent((totalCollected / totalGoal) * 100)
  });
});

app.get("/api/projets", (req, res) => {
  res.json(projects.map(publicProject));
});

app.get("/api/projets/:id", (req, res) => {
  const project = projects.find((item) => item.id === req.params.id);
  if (!project) return res.status(404).json({ error: "Projet non trouve" });
  res.json(publicProject(project));
});

app.get("/api/ledger", (req, res) => {
  res.json({ valid: verifyLedger(), blocks: ledger });
});

app.post("/api/invoice", async (req, res) => {
  try {
    const { projetId, projectId = projetId, investisseur, investor = investisseur } = req.body;
    const amountFcfa = toNumber(req.body.montant || req.body.amountFcfa);
    const project = projects.find((item) => item.id === String(projectId));

    if (!project) return res.status(404).json({ success: false, error: "Projet non trouve" });
    if (amountFcfa < 100) return res.status(400).json({ success: false, error: "Montant minimum: 100 FCFA" });
    if (!investor || String(investor).trim().length < 2) {
      return res.status(400).json({ success: false, error: "Nom investisseur requis" });
    }

    let invoice;
    const canUseLnbits = Boolean(process.env.LNBITS_URL && process.env.LNBITS_API_KEY);

    if (canUseLnbits) {
      try {
        invoice = await createLnbitsInvoice({ project, amountFcfa, investor: String(investor).trim() });
      } catch (error) {
        if (!SIMULATION_ENABLED) throw error;
        invoice = createSimulatedInvoice({ project, amountFcfa, investor: String(investor).trim() });
        invoice.fallbackReason = "LNbits indisponible, simulation activee";
      }
    } else {
      invoice = createSimulatedInvoice({ project, amountFcfa, investor: String(investor).trim() });
    }

    addBlock("INVOICE_CREATED", {
      projectId: project.id,
      projectTitle: project.title,
      amountFcfa: invoice.amountFcfa,
      sats: invoice.sats,
      investor: invoice.investor,
      paymentHash: invoice.hash,
      mode: invoice.mode
    });

    const qrCodeDataUrl = await QRCode.toDataURL(invoice.paymentRequest, { margin: 1, width: 280 });

    res.json({
      success: true,
      invoice: invoice.paymentRequest,
      paymentRequest: invoice.paymentRequest,
      hash: invoice.hash,
      sats: invoice.sats,
      amountFcfa: invoice.amountFcfa,
      mode: invoice.mode,
      qrCodeDataUrl,
      simulationEnabled: SIMULATION_ENABLED,
      fallbackReason: invoice.fallbackReason
    });
  } catch (error) {
    res.status(500).json({ success: false, error: error.message });
  }
});

app.get("/api/check/:hash", async (req, res) => {
  try {
    const invoice = invoices.get(req.params.hash);
    if (!invoice) return res.status(404).json({ paid: false, error: "Invoice inconnue" });

    if (invoice.mode === "lnbits" && !invoice.paid) {
      const response = await axios.get(`${process.env.LNBITS_URL}/api/v1/payments/${invoice.hash}`, {
        headers: { "X-Api-Key": process.env.LNBITS_API_KEY },
        timeout: 12000
      });
      if (response.data.paid) registerPaidInvoice(invoice);
    }

    res.json({
      paid: invoice.paid,
      hash: invoice.hash,
      mode: invoice.mode,
      paidAt: invoice.paidAt || null
    });
  } catch (error) {
    res.status(500).json({ paid: false, error: error.message });
  }
});

app.post("/api/payments/:hash/simulate", (req, res) => {
  try {
    const invoice = invoices.get(req.params.hash);
    if (!invoice) return res.status(404).json({ success: false, error: "Invoice inconnue" });
    if (invoice.mode !== "simulation" && !SIMULATION_ENABLED) {
      return res.status(403).json({ success: false, error: "Simulation desactivee" });
    }
    registerPaidInvoice(invoice);
    res.json({ success: true, paid: true, invoice });
  } catch (error) {
    res.status(500).json({ success: false, error: error.message });
  }
});

app.post("/api/jalon/soumettre", (req, res) => {
  const { projetId, projectId = projetId, jalonId, description } = req.body;
  const project = projects.find((item) => item.id === String(projectId));
  if (!project) return res.status(404).json({ success: false, error: "Projet non trouve" });

  const milestone = project.milestones.find((item) => item.id === jalonId);
  if (!milestone) return res.status(404).json({ success: false, error: "Jalon non trouve" });

  milestone.status = "in_review";
  milestone.proof = { description: description || "Preuve terrain soumise", date: now() };

  addBlock("MILESTONE_SUBMITTED", {
    projectId: project.id,
    projectTitle: project.title,
    milestoneId: milestone.id,
    milestoneTitle: milestone.title,
    proof: milestone.proof.description
  });

  res.json({ success: true, milestone });
});

app.post("/api/jalon/valider", (req, res) => {
  const { projetId, projectId = projetId, jalonId } = req.body;
  const project = projects.find((item) => item.id === String(projectId));
  if (!project) return res.status(404).json({ success: false, error: "Projet non trouve" });

  const milestone = project.milestones.find((item) => item.id === jalonId);
  if (!milestone) return res.status(404).json({ success: false, error: "Jalon non trouve" });

  milestone.status = "validated";
  milestone.validationDate = now();
  milestone.hashBitcoin = `simtx_${crypto.randomBytes(16).toString("hex")}`;

  const block = addBlock("MILESTONE_VALIDATED", {
    projectId: project.id,
    projectTitle: project.title,
    milestoneId: milestone.id,
    milestoneTitle: milestone.title,
    amountFcfa: milestone.amount,
    anchorHash: milestone.hashBitcoin
  });

  res.json({
    success: true,
    hashBitcoin: milestone.hashBitcoin,
    ledgerHash: block.hash,
    lienMempool: `https://mempool.space/testnet/tx/${milestone.hashBitcoin}`
  });
});

app.listen(PORT, () => {
  console.log(`AgroTraceBTC pret sur http://localhost:${PORT}`);
  console.log(`Mode simulation paiement: ${SIMULATION_ENABLED ? "actif" : "inactif"}`);
});
