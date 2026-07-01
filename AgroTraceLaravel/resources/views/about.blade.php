<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>À Propos | AgroTrace-BTC</title>
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
<body class="bg-white text-slate-800 antialiased selection:bg-orange-500 selection:text-white flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav x-data="{ open: false }" class="fixed w-full z-50 bg-white border-b border-slate-100 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 group">
                    <i class="fa-brands fa-bitcoin text-orange-500 text-3xl group-hover:rotate-12 transition-transform"></i>
                    <span class="text-2xl font-black tracking-tight text-[#063b27]">AGRO<span class="text-orange-500">TRACE-BTC</span></span>
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
                    <a href="{{ url('/about') }}" class="text-sm font-semibold text-orange-600">À propos</a>
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
                <a href="{{ url('/about') }}" class="text-base font-semibold text-orange-600">À propos</a>
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

    <!-- Main Content -->
    <main class="flex-1 pt-32 pb-24 bg-slate-50 relative overflow-hidden">
        <!-- Decor -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-orange-100 opacity-50 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-green-100 opacity-50 blur-3xl"></div>

        <div class="max-w-3xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h1 class="text-base font-bold leading-7 text-orange-600 tracking-widest uppercase mb-4">À Propos de Nous</h1>
                <p class="text-4xl md:text-5xl font-black tracking-tight text-slate-900 mb-8">Notre Mission : Connecter la Diaspora à la Terre</p>
                <div class="h-1 w-20 bg-orange-500 mx-auto rounded-full"></div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 md:p-12 prose prose-lg prose-slate mx-auto">
                <p class="lead text-xl text-slate-700 font-medium mb-8">
                    AgroTrace-BTC est né d'un constat simple : l'agriculture africaine manque cruellement de financements, tandis que les investisseurs de la diaspora ou du monde entier hésitent à investir par manque de confiance et de traçabilité.
                </p>

                <h3 class="text-2xl font-bold text-[#063b27] mt-8 mb-4">Le Déficit de Confiance</h3>
                <p>
                    Aujourd'hui, envoyer de l'argent pour financer un champ à des milliers de kilomètres comporte un risque majeur : celui de ne jamais voir la récolte, ni de savoir si l'argent a réellement été utilisé pour acheter des semences ou des engrais. C'est ce que nous appelons le <strong>déficit de confiance</strong>.
                </p>

                <h3 class="text-2xl font-bold text-[#063b27] mt-8 mb-4">Notre Solution Technologique</h3>
                <p>
                    Notre plateforme agit comme un <b>pont de confiance incassable</b>. Nous utilisons deux technologies de pointe pour résoudre ce problème :
                </p>
                <ul class="space-y-4 mt-4 list-none pl-0">
                    <li class="flex gap-3">
                        <i class="fa-brands fa-bitcoin text-orange-500 text-xl mt-1"></i>
                        <span><strong>Le Lightning Network (Bitcoin) :</strong> Permet d'effectuer des investissements instantanés, de n'importe où dans le monde, sans les frais astronomiques des banques traditionnelles.</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fa-solid fa-link text-orange-500 text-xl mt-1"></i>
                        <span><strong>La Blockchain (Immuabilité) :</strong> Chaque preuve de travail (photo, facture) soumise par un agriculteur est cryptée et ancrée dans la blockchain. Elle ne peut plus jamais être modifiée ou falsifiée.</span>
                    </li>
                </ul>

                <h3 class="text-2xl font-bold text-[#063b27] mt-8 mb-4">Le Paiement par Jalons</h3>
                <p>
                    L'innovation majeure d'AgroTrace-BTC réside dans son système de "Jalons". L'argent de l'investisseur n'est pas donné d'un coup à l'agriculteur. L'argent est débloqué <strong>étape par étape</strong> uniquement lorsque l'agriculteur prouve que l'étape précédente a été réalisée avec succès. Le risque de fraude ou de mauvaise gestion est donc drastiquement réduit.
                </p>

                <div class="mt-12 text-center">
                    <a href="{{ url('/projects') }}" class="inline-flex items-center gap-2 bg-[#063b27] text-white px-8 py-4 rounded-full font-bold hover:bg-[#0a4b33] hover:shadow-lg hover:-translate-y-1 transition-all">
                        Explorer les Projets Agricoles <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center text-slate-400 text-sm font-medium">
            &copy; 2026 AgroTrace-BTC - Built for the Bitcoin Mastermind Hackathon.
        </div>
    </footer>

</body>
</html>
