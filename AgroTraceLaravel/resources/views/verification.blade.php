@extends('layouts.app')
@section('title', 'Live On-Chain Proofs')
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
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                    <tr>
                        <th class="p-6">Date de Vérification</th>
                        <th class="p-6">Coopérative / Projet</th>
                        <th class="p-6">Jalon Atteint</th>
                        <th class="p-6 text-right">Ancrage Bitcoin (OP_RETURN)</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @foreach($milestones as $milestone)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-6 text-slate-500 font-medium">
                            {{ $milestone->updated_at->diffForHumans() }}
                        </td>
                        <td class="p-6">
                            <p class="font-bold text-slate-900">{{ $milestone->project->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $milestone->project->title }}</p>
                        </td>
                        <td class="p-6">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-md text-xs font-bold">
                                {{ $milestone->title }}
                            </span>
                        </td>
                        <td class="p-6 text-right">
                            <a href="#" class="inline-flex items-center gap-2 font-mono text-orange-500 hover:text-orange-600 bg-orange-50 hover:bg-orange-100 px-4 py-2 rounded-xl transition">
                                8c92...1f4e <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($milestones->count() == 0)
                    <tr>
                        <td colspan="4" class="p-12 text-center text-slate-400">
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
