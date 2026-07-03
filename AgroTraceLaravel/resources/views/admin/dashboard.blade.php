@extends('layouts.app')
@section('title', 'Admin Control Panel')
@section('content')

<!-- Header -->
<div class="bg-slate-900 border-b border-slate-800 px-8 py-8 text-white">
    <div class="flex justify-between items-center max-w-7xl mx-auto">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 border border-emerald-500/30 shadow-sm">
                <i class="fa-solid fa-server"></i> Control Center
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">Administration Système</h1>
            <p class="text-slate-400 mt-1.5 font-medium text-sm">Gestion des projets, validation des preuves et ancrage blockchain.</p>
        </div>
    </div>
</div>

<div class="bg-slate-50 min-h-screen pb-12">
    <div class="max-w-7xl mx-auto px-8 py-8">
        <!-- Financial KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Volume Total Financé</p>
                    <h3 class="text-2xl font-black text-slate-900">{{ number_format($totalInvested) }} <span class="text-[10px] text-slate-400 font-bold">FCFA</span></h3>
                </div>
                <div class="h-12 w-12 bg-blue-50 border border-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-xl shadow-sm">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Commissions (2%)</p>
                    <h3 class="text-2xl font-black text-slate-900">{{ number_format($totalFeesSats) }} <span class="text-[10px] text-slate-400 font-bold">SATS</span></h3>
                </div>
                <div class="h-12 w-12 bg-orange-50 border border-orange-100 rounded-lg flex items-center justify-center text-orange-500 text-xl shadow-sm">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Projets sur la plateforme</p>
                    <h3 class="text-2xl font-black text-slate-900">{{ $projects->count() }} <span class="text-[10px] text-slate-400 font-bold">PROJETS</span></h3>
                </div>
                <div class="h-12 w-12 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-xl shadow-sm">
                    <i class="fa-solid fa-folder-tree"></i>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Pending Proofs (Milestones) Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-check-to-slot text-blue-500"></i> Preuves en attente de validation
                    </h2>
                    <span class="bg-blue-100 text-blue-800 border border-blue-200 text-[10px] font-bold px-2 py-0.5 rounded uppercase">{{ $milestones->where('status', 'submitted')->count() }} à traiter</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-slate-100 text-[10px] uppercase tracking-widest text-slate-400">
                                <th class="py-3 px-6 font-bold">Projet & Jalon</th>
                                <th class="py-3 px-6 font-bold">Preuves / Pièces jointes</th>
                                <th class="py-3 px-6 font-bold">Notes / Description</th>
                                <th class="py-3 px-6 font-bold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @forelse($milestones->where('status', 'submitted') as $milestone)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-slate-900">{{ $milestone->title }}</p>
                                    <p class="text-[10px] text-slate-500 mt-1">Projet: <span class="font-bold text-blue-600 bg-blue-50 px-1 py-0.5 rounded">{{ $milestone->project->title }}</span></p>
                                </td>
                                <td class="py-4 px-6">
                                    @if($milestone->proof_images && count($milestone->proof_images) > 0)
                                        <div class="flex items-center gap-2 flex-wrap max-w-[250px]">
                                            @foreach($milestone->proof_images as $img)
                                            <a href="{{ asset('storage/' . $img) }}" target="_blank" class="block shrink-0">
                                                <img src="{{ asset('storage/' . $img) }}" alt="Preuve" class="w-10 h-10 object-cover rounded shadow-sm border border-slate-200 hover:scale-110 transition">
                                            </a>
                                            @endforeach
                                        </div>
                                    @elseif($milestone->proof_image)
                                        <a href="{{ asset('storage/' . $milestone->proof_image) }}" target="_blank" class="block shrink-0">
                                            <img src="{{ asset('storage/' . $milestone->proof_image) }}" alt="Preuve" class="w-10 h-10 object-cover rounded shadow-sm border border-slate-200 hover:scale-110 transition">
                                        </a>
                                    @else
                                        <span class="text-[10px] italic text-slate-400 bg-slate-100 px-2 py-1 rounded">Aucune image</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 max-w-xs">
                                    <p class="text-[10px] text-slate-600 truncate italic" title="{{ $milestone->proof_notes }}">"{{ $milestone->proof_notes ?? 'Sans commentaire' }}"</p>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <form action="{{ url('/milestones/' . $milestone->id . '/validate') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-[#063b27] hover:bg-[#0a4b33] text-white font-bold py-1.5 px-3 rounded shadow-sm inline-flex items-center gap-1.5 text-[10px] transition">
                                            <i class="fa-solid fa-link"></i> Valider
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400 text-xs font-medium">
                                    <i class="fa-solid fa-check-double text-3xl mb-2 text-slate-200 block"></i>
                                    Aucune preuve en attente de traitement.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Projects Management Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-orange-500"></i> Table des Projets
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-slate-100 text-[10px] uppercase tracking-widest text-slate-400">
                                <th class="py-3 px-6 font-bold">Projet & Emplacement</th>
                                <th class="py-3 px-6 font-bold">Coopérative</th>
                                <th class="py-3 px-6 font-bold">Objectif de Financement</th>
                                <th class="py-3 px-6 font-bold">Document</th>
                                <th class="py-3 px-6 font-bold">Statut & Mise à jour</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @forelse($projects->sortByDesc('created_at') as $project)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-slate-900">{{ $project->title }}</p>
                                    <p class="text-[10px] text-slate-500 mt-1"><i class="fa-solid fa-location-dot text-slate-400"></i> {{ $project->region }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-slate-700 font-medium">{{ optional($project->user)->name ?? 'Inconnu' }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-black text-slate-700">{{ number_format($project->target_amount_fcfa) }} <span class="text-[9px] text-slate-400 font-bold">FCFA</span></p>
                                </td>
                                <td class="py-4 px-6">
                                    @if($project->supporting_documents)
                                        <a href="{{ asset('storage/' . $project->supporting_documents) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold flex items-center gap-1.5 text-[10px] bg-blue-50 px-2 py-1 rounded inline-block border border-blue-100">
                                            <i class="fa-solid fa-file-pdf"></i> Ouvrir
                                        </a>
                                    @else
                                        <span class="text-[10px] italic text-slate-400">Aucun</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <form action="{{ url('/projects/' . $project->id . '/status') }}" method="POST" class="flex gap-2 items-center">
                                        @csrf
                                        <select name="status" class="w-36 text-[10px] font-bold text-slate-700 border-slate-200 rounded py-1 pl-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm bg-white cursor-pointer">
                                            <option value="submitted" {{ $project->status == 'submitted' ? 'selected' : '' }}>Soumis</option>
                                            <option value="under_review" {{ $project->status == 'under_review' ? 'selected' : '' }}>En étude</option>
                                            <option value="validated" {{ $project->status == 'validated' ? 'selected' : '' }}>Validé</option>
                                            <option value="awaiting_funding" {{ $project->status == 'awaiting_funding' ? 'selected' : '' }}>En attente (Fond)</option>
                                            <option value="funded" {{ $project->status == 'funded' ? 'selected' : '' }}>Financé</option>
                                            <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                            <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Terminé</option>
                                        </select>
                                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-1 px-2.5 rounded shadow-sm text-[10px] transition cursor-pointer">
                                            OK
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 text-xs font-medium">
                                    <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
                                    Aucun projet trouvé sur la plateforme.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
