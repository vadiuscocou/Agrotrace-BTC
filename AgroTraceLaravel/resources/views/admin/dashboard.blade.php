@extends('layouts.app')
@section('title', 'Admin Control Panel')
@section('content')

<!-- Header -->
<div class="bg-slate-900 border-b border-slate-800 px-8 py-10 text-white">
    <div class="flex justify-between items-center">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 border border-red-500/30">
                <i class="fa-solid fa-shield-halved"></i> Administrateur Système
            </div>
            <h1 class="text-3xl font-black tracking-tight text-white">Opérations de la Plateforme</h1>
            <p class="text-slate-400 mt-2 font-medium">Vérifiez les projets et ancrez les jalons sur la blockchain Bitcoin.</p>
        </div>
    </div>
</div>

<div class="p-8">
    <!-- Financial KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-6">
            <div class="h-16 w-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 text-2xl">
                <i class="fa-solid fa-vault"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Volume Total Financé</p>
                <h3 class="text-3xl font-black text-slate-900">{{ number_format($totalInvested) }} <span class="text-lg text-slate-400">FCFA</span></h3>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-6 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 text-orange-50/50 text-9xl">
                <i class="fa-brands fa-bitcoin"></i>
            </div>
            <div class="h-16 w-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 text-2xl relative z-10">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-orange-400 uppercase tracking-widest mb-1">Commissions AgroTrace</p>
                <h3 class="text-3xl font-black text-slate-900">{{ number_format($totalFeesSats) }} <span class="text-lg text-slate-400">SATS</span></h3>
                <p class="text-xs text-slate-500 font-medium">≈ {{ number_format($totalFeesSats / 6) }} FCFA collectés en frais (2%)</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Projects Management -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                    <i class="fa-solid fa-list-check text-orange-500"></i> Gestion des Projets
                </h2>
                <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2 py-1 rounded-md">{{ $projects->count() }}</span>
            </div>
            
            <div class="p-6 flex-1">
                <div class="space-y-4">
                    @foreach($projects->sortByDesc('created_at') as $project)
                    <div class="border border-slate-200 rounded-2xl p-5 hover:border-orange-300 hover:shadow-md transition bg-white group">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-bold text-slate-900">{{ $project->title }}</h3>
                            <span class="text-xs font-black text-slate-400 uppercase">{{ number_format($project->target_amount_fcfa) }} CFA</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-2 font-medium"><i class="fa-solid fa-user mr-1"></i> {{ optional($project->user)->name ?? 'Unknown' }} &nbsp;&bull;&nbsp; <i class="fa-solid fa-location-dot mr-1"></i> {{ $project->region }}</p>
                        
                        @if($project->supporting_documents)
                            <a href="{{ asset('storage/' . $project->supporting_documents) }}" target="_blank" class="inline-block text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded mb-4 hover:bg-blue-100">
                                <i class="fa-solid fa-file-pdf"></i> Voir le document justificatif
                            </a>
                        @else
                            <p class="text-xs text-slate-400 mb-4 italic">Aucun document</p>
                        @endif

                        <div class="mt-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <form action="{{ url('/projects/' . $project->id . '/status') }}" method="POST" class="flex gap-2 items-center">
                                @csrf
                                <select name="status" class="flex-1 text-sm border-slate-200 rounded-lg py-2 focus:ring-orange-500 focus:border-orange-500">
                                    <option value="submitted" {{ $project->status == 'submitted' ? 'selected' : '' }}>Soumis</option>
                                    <option value="under_review" {{ $project->status == 'under_review' ? 'selected' : '' }}>En étude</option>
                                    <option value="validated" {{ $project->status == 'validated' ? 'selected' : '' }}>Validé</option>
                                    <option value="awaiting_funding" {{ $project->status == 'awaiting_funding' ? 'selected' : '' }}>En attente de financement</option>
                                    <option value="funded" {{ $project->status == 'funded' ? 'selected' : '' }}>Financé</option>
                                    <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                    <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Terminé</option>
                                </select>
                                <button type="submit" class="bg-[#063b27] hover:bg-[#0a4b33] text-white font-bold text-xs py-2 px-4 rounded-lg transition">
                                    Mettre à jour
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($projects->count() == 0)
                    <div class="text-center py-10 text-slate-400 text-sm font-medium">
                        <i class="fa-solid fa-check-circle text-3xl mb-2 text-slate-200 block"></i>
                        Aucun projet sur la plateforme.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Proofs (Milestones) -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                    <i class="fa-solid fa-camera text-blue-500"></i> Preuves de Jalons
                </h2>
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-md">{{ $milestones->where('status', 'submitted')->count() }}</span>
            </div>
            
            <div class="p-6 flex-1">
                <div class="space-y-4">
                    @foreach($milestones->where('status', 'submitted') as $milestone)
                    <div class="border border-slate-200 rounded-2xl p-5 hover:border-blue-300 hover:shadow-md transition bg-white">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-slate-900">{{ $milestone->title }}</h3>
                        </div>
                        <p class="text-xs text-blue-600 font-bold mb-4 bg-blue-50 inline-block px-2 py-1 rounded">
                            Projet : {{ $milestone->project->title }}
                        </p>
                        
                        <!-- Actual Image Proof or Placeholder -->
                        @if($milestone->proof_image)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $milestone->proof_image) }}" alt="Preuve" class="w-full h-auto max-h-48 object-cover rounded-xl border border-slate-200">
                            </div>
                        @else
                            <div class="w-full h-32 bg-slate-100 rounded-xl border border-slate-200 mb-4 flex items-center justify-center text-slate-400">
                                <div class="text-center">
                                    <i class="fa-solid fa-image text-2xl mb-1"></i>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">Aucune image jointe</p>
                                </div>
                            </div>
                        @endif

                        @if($milestone->proof_notes)
                            <p class="text-sm text-slate-600 mb-4 bg-slate-50 p-3 rounded-lg border border-slate-100 italic">"{{ $milestone->proof_notes }}"</p>
                        @endif
                        
                        <form action="{{ url('/milestones/' . $milestone->id . '/validate') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-[#063b27] hover:bg-[#0a4b33] text-white font-bold text-sm py-3 rounded-xl transition flex justify-center items-center gap-2">
                                <i class="fa-solid fa-link"></i> Valider & Ancrer sur Blockchain
                            </button>
                        </form>
                    </div>
                    @endforeach
                    
                    @if($milestones->where('status', 'submitted')->count() == 0)
                    <div class="text-center py-10 text-slate-400 text-sm font-medium">
                        <i class="fa-solid fa-check-circle text-3xl mb-2 text-slate-200 block"></i>
                        Aucune preuve en attente de validation.
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
