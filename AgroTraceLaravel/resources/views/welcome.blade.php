<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AgroTrace BTC | Track Every Satoshi to the Green Fields</title>
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Ccircle cx='256' cy='256' r='248' fill='%23000'/%3E%3Cpath fill='%23f7931a' d='M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zm-141.651-35.33c4.937-32.999-20.196-50.739-54.55-62.573l11.146-44.702-27.213-6.781-10.851 43.524c-7.154-1.783-14.502-3.464-21.803-5.13l10.929-43.81-27.198-6.781-11.153 44.686c-5.922-1.349-11.735-2.682-17.377-4.084l.031-.14-37.53-9.37-7.239 29.062s20.191 4.627 19.765 4.913c11.022 2.751 13.014 10.044 12.68 15.765l-12.639 50.668c.76.19.167.042.822.215l-1.071 4.29-17.653 70.825c-1.168 2.996-4.013 7.525-10.155 5.986.295.342-19.789-4.932-19.789-4.932l-13.442 31.082 35.474 8.847c6.643 1.656 13.238 3.376 19.756 5.041l-11.185 44.869 27.21 6.781 11.026-44.235c7.307 1.956 14.417 3.655 21.365 5.212l-10.978 44.029 27.202 6.781 11.233-45.03c45.992 8.705 80.603 5.176 95.311-36.425 11.838-33.454-1.341-52.793-24.3-65.048 17.293-3.972 30.292-15.531 33.153-39.387zm-73.078 81.936c-8.243 33.111-64.084 15.35-82.164 10.841l14.654-58.775c18.09 4.512 75.986 13.784 67.51 47.934zm9.324-85.748c-7.551 30.291-54.686 14.422-69.897 10.63l13.313-53.399c15.207 3.791 64.364 11.867 56.584 42.769z'/%3E%3C/svg%3E">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased selection:bg-orange-500 selection:text-white">

    <!-- Navbar (Glassmorphism) -->
    <nav x-data="{ open: false }" class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 group">
                    <i class="fa-brands fa-bitcoin text-orange-500 text-3xl group-hover:rotate-12 transition-transform"></i>
                    <span class="text-2xl font-black tracking-tight text-[#063b27]">AGRO<span class="text-orange-500">TRACE</span></span>
                </a>

                <!-- Mobile menu button -->
                <div class="flex md:hidden items-center">
                    <button @click="open = !open" class="text-slate-600 hover:text-[#063b27] focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl" x-show="!open"></i>
                        <i class="fa-solid fa-xmark text-2xl" x-show="open" style="display: none;"></i>
                    </button>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/projects') }}" class="text-sm font-semibold text-slate-600 hover:text-[#063b27] transition">Explorer</a>
                    <a href="{{ url('/verification') }}" class="text-sm font-semibold text-slate-600 hover:text-[#063b27] transition">Preuves en direct</a>
                    
                    <div class="h-6 w-px bg-slate-200"></div>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-[#063b27] hover:text-orange-500 transition">Aller au Tableau de bord <i class="fa-solid fa-arrow-right ml-1"></i></a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-[#063b27] transition">Se connecter</a>
                        <a href="{{ route('register') }}" class="bg-[#063b27] text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-[#0a4b33] hover:shadow-lg hover:-translate-y-0.5 transition-all">Commencer à Investir</a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-white border-b border-slate-100 shadow-lg absolute w-full"
             style="display: none;">
            <div class="px-6 pt-2 pb-6 space-y-4 flex flex-col">
                <a href="{{ url('/projects') }}" class="text-base font-semibold text-slate-600 hover:text-[#063b27]">Explorer</a>
                <a href="{{ url('/verification') }}" class="text-base font-semibold text-slate-600 hover:text-[#063b27]">Preuves en direct</a>
                <div class="h-px w-full bg-slate-100 my-2"></div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-base font-bold text-[#063b27]">Tableau de bord</a>
                @else
                    <a href="{{ route('login') }}" class="text-base font-semibold text-slate-600">Se connecter</a>
                    <a href="{{ route('register') }}" class="bg-[#063b27] text-white px-5 py-3 rounded-xl text-center font-bold mt-2">Commencer à Investir</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden">
        <!-- Background Gradients -->
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-green-200 to-orange-100 opacity-60 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 border border-orange-100 text-orange-600 text-sm font-bold mb-8 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-orange-500 animate-ping"></span>
                Hackathon Bitcoin Mastermind 2026
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black tracking-tight text-slate-900 mb-8 leading-[1.1]">
                Suivez Chaque <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Satoshi</span> <br class="hidden md:block">
                jusqu'aux Champs.
            </h1>
            
            <p class="mt-6 text-lg md:text-xl leading-8 text-slate-600 max-w-2xl mx-auto mb-10 font-medium">
                Nous éliminons le "déficit de confiance" dans l'investissement agricole. Nous utilisons le <b class="text-slate-900">Lightning Network</b> 
                pour des financements instantanés et <b class="text-slate-900">OP_RETURN</b> pour une preuve d'impact immuable sur la blockchain.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mt-8">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-[#063b27] text-white px-8 py-4 rounded-xl text-lg font-bold shadow-lg hover:shadow-2xl hover:-translate-y-0.5 hover:bg-[#0a4b33] transition-all duration-300 flex items-center justify-center gap-3">
                    <i class="fa-solid fa-bolt text-orange-400"></i> Investir via Lightning
                </a>
                <a href="{{ url('/verification') }}" class="w-full sm:w-auto bg-white border-2 border-[#063b27] text-[#063b27] px-8 py-4 rounded-xl text-lg font-bold hover:bg-[#063b27] hover:text-white transition-all duration-300 flex items-center justify-center gap-3 group">
                    Voir les Preuves <i class="fa-solid fa-arrow-right opacity-70 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="max-w-6xl mx-auto px-6 lg:px-8 mt-16 mb-24 relative z-10">
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-slate-100">
                <div class="p-8 text-center hover:bg-slate-50 transition-colors">
                    <div class="text-orange-500 text-2xl mb-3"><i class="fa-solid fa-vault"></i></div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Distribué</p>
                    <p class="text-3xl font-black text-[#063b27]">12.45M <span class="text-sm text-slate-500 font-bold ml-1">FCFA</span></p>
                </div>
                <div class="p-8 text-center hover:bg-slate-50 transition-colors">
                    <div class="text-orange-500 text-2xl mb-3"><i class="fa-brands fa-bitcoin"></i></div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Volume Bitcoin</p>
                    <p class="text-3xl font-black text-[#063b27]">0.428 <span class="text-sm text-slate-500 font-bold ml-1">BTC</span></p>
                </div>
                <div class="p-8 text-center hover:bg-slate-50 transition-colors">
                    <div class="text-[#063b27] text-2xl mb-3"><i class="fa-solid fa-link"></i></div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Preuves On-Chain</p>
                    <p class="text-3xl font-black text-[#063b27]">1,242</p>
                </div>
                <div class="p-8 text-center hover:bg-slate-50 transition-colors">
                    <div class="text-green-500 text-2xl mb-3"><i class="fa-solid fa-leaf"></i></div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Émissions CO2 Évitées</p>
                    <p class="text-3xl font-black text-[#063b27]">14.5 <span class="text-sm text-slate-500 font-bold ml-1">T</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features / The "Why" -->
    <div class="bg-slate-50 py-24 sm:py-32 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center mb-16">
                <h2 class="text-base font-bold leading-7 text-orange-500 tracking-widest uppercase">Le Changement de Paradigme</h2>
                <p class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Comment AgroTrace change la donne</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">Micropaiements Instantanés</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Les investisseurs de la diaspora peuvent financer les intrants agricoles de n'importe où dans le monde instantanément via le réseau Lightning. Zéro friction bancaire.
                    </p>
                </div>

                <!-- Feature 2: Escrow -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">Déblocage sous Conditions</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        L'argent de la diaspora est sécurisé. Les fonds ne sont versés à la coopérative que jalon par jalon, uniquement après validation stricte des preuves (photos, reçus).
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-link"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">Preuves Immuables</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Chaque jalon atteint (ex. semences plantées) est haché et ancré sur la blockchain Bitcoin via OP_RETURN. Une transparence absolue.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">Monétisation ESG</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Les impacts vérifiés génèrent des certificats On-Chain qui peuvent être vendus à des fonds ESG internationaux, créant une boucle de revenus durables.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Comparison Table / Why AgroTrace Emerged -->
    <div class="bg-slate-50 py-24">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center mb-16">
                <h2 class="text-base font-bold leading-7 text-orange-500 tracking-widest uppercase">Pourquoi AgroTrace ?</h2>
                <p class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Le Problème vs La Solution</p>
            </div>
            
            <div class="grid md:grid-cols-2 rounded-[2rem] overflow-hidden shadow-2xl bg-white border border-slate-100">
                <!-- Traditional -->
                <div class="p-8 md:p-12 lg:p-16 bg-white">
                    <h3 class="text-2xl font-black mb-8 text-slate-700">Base de données traditionnelle</h3>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="bg-red-50 text-red-500 h-6 w-6 rounded-full flex items-center justify-center shrink-0 mt-0.5"><i class="fa-solid fa-xmark text-sm"></i></div>
                            <span class="text-slate-600 font-medium text-lg leading-snug">Données modifiées par l'administrateur</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="bg-red-50 text-red-500 h-6 w-6 rounded-full flex items-center justify-center shrink-0 mt-0.5"><i class="fa-solid fa-xmark text-sm"></i></div>
                            <span class="text-slate-600 font-medium text-lg leading-snug">SWIFT : 3 à 5 jours, frais de 5 %</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="bg-red-50 text-red-500 h-6 w-6 rounded-full flex items-center justify-center shrink-0 mt-0.5"><i class="fa-solid fa-xmark text-sm"></i></div>
                            <span class="text-slate-600 font-medium text-lg leading-snug">Les preuves peuvent être falsifiées</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="bg-red-50 text-red-500 h-6 w-6 rounded-full flex items-center justify-center shrink-0 mt-0.5"><i class="fa-solid fa-xmark text-sm"></i></div>
                            <span class="text-slate-600 font-medium text-lg leading-snug">Virement minimum : 10 000 FCFA</span>
                        </li>
                    </ul>
                </div>
                <!-- Bitcoin -->
                <div class="p-8 md:p-12 lg:p-16 bg-[#063b27]">
                    <h3 class="text-2xl font-black mb-8 text-orange-400">Bitcoin / Lightning</h3>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="bg-orange-500 text-white h-6 w-6 rounded-full flex items-center justify-center shrink-0 mt-0.5"><i class="fa-solid fa-check text-sm"></i></div>
                            <span class="text-white font-medium text-lg leading-snug">Immutable (Personne ne peut modifier)</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="bg-orange-500 text-white h-6 w-6 rounded-full flex items-center justify-center shrink-0 mt-0.5"><i class="fa-solid fa-check text-sm"></i></div>
                            <span class="text-white font-medium text-lg leading-snug">Paiement instantané, 0 frais</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="bg-orange-500 text-white h-6 w-6 rounded-full flex items-center justify-center shrink-0 mt-0.5"><i class="fa-solid fa-check text-sm"></i></div>
                            <span class="text-white font-medium text-lg leading-snug">Les preuves existent indéfiniment sur la chaîne</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="bg-orange-500 text-white h-6 w-6 rounded-full flex items-center justify-center shrink-0 mt-0.5"><i class="fa-solid fa-check text-sm"></i></div>
                            <span class="text-white font-medium text-lg leading-snug">Micro-investissez à partir de 1 000 FCFA</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- How it works (Clean Design) -->
    <div class="bg-slate-50 pt-12 sm:pt-16 pb-24 sm:pb-32 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center mb-16">
                <h2 class="text-base font-bold leading-7 text-orange-500 tracking-widest uppercase">Écosystème AgroTrace</h2>
                <p class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Un Cercle Vertueux de Confiance</p>
                <p class="mt-6 text-lg leading-8 text-slate-600">Découvrez comment nos différents acteurs interagissent pour révolutionner le financement agricole africain grâce au Bitcoin.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Actor 1: Cooperative -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">1. Coopératives</h3>
                    <p class="text-slate-500 leading-relaxed text-sm mb-6">
                        Les agriculteurs s'inscrivent sur la plateforme, décrivent leurs besoins et les divisent en étapes clés (jalons).
                    </p>
                    <ul class="space-y-3 text-sm font-medium text-slate-600">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-500"></i> Création de projets</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-500"></i> Soumission de preuves</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-green-500"></i> Réception des fonds</li>
                    </ul>
                </div>

                <!-- Actor 2: Investor -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">2. La Diaspora</h3>
                    <p class="text-slate-500 leading-relaxed text-sm mb-6">
                        Les investisseurs utilisent le réseau Lightning pour financer instantanément l'économie locale.
                    </p>
                    <ul class="space-y-3 text-sm font-medium text-slate-600">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-orange-500"></i> Sans frais bancaires</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-orange-500"></i> Suivi de l'impact</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-orange-500"></i> Portefeuille transparent</li>
                    </ul>
                </div>

                <!-- Actor 3: Admin / Protocol -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">3. Le Protocole</h3>
                    <p class="text-slate-500 leading-relaxed text-sm mb-6">
                        Les preuves soumises sont vérifiées. Une fois validées, elles sont ancrées dans la blockchain Bitcoin.
                    </p>
                    <ul class="space-y-3 text-sm font-medium text-slate-600">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-blue-500"></i> Validation des jalons</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-blue-500"></i> Hachage des preuves</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-blue-500"></i> Ancrage OP_RETURN</li>
                    </ul>
                </div>
                
                <!-- Actor 4: ROI -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">4. Le Rendement</h3>
                    <p class="text-slate-500 leading-relaxed text-sm mb-6">
                        Les revenus des récoltes et la vente de Certificats ESG créent un véritable ROI pour l'investisseur.
                    </p>
                    <ul class="space-y-3 text-sm font-medium text-slate-600">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-purple-500"></i> Remboursement post-récolte</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-purple-500"></i> Dividendes (ESG)</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-purple-500"></i> Réception en SATS</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center text-slate-400 text-sm font-medium">
            &copy; 2026 AgroTrace BTC - Built for the Bitcoin Mastermind Hackathon.
        </div>
    </footer>

</body>
</html>
