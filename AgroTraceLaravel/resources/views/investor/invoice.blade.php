<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture - {{ $investment->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            .invoice-box { box-shadow: none !important; border: none !important; max-width: 100% !important; padding: 0 !important; }
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 py-10">

<div class="max-w-3xl mx-auto bg-white p-12 shadow-xl border border-slate-100 invoice-box relative">
    <!-- Print Button -->
    <button onclick="window.print()" class="no-print absolute top-8 right-8 bg-[#063b27] text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-[#0a4b33] transition flex items-center gap-2">
        <i class="fa-solid fa-print"></i> Imprimer la Facture
    </button>

    <!-- Header -->
    <div class="flex justify-between items-start mb-12 border-b-2 border-slate-100 pb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="h-10 w-10 bg-[#063b27] rounded-lg flex items-center justify-center text-white text-xl">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <h1 class="text-2xl font-black tracking-widest uppercase text-slate-900">AgroTrace-BTC</h1>
            </div>
            <p class="text-slate-500 text-sm">Plateforme de Financement Agricole</p>
            <p class="text-slate-500 text-sm">Lightning Network (Bitcoin)</p>
        </div>
        <div class="text-right">
            <h2 class="text-3xl font-black text-slate-200 uppercase tracking-widest">Facture</h2>
            <p class="text-slate-900 font-bold mt-2">N° FAC-{{ date('Y') }}-{{ str_pad($investment->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p class="text-slate-500 text-sm">Date : {{ $investment->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Info Section -->
    <div class="flex justify-between mb-12">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Facturé à :</p>
            <h3 class="text-lg font-bold text-slate-900">{{ $investment->user->name }}</h3>
            <p class="text-slate-500 text-sm">{{ $investment->user->email }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Projet Financé :</p>
            <h3 class="text-lg font-bold text-slate-900">{{ $investment->project->title }}</h3>
            <p class="text-slate-500 text-sm">Réf : {{ $investment->project->formatted_id }}</p>
        </div>
    </div>

    <!-- Table -->
    <table class="w-full mb-12 text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-y border-slate-200 text-sm">
                <th class="py-4 px-4 font-bold text-slate-700 uppercase tracking-wider">Description</th>
                <th class="py-4 px-4 font-bold text-slate-700 uppercase tracking-wider text-right">Montant (FCFA)</th>
                <th class="py-4 px-4 font-bold text-slate-700 uppercase tracking-wider text-right">Equivalent SATS</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            <tr class="border-b border-slate-100">
                <td class="py-6 px-4">
                    <p class="font-bold text-slate-900">Apport en capital</p>
                    <p class="text-slate-500 mt-1 text-xs">Investissement participatif pour le projet "{{ $investment->project->title }}"</p>
                </td>
                <td class="py-6 px-4 text-right font-medium text-slate-900">{{ number_format($investment->amount_fcfa) }}</td>
                <td class="py-6 px-4 text-right font-medium text-slate-900">{{ number_format($investment->amount_sats) }}</td>
            </tr>
            <tr class="border-b border-slate-100 bg-slate-50/50">
                <td class="py-4 px-4 text-slate-600">Frais de traitement technique (2%)</td>
                <td class="py-4 px-4 text-right text-slate-600">-</td>
                <td class="py-4 px-4 text-right text-slate-600">{{ number_format($investment->fee_sats) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="text-lg font-black bg-[#063b27] text-white">
                <td class="py-4 px-4 rounded-l-xl">TOTAL RÉGLÉ</td>
                <td class="py-4 px-4 text-right">{{ number_format($investment->amount_fcfa) }} FCFA</td>
                <td class="py-4 px-4 text-right rounded-r-xl">{{ number_format($investment->amount_sats + $investment->fee_sats) }} SATS</td>
            </tr>
        </tfoot>
    </table>

    <!-- Payment details -->
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 mb-12">
        <h4 class="font-bold text-slate-900 mb-4 flex items-center gap-2"><i class="fa-brands fa-bitcoin text-orange-500"></i> Preuve de Paiement Lightning</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-slate-500 font-medium mb-1">Méthode</p>
                <p class="font-bold text-slate-900">Bitcoin Lightning Network</p>
            </div>
            <div>
                <p class="text-slate-500 font-medium mb-1">Statut</p>
                <p class="font-bold text-green-600"><i class="fa-solid fa-circle-check"></i> Payé et Confirmé</p>
            </div>
            <div class="col-span-full">
                <p class="text-slate-500 font-medium mb-1">Hash de Transaction (Preuve Cryptographique)</p>
                <p class="font-mono text-xs font-bold text-slate-700 bg-white border border-slate-200 p-2 rounded break-all">
                    {{ $investment->payment_hash }}
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center text-xs text-slate-400 mt-16 pt-8 border-t border-slate-100">
        <p class="font-bold text-slate-500 mb-1">AgroTrace-BTC - Révolutionner le financement agricole</p>
        <p>Ce document tient lieu de reçu officiel de paiement. Les montants en SATS sont définitifs à l'instant de la transaction.</p>
    </div>
</div>

</body>
</html>
