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
        <div class="bg-white rounded-2xl shadow-sm border border-slate-300 overflow-hidden flex flex-col hover:border-slate-400 hover:shadow-md transition-all">
            <!-- Project Header -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h3 class="text-sm font-black text-[#063b27] mb-1">{{ $project->title }}</h3>
                    <div class="flex flex-wrap gap-1.5">
                        @if($project->status == 'submitted')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-600 rounded text-[9px] font-bold uppercase"><i class="fa-solid fa-file-arrow-up"></i> Soumis</span>
                        @elseif($project->status == 'under_review')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-orange-50 border border-orange-200 text-orange-700 rounded text-[9px] font-bold uppercase"><i class="fa-solid fa-magnifying-glass"></i> En étude</span>
                        @elseif($project->status == 'validated')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 border border-blue-200 text-blue-700 rounded text-[9px] font-bold uppercase"><i class="fa-solid fa-check"></i> Validé</span>
                        @elseif($project->status == 'awaiting_funding')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded text-[9px] font-bold uppercase"><i class="fa-solid fa-hourglass-half"></i> En attente</span>
                        @elseif($project->status == 'funded' || $project->status == 'in_progress')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 border border-green-200 text-green-700 rounded text-[9px] font-bold uppercase"><i class="fa-solid fa-seedling"></i> {{ $project->status == 'funded' ? 'Financé' : 'En cours' }}</span>
                        @elseif($project->status == 'completed')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-800 border border-slate-900 text-white rounded text-[9px] font-bold uppercase"><i class="fa-solid fa-check-double"></i> Terminé</span>
                        @endif
                    </div>
                </div>
                <div class="text-left sm:text-right shrink-0">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Budget Total</p>
                    <p class="text-base font-black text-slate-900">{{ number_format($project->target_amount_fcfa) }} <span class="text-[9px] text-slate-600 font-bold">FCFA</span></p>
                </div>
            </div>

            <div class="bg-white px-4 py-2 border-b border-slate-100 flex justify-end">
                <a href="{{ url('/projects/' . $project->id . '/contract') }}" target="_blank" class="text-[10px] font-bold text-slate-600 hover:text-slate-900 transition inline-flex items-center gap-1">
                    <i class="fa-solid fa-file-contract text-orange-500"></i> Voir le contrat
                </a>
            </div>

            <!-- Échéancier de Remboursement -->
            @if($project->status == 'in_progress')
            <div class="p-4 bg-slate-50/50">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest"><i class="fa-solid fa-calendar-check text-orange-500 mr-1.5"></i>Échéancier</h4>
                    @if($project->repayments->count() == 0)
                    <form action="{{ url('/projects/'.$project->id.'/generate-tranches') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-[#063b27] hover:bg-[#0a4b33] text-white font-bold py-1 px-2.5 rounded text-[10px] transition shadow-sm">
                            Générer
                        </button>
                    </form>
                    @endif
                </div>

                @if($project->repayments->count() > 0)
                <div class="space-y-2">
                    @php
                        $totalRepayments = $project->repayments->count();
                        $paidRepayments = $project->repayments->where('status', 'paid')->count();
                        $progressPercent = $totalRepayments > 0 ? round(($paidRepayments / $totalRepayments) * 100) : 0;
                    @endphp
                    
                    <!-- Barre de progression -->
                    <div class="mb-3">
                        <div class="flex justify-between text-[10px] font-bold text-slate-500 mb-1">
                            <span>Progression</span>
                            <span class="text-orange-600">{{ $progressPercent }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-orange-400 to-orange-500 h-1.5 rounded-full transition-all duration-1000" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        @foreach($project->repayments as $index => $repayment)
                        @php
                            $isLate = $repayment->status == 'pending' && \Carbon\Carbon::parse($repayment->due_date)->isPast();
                        @endphp
                        <div class="bg-white border {{ $isLate ? 'border-red-200 bg-red-50/30' : 'border-slate-200' }} rounded-lg p-2 flex flex-col justify-between transition hover:shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h5 class="font-bold {{ $isLate ? 'text-red-700' : 'text-slate-800' }} text-[10px]">T{{ $index + 1 }}</h5>
                                    <p class="text-[9px] {{ $isLate ? 'text-red-500 font-bold' : 'text-slate-500' }}">{{ \Carbon\Carbon::parse($repayment->due_date)->format('d/m/Y') }}</p>
                                </div>
                                @if($repayment->status == 'paid')
                                    <span class="text-green-500 text-[10px]"><i class="fa-solid fa-check-circle"></i></span>
                                @endif
                            </div>
                            
                            <div class="flex justify-between items-end">
                                <p class="font-black text-slate-900 text-xs">{{ number_format($repayment->amount_fcfa) }} <span class="text-[8px] text-slate-500">FCFA</span></p>
                                @if($repayment->status == 'pending')
                                    <button @click="$dispatch('open-repay-modal', { id: {{ $project->id }}, repayment_id: {{ $repayment->id }}, title: 'Tranche {{ $index + 1 }} - {{ addslashes($project->title) }}', amount: {{ $repayment->amount_fcfa }} })" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-0.5 px-2 rounded text-[9px] transition">
                                        Payer
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Milestones Section -->
            <div class="p-4 border-t border-slate-100 flex-1">
                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Jalons</h4>

                <div class="space-y-2">
                    @foreach($project->milestones as $milestone)
                    <div class="bg-white border border-slate-200 rounded-lg p-3 flex flex-col sm:flex-row justify-between sm:items-center gap-2 hover:border-slate-300 transition">
                        <div>
                            <div class="flex items-center gap-1.5 mb-0.5">
                                @if($milestone->status == 'pending')
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                                @elseif($milestone->status == 'submitted')
                                <span class="h-1.5 w-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                                @else
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                @endif
                                <h5 class="font-bold text-slate-700 text-xs">{{ $milestone->title }}</h5>
                            </div>
                            <p class="text-[10px] text-slate-500 line-clamp-1 ml-3">{{ $milestone->description }}</p>
                        </div>

                        <div class="flex-shrink-0 ml-3 sm:ml-0">
                            @if($milestone->status == 'pending')
                            <button @click="currentMilestoneId = {{ $milestone->id }}; proofModalOpen = true" class="bg-slate-50 border border-slate-200 text-slate-600 hover:bg-[#063b27] hover:text-white hover:border-[#063b27] font-bold text-[10px] px-2.5 py-1 rounded transition">
                                <i class="fa-solid fa-camera mr-1"></i> Preuve
                            </button>
                            @elseif($milestone->status == 'submitted')
                            <span class="inline-block px-2 py-1 bg-orange-50 text-orange-600 rounded text-[9px] font-bold">
                                En révision
                            </span>
                            @else
                            <span class="inline-block px-2 py-1 bg-green-50 text-green-700 rounded text-[9px] font-bold">
                                Validé
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
    <div x-data="{ createModalOpen: false, milestones: [{title: '', amount: '', desc: ''}] }" @open-create-modal.window="createModalOpen = true" x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="createModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="createModalOpen = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="createModalOpen" x-transition.opacity.duration.300ms class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full">
                <div class="bg-white px-6 pt-6 pb-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[#063b27]/10">
                            <i class="fa-solid fa-tractor text-[#063b27] text-xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900" id="modal-title">Créer un Projet Agricole</h3>
                    </div>
                    <form action="{{ url('/projects') }}" method="POST" id="createProjectForm" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Colonne Gauche : Informations de base -->
                            <div>
                                <div class="mb-4">
                                    <label class="block text-slate-700 text-sm font-bold mb-1" for="title">Titre du Projet</label>
                                    <input class="shadow-sm appearance-none border border-slate-200 rounded-xl w-full py-2 px-4 text-slate-700 leading-tight focus:outline-none focus:border-[#063b27] focus:ring-2 focus:ring-[#063b27]/20 transition-all" id="title" name="title" type="text" required placeholder="ex: Coopérative Maïs Koudougou">
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="col-span-2 sm:col-span-1">
                                        <label class="block text-slate-700 text-sm font-bold mb-1" for="location">Localisation</label>
                                        <input class="shadow-sm appearance-none border border-slate-200 rounded-xl w-full py-2 px-4 text-slate-700 leading-tight focus:outline-none focus:border-[#063b27] focus:ring-2 focus:ring-[#063b27]/20 transition-all" id="location" name="location" type="text" required placeholder="ex: Koudougou, BF">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label class="block text-slate-700 text-sm font-bold mb-1" for="budget">Budget (FCFA)</label>
                                        <input class="shadow-sm appearance-none border border-slate-200 rounded-xl w-full py-2 px-4 text-slate-700 leading-tight focus:outline-none focus:border-[#063b27] focus:ring-2 focus:ring-[#063b27]/20 transition-all" id="budget" name="budget_fcfa" type="number" required placeholder="ex: 5000000">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-slate-700 text-sm font-bold mb-1" for="description">Description</label>
                                    <textarea class="shadow-sm appearance-none border border-slate-200 rounded-xl w-full py-2 px-4 text-slate-700 leading-tight focus:outline-none focus:border-[#063b27] focus:ring-2 focus:ring-[#063b27]/20 transition-all" id="description" name="description" rows="3" required placeholder="Impact et objectifs..."></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-slate-700 text-sm font-bold mb-1" for="document">Document (PDF, Image)</label>
                                    <input class="shadow-sm appearance-none border border-slate-200 rounded-xl w-full py-1.5 px-3 text-slate-700 leading-tight focus:outline-none focus:border-[#063b27] focus:ring-2 focus:ring-[#063b27]/20 transition-all" id="document" name="document" type="file" accept=".pdf,image/*">
                                </div>
                            </div>
                            
                            <!-- Colonne Droite : Jalons -->
                            <div class="bg-slate-50/50 rounded-xl p-2 border border-slate-100">
                                <div class="flex justify-between items-center mb-3">
                                    <label class="block text-slate-700 text-sm font-bold">Découpage en Jalons</label>
                                    <button type="button" @click="milestones.push({title: '', amount: '', desc: ''})" class="text-xs bg-orange-100 text-orange-600 font-bold py-1 px-3 rounded-lg hover:bg-orange-200 transition"><i class="fa-solid fa-plus"></i> Ajouter</button>
                                </div>

                                <div class="max-h-[350px] overflow-y-auto pr-1">
                                    <template x-for="(milestone, index) in milestones" :key="index">
                                        <div class="bg-white p-3 rounded-xl border border-slate-200 mb-3 relative shadow-sm">
                                            <button type="button" @click="milestones.splice(index, 1)" x-show="milestones.length > 1" class="absolute top-1 right-2 text-red-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>

                                            <div class="grid grid-cols-2 gap-2 mb-2 pr-5">
                                                <div>
                                                    <input class="appearance-none border border-slate-200 rounded-lg w-full py-1.5 px-2 text-xs text-slate-700" :name="'milestones['+index+'][title]'" x-model="milestone.title" type="text" required placeholder="Titre (ex: Semis)">
                                                </div>
                                                <div>
                                                    <input class="appearance-none border border-slate-200 rounded-lg w-full py-1.5 px-2 text-xs text-slate-700" :name="'milestones['+index+'][amount]'" x-model="milestone.amount" type="number" required placeholder="Budget FCFA">
                                                </div>
                                            </div>
                                            <input class="appearance-none border border-slate-200 rounded-lg w-full py-1.5 px-2 text-xs text-slate-700" :name="'milestones['+index+'][desc]'" x-model="milestone.desc" type="text" required placeholder="Description de l'étape">
                                        </div>
                                    </template>
                                </div>
                            </div>
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
                                <form :action="'{{ url('/milestones') }}/' + currentMilestoneId + '/proof'" method="POST" id="submitProofForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex items-center gap-4 bg-slate-50 border border-slate-200 p-3 rounded-xl">
                                        <label class="flex-shrink-0 cursor-pointer inline-flex items-center gap-2 bg-white border border-slate-300 hover:border-[#063b27] hover:text-[#063b27] text-slate-700 font-bold py-2 px-4 rounded-lg text-xs transition shadow-sm">
                                            <i class="fa-solid fa-paperclip"></i>
                                            <span>Joindre des fichiers</span>
                                            <input type="file" name="proof_images[]" multiple accept="image/*,.pdf" class="hidden" required onchange="document.getElementById('file-count').textContent = this.files.length + ' fichier(s) sélectionné(s)'">
                                        </label>
                                        <div class="text-xs text-slate-500">
                                            <span id="file-count" class="font-bold text-slate-800">Aucun fichier</span>
                                            <p class="text-[9px] mt-0.5 uppercase tracking-wide">PNG, JPG, PDF (Max 5MB)</p>
                                        </div>
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

    <!-- Repay Project Modal -->
    <div x-data="{ repayModalOpen: false, projectId: null, repaymentId: null, projectTitle: '', repayAmount: 0 }"
        @open-repay-modal.window="repayModalOpen = true; projectId = $event.detail.id; repaymentId = $event.detail.repayment_id; projectTitle = $event.detail.title; repayAmount = $event.detail.amount;"
        x-show="repayModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="repayModalOpen" class="fixed inset-0 bg-slate-900 bg-opacity-75" @click="repayModalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="repayModalOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-6 pt-6 pb-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100">
                            <i class="fa-solid fa-hand-holding-dollar text-orange-500 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900">Rembourser une Tranche</h3>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 mb-5 text-center">
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1" x-text="projectTitle"></p>
                        <p class="text-xs text-slate-400 mb-4">Distribution automatique via Lightning Network</p>

                        <div class="flex justify-between items-center pt-3 border-t border-slate-200 mt-2">
                            <span class="text-sm font-black text-slate-900">Total à reverser :</span>
                            <span class="text-xl font-black text-orange-500" x-text="new Intl.NumberFormat('fr-FR').format(repayAmount) + ' FCFA'"></span>
                        </div>
                    </div>

                    <form :action="'{{ url('/repayments') }}/' + repaymentId + '/pay'" method="POST" id="repayProjectForm">
                        @csrf
                        <div class="mt-4 border-t border-slate-200 pt-4 text-center">
                            <p class="text-slate-600 mb-2">Cliquez ci-dessous pour générer le QR Code de paiement de cette tranche.</p>
                        </div>
                    </form>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('repayProjectForm').submit()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-orange-500 text-base font-medium text-white hover:bg-orange-600 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fa-solid fa-qrcode mr-2 mt-1"></i> Générer le QR Code
                    </button>
                    <button type="button" @click="repayModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection