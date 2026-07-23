@extends('layouts.app')
@section('title', 'Mes Factures')
@section('content')

<!-- Invoices Header (Premium) -->
<div class="relative bg-slate-900 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&q=80&w=2000" alt="Finance" class="w-full h-full object-cover opacity-10">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/90 to-transparent"></div>
    </div>
    <div class="relative px-8 py-12 md:py-16 max-w-7xl mx-auto flex items-center justify-between">
        <div class="max-w-2xl">
            <h1 class="text-3xl md:text-4xl font-black tracking-tight text-white mb-2 flex items-center gap-3">
                <i class="fa-solid fa-file-invoice-dollar text-orange-500"></i> Mes Factures
            </h1>
            <p class="text-slate-400 font-medium leading-relaxed">Historique de vos paiements Lightning et justificatifs comptables générés automatiquement.</p>
        </div>
        <div class="hidden md:block">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-center">
                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">Total Facturé</p>
                <p class="text-2xl font-black text-white">{{ number_format($investments->sum('amount_fcfa')) }} <span class="text-sm text-green-400">FCFA</span></p>
            </div>
        </div>
    </div>
</div>

<div class="p-8 max-w-7xl mx-auto relative z-10">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="py-5 px-6 font-black text-[10px] text-slate-400 uppercase tracking-widest">N° Facture</th>
                        <th class="py-5 px-6 font-black text-[10px] text-slate-400 uppercase tracking-widest">Date & Heure</th>
                        <th class="py-5 px-6 font-black text-[10px] text-slate-400 uppercase tracking-widest">Projet Lié</th>
                        <th class="py-5 px-6 font-black text-[10px] text-slate-400 uppercase tracking-widest text-right">Montant (FCFA)</th>
                        <th class="py-5 px-6 font-black text-[10px] text-slate-400 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($investments as $inv)
                    <tr class="hover:bg-slate-50 transition-colors group cursor-default">
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <span class="font-mono text-sm font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded">FAC-{{ $inv->created_at->format('Y') }}-{{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-sm text-slate-500 font-medium">
                            <i class="fa-regular fa-calendar mr-1 text-slate-400"></i> {{ $inv->created_at->format('d/m/Y') }}<br>
                            <span class="text-xs text-slate-400"><i class="fa-regular fa-clock mr-1"></i> {{ $inv->created_at->format('H:i') }}</span>
                        </td>
                        <td class="py-5 px-6">
                            <p class="font-bold text-[#063b27] group-hover:text-green-600 transition-colors">{{ $inv->project->title }}</p>
                            <p class="text-[10px] font-mono text-slate-400">{{ $inv->project->formatted_id }}</p>
                        </td>
                        <td class="py-5 px-6 text-right">
                            <span class="font-black text-slate-900 text-base">{{ number_format($inv->amount_fcfa) }}</span>
                            <span class="text-xs text-slate-500 font-bold ml-1">FCFA</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <a href="{{ route('investments.invoice', $inv->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-[#063b27] text-slate-700 hover:text-[#063b27] hover:shadow-md text-xs font-bold py-2 px-4 rounded-xl transition-all">
                                <i class="fa-solid fa-file-pdf text-red-500"></i> PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center text-slate-500 bg-slate-50/50">
                            <div class="h-16 w-16 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-300 text-3xl mx-auto mb-4 border border-slate-100">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <p class="font-black text-slate-800 mb-1 text-lg">Aucune facture disponible</p>
                            <p class="text-sm font-medium text-slate-400">Vos factures apparaîtront ici après votre premier investissement.</p>
                            <a href="{{ url('/projects') }}" class="inline-block mt-6 bg-[#063b27] text-white font-bold py-2 px-6 rounded-xl hover:bg-[#0a4b33] transition shadow-md">
                                Explorer les projets
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
