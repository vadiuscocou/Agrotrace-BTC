@extends('layouts.guest')

@section('title', 'Conditions d\'utilisation - AgroTrace-BTC')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 max-w-4xl mx-auto my-10">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-black text-slate-900 mb-2">Conditions d'Utilisation</h1>
        <p class="text-slate-500">Dernière mise à jour : {{ date('d/m/Y') }}</p>
    </div>

    <div class="prose prose-slate max-w-none space-y-6 text-slate-700">
        <p>Bienvenue sur AgroTrace-BTC. En créant un compte (Investisseur ou Coopérative), vous acceptez les présentes conditions d'utilisation.</p>
        
        <h2 class="text-xl font-bold text-slate-900 mt-6 border-b pb-2">1. Objet de la plateforme</h2>
        <p>AgroTrace-BTC est une plateforme de mise en relation entre des coopératives agricoles africaines et des investisseurs (notamment la diaspora) permettant le financement de projets agricoles via le réseau Bitcoin (Lightning Network).</p>
        
        <h2 class="text-xl font-bold text-slate-900 mt-6 border-b pb-2">2. Engagements des Coopératives</h2>
        <ul class="list-disc pl-5 space-y-2">
            <li>Fournir des informations exactes et véridiques sur les projets agricoles.</li>
            <li>Soumettre régulièrement des preuves d'avancement (photos, reçus) pour chaque jalon financé.</li>
            <li>S'engager à rembourser ou reverser les dividendes convenus après la récolte.</li>
            <li>Utiliser les fonds exclusivement pour les besoins du projet validé.</li>
        </ul>

        <h2 class="text-xl font-bold text-slate-900 mt-6 border-b pb-2">3. Engagements des Investisseurs</h2>
        <ul class="list-disc pl-5 space-y-2">
            <li>Comprendre que l'investissement agricole comporte des risques naturels (climat, ravageurs).</li>
            <li>Les fonds envoyés via Bitcoin/Lightning sont convertis et alloués au projet. Les transactions blockchain sont irréversibles.</li>
            <li>AgroTrace-BTC agit comme tiers de confiance technologique mais ne peut garantir le rendement absolu des récoltes.</li>
        </ul>

        <h2 class="text-xl font-bold text-slate-900 mt-6 border-b pb-2">4. Frais de plateforme</h2>
        <p>AgroTrace-BTC prélève des frais minimes de fonctionnement (2%) sur chaque transaction d'investissement pour assurer la maintenance de la plateforme et la validation des projets.</p>

        <h2 class="text-xl font-bold text-slate-900 mt-6 border-b pb-2">5. Résolution des litiges</h2>
        <p>En cas de non-respect des engagements, le compte de la coopérative pourra être suspendu, et AgroTrace-BTC se réserve le droit de mener des audits sur le terrain.</p>
    </div>

    <div class="mt-10 text-center">
        <a href="{{ route('register') }}" class="inline-block bg-[#063b27] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#0a4b33] transition">
            Retour à l'inscription
        </a>
    </div>
</div>
@endsection
