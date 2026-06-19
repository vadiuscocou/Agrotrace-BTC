const money = new Intl.NumberFormat("fr-FR");
const params = new URLSearchParams(window.location.search);

function fcfa(value) {
  return `${money.format(value || 0)} FCFA`;
}

function statusLabel(status) {
  return {
    pending: "En attente",
    in_review: "En validation",
    validated: "Valide"
  }[status] || status;
}

function blockTypeLabel(type) {
  return {
    GENESIS: "Genese",
    PROJECT_REGISTERED: "Projet inscrit",
    INVOICE_CREATED: "Facture creee",
    PAYMENT_CONFIRMED: "Paiement confirme",
    MILESTONE_SUBMITTED: "Jalon soumis",
    MILESTONE_VALIDATED: "Jalon valide"
  }[type] || type;
}

function setLoading(active) {
  document.body.classList.toggle("loading-ui", active);
}

async function api(path, options) {
  setLoading(true);
  try {
    const response = await fetch(path, options);
    const data = await response.json();
    if (!response.ok) throw new Error(data.error || "Erreur API");
    return data;
  } finally {
    setLoading(false);
  }
}

function projectCard(project) {
  return `
    <div class="col-lg-4 col-md-6">
      <article class="project-card fade-in">
        <img src="${project.image}" alt="${project.title}">
        <div class="content">
          <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
            <span class="tag green">${project.category}</span>
            <span class="muted small">${project.progress}%</span>
          </div>
          <h3 class="h5 fw-black">${project.title}</h3>
          <p class="muted mb-2">${project.region} - ${project.owner}</p>
          <p>${project.summary}</p>
          <div class="progress mb-2">
            <div class="progress-bar" style="width:${project.progress}%"></div>
          </div>
          <div class="d-flex justify-content-between muted small mb-3">
            <span>${fcfa(project.collected)}</span>
            <span>${fcfa(project.goal)}</span>
          </div>
          <div class="d-flex gap-2">
            <a class="btn btn-outline-light flex-fill" href="projet-detail.html?id=${project.id}">Voir</a>
            <a class="btn btn-primary flex-fill" href="investir.html?id=${project.id}">Investir</a>
          </div>
        </div>
      </article>
    </div>
  `;
}

async function loadHome() {
  const [stats, projects] = await Promise.all([api("/api/stats"), api("/api/projets")]);
  document.querySelector("[data-stat='projects']").textContent = stats.totalProjects;
  document.querySelector("[data-stat='funds']").textContent = fcfa(stats.totalCollected);
  document.querySelector("[data-stat='beneficiaries']").textContent = money.format(stats.beneficiaries);
  document.querySelector("[data-stat='blocks']").textContent = stats.ledgerBlocks;
  document.querySelector("#projects-grid").innerHTML = projects.map(projectCard).join("");
  document.querySelector("#ledger-state").textContent = stats.ledgerValid ? "Registre verifie" : "Registre invalide";
}

async function loadDashboard() {
  const [stats, projects] = await Promise.all([api("/api/stats"), api("/api/projets")]);
  document.querySelector("[data-stat='funds']").textContent = fcfa(stats.totalCollected);
  document.querySelector("[data-stat='target']").textContent = fcfa(stats.totalGoal);
  document.querySelector("[data-stat='payments']").textContent = stats.confirmedPayments;
  document.querySelector("[data-stat='progress']").textContent = `${stats.progress}%`;
  document.querySelector("#projects-grid").innerHTML = projects.map(projectCard).join("");
}

async function loadProjectDetail() {
  const project = await api(`/api/projets/${params.get("id")}`);
  document.title = `${project.title} - AgroTraceBTC`;
  document.querySelector("#project-title").textContent = project.title;
  document.querySelector("#project-meta").textContent = `${project.region} - ${project.owner}`;
  document.querySelector("#project-image").src = project.image;
  document.querySelector("#project-image").alt = project.title;
  document.querySelector("#project-summary").textContent = project.summary;
  document.querySelector("#project-progress").style.width = `${project.progress}%`;
  document.querySelector("#project-progress-label").textContent = `${project.progress}% finance`;
  document.querySelector("#project-money").textContent = `${fcfa(project.collected)} sur ${fcfa(project.goal)}`;
  document.querySelector("#invest-link").href = `investir.html?id=${project.id}`;
  document.querySelector("#impact-list").innerHTML = project.impact.map((item) => `<li>${item}</li>`).join("");
  document.querySelector("#milestones").innerHTML = project.milestones.map((milestone) => `
    <div class="milestone">
      <div class="d-flex justify-content-between gap-3">
        <strong><span class="status-dot ${milestone.status}"></span> ${milestone.title}</strong>
        <span>${fcfa(milestone.amount)}</span>
      </div>
      <div class="muted small mt-2">${statusLabel(milestone.status)}</div>
      ${milestone.proof ? `<div class="small mt-2">Preuve: ${milestone.proof.description}</div>` : ""}
      ${milestone.hashBitcoin ? `<div class="hash mt-2">${milestone.hashBitcoin}</div>` : ""}
    </div>
  `).join("");
  document.querySelector("#transactions").innerHTML = project.transactions.length
    ? project.transactions.map((tx) => `<div class="hash mb-2">${tx.paymentHash || tx.hash} - ${fcfa(tx.amountFcfa)} - ${tx.investor}</div>`).join("")
    : `<p class="muted mb-0">Les prochains paiements confirmes apparaitront ici.</p>`;
}

