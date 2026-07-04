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

<div class="bg-slate-50 min-h-screen pb-12" x-data="{ showWithdrawModal: false }">
    <div class="max-w-7xl mx-auto px-8 py-8">
        <!-- Financial KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 border-2 border-blue-400 shadow-md flex items-center justify-between hover:shadow-lg transition-all text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-10">
                    <i class="fa-solid fa-wallet text-8xl"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[11px] font-black text-blue-100 uppercase tracking-widest mb-1">Volume Total Financé</p>
                    <h3 class="text-3xl font-black text-white">{{ number_format($totalInvested) }} <span class="text-[12px] text-blue-200 font-bold">FCFA</span></h3>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 border-2 border-orange-400 shadow-md flex items-center justify-between hover:shadow-lg transition-all text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-10">
                    <i class="fa-solid fa-coins text-8xl"></i>
                </div>
                <div class="flex-1 relative z-10">
                    <p class="text-[11px] font-black text-orange-100 uppercase tracking-widest mb-1">Commissions Disponibles</p>
                    <h3 class="text-3xl font-black text-white mb-4">{{ number_format($totalFeesSats) }} <span class="text-[12px] text-orange-200 font-bold">SATS</span></h3>
                    <form action="{{ url('/admin/withdraw') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-black bg-white text-orange-600 px-4 py-2.5 rounded-lg hover:bg-orange-50 transition-colors shadow-sm ring-4 ring-white/20">
                            <i class="fa-solid fa-bolt text-orange-500"></i> Retirer les bénéfices
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 border-2 border-emerald-400 shadow-md flex items-center justify-between hover:shadow-lg transition-all text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-10">
                    <i class="fa-solid fa-folder-tree text-8xl"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[11px] font-black text-emerald-100 uppercase tracking-widest mb-1">Projets sur la plateforme</p>
                    <h3 class="text-3xl font-black text-white">{{ $projects->count() }} <span class="text-[12px] text-emerald-200 font-bold">PROJETS</span></h3>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Pending Proofs (Milestones) Table -->
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b-2 border-slate-200 bg-slate-100 flex justify-between items-center">
                    <h2 class="text-base font-black text-slate-800 flex items-center gap-3">
                        <i class="fa-solid fa-check-to-slot text-blue-600 text-lg"></i> Preuves en attente de validation
                    </h2>
                    <span class="bg-blue-600 text-white text-[11px] font-black px-3 py-1 rounded-full uppercase shadow-sm">{{ $milestones->where('status', 'submitted')->count() }} à traiter</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-300 text-xs uppercase tracking-widest text-slate-600">
                                <th class="py-4 px-6 font-black">Projet & Jalon</th>
                                <th class="py-4 px-6 font-black">Preuves / Pièces jointes</th>
                                <th class="py-4 px-6 font-black">Notes / Description</th>
                                <th class="py-4 px-6 font-black text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($milestones->where('status', 'submitted') as $milestone)
                            <tr class="border-b border-slate-200 hover:bg-blue-50/50 transition-colors">
                                <td class="py-5 px-6">
                                    <p class="font-black text-slate-900 text-base mb-1">{{ $milestone->title }}</p>
                                    <p class="text-xs text-slate-600 font-medium mb-1">Projet: <span class="font-black text-blue-700 bg-blue-100 border border-blue-200 px-2 py-0.5 rounded-md">{{ $milestone->project->title }}</span></p>
                                    @if($milestone->project->start_date && $milestone->project->end_date)
                                        <p class="text-[10px] text-slate-500 font-bold mb-1"><i class="fa-solid fa-calendar-days text-slate-400"></i> Période : {{ $milestone->project->start_date->format('d/m/Y') }} - {{ $milestone->project->end_date->format('d/m/Y') }}</p>
                                    @endif
                                    <p class="text-[10px] text-slate-400 font-bold"><i class="fa-regular fa-calendar"></i> Preuve datée du {{ $milestone->proof_date ? \Carbon\Carbon::parse($milestone->proof_date)->format('d/m/Y') : 'N/A' }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold"><i class="fa-regular fa-clock"></i> Soumis le {{ $milestone->updated_at->format('d/m/Y à H:i') }}</p>
                                </td>
                                <td class="py-5 px-6">
                                    @if($milestone->proof_images && count($milestone->proof_images) > 0)
                                        <div class="flex items-center gap-3 flex-wrap max-w-[300px]">
                                            @foreach($milestone->proof_images as $img)
                                            <a href="{{ asset('storage/' . $img) }}" target="_blank" class="block shrink-0">
                                                <img src="{{ asset('storage/' . $img) }}" alt="Preuve" class="w-14 h-14 object-cover rounded-lg shadow-sm border-2 border-slate-300 hover:scale-110 transition-transform hover:border-blue-500">
                                            </a>
                                            @endforeach
                                        </div>
                                    @elseif($milestone->proof_image)
                                        <a href="{{ asset('storage/' . $milestone->proof_image) }}" target="_blank" class="block shrink-0">
                                            <img src="{{ asset('storage/' . $milestone->proof_image) }}" alt="Preuve" class="w-14 h-14 object-cover rounded-lg shadow-sm border-2 border-slate-300 hover:scale-110 transition-transform hover:border-blue-500">
                                        </a>
                                    @else
                                        <span class="text-xs font-bold text-slate-500 bg-slate-200 px-3 py-1.5 rounded-lg border border-slate-300">Aucune image</span>
                                    @endif
                                </td>
                                <td class="py-5 px-6 max-w-xs">
                                    <p class="text-xs text-slate-700 truncate italic bg-slate-50 p-2 rounded border border-slate-200" title="{{ $milestone->proof_notes }}">"{{ $milestone->proof_notes ?? 'Sans commentaire' }}"</p>
                                </td>
                                <td class="py-5 px-6 text-right">
                                    <form action="{{ url('/milestones/' . $milestone->id . '/validate') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-2 px-4 rounded-lg shadow-md inline-flex items-center gap-2 text-xs transition-colors hover:shadow-lg">
                                            <i class="fa-solid fa-check"></i> Valider
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center text-slate-500 text-sm font-bold bg-slate-50">
                                    <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center text-slate-300 text-3xl mx-auto mb-3 shadow-sm border border-slate-200">
                                        <i class="fa-solid fa-check-double"></i>
                                    </div>
                                    Aucune preuve en attente de traitement.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Espacement forcé -->
            <div class="h-10"></div>

            <!-- Projects Management Table -->
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b-2 border-slate-200 bg-slate-100 flex justify-between items-center">
                    <h2 class="text-base font-black text-slate-800 flex items-center gap-3">
                        <i class="fa-solid fa-layer-group text-emerald-600 text-lg"></i> Base de données des Projets
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-300 text-xs uppercase tracking-widest text-slate-600">
                                <th class="py-4 px-6 font-black">Projet & Emplacement</th>
                                <th class="py-4 px-6 font-black">Coopérative</th>
                                <th class="py-4 px-6 font-black">Période du Projet</th>
                                <th class="py-4 px-6 font-black">Objectif</th>
                                <th class="py-4 px-6 font-black text-center">Contrat</th>
                                <th class="py-4 px-6 font-black text-right">Statut & Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($projects->sortByDesc('created_at') as $project)
                            <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                <td class="py-5 px-6">
                                    <p class="font-black text-slate-900 text-base mb-1">{{ $project->title }}</p>
                                    <p class="text-xs text-slate-500 font-bold mb-2"><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $project->region }}</p>
                                    <div class="flex flex-col gap-1 mt-2 border-t border-slate-200 pt-2">
                                        <p class="text-[10px] uppercase font-black text-slate-400 mb-1 tracking-widest">Dossier Coopérative</p>
                                        @if($project->registration_certificate)
                                            <a href="{{ asset('storage/' . $project->registration_certificate) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1"><i class="fa-solid fa-file-pdf"></i> Immatriculation</a>
                                        @endif
                                        @if($project->signatories_id)
                                            <a href="{{ asset('storage/' . $project->signatories_id) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1"><i class="fa-solid fa-id-card"></i> Pièce d'identité</a>
                                        @endif
                                        @if($project->bank_account_proof)
                                            <a href="{{ asset('storage/' . $project->bank_account_proof) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1"><i class="fa-solid fa-building-columns"></i> Preuve Bancaire</a>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-5 px-6">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 border border-slate-300 text-slate-700 rounded-lg text-xs font-bold">
                                        <i class="fa-solid fa-users text-slate-400"></i> {{ optional($project->user)->name ?? 'Inconnu' }}
                                    </span>
                                </td>
                                <td class="py-5 px-6">
                                    @if($project->start_date && $project->end_date)
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs font-bold text-slate-700"><i class="fa-solid fa-play text-emerald-500 mr-1"></i> {{ $project->start_date->format('d/m/Y') }}</span>
                                            <span class="text-xs font-bold text-slate-700"><i class="fa-solid fa-stop text-red-500 mr-1"></i> {{ $project->end_date->format('d/m/Y') }}</span>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">Non défini</span>
                                    @endif
                                </td>
                                <td class="py-5 px-6">
                                    <p class="font-black text-slate-900 text-lg">{{ number_format($project->target_amount_fcfa) }} <span class="text-[10px] text-slate-500 font-bold">FCFA</span></p>
                                </td>
                                <td class="py-5 px-6 text-center">
                                    <a href="{{ url('/projects/' . $project->id . '/contract') }}" target="_blank" class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-slate-100 hover:bg-orange-500 hover:text-white text-slate-600 transition-colors shadow-sm border border-slate-200 hover:border-orange-600" title="Voir le contrat">
                                        <i class="fa-solid fa-file-contract text-lg"></i>
                                    </a>
                                </td>
                                <td class="py-5 px-6 text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        @if($project->status == 'submitted')
                                            <span class="inline-block bg-orange-100 text-orange-800 border border-orange-300 text-[11px] font-black px-3 py-1 rounded-full uppercase shadow-sm">À réviser</span>
                                        @elseif($project->status == 'under_review')
                                            <span class="inline-block bg-blue-100 text-blue-800 border border-blue-300 text-[11px] font-black px-3 py-1 rounded-full uppercase shadow-sm">En révision</span>
                                        @elseif($project->status == 'validated')
                                            <span class="inline-block bg-teal-100 text-teal-800 border border-teal-300 text-[11px] font-black px-3 py-1 rounded-full uppercase shadow-sm">Validé</span>
                                        @elseif($project->status == 'awaiting_funding')
                                            <span class="inline-block bg-yellow-100 text-yellow-800 border border-yellow-300 text-[11px] font-black px-3 py-1 rounded-full uppercase shadow-sm">Financement</span>
                                        @elseif($project->status == 'funded')
                                            <span class="inline-block bg-green-100 text-green-800 border border-green-300 text-[11px] font-black px-3 py-1 rounded-full uppercase shadow-sm">Financé</span>
                                        @elseif($project->status == 'in_progress')
                                            <span class="inline-block bg-indigo-100 text-indigo-800 border border-indigo-300 text-[11px] font-black px-3 py-1 rounded-full uppercase shadow-sm">Exécution</span>
                                        @elseif($project->status == 'completed')
                                            <span class="inline-block bg-slate-800 text-white border border-slate-900 text-[11px] font-black px-3 py-1 rounded-full uppercase shadow-sm">Terminé</span>
                                        @endif
                                        
                                        @if($project->status == 'submitted' || $project->status == 'under_review')
                                        <form action="{{ url('/admin/projects/' . $project->id . '/validate') }}" method="POST" class="mt-1">
                                            @csrf
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-1.5 px-3 rounded-lg shadow-sm border border-emerald-700 text-xs transition-colors inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-thumbs-up"></i> Approuver
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center text-slate-500 font-bold bg-slate-50">
                                    Aucun projet sur la plateforme.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Withdraw functionality is now handled via direct POST and LNURL -->
</div>
@endsection
