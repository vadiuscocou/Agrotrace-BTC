<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat d'Engagement - {{ $project->title }}</title>
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
        <h2 class="text-2xl font-bold uppercase mt-4">Contrat d'Engagement Numérique</h2>
        <p class="text-slate-500 mt-2 italic">Garantie de Financement et de Rendement Agricole</p>
    </div>

    <!-- Preamble -->
    <div class="mb-8 text-justify leading-relaxed text-lg">
        <p class="mb-4">Le présent contrat numérique est généré automatiquement par la plateforme <strong>AgroTrace</strong> et engage formellement le porteur de projet désigné ci-dessous, suite à sa validation par l'administration.</p>
        
        <p><strong>Fait virtuellement à :</strong> Siège AgroTrace<br>
        <strong>Le :</strong> {{ $project->created_at->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- Parties -->
    <div class="mb-10 border border-slate-300 p-6 bg-slate-50">
        <h3 class="text-xl font-bold mb-4 uppercase underline">Entre les soussignés :</h3>
        <p class="mb-2"><strong>La Coopérative / Le Porteur de Projet :</strong> {{ optional($project->user)->name ?? 'Utilisateur Inconnu' }}</p>
        <p class="mb-2"><strong>Localisation :</strong> {{ $project->region }}</p>
        <p class="mb-4"><em>Ci-après désigné "Le Bénéficiaire"</em></p>

        <p class="mb-2"><strong>ET</strong></p>

        <p class="mb-2"><strong>Les Investisseurs de la plateforme AgroTrace</strong> (représentés par l'entité gestionnaire des fonds séquestrés via Lightning Network).</p>
        <p><em>Ci-après désignés "Les Investisseurs"</em></p>
    </div>

    <!-- Project Details -->
    <div class="mb-10 text-justify leading-relaxed text-lg space-y-6">
        <h3 class="text-xl font-bold mb-4 uppercase underline">Article 1 : Objet du Financement</h3>
        <p>Le Bénéficiaire sollicite et accepte un financement participatif d'un montant cible de <strong>{{ number_format($project->target_amount_fcfa) }} FCFA</strong> pour la réalisation du projet intitulé <em>"{{ $project->title }}"</em>.</p>
        <p>Ce financement sera débloqué progressivement par AgroTrace sous réserve de la validation stricte des preuves d'avancement (jalons) soumises par le Bénéficiaire.</p>

        <h3 class="text-xl font-bold mb-4 uppercase underline mt-8">Article 2 : Engagement Formel de Rendement (8%)</h3>
        <p>Le Bénéficiaire <strong>s'engage formellement, inconditionnellement et irrévocablement</strong> à reverser la totalité du capital investi, majoré d'une <strong>plus-value nette garantie de 8%</strong> à l'issue du cycle de production ou de la vente des récoltes.</p>
        <p>Le montant total à restituer aux Investisseurs, via la plateforme AgroTrace, est fixé à : <strong>{{ number_format($project->target_amount_fcfa * 1.08) }} FCFA</strong>.</p>
        
        <h3 class="text-xl font-bold mb-4 uppercase underline mt-8">Article 3 : Transparence et Sanctions</h3>
        <p>Le Bénéficiaire s'engage à fournir des preuves tangibles (photos, reçus) pour chaque étape du projet. En cas de fraude avérée, de détournement de fonds ou de rupture abusive de l'engagement de remboursement, AgroTrace se réserve le droit de suspendre immédiatement les décaissements et d'engager des poursuites judiciaires, en s'appuyant sur l'identité vérifiée du Bénéficiaire et les documents justificatifs fournis lors de l'inscription.</p>
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
                <p class="font-bold mb-4">Le Bénéficiaire (Consentement Numérique) :</p>
                <div class="border border-[#063b27] text-[#063b27] bg-[#063b27]/5 px-6 py-3 inline-block text-sm text-left">
                    <i class="fa-solid fa-check-circle mr-2"></i><strong>Lu, Approuvé et Signé</strong><br>
                    <span class="font-mono text-xs text-slate-500 mt-1 block">Hash cryptographique de soumission :<br>
                    {{ hash('sha256', $project->id . $project->created_at . $project->target_amount_fcfa . $project->user_id) }}</span>
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
