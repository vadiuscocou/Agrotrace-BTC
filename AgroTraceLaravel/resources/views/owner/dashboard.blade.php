@extends('layouts.app')
@section('title', 'Cooperative Dashboard')
@section('content')

<!-- Header -->
<div class="bg-white border-b border-slate-200 px-8 py-10">
    <div class="flex justify-between items-center">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-widest mb-3">
                <i class="fa-solid fa-tractor"></i> Coopérative Agricole
            </div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">Espace Coopérative</h1>
            <p class="text-slate-500 mt-2 font-medium">Gérez vos projets agricoles et soumettez des preuves d'avancement.</p>
        </div>
        <button @click="$dispatch('open-create-modal')" class="hidden sm:flex bg-[#063b27] hover:bg-[#0a4b33] text-white font-bold py-3 px-6 rounded-xl transition shadow-md items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nouveau Projet
        </button>
    </div>
</div>

<div class="p-8" x-data="{ createModalOpen: false, proofModalOpen: false, currentMilestoneId: null }">
    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
        <i class="fa-solid fa-seedling text-slate-400"></i> Mes Projets Actifs
    </h2>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        @foreach($projects as $project)
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <!-- Project Header -->
            <div class="bg-slate-50 border-b border-slate-100 p-6 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-[#063b27]">{{ $project->title }}</h3>
                    <p class="text-slate-500 text-sm font-medium mt-1"><i class="fa-solid fa-location-dot"></i> {{ $project->region }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Budget</p>
                    <p class="font-black text-slate-900">{{ number_format($project->target_amount_fcfa) }} <span class="text-xs text-slate-400">FCFA</span></p>
                </div>
            </div>

            <!-- Milestones Section -->
            <div class="p-6">
                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Jalons du Projet</h4>
                
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
                                <button @click="currentMilestoneId = {{ $milestone->id }}; proofModalOpen = true" class="bg-white border border-slate-200 text-slate-600 hover:text-[#063b27] hover:border-[#063b27] font-bold text-xs px-4 py-2 rounded-lg transition">
                                    <i class="fa-solid fa-camera mr-1"></i> Soumettre Preuve
                                </button>
                            @elseif($milestone->status == 'submitted')
                                <span class="inline-block px-3 py-1.5 bg-orange-50 text-orange-600 rounded-lg text-xs font-bold border border-orange-100">
                                    <i class="fa-solid fa-clock mr-1"></i> En révision
                                </span>
                            @else
                                <span class="inline-block px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-bold border border-green-100">
                                    <i class="fa-solid fa-check mr-1"></i> Validé
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
                <h3 class="text-lg font-bold text-slate-800 mb-2">Aucun projet pour le moment</h3>
                <p class="text-slate-500 mb-6 max-w-sm mx-auto">Créez votre premier projet agricole pour commencer à recevoir des investissements.</p>
                <button @click="$dispatch('open-create-modal')" class="bg-[#063b27] text-white font-bold px-8 py-3 rounded-full hover:bg-[#0a4b33] shadow-md transition-all">Créer un Projet</button>
            </div>
        </div>
        @endif
    </div>

    <!-- Create Project Modal -->
    <div x-data="{ createModalOpen: false }" @open-create-modal.window="createModalOpen = true" x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="createModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="createModalOpen = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="createModalOpen" x-transition.opacity.duration.300ms class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-6 pt-6 pb-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[#063b27]/10">
                            <i class="fa-solid fa-tractor text-[#063b27] text-xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900" id="modal-title">Créer un Projet Agricole</h3>
                    </div>
                    <form action="{{ url('/projects') }}" method="POST" id="createProjectForm">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-slate-700 text-sm font-bold mb-2" for="title">Titre du Projet</label>
                            <input class="shadow-sm appearance-none border border-slate-200 rounded-xl w-full py-3 px-4 text-slate-700 leading-tight focus:outline-none focus:border-[#063b27] focus:ring-2 focus:ring-[#063b27]/20 transition-all" id="title" name="title" type="text" required placeholder="ex: Coopérative Maïs Koudougou">
                        </div>
                        <div class="grid grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-slate-700 text-sm font-bold mb-2" for="location">Localisation</label>
                                <input class="shadow-sm appearance-none border border-slate-200 rounded-xl w-full py-3 px-4 text-slate-700 leading-tight focus:outline-none focus:border-[#063b27] focus:ring-2 focus:ring-[#063b27]/20 transition-all" id="location" name="location" type="text" required placeholder="ex: Burkina Faso">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-bold mb-2" for="budget">Budget Cible (FCFA)</label>
                                <input class="shadow-sm appearance-none border border-slate-200 rounded-xl w-full py-3 px-4 text-slate-700 leading-tight focus:outline-none focus:border-[#063b27] focus:ring-2 focus:ring-[#063b27]/20 transition-all" id="budget" name="budget_fcfa" type="number" required placeholder="ex: 5000000">
                            </div>
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2" for="description">Description détaillée</label>
                            <textarea class="shadow-sm appearance-none border border-slate-200 rounded-xl w-full py-3 px-4 text-slate-700 leading-tight focus:outline-none focus:border-[#063b27] focus:ring-2 focus:ring-[#063b27]/20 transition-all" id="description" name="description" rows="3" required placeholder="Décrivez l'impact et les objectifs de votre projet..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('createProjectForm').submit()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#063b27] text-base font-medium text-white hover:bg-[#0a4b33] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Créer le Projet
                    </button>
                    <button type="button" @click="createModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Proof Modal -->
    <div x-show="proofModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="proofModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="proofModalOpen = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="proofModalOpen" x-transition.opacity.duration.300ms class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-camera text-blue-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">Soumettre une Preuve de Jalon</h3>
                            <div class="mt-4">
                                <form :action="'{{ url('/milestones') }}/' + currentMilestoneId + '/proof'" method="POST" id="submitProofForm">
                                    @csrf
                                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center bg-slate-50">
                                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 mb-2"></i>
                                        <p class="text-sm text-slate-500 font-medium">Cliquez pour télécharger une photo ou un reçu</p>
                                        <p class="text-xs text-slate-400 mt-1">PNG, JPG, PDF jusqu'à 5MB</p>
                                        <input type="file" class="hidden">
                                        <p class="mt-4 text-xs text-orange-500 font-bold bg-orange-50 p-2 rounded inline-block"><i class="fa-solid fa-circle-info"></i> Pour la démo, l'envoi de fichier est simulé</p>
                                    </div>
                                    <div class="mt-4">
                                        <label class="block text-slate-700 text-sm font-bold mb-2" for="proof_desc">Détails de la preuve</label>
                                        <textarea class="shadow-sm appearance-none border border-slate-200 rounded-xl w-full py-3 px-4 text-slate-700 leading-tight focus:outline-none focus:border-[#063b27] focus:ring-1 focus:ring-[#063b27]" id="proof_desc" name="proof_notes" rows="2" placeholder="Décrivez brièvement cette preuve..."></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('submitProofForm').submit()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Soumettre
                    </button>
                    <button type="button" @click="proofModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
