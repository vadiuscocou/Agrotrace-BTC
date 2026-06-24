@extends('layouts.app')
@section('title', 'Investor Dashboard')
@section('content')

<!-- Header -->
<div class="bg-white border-b border-slate-200 px-8 py-10">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">Impact Portfolio</h1>
            <p class="text-slate-500 mt-2 font-medium">Track your investments and verified milestones.</p>
        </div>
        <div class="hidden sm:block text-right bg-orange-50 border border-orange-100 px-6 py-3 rounded-2xl">
            <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-1">Total Routed</p>
            <p class="text-2xl font-black text-orange-600 flex items-center gap-2">
                <i class="fa-brands fa-bitcoin"></i> {{ number_format($investments->sum('amount_sats')) }} <span class="text-sm font-bold opacity-50">SATS</span>
            </p>
        </div>
    </div>
</div>

<div class="p-8">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-6">
            <div class="h-14 w-14 rounded-full bg-green-50 flex items-center justify-center text-green-600 text-2xl">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Invested</p>
                <p class="text-2xl font-black text-slate-900">{{ number_format($investments->sum('amount_fcfa')) }} <span class="text-sm text-slate-400">FCFA</span></p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-6">
            <div class="h-14 w-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-2xl">
                <i class="fa-solid fa-leaf"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Projects</p>
                <p class="text-2xl font-black text-slate-900">{{ $investments->unique('project_id')->count() }} <span class="text-sm text-slate-400">Supported</span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-6">
            <div class="h-14 w-14 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 text-2xl">
                <i class="fa-solid fa-certificate"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">ESG Impact</p>
                <p class="text-2xl font-black text-slate-900">Verified <span class="text-sm text-slate-400">On-Chain</span></p>
            </div>
        </div>
    </div>

    <!-- My Investments List -->
    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
        <i class="fa-solid fa-list-check text-slate-400"></i> Investment History
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($investments as $inv)
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-black uppercase tracking-widest mb-2">Active</span>
                    <h4 class="font-bold text-lg text-slate-900">{{ $inv->project->title }}</h4>
                    <p class="text-xs text-slate-500 font-medium"><i class="fa-solid fa-location-dot"></i> {{ $inv->project->location }}</p>
                </div>
            </div>
            
            <div class="mt-auto pt-6 border-t border-slate-50 space-y-4">
                <div class="flex justify-between items-end">
                    <span class="text-xs font-bold text-slate-400 uppercase">Amount</span>
                    <span class="font-black text-slate-900">{{ number_format($inv->amount_fcfa) }} FCFA</span>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Blockchain Receipt (OP_RETURN)</p>
                    <a href="https://mempool.space/tx/{{ $inv->payment_hash }}" target="_blank" class="flex items-center justify-between text-sm font-mono text-orange-500 hover:text-orange-600 transition group-hover:bg-orange-50 p-2 rounded-lg -mx-2">
                        <span>{{ substr($inv->payment_hash, 0, 16) }}...</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs opacity-50 group-hover:opacity-100"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
        
        @if($investments->count() == 0)
        <div class="col-span-full">
            <div class="bg-orange-50 border-2 border-dashed border-orange-200 rounded-[2rem] p-12 text-center">
                <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center text-orange-300 text-2xl mx-auto mb-4 shadow-sm">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <h3 class="text-lg font-bold text-orange-800 mb-2">Your portfolio is empty</h3>
                <p class="text-orange-600/70 mb-6 max-w-sm mx-auto">Start investing in agricultural projects and watch your impact grow on the blockchain.</p>
                <a href="{{ url('/projects') }}" class="inline-block bg-orange-500 text-white font-bold px-8 py-3 rounded-full hover:bg-orange-600 shadow-md transition-all hover:-translate-y-0.5">Browse Projects</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
