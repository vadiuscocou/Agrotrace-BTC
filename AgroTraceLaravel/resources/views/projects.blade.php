@extends('layouts.app')
@section('title', 'Explorer | AgroTrace BTC')
@section('content')

<!-- Explorer Header -->
<div class="bg-white border-b border-slate-200 px-8 py-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">Explorateur de Projets</h1>
            <p class="text-slate-500 mt-2 font-medium">Parcourez les coopératives agricoles actives et financez-les via Lightning.</p>
        </div>
        <div class="inline-flex items-center gap-2 bg-green-50 px-4 py-2 rounded-full border border-green-100">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            <span class="text-xs font-bold text-green-700 uppercase tracking-widest">Réseau En Ligne</span>
        </div>
    </div>
</div>

<div class="p-8">
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8 rounded-r-xl shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
            <p class="text-green-700 font-bold m-0">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($projects as $project)
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
            
            <!-- Card Header -->
            <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-start">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 {{ in_array($project->status, ['active', 'verified']) ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                        {{ in_array($project->status, ['active', 'verified']) ? 'Actif' : 'En attente' }}
                    </span>
                    <h3 class="text-xl font-bold text-[#063b27] group-hover:text-orange-500 transition-colors">{{ $project->title }}</h3>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1">
                <div class="flex items-center gap-2 text-slate-500 text-sm font-medium mb-4">
                    <i class="fa-solid fa-location-dot"></i> {{ $project->region }}
                </div>
                <div class="flex items-center gap-2 text-slate-500 text-sm font-medium mb-4">
                    <i class="fa-solid fa-users"></i> {{ $project->user->name }}
                </div>
                <p class="text-slate-600 text-sm leading-relaxed line-clamp-3">
                    {{ $project->description }}
                </p>
                
                @if($project->milestones->count() > 0)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Jalons du projet</h4>
                    <ul class="space-y-2">
                        @foreach($project->milestones as $milestone)
                        <li class="flex items-center gap-2 text-sm">
                            @if(in_array($milestone->status, ['verified', 'validated']))
                                <i class="fa-solid fa-circle-check text-green-500"></i>
                            @elseif($milestone->status == 'submitted')
                                <i class="fa-solid fa-circle-pause text-orange-400"></i>
                            @else
                                <i class="fa-regular fa-circle text-slate-300"></i>
                            @endif
                            <span class="text-slate-700 font-medium">{{ $milestone->title }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <!-- Card Footer & Action -->
            <div class="p-6 bg-slate-50/80 border-t border-slate-100 mt-auto">
                <div class="mb-4">
                    <div class="flex justify-between text-xs font-bold text-slate-500 mb-1">
                        <span>Objectif</span>
                    </div>
                    <p class="font-black text-lg text-slate-900">{{ number_format($project->budget_fcfa) }} <span class="text-xs text-slate-500">FCFA</span></p>
                </div>
                
                @auth
                    @if(Auth::user()->role === 'investor' && in_array($project->status, ['active', 'verified']))
                    <form action="{{ url('/invest/'.$project->id) }}" method="POST">
                        @csrf
                        <div class="relative mb-3">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm font-bold">CFA</span>
                            </div>
                            <input type="number" name="amount_fcfa" class="block w-full pl-12 pr-3 py-3 border border-slate-200 rounded-xl text-sm focus:ring-orange-500 focus:border-orange-500 transition bg-white shadow-inner" placeholder="Montant (ex: 50000)" required>
                        </div>
                        <button type="submit" class="w-full bg-[#063b27] hover:bg-[#0a4b33] text-white font-bold py-3 px-4 rounded-xl transition shadow-md flex items-center justify-center gap-2" onclick="return confirm('Simuler un paiement Lightning pour cet investissement ? (Calcule 2% de frais via routage intelligent)')">
                            <i class="fa-brands fa-bitcoin text-orange-400"></i> Financer via Lightning
                        </button>
                    </form>
                    @elseif(Auth::user()->role === 'investor' && !in_array($project->status, ['active', 'verified']))
                    <button class="w-full bg-slate-200 text-slate-500 font-bold py-3 px-4 rounded-xl cursor-not-allowed">
                        En attente de validation
                    </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center bg-white border-2 border-orange-200 text-orange-600 hover:bg-orange-50 hover:border-orange-300 font-bold py-3 px-4 rounded-xl transition">
                        Se connecter pour investir
                    </a>
                @endauth
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
