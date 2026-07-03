@extends('layouts.app')
@section('title', 'Preuves On-Chain en direct')
@section('content')

<!-- Header -->
<div class="bg-slate-900 border-b border-slate-800 px-8 py-10 text-white">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-white flex items-center gap-3">
                <i class="fa-solid fa-link text-orange-500"></i> Registre On-Chain en Direct
            </h1>
            <p class="text-slate-400 mt-2 font-medium">Jalons agricoles immuables ancrés au réseau Bitcoin.</p>
        </div>
        <div class="inline-flex items-center gap-2 bg-green-500/20 px-4 py-2 rounded-full border border-green-500/30">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            <span class="text-xs font-bold text-green-400 uppercase tracking-widest">Réseau Synchronisé</span>
        </div>
    </div>
</div>

<div class="p-8">
    <div class="bg-white rounded-[2rem] shadow-md border border-slate-300 overflow-hidden flex flex-col hover:border-slate-400 transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-200">
                    <tr>
                        <th class="py-4 px-6">Date</th>
                        <th class="py-4 px-6">Coopérative / Projet</th>
                        <th class="py-4 px-6">Jalon Atteint</th>
                        <th class="py-4 px-6">Preuve Visuelle</th>
                        <th class="py-4 px-6 text-right">Ancrage (OP_RETURN)</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @foreach($milestones as $milestone)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 px-6 text-slate-500 font-medium text-xs">
                            {{ $milestone->updated_at->diffForHumans() }}
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800 text-sm">{{ $milestone->project->user->name }}</p>
                            <p class="text-[11px] text-slate-400">{{ $milestone->project->title }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-block bg-green-50 text-green-600 px-2 py-0.5 rounded border border-green-100 text-[10px] font-bold mb-1">
                                {{ $milestone->title }}
                            </span>
                            <p class="text-[11px] text-slate-500 line-clamp-1 max-w-[200px]">{{ $milestone->description }}</p>
                        </td>
                        <td class="py-4 px-6">
                            @if($milestone->proof_images && count($milestone->proof_images) > 0)
                                <div class="flex items-center gap-2 flex-wrap">
                                    @foreach($milestone->proof_images as $img)
                                    <a href="{{ asset('storage/' . $img) }}" target="_blank" class="block shrink-0">
                                        <img src="{{ asset('storage/' . $img) }}" alt="Preuve" class="w-8 h-8 rounded-md object-cover border border-slate-200 shadow-sm hover:scale-110 transition">
                                    </a>
                                    @endforeach
                                </div>
                            @elseif($milestone->proof_image)
                                <div class="flex items-center gap-3">
                                    <a href="{{ asset('storage/' . $milestone->proof_image) }}" target="_blank" class="block shrink-0">
                                        <img src="{{ asset('storage/' . $milestone->proof_image) }}" alt="Preuve" class="w-8 h-8 rounded-md object-cover border border-slate-200 shadow-sm hover:scale-110 transition">
                                    </a>
                                </div>
                            @else
                                <span class="text-[10px] text-slate-400 font-medium italic">Aucune photo</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            @if($milestone->blockchain_tx_id)
                            <a href="#" class="inline-flex items-center gap-1 font-mono text-[11px] text-orange-500 hover:text-orange-600 bg-orange-50 px-2 py-1 rounded transition" title="{{ $milestone->blockchain_tx_id }}">
                                {{ substr($milestone->blockchain_tx_id, 0, 8) }}... <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                            </a>
                            @else
                            <span class="text-[11px] text-slate-400">Non ancré</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($milestones->count() == 0)
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-400">
                            <i class="fa-solid fa-clock-rotate-left text-3xl mb-3 opacity-50 block"></i>
                            Aucune preuve validée pour le moment.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
