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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Pending Projects Queue -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                    <i class="fa-solid fa-list-check text-orange-500"></i> File d'attente des Projets
                </h2>
                <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2 py-1 rounded-md">{{ $projects->where('status', 'pending')->count() }}</span>
            </div>
            
            <div class="p-6 flex-1">
                <div class="space-y-4">
                    @foreach($projects->where('status', 'pending') as $project)
                    <div class="border border-slate-200 rounded-2xl p-5 hover:border-orange-300 hover:shadow-md transition bg-white group">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-bold text-slate-900">{{ $project->title }}</h3>
                            <span class="text-xs font-black text-slate-400 uppercase">{{ number_format($project->target_amount_fcfa) }} CFA</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-4 font-medium"><i class="fa-solid fa-user mr-1"></i> {{ optional($project->user)->name ?? 'Unknown' }} &nbsp;&bull;&nbsp; <i class="fa-solid fa-location-dot mr-1"></i> {{ $project->region }}</p>
                        
                        <div class="flex gap-2">
                            <form action="{{ url('/projects/' . $project->id . '/approve') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-green-50 text-green-700 hover:bg-green-100 font-bold text-xs py-2.5 rounded-xl border border-green-200 transition">
                                    Approuver
                                </button>
                            </form>
                            <form action="{{ url('/projects/' . $project->id . '/reject') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-red-50 text-red-700 hover:bg-red-100 font-bold text-xs py-2.5 rounded-xl border border-red-200 transition">
                                    Rejeter
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($projects->where('status', 'pending')->count() == 0)
                    <div class="text-center py-10 text-slate-400 text-sm font-medium">
                        <i class="fa-solid fa-check-circle text-3xl mb-2 text-slate-200 block"></i>
                        Aucun projet en attente de révision.
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
                        
                        <!-- Fake Image Proof Placeholder -->
                        <div class="w-full h-32 bg-slate-100 rounded-xl border border-slate-200 mb-4 flex items-center justify-center text-slate-400">
                            <div class="text-center">
                                <i class="fa-solid fa-image text-2xl mb-1"></i>
                                <p class="text-[10px] font-bold uppercase tracking-widest">Preuve Image Jointe</p>
                            </div>
                        </div>
                        
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
