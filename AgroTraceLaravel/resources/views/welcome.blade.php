<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AgroTrace BTC | Track Every Satoshi to the Green Fields</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-[#063b27] text-white px-8 py-4 rounded-2xl text-lg font-bold hover:bg-[#0a4b33] hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    Investir via Lightning
                </a>
                <a href="{{ url('/verification') }}" class="w-full sm:w-auto bg-white border-2 border-slate-200 text-slate-700 px-8 py-4 rounded-2xl text-lg font-bold hover:border-orange-400 hover:text-orange-600 transition-all duration-300 group">
                    Voir les Preuves en direct <i class="fa-solid fa-arrow-up-right-from-square ml-2 opacity-50 group-hover:opacity-100 transition-opacity"></i>
                </a>
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
            
            <div class="grid md:grid-cols-3 gap-8">
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
                
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-link"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">Preuves Immuables</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Chaque jalon atteint (ex. semences plantées) est haché et ancré sur la blockchain Bitcoin via OP_RETURN. Une transparence absolue.
                    </p>
                </div>

                <!-- Feature 3 -->
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
    
    <!-- How it works (Redesigned) -->
    <div class="py-24 sm:py-32 bg-white relative overflow-hidden">
        <!-- Decorative background -->
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 2px 2px, black 1px, transparent 0); background-size: 32px 32px;"></div>
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="mx-auto max-w-2xl lg:text-center mb-20">
                <h2 class="text-base font-bold leading-7 text-orange-500 tracking-widest uppercase">Écosystème AgroTrace</h2>
                <p class="mt-2 text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">Un Cercle Vertueux de Confiance</p>
                <p class="mt-6 text-lg leading-8 text-slate-600">Découvrez comment nos différents acteurs interagissent pour révolutionner le financement agricole africain grâce au Bitcoin.</p>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-12">
                <!-- Actor 1: Cooperative -->
                <div class="group relative bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden cursor-default">
                    <div class="absolute -top-10 -right-10 p-6 opacity-5 group-hover:opacity-10 group-hover:rotate-12 transition-all duration-700">
                        <i class="fa-solid fa-seedling text-[150px] text-green-500"></i>
                    </div>
                    <div class="h-16 w-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-2xl mb-8 shadow-inner group-hover:scale-110 group-hover:bg-green-500 group-hover:text-white transition-all duration-500">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">1. Les Coopératives</h3>
                    <p class="text-slate-600 leading-relaxed mb-8">
                        Les agriculteurs locaux s'inscrivent sur la plateforme, décrivent leurs besoins (le projet) et le divisent en étapes clés (jalons).
                    </p>
                    <ul class="space-y-4 text-sm font-bold text-slate-500">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-green-500 text-lg"></i> Création de projets</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-green-500 text-lg"></i> Soumission de preuves (photos)</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-green-500 text-lg"></i> Réception des fonds</li>
                    </ul>
                </div>

                <!-- Actor 2: Investor -->
                <div class="group relative bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden cursor-default">
                    <div class="absolute -top-10 -right-10 p-6 opacity-5 group-hover:opacity-10 group-hover:-rotate-12 transition-all duration-700">
                        <i class="fa-solid fa-globe text-[150px] text-orange-500"></i>
                    </div>
                    <div class="h-16 w-16 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center text-2xl mb-8 shadow-inner group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-500">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">2. La Diaspora</h3>
                    <p class="text-slate-600 leading-relaxed mb-8">
                        Les investisseurs parcourent les projets et utilisent le réseau Lightning pour financer instantanément l'économie locale.
                    </p>
                    <ul class="space-y-4 text-sm font-bold text-slate-500">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-orange-500 text-lg"></i> Financement sans frais bancaires</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-orange-500 text-lg"></i> Suivi en temps réel de l'impact</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-orange-500 text-lg"></i> Portefeuille d'investissements</li>
                    </ul>
                </div>

                <!-- Actor 3: Admin / Protocol -->
                <div class="group relative bg-slate-900 rounded-[2rem] p-8 border border-slate-800 shadow-sm hover:shadow-2xl hover:shadow-blue-900/50 hover:-translate-y-2 transition-all duration-500 overflow-hidden cursor-default">
                    <div class="absolute -top-10 -right-10 p-6 opacity-5 group-hover:opacity-20 group-hover:rotate-12 transition-all duration-700">
                        <i class="fa-brands fa-bitcoin text-[150px] text-white"></i>
                    </div>
                    <div class="h-16 w-16 bg-white/10 text-white rounded-2xl flex items-center justify-center text-2xl mb-8 shadow-inner group-hover:scale-110 group-hover:bg-white group-hover:text-slate-900 transition-all duration-500">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4">3. Le Protocole (Admin)</h3>
                    <p class="text-slate-400 leading-relaxed mb-8">
                        Les preuves soumises sont vérifiées. Une fois validées, elles sont ancrées dans la blockchain de manière immuable.
                    </p>
                    <ul class="space-y-4 text-sm font-bold text-slate-300">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-blue-400 text-lg"></i> Vérification des jalons</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-blue-400 text-lg"></i> Hachage des preuves (SHA-256)</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-blue-400 text-lg"></i> Ancrage OP_RETURN (Bitcoin)</li>
                    </ul>
                </div>
            </div>
            
            <!-- Connection Line (Visual Only) -->
            <div class="hidden lg:flex justify-center mt-16 gap-6 items-center text-slate-400 text-sm font-black uppercase tracking-widest bg-slate-50 py-4 px-8 rounded-full w-max mx-auto shadow-sm">
                <span class="text-green-600">Le Besoin</span>
                <i class="fa-solid fa-arrow-right-long animate-pulse text-slate-300"></i>
                <span class="text-orange-500">Le Financement</span>
                <i class="fa-solid fa-arrow-right-long animate-pulse text-slate-300"></i>
                <span class="text-slate-900">La Preuve Irréfutable</span>
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
