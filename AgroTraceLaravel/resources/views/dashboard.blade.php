@extends('layouts.app')
@section('title', 'Investor Dashboard')
@section('content')

<!-- Header -->
<div class="bg-white border-b border-slate-200 px-8 py-10">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">Portfolio d'Impact</h1>
            <p class="text-slate-500 mt-2 font-medium">Suivez vos investissements et les jalons vérifiés.</p>
        </div>
        <div class="hidden sm:block text-right bg-orange-50 border border-orange-100 px-6 py-3 rounded-2xl">
            <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-1">Total Routé</p>
            <p class="text-2xl font-black text-orange-600 flex items-center gap-2">
                <i class="fa-brands fa-bitcoin"></i> {{ number_format($investments->where('status', 'paid')->sum('amount_sats')) }} <span class="text-sm font-bold opacity-50">SATS</span>
            </p>
        </div>
    </div>
</div>

<div class="p-8">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[2rem] shadow-md border border-slate-300 flex items-center gap-6">
            <div class="h-14 w-14 rounded-full bg-green-50 flex items-center justify-center text-green-600 text-2xl flex-shrink-0">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Investi</p>
                <p class="text-xl font-black text-slate-900">{{ number_format($investments->where('status', 'paid')->sum('amount_fcfa')) }} <span class="text-xs text-slate-400">FCFA</span></p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-[2rem] shadow-md border border-slate-300 flex items-center gap-6">
            <div class="h-14 w-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-2xl flex-shrink-0">
                <i class="fa-solid fa-leaf"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Projets</p>
                <p class="text-xl font-black text-slate-900">{{ $investments->where('status', 'paid')->unique('project_id')->count() }} <span class="text-xs text-slate-400">Soutenus</span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-md border border-slate-300 flex items-center gap-6">
            <div class="h-14 w-14 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 text-2xl flex-shrink-0">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Bénéfices Attendus</p>
                <p class="text-xl font-black text-slate-900">+{{ number_format($investments->where('status', 'paid')->sum('amount_fcfa') * 0.30) }} <span class="text-xs text-slate-400">FCFA (30%)</span></p>
            </div>
        </div>
    </div>

    <!-- ROI Section -->
    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 mt-12">
        <i class="fa-solid fa-arrow-trend-up text-orange-500"></i> Rendements & Dividendes
    </h2>
    <div class="bg-white rounded-3xl p-6 shadow-md border border-slate-300 mb-16">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1">
                <p class="text-slate-400 font-bold mb-2 uppercase tracking-widest text-xs">Gains redistribués</p>
                <div class="flex items-end gap-3 mb-4">
                    <span class="text-4xl font-black text-orange-500">{{ number_format($investments->where('status', 'paid')->sum('amount_fcfa') * 0.30) }}</span>
                    <span class="text-lg font-bold text-slate-400 pb-1 flex items-center gap-2">FCFA</span>
                </div>
                <p class="text-slate-500 text-sm leading-relaxed max-w-lg">
                    Vos investissements génèrent un retour estimé de <strong class="text-green-600">+30%</strong> après la vente des récoltes. Les paiements Lightning seront activés automatiquement à la fin des projets.
                </p>
            </div>
            <div class="w-full md:w-auto bg-slate-50 border border-slate-100 p-5 rounded-2xl">
                <div class="flex items-center justify-between gap-6 mb-3 border-b border-slate-200 pb-3">
                    <span class="text-slate-500 text-xs font-medium">Dernier paiement</span>
                    <span class="text-slate-900 text-sm font-bold">+ 4,200 SATS</span>
                </div>
                <div class="flex items-center justify-between gap-6">
                    <span class="text-slate-500 text-xs font-medium">Prochain estimé</span>
                    <span class="text-orange-500 text-sm font-bold">~ 8,300 SATS</span>
                </div>
            </div>
        </div>
    </div>

    <!-- My Investments List -->
    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
        <i class="fa-solid fa-list-check text-slate-400"></i> Historique d'Investissement
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($investments as $inv)
        <div class="bg-white rounded-[2rem] shadow-md border border-slate-300 p-6 flex flex-col hover:border-slate-400 transition-colors group relative overflow-hidden">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-block px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-black uppercase tracking-widest">{{ $inv->project->formatted_id }}</span>
                        @if($inv->status == 'paid')
                            <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-[10px] font-black uppercase tracking-widest">Actif</span>
                        @else
                            <span class="inline-block px-2 py-1 bg-orange-100 text-orange-700 rounded text-[10px] font-black uppercase tracking-widest">En attente</span>
                        @endif
                    </div>
                    <h4 class="font-bold text-lg text-slate-900 leading-tight">{{ $inv->project->title }}</h4>
                    <p class="text-xs text-slate-500 font-medium mt-1"><i class="fa-solid fa-location-dot"></i> {{ $inv->project->region }}</p>
                </div>
            </div>
            
            <div class="mt-auto pt-6 border-t border-slate-50 space-y-4">
                <div class="flex justify-between items-end">
                    <span class="text-xs font-bold text-slate-400 uppercase">Montant</span>
                    <span class="font-black text-slate-900">{{ number_format($inv->amount_fcfa) }} FCFA</span>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-300">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Reçu Blockchain (OP_RETURN)</p>
                    <a href="https://mempool.space/tx/{{ $inv->payment_hash }}" target="_blank" class="flex items-center justify-between text-sm font-mono text-orange-500 hover:text-orange-600 transition group-hover:bg-orange-50 p-2 rounded-lg -mx-2">
                        <span>{{ substr($inv->payment_hash, 0, 16) }}...</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs opacity-50 group-hover:opacity-100"></i>
                    </a>
                </div>

                <a href="{{ route('investments.contract', $inv->id) }}" target="_blank" class="w-full mt-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-file-signature"></i> Voir le contrat nominatif
                </a>
                @if($inv->status == 'paid')
                <a href="{{ route('investments.invoice', $inv->id) }}" target="_blank" class="w-full mt-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-file-invoice"></i> Télécharger la facture
                </a>
                @endif
            </div>
        </div>
        @endforeach
        
        @if($investments->count() == 0)
        <div class="col-span-full">
            <div class="bg-white border border-slate-300 rounded-[2rem] p-12 text-center shadow-md">
                <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center text-orange-300 text-2xl mx-auto mb-4 shadow-sm">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <h3 class="text-lg font-bold text-orange-800 mb-2">Votre portfolio est vide</h3>
                <p class="text-orange-600/70 mb-6 max-w-sm mx-auto">Commencez à investir dans des projets agricoles et regardez votre impact grandir sur la blockchain.</p>
                <a href="{{ url('/projects') }}" class="inline-block bg-orange-500 text-white font-bold px-8 py-3 rounded-full hover:bg-orange-600 shadow-md transition-all hover:-translate-y-0.5">Parcourir les Projets</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
