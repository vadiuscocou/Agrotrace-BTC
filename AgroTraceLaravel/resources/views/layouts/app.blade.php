<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AgroTrace-BTC | Dashboard')</title>
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Ccircle cx='16' cy='16' r='16' fill='%23f7931a'/%3E%3Cpath fill='%23fff' d='M21.78 15.37c.41-2.75-1.68-4.23-4.52-5.2l.92-3.72-2.27-.56-.9 3.63c-.59-.15-1.2-.28-1.82-.41l.91-3.66-2.27-.56-.92 3.7c-.5-.11-.98-.23-1.45-.35l.01-.04-3.13-.78-.6 2.42s1.68.38 1.64.41c.92.23 1.08.83 1.05 1.31l-1.05 4.23c.06.01.14.04.22.09-.07-.02-.15-.04-.23-.06l-1.47 5.92c-.1.25-.33.62-.85.49.03.04-1.65-.41-1.65-.41l-1.12 2.58 2.96.74c.55.14 1.1.28 1.63.42l-.93 3.75 2.27.56.91-3.67c.61.16 1.2.3 1.78.43l-.91 3.68 2.27.56.93-3.73c3.83.72 6.72.43 7.95-3.03.99-2.78-.05-4.38-2.02-5.42 1.44-.33 2.53-1.29 2.76-3.26zm-3.83 6.83c-.69 2.78-5.38 1.28-6.9 1.9l1.22-4.91c1.51.37 6.4 1.15 5.68 3.01zm.48-5.38c-.63 2.53-4.54 1.2-5.8 1.51l1.11-4.47c1.26.31 5.34.87 4.69 2.96z'/%3E%3C/svg%3E">
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
<body class="h-full overflow-hidden flex bg-slate-50 text-slate-800 antialiased selection:bg-orange-500 selection:text-white" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 lg:hidden" style="z-index: 1900;"
         @click="mobileMenuOpen = false" 
         style="display: none;"></div>

    <!-- Sidebar (Retractable on Desktop, Fixed on Mobile) -->
    <aside :class="[
            mobileMenuOpen ? 'translate-x-0' : '-translate-x-full',
            'lg:translate-x-0',
            sidebarOpen ? 'lg:w-72' : 'lg:w-20'
        ]" 
        class="fixed lg:relative inset-y-0 left-0 bg-[#063b27] text-white flex flex-col transition-all duration-300 ease-in-out flex-shrink-0 shadow-2xl w-72" style="z-index: 9999;">
        
        <!-- Toggle Button (Desktop Only) -->
        <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex absolute -right-4 top-8 bg-orange-500 text-white rounded-full w-8 h-8 items-center justify-center shadow-lg hover:bg-orange-600 transition-transform" style="z-index: 10000;">
            <i class="fa-solid fa-chevron-left transition-transform duration-300" :class="!sidebarOpen ? 'rotate-180' : ''"></i>
        </button>

        <!-- Logo Area -->
        <div class="h-20 flex items-center px-6 border-b border-white/10 overflow-hidden shrink-0">
            <a href="{{ url('/') }}" class="text-2xl font-black tracking-tight text-white flex items-center gap-3 hover:opacity-80 transition min-w-max">
                <i class="fa-brands fa-bitcoin text-orange-400 text-3xl"></i>
                <span x-show="sidebarOpen" class="transition-opacity duration-300 delay-100">AGRO<span class="text-orange-400">TRACE-BTC</span></span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 py-8 space-y-2 overflow-y-auto overflow-x-hidden">
            <p x-show="sidebarOpen" class="px-6 text-xs font-bold text-white/40 uppercase tracking-wider mb-4 transition-opacity duration-300 delay-100 min-w-max">Menu</p>
            
            <a href="{{ url('/dashboard') }}" class="flex items-center gap-4 px-6 py-3 font-medium transition {{ request()->is('dashboard') ? 'bg-white/10 text-white shadow-inner border-l-4 border-orange-500' : 'text-white/70 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }}" title="Tableau de bord">
                <i class="fa-solid fa-chart-pie text-xl w-6 text-center"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity duration-300 delay-100">Tableau de bord</span>
            </a>
            
            <a href="{{ url('/projects') }}" class="flex items-center gap-4 px-6 py-3 font-medium transition {{ request()->is('projects') ? 'bg-white/10 text-white shadow-inner border-l-4 border-orange-500' : 'text-white/70 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }}" title="Explorer les Projets">
                <i class="fa-solid fa-seedling text-xl w-6 text-center"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity duration-300 delay-100">Explorer les Projets</span>
            </a>
            
            @if(Auth::user()->role === 'investor' || Auth::user()->role === 'admin')
            <a href="{{ route('invoices.index') }}" class="flex items-center gap-4 px-6 py-3 font-medium transition {{ request()->routeIs('invoices.index') ? 'bg-white/10 text-white shadow-inner border-l-4 border-orange-500' : 'text-white/70 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }}" title="Mes Factures">
                <i class="fa-solid fa-file-invoice text-xl w-6 text-center"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity duration-300 delay-100">Mes Factures</span>
            </a>
            @endif

            <a href="{{ url('/impact-map') }}" class="flex items-center gap-4 px-6 py-3 font-medium transition {{ request()->is('impact-map') ? 'bg-white/10 text-white shadow-inner border-l-4 border-orange-500' : 'text-white/70 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }}" title="Impact Map">
                <i class="fa-solid fa-map-location-dot text-xl w-6 text-center"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity duration-300 delay-100">Impact Map</span>
            </a>
            
            <a href="{{ url('/verification') }}" class="flex items-center gap-4 px-6 py-3 font-medium transition {{ request()->is('verification') ? 'bg-white/10 text-white shadow-inner border-l-4 border-orange-500' : 'text-white/70 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }}" title="Preuves en Direct">
                <i class="fa-solid fa-shield-check text-xl w-6 text-center"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity duration-300 delay-100">Preuves en Direct</span>
            </a>
        </nav>

        <!-- User Profile Area -->
        @auth
        <div class="p-4 border-t border-white/10 bg-black/10 overflow-hidden shrink-0">
            <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 transition w-full">
                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-orange-500 to-amber-400 flex items-center justify-center text-white font-bold shadow-lg shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div x-show="sidebarOpen" class="flex-1 min-w-0 transition-opacity duration-300 delay-100">
                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-orange-400 font-medium">
                        @if(Auth::user()->role === 'investor')
                            Investisseur
                        @elseif(Auth::user()->role === 'project_owner')
                            Coopérative
                        @elseif(Auth::user()->role === 'admin')
                            Administrateur
                        @else
                            {{ ucfirst(Auth::user()->role) }}
                        @endif
                    </p>
                </div>
                <div class="ml-auto flex items-center shrink-0" x-show="sidebarOpen">
                    <a href="{{ route('profile.edit') }}" class="text-white/50 hover:text-white transition px-2" title="Mon Profil">
                        <i class="fa-solid fa-user-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white/50 hover:text-white transition px-2" title="Déconnexion">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Mobile/Collapsed Logout -->
            <form method="POST" action="{{ route('logout') }}" class="mt-4 flex justify-center" x-show="!sidebarOpen" style="display: none;">
                @csrf
                <button type="submit" class="text-white/50 hover:text-white transition" title="Déconnexion">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xl"></i>
                </button>
            </form>
        </div>
        @endauth
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50 relative min-w-0">
        
        <!-- Mobile Header -->
        <header class="lg:hidden h-16 bg-[#063b27] text-white flex items-center justify-between px-4 shadow-md shrink-0" style="z-index: 1950;">
            <a href="{{ url('/') }}" class="text-xl font-black tracking-tight flex items-center gap-2">
                <i class="fa-brands fa-bitcoin text-orange-400"></i> AGRO<span class="text-orange-400">TRACE-BTC</span>
            </a>
            <button @click="mobileMenuOpen = true" class="text-white/80 hover:text-white text-2xl focus:outline-none">
                <i class="fa-solid fa-bars"></i>
            </button>
        </header>

        <!-- Page Header (Optional Slot) -->
        @isset($header)
            <header class="bg-white border-b border-slate-200 shadow-sm z-10 shrink-0">
                <div class="px-8 py-6">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Scrollable Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <div class="w-full max-w-7xl mx-auto">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
        
    </div>

</body>
</html>
