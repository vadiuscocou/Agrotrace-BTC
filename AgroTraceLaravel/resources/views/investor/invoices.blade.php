@extends('layouts.app')
@section('title', 'Mes Factures')
@section('content')

<div class="bg-white border-b border-slate-200 px-8 py-10">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">Mes Factures</h1>
            <p class="text-slate-500 mt-2 font-medium">Historique de vos paiements et justificatifs comptables.</p>
        </div>
    </div>
</div>

<div class="p-8">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-4 px-6 font-bold text-xs text-slate-400 uppercase tracking-widest">N° Facture</th>
                        <th class="py-4 px-6 font-bold text-xs text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="py-4 px-6 font-bold text-xs text-slate-400 uppercase tracking-widest">Projet Lié</th>
                        <th class="py-4 px-6 font-bold text-xs text-slate-400 uppercase tracking-widest text-right">Montant (FCFA)</th>
                        <th class="py-4 px-6 font-bold text-xs text-slate-400 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($investments as $inv)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 px-6">
                            <span class="font-mono text-sm font-bold text-slate-700">FAC-{{ $inv->created_at->format('Y') }}-{{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="py-4 px-6 text-sm text-slate-500 font-medium">
                            {{ $inv->created_at->format('d/m/Y à H:i') }}
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-900">{{ $inv->project->title }}</p>
                            <p class="text-xs text-slate-400">{{ $inv->project->formatted_id }}</p>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <span class="font-black text-slate-900">{{ number_format($inv->amount_fcfa) }} FCFA</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('investments.invoice', $inv->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-[#063b27] hover:bg-[#0a4b33] text-white text-xs font-bold py-2 px-4 rounded-lg transition shadow-sm">
                                <i class="fa-solid fa-download"></i> Télécharger
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-500">
                            <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl mx-auto mb-3">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <p class="font-bold text-slate-700 mb-1">Aucune facture</p>
                            <p class="text-sm">Vous n'avez pas encore effectué d'investissement.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
