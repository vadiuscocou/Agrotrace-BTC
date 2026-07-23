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
                    <span class="text-slate-500 text-xs font-medium">Solde Disponible</span>
                    <span class="text-slate-900 text-lg font-black text-orange-500">{{ number_format(Auth::user()->balance_sats) }} SATS</span>
                </div>
                <form action="{{ url('/withdraw') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-slate-900 text-white text-sm font-bold py-3 px-4 rounded-xl hover:bg-slate-800 transition shadow-md flex items-center justify-center gap-2 {{ Auth::user()->balance_sats < 100 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ Auth::user()->balance_sats < 100 ? 'disabled' : '' }}>
                        <i class="fa-solid fa-bolt text-yellow-400"></i> Retirer mes gains (LNURL)
                    </button>
                    @if(Auth::user()->balance_sats < 100)
                        <p class="text-[10px] text-slate-400 mt-2 text-center">Minimum de retrait : 100 SATS</p>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- My Investments List -->
    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
        <i class="fa-solid fa-list-check text-slate-400"></i> Historique d'Investissement
    </h2>
      <div class="bg-white rounded-[2rem] shadow-md border border-slate-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-widest text-slate-500">
                        <th class="px-6 py-4 font-bold">Date & Heure</th>
                        <th class="px-6 py-4 font-bold">Projet</th>
                        <th class="px-6 py-4 font-bold">Statut</th>
                        <th class="px-6 py-4 font-bold">Montant</th>
                        <th class="px-6 py-4 font-bold">Preuve (OP_RETURN)</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($investments as $inv)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-slate-800">{{ $inv->created_at->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-slate-400 font-medium">{{ $inv->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $inv->project->title }}</div>
                            <div class="text-[10px] text-slate-500">{{ $inv->project->region }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($inv->status == 'paid')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-[10px] font-black uppercase"><i class="fa-solid fa-check"></i> Actif</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-50 text-orange-700 border border-orange-200 rounded-full text-[10px] font-black uppercase"><i class="fa-solid fa-hourglass-half"></i> En attente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-black text-slate-900">{{ number_format($inv->amount_fcfa) }} FCFA</div>
                            <div class="text-[10px] text-orange-500 font-bold">{{ number_format($inv->amount_sats) }} SATS</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="https://mempool.space/tx/{{ $inv->payment_hash }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-mono bg-slate-100 hover:bg-orange-100 text-slate-600 hover:text-orange-600 px-3 py-1.5 rounded-lg transition-colors">
                                {{ substr($inv->payment_hash, 0, 8) }}... <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('investments.contract', $inv->id) }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#063b27] hover:text-white text-slate-600 flex items-center justify-center transition-colors" title="Voir le contrat">
                                    <i class="fa-solid fa-file-contract text-sm"></i>
                                </a>
                                @if($inv->status == 'paid')
                                <a href="{{ route('investments.invoice', $inv->id) }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-600 flex items-center justify-center transition-colors" title="Télécharger la facture">
                                    <i class="fa-solid fa-file-invoice text-sm"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="h-16 w-16 bg-orange-50 rounded-full flex items-center justify-center text-orange-300 text-2xl mx-auto mb-4 shadow-sm">
                                <i class="fa-solid fa-seedling"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-2">Votre portfolio est vide</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto">Commencez à investir dans des projets agricoles et regardez votre impact grandir sur la blockchain.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($investments->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $investments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
