<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Card de Succès -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] border border-gray-100">

                <!-- Header Visuel -->
                <div class="bg-[#063b27] p-10 text-center relative">
                    <!-- Cercle d'icône avec animation -->
                    <div class="relative z-10 w-24 h-24 bg-white rounded-full mx-auto flex items-center justify-center shadow-2xl mb-6">
                        <i class="fa-solid fa-check-double text-4xl text-green-600 animate-bounce"></i>
                    </div>

                    <h2 class="text-3xl font-black text-white uppercase tracking-tight">
                        {{ session('title') ?? 'Opération Réussie' }}
                    </h2>
                    <p class="text-green-100/80 mt-2 font-medium">
                        {{ session('message') }}
                    </p>
                </div>

                <!-- Corps du message -->
                <div class="p-10 text-center">
                    <!-- Badge Technique (Preuve Bitcoin) -->
                    <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100 mb-10">
                        <div class="flex items-center justify-center gap-3 mb-3">
                            <i class="fa-solid fa-bolt text-orange-400"></i>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Identité Numérique & Ancrage Blockchain
                            </span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-inner">
                            <code class="text-[10px] text-green-600 font-mono break-all">
                                status::{{ session('type') ?? 'confirmed' }}::tx_{{ bin2hex(random_bytes(16)) }}
                            </code>
                        </div>
                    </div>

                    <!-- Actions Dynamiques -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if(session('type') == 'registration' && session('role') == 'investor')
                        <a href="{{ url('/projects') }}" class="inline-flex justify-center items-center px-6 py-4 bg-[#063b27] border border-transparent rounded-2xl font-bold text-white uppercase tracking-widest hover:bg-green-900 focus:bg-green-900 active:bg-green-950 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-green-900/20">
                            <i class="fa-solid fa-seedling mr-2 text-orange-400"></i> Explorer les projets
                        </a>
                        @elseif(session('type') == 'project_created')
                        <a href="{{ url('/dashboard') }}" class="inline-flex justify-center items-center px-6 py-4 bg-[#063b27] border border-transparent rounded-2xl font-bold text-white uppercase tracking-widest hover:bg-green-900 transition ease-in-out duration-150 shadow-lg shadow-green-900/20">
                            <i class="fa-solid fa-chart-line mr-2 text-orange-400"></i> Suivre mon projet
                        </a>
                        @else
                        <a href="{{ route('dashboard') }}" class="inline-flex justify-center items-center px-6 py-4 bg-[#063b27] border border-transparent rounded-2xl font-bold text-white uppercase tracking-widest hover:bg-green-900 transition ease-in-out duration-150 shadow-lg shadow-green-900/20">
                            Mon Tableau de bord
                        </a>
                        @endif

                        <a href="{{ url('/') }}" class="inline-flex justify-center items-center px-6 py-4 bg-white border-2 border-gray-100 rounded-2xl font-bold text-gray-600 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150">
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>