async function loadInvestPage() {
  const project = await api(`/api/projets/${params.get("id")}`);
  document.querySelector("#project-name").textContent = project.title;
  document.querySelector("#project-context").textContent = `${project.region} - objectif ${fcfa(project.goal)}`;
  document.querySelector("#amount").value = 10000;
}

async function createInvoice(event) {
  event.preventDefault();
  const payload = {
    projectId: params.get("id"),
    amountFcfa: Number(document.querySelector("#amount").value),
    investor: document.querySelector("#investor").value.trim()
  };

  const data = await api("/api/invoice", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload)
  });

  window.currentPaymentHash = data.hash;
  document.querySelector("#qr").innerHTML = `<img src="${data.qrCodeDataUrl}" alt="QR Lightning">`;
  document.querySelector("#invoice").textContent = data.paymentRequest;
  document.querySelector("#payment-mode").textContent = data.mode === "simulation" ? "Mode simulation" : "LNbits reel";
  document.querySelector("#payment-status").textContent = "Facture creee. En attente de paiement.";
  document.querySelector("#simulate-payment").hidden = data.mode !== "simulation";
  document.querySelector("#payment-result").hidden = false;
  pollPayment(data.hash);
}

async function simulatePayment() {
  if (!window.currentPaymentHash) return;
  const data = await api(`/api/payments/${window.currentPaymentHash}/simulate`, { method: "POST" });
  if (data.success) await showPaid();
}

function pollPayment(hash) {
  clearInterval(window.paymentPoller);
  window.paymentPoller = setInterval(async () => {
    const data = await api(`/api/check/${hash}`);
    if (data.paid) {
      clearInterval(window.paymentPoller);
      await showPaid();
    }
  }, 2500);
}

async function showPaid() {
  document.querySelector("#payment-status").textContent = "Paiement confirme. Fonds traces dans le registre interne.";
  document.querySelector("#simulate-payment").hidden = true;
  setTimeout(() => {
    window.location.href = `projet-detail.html?id=${params.get("id")}`;
  }, 1800);
}

async function loadExplorer() {
  const ledger = await api("/api/ledger");
  document.querySelector("#ledger-valid").textContent = ledger.valid ? "Chaine valide" : "Chaine invalide";
  document.querySelector("#ledger").innerHTML = ledger.blocks.slice().reverse().map((block) => `
    <article class="block-card fade-in">
      <span class="tag ${block.data.type === "PAYMENT_CONFIRMED" ? "green" : "blue"}">${blockTypeLabel(block.data.type)}</span>
      <h2 class="h5 mt-3">Bloc #${block.index}</h2>
      <div class="hash">Hash: ${block.hash}</div>
      <div class="hash">Prev: ${block.prevHash}</div>
      <hr>
      <pre class="hash mb-0">${JSON.stringify(block.data, null, 2)}</pre>
      <div class="muted small mt-3">${block.timestamp}</div>
    </article>
  `).join("");
}

async function loadAdmin() {
  const projects = await api("/api/projets");
  document.querySelector("#admin-projects").innerHTML = projects.map((project) => `
    <div class="panel mb-3">
      <div class="d-flex justify-content-between gap-3 align-items-start">
        <div>
          <h2 class="h4">${project.title}</h2>
          <p class="muted">${project.owner} - ${fcfa(project.collected)} collectes</p>
        </div>
        <span class="tag green">${project.progress}%</span>
      </div>
      ${project.milestones.map((milestone) => `
        <div class="milestone">
          <div class="d-flex justify-content-between gap-3">
            <strong>${milestone.title}</strong>
            <span>${statusLabel(milestone.status)}</span>
          </div>
          <div class="d-flex flex-wrap gap-2 mt-3">
            <button class="btn btn-outline-light btn-sm" onclick="submitMilestone('${project.id}','${milestone.id}')">Soumettre preuve</button>
            <button class="btn btn-primary btn-sm" onclick="validateMilestone('${project.id}','${milestone.id}')">Valider jalon</button>
          </div>
        </div>
      `).join("")}
    </div>
  `).join("");
}

async function submitMilestone(projectId, milestoneId) {
  await api("/api/jalon/soumettre", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ projectId, jalonId: milestoneId, description: "Preuve terrain ajoutee pendant la demo" })
  });
  await loadAdmin();
}

async function validateMilestone(projectId, milestoneId) {
  await api("/api/jalon/valider", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ projectId, jalonId: milestoneId })
  });
  await loadAdmin();
}

window.AgroTrace = {
  loadHome,
  loadDashboard,
  loadProjectDetail,
  loadInvestPage,
  createInvoice,
  simulatePayment,
  loadExplorer,
  loadAdmin
};
