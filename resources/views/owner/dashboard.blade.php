@extends('layouts.app')
@section('title', 'Cooperative Dashboard')
@section('content')

<!-- Header -->
<div class="bg-white border-b border-slate-200 px-8 py-10">
    <div class="flex justify-between items-center">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-widest mb-3">
                <i class="fa-solid fa-tractor"></i> Project Owner
            </div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">Cooperative Hub</h1>
            <p class="text-slate-500 mt-2 font-medium">Manage your agricultural projects and submit proofs of progress.</p>
        </div>
        <button class="hidden sm:flex bg-[#063b27] hover:bg-[#0a4b33] text-white font-bold py-3 px-6 rounded-xl transition shadow-md items-center gap-2">
            <i class="fa-solid fa-plus"></i> New Project
        </button>
    </div>
</div>

<div class="p-8">
    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
        <i class="fa-solid fa-seedling text-slate-400"></i> My Active Projects
    </h2>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        @foreach($projects as $project)
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <!-- Project Header -->
            <div class="bg-slate-50 border-b border-slate-100 p-6 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-[#063b27]">{{ $project->title }}</h3>
                    <p class="text-slate-500 text-sm font-medium mt-1"><i class="fa-solid fa-location-dot"></i> {{ $project->location }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Budget</p>
                    <p class="font-black text-slate-900">{{ number_format($project->budget_fcfa) }} <span class="text-xs text-slate-400">FCFA</span></p>
                </div>
            </div>

            <!-- Milestones Section -->
            <div class="p-6">
                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Milestones (Jalons)</h4>
                
                <div class="space-y-4">
                    @foreach($project->milestones as $milestone)
                    <div class="bg-white border border-slate-100 rounded-2xl p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4 hover:border-slate-200 transition">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                @if($milestone->status == 'pending')
                                    <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                                @elseif($milestone->status == 'submitted')
                                    <span class="h-2 w-2 rounded-full bg-orange-400 animate-pulse"></span>
                                @else
                                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                @endif
                                <h5 class="font-bold text-slate-800">{{ $milestone->title }}</h5>
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-1 ml-4">{{ $milestone->description }}</p>
                        </div>
                        
                        <div class="flex-shrink-0 ml-4 sm:ml-0">
                            @if($milestone->status == 'pending')
                                <button class="bg-white border border-slate-200 text-slate-600 hover:text-[#063b27] hover:border-[#063b27] font-bold text-xs px-4 py-2 rounded-lg transition">
                                    <i class="fa-solid fa-camera mr-1"></i> Submit Proof
                                </button>
                            @elseif($milestone->status == 'submitted')
                                <span class="inline-block px-3 py-1.5 bg-orange-50 text-orange-600 rounded-lg text-xs font-bold border border-orange-100">
                                    <i class="fa-solid fa-clock mr-1"></i> Under Review
                                </span>
                            @else
                                <span class="inline-block px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-bold border border-green-100">
                                    <i class="fa-solid fa-check mr-1"></i> Validated
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        @if($projects->count() == 0)
        <div class="col-span-full">
            <div class="bg-white border border-slate-100 rounded-[2rem] p-12 text-center shadow-sm">
                <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">No projects yet</h3>
                <p class="text-slate-500 mb-6 max-w-sm mx-auto">Create your first agricultural project to start receiving investments.</p>
                <button class="bg-[#063b27] text-white font-bold px-8 py-3 rounded-full hover:bg-[#0a4b33] shadow-md transition-all">Create Project</button>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
