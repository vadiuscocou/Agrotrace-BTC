<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat d'Investissement - {{ $investment->project->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white;
            }
            .contract-box {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                padding: 0 !important;
            }
        }
        body {
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>
<body class="bg-slate-100 py-10">

<div class="max-w-4xl mx-auto bg-white p-12 md:p-20 shadow-2xl border border-slate-200 contract-box relative">
    
    <!-- Print Button -->
    <button onclick="window.print()" class="no-print absolute top-8 right-8 bg-slate-800 text-white px-4 py-2 rounded-lg font-sans text-sm hover:bg-slate-700 transition flex items-center gap-2">
        <i class="fa-solid fa-print"></i> Imprimer / Sauvegarder PDF
    </button>

    <!-- Header -->
    <div class="text-center mb-12 border-b-2 border-slate-800 pb-8">
        <div class="flex justify-center items-center gap-3 mb-4">
            <i class="fa-solid fa-seedling text-3xl text-[#063b27]"></i>
            <h1 class="text-3xl font-bold tracking-widest uppercase">AgroTrace</h1>
        </div>
        <h2 class="text-2xl font-bold uppercase mt-4">Contrat d'Investissement Nominatif</h2>
        <p class="text-slate-500 mt-2 italic">Garantie de Financement et de Rendement Agricole</p>
    </div>

    <!-- Preamble -->
    <div class="mb-8 text-justify leading-relaxed text-lg">
        <p class="mb-4">Le présent contrat numérique est généré automatiquement par la plateforme <strong>AgroTrace</strong> et formalise l'accord entre les parties prenantes du projet agricole, suite à sa validation par l'administration.</p>
        
        <p><strong>Fait virtuellement à :</strong> Siège AgroTrace<br>
        <strong>ID Contrat :</strong> CT-INV-{{ $investment->id }}-{{ $investment->project->formatted_id }}-{{ date('Y') }}<br>
        <strong>Le :</strong> {{ $investment->created_at->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- Parties -->
    <div class="mb-10 border border-slate-300 p-6 bg-slate-50">
        <h3 class="text-xl font-bold mb-4 uppercase underline">Entre les soussignés :</h3>
        <p class="mb-2"><strong>La Coopérative / Le Porteur de Projet :</strong> {{ optional($investment->project->user)->name ?? 'Utilisateur Inconnu' }}</p>
        <p class="mb-2"><strong>Localisation :</strong> {{ $investment->project->region }}</p>
        <p class="mb-4"><em>Ci-après désigné "Le Bénéficiaire" ou "Le Porteur de Projet"</em></p>

        <p class="mb-2"><strong>ET</strong></p>

        <p class="mb-2"><strong>L'Investisseur :</strong> {{ $investment->user->name }}</p>
        <p class="mb-4"><em>Ci-après désigné "L'Investisseur"</em></p>

        <p class="mb-2"><strong>EN PRÉSENCE DE :</strong></p>
        <p class="mb-2"><strong>AgroTrace BTC</strong>, agissant en tant que tiers de confiance, garant technique et financier.</p>
        <p><em>Ci-après désignée "La Plateforme"</em></p>
    </div>

    <!-- Project Details -->
    <div class="mb-10 text-justify leading-relaxed text-lg space-y-6">
        <h3 class="text-xl font-bold mb-4 uppercase underline text-[#063b27]">Article 1 : Objet du Contrat</h3>
        <p>L'Investisseur s'engage à financer le projet <em>"{{ $investment->project->title }}"</em> à hauteur de <strong>{{ number_format($investment->amount_fcfa) }} FCFA</strong>.</p>
        @php
            $percentage = ($investment->amount_fcfa / $investment->project->target_amount_fcfa) * 100;
        @endphp
        <p>Cet apport représente <strong>{{ number_format($percentage, 2) }}%</strong> du budget total du projet ({{ number_format($investment->project->target_amount_fcfa) }} FCFA).</p>
        <p>Ce financement sera débloqué progressivement par AgroTrace sous réserve de la validation stricte des preuves d'avancement (jalons) soumises par le Bénéficiaire, garantissant ainsi le bon usage des fonds.</p>

        <h3 class="text-xl font-bold mb-4 uppercase underline text-[#063b27] mt-8">Article 2 : Obligations des Parties</h3>
        
        <h4 class="font-bold mt-4">2.1. Obligations de l'Investisseur</h4>
        <ul class="list-disc pl-6 space-y-2">
            <li>S'engage à fournir les fonds via le réseau Bitcoin (Lightning Network) de manière irrévocable.</li>
            <li>Accepte que les fonds soient bloqués dans un portefeuille séquestre géré par La Plateforme et libérés par jalons.</li>
            <li>Accepte le risque inhérent à toute activité agricole, tempéré par le mécanisme de validation par jalons.</li>
        </ul>

        <h4 class="font-bold mt-4">2.2. Obligations du Porteur de Projet (Bénéficiaire)</h4>
        <ul class="list-disc pl-6 space-y-2">
            <li>S'engage à utiliser l'intégralité des fonds alloués exclusivement pour le projet <em>"{{ $investment->project->title }}"</em>.</li>
            <li>S'engage à fournir des preuves tangibles (photos, reçus) pour chaque jalon sur La Plateforme.</li>
            <li>S'engage à déclarer de manière honnête et transparente la quantité récoltée et le prix de vente à l'issue de la campagne.</li>
        </ul>

        <h4 class="font-bold mt-4">2.3. Obligations de la Plateforme (AgroTrace)</h4>
        <ul class="list-disc pl-6 space-y-2">
            <li>Garantir la sécurité des fonds et leur déblocage exclusif sur validation des jalons.</li>
            <li>Assurer la transparence et la traçabilité des investissements sur la Blockchain.</li>
            <li>Calculer automatiquement la répartition des revenus et procéder à la distribution via Lightning Network.</li>
        </ul>

        <h3 class="text-xl font-bold mb-4 uppercase underline text-[#063b27] mt-8">Article 3 : Répartition des Revenus</h3>
        <p>À l'issue de la vente des récoltes, le chiffre d'affaires généré sera automatiquement réparti par La Plateforme selon la règle de base (70% pour la Coopérative, 30% pour les Investisseurs).</p>
        <p>L'Investisseur étant titulaire de <strong>{{ number_format($percentage, 2) }}%</strong> du financement, il percevra <strong>{{ number_format($percentage, 2) }}%</strong> de la part globale de dividendes de 30%.</p>

        <h3 class="text-xl font-bold mb-4 uppercase underline text-[#063b27] mt-8">Article 4 : Modalités de Remboursement</h3>
        <p>Le Bénéficiaire procède au remboursement du capital et des dividendes via la création d'une facture Lightning sur AgroTrace. La Plateforme se charge ensuite de router automatiquement la part de chaque investisseur (les 30%) directement vers leur portefeuille Lightning personnel, sans frais de transaction bancaire.</p>
        
        <h3 class="text-xl font-bold mb-4 uppercase underline text-[#063b27] mt-8">Article 5 : Gestion des Risques et Force Majeure</h3>
        <p>Sont considérés comme cas de force majeure les événements climatiques extrêmes (sécheresse prolongée, inondations), les catastrophes naturelles ou les épidémies dévastatrices de cultures, dûment constatés par l'administration locale.</p>
        <p>En cas de force majeure avérée entraînant la perte de la récolte :</p>
        <ul class="list-disc pl-6 space-y-2">
            <li>Les fonds non encore décaissés pour les jalons futurs sont restitués aux Investisseurs.</li>
            <li>Le Bénéficiaire est dispensé de rembourser les fonds déjà dépensés pour les jalons validés.</li>
            <li>Les Investisseurs acceptent la perte du capital correspondant aux jalons validés (risque mutualisé).</li>
        </ul>
        <p>En dehors des cas de force majeure, toute défaillance ou tentative de fraude entraînera des poursuites judiciaires, le gel du compte, et la dégradation définitive du <em>Score de Confiance</em> du Bénéficiaire.</p>
    </div>

    <!-- Signatures -->
    <div class="mt-16 pt-8 border-t-2 border-slate-800">
        <div class="flex flex-col md:flex-row justify-between items-end gap-8">
            <div>
                <p class="font-bold mb-4">Pour AgroTrace (La Plateforme) :</p>
                <div class="border-2 border-blue-600 text-blue-600 px-4 py-2 inline-block font-mono text-sm transform -rotate-2">
                    CERTIFIÉ CONFORME<br>
                    ID: AGT-SYS-{{ date('Y') }}
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold mb-4">L'Investisseur (Consentement Blockchain) :</p>
                <div class="border border-[#063b27] text-[#063b27] bg-[#063b27]/5 px-6 py-3 inline-block text-sm text-left">
                    <i class="fa-solid fa-check-circle mr-2"></i><strong>Lu, Approuvé et Payé</strong><br>
                    <span class="font-mono text-xs text-slate-500 mt-1 block">Hash cryptographique (Lightning) :<br>
                    {{ substr($investment->payment_hash, 0, 32) }}...</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer info -->
    <div class="mt-12 text-center text-xs text-slate-400 no-print">
        <p>Document généré sécuritairement sur AgroTrace. Ne pas modifier ce document.</p>
    </div>
</div>

</body>
</html>
