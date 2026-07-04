@extends('layouts.app')
@section('title', 'Explorer | AgroTrace-BTC')
@section('content')

<!-- Explorer Header (Premium) -->
<div class="relative bg-slate-900 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1586771107445-d3ca888129ff?auto=format&fit=crop&q=80&w=2000" alt="Agriculture" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/90 to-transparent"></div>
    </div>
    <div class="relative px-8 py-16 md:py-24 max-w-7xl mx-auto">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 bg-green-500/10 px-4 py-2 rounded-full border border-green-500/30 mb-6 backdrop-blur-sm">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-xs font-bold text-green-400 uppercase tracking-widest">Réseau Lightning Actif</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-black tracking-tight text-white mb-4">Investissez dans l'avenir de l'agriculture.</h1>
            <p class="text-lg text-slate-300 font-medium leading-relaxed">Parcourez les coopératives agricoles rigoureusement sélectionnées, financez leurs campagnes instantanément via Bitcoin et profitez de rendements transparents ancrés sur la blockchain.</p>
        </div>
    </div>
</div>

<div class="p-8 max-w-7xl mx-auto">
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8 rounded-r-xl shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
            <p class="text-green-700 font-bold m-0">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-xl shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
            <p class="text-red-700 font-bold m-0">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($projects as $project)
        <div class="bg-white rounded-3xl shadow-lg border border-slate-200 hover:border-orange-300 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col overflow-hidden group">

            <!-- Card Header Cover -->
            <div class="h-32 bg-gradient-to-br from-[#063b27] to-slate-900 relative p-6 flex flex-col justify-between overflow-hidden">
                <div class="absolute -right-4 -bottom-4 p-4 opacity-10 group-hover:opacity-20 group-hover:scale-110 transition-all duration-500">
                    <i class="fa-solid fa-seedling text-8xl text-white"></i>
                </div>
                <div class="relative z-10 flex justify-between items-start">
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm {{ in_array($project->status, ['funded', 'in_progress', 'completed']) ? 'bg-green-500 text-white' : 'bg-orange-500 text-white' }}">
                        {{ in_array($project->status, ['funded', 'in_progress', 'completed']) ? 'Actif' : 'En attente' }}
                    </span>
                    <div class="bg-white/20 backdrop-blur-md px-2 py-1 rounded-lg text-white text-xs font-bold flex items-center gap-1 border border-white/10">
                        <i class="fa-solid fa-star text-yellow-400 text-[10px]"></i> {{ $project->user->trust_score }}
                    </div>
                </div>
                <h3 class="relative z-10 text-xl font-black text-white mt-auto truncate pr-8">{{ $project->title }}</h3>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                        <i class="fa-solid fa-location-dot text-orange-500"></i> {{ $project->region }}
                    </div>
                    <div class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">
                        <i class="fa-solid fa-users text-slate-400"></i> {{ explode(' ', trim($project->user->name))[0] }}
                    </div>
                </div>

                <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-6">
                    {{ $project->description }}
                </p>

                @if($project->milestones->count() > 0)
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3"><i class="fa-solid fa-list-check mr-1"></i> Jalons du projet</h4>
                    <ul class="space-y-2">
                        @foreach($project->milestones->take(3) as $milestone)
                        <li class="flex items-center gap-2 text-xs">
                            @if(in_array($milestone->status, ['verified', 'validated']))
                            <i class="fa-solid fa-circle-check text-green-500"></i>
                            @elseif($milestone->status == 'submitted')
                            <i class="fa-solid fa-circle-pause text-orange-400"></i>
                            @else
                            <i class="fa-regular fa-circle text-slate-300"></i>
                            @endif
                            <span class="text-slate-700 font-medium truncate">{{ $milestone->title }}</span>
                        </li>
                        @endforeach
                        @if($project->milestones->count() > 3)
                        <li class="text-xs text-slate-400 font-bold italic ml-5">+ {{ $project->milestones->count() - 3 }} autres...</li>
                        @endif
                    </ul>
                </div>
                @endif
            </div>

            <!-- Card Footer & Action -->
            <div class="p-6 bg-slate-50/50 border-t border-slate-100 mt-auto">
                <div class="mb-5">
                    @php
                        $funded = $project->target_amount_fcfa - $project->remaining_amount;
                        $percent = $project->target_amount_fcfa > 0 ? round(($funded / $project->target_amount_fcfa) * 100) : 0;
                        if($percent > 100) $percent = 100;
                    @endphp
                    <div class="flex justify-between text-xs font-bold text-slate-500 mb-2">
                        <span class="uppercase tracking-wider">Objectif : {{ number_format($project->target_amount_fcfa) }} FCFA</span>
                        <span class="text-green-600 bg-green-100 px-2 py-0.5 rounded">{{ $percent }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 mb-2 overflow-hidden shadow-inner">
                        <div class="bg-green-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                    </div>
                    <p class="font-black text-sm text-slate-800">{{ number_format($project->remaining_amount) }} <span class="text-xs text-slate-500 font-medium">FCFA restants</span></p>
                </div>

                @auth
                @if(Auth::user()->role === 'investor' && in_array($project->status, ['validated', 'awaiting_funding', 'funded', 'in_progress']) && $project->remaining_amount > 0)
                @php
                $minInvestment = max(1, intval($project->target_amount_fcfa / 4));
                if ($project->remaining_amount < $minInvestment) {
                    $minInvestment=$project->remaining_amount;
                    }
                    $isFixed = ($project->remaining_amount <= $minInvestment);
                        @endphp
                        <form id="investForm-{{ $project->id }}" action="{{ url('/invest/'.$project->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Montant (FCFA)</label>
                                <span class="text-[10px] text-slate-400 font-bold">Min: {{ number_format($minInvestment) }}</span>
                            </div>
                            <input type="number" name="amount_fcfa" min="{{ $minInvestment }}" max="{{ $project->remaining_amount }}" step="1000" value="{{ $isFixed ? $project->remaining_amount : $minInvestment }}" {{ $isFixed ? 'readonly' : '' }} class="w-full bg-white border border-slate-200 text-slate-800 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 font-black shadow-inner {{ $isFixed ? 'opacity-70 cursor-not-allowed' : '' }}" required>
                            @if($isFixed)
                            <p class="text-[10px] text-orange-500 mt-1 font-medium"><i class="fa-solid fa-lock text-[8px]"></i> Montant restant verrouillé pour clôturer le projet.</p>
                            @endif
                        </div>
                        <button type="button" class="w-full bg-gradient-to-r from-[#063b27] to-[#0a4b33] hover:from-[#084b32] hover:to-[#0c5c3e] text-white font-bold py-3 px-4 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 group-hover:scale-[1.02]" onclick="handleInvest(event, {{ $project->id }})">
                            <i class="fa-solid fa-bolt text-yellow-400"></i> Investir
                        </button>
                        </form>
                        @elseif(Auth::user()->role === 'investor' && $project->remaining_amount <= 0)
                            <button disabled class="w-full bg-slate-100 border border-slate-200 text-slate-400 font-bold py-3 px-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check-double"></i> Financement Atteint
                            </button>
                            @elseif(Auth::user()->role === 'investor')
                            <button disabled class="w-full bg-slate-100 border border-slate-200 text-slate-400 font-bold py-3 px-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fa-solid fa-hourglass-half"></i> En attente de validation
                            </button>
                            @endif
                            @else
                            <a href="{{ route('login') }}" class="block w-full text-center bg-white border-2 border-orange-200 text-orange-600 hover:bg-orange-50 hover:border-orange-300 font-bold py-3 px-4 rounded-xl transition shadow-sm">
                                Se connecter pour investir
                            </a>
                            @endauth
            </div>
        </div>
        @endforeach
    </div>
</div>
<script>
    function handleInvest(event, projectId) {
        event.preventDefault();

        if (!confirm('Générer une facture Lightning pour cet investissement ?')) {
            return;
        }

        const form = document.getElementById('investForm-' + projectId);
        form.submit();
    }
</script>
@endsection
