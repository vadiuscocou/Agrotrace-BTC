<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AgroTrace BTC | Dashboard')</title>
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
         class="fixed inset-0 bg-black/50 z-40 lg:hidden" 
         @click="mobileMenuOpen = false" 
         style="display: none;"></div>

    <!-- Sidebar (Retractable on Desktop, Fixed on Mobile) -->
    <aside :class="[
            mobileMenuOpen ? 'translate-x-0' : '-translate-x-full',
            'lg:translate-x-0',
            sidebarOpen ? 'lg:w-72' : 'lg:w-20'
        ]" 
        class="fixed lg:relative inset-y-0 left-0 bg-[#063b27] text-white flex flex-col transition-all duration-300 ease-in-out z-50 flex-shrink-0 shadow-2xl w-72">
        
        <!-- Toggle Button (Desktop Only) -->
        <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex absolute -right-4 top-8 bg-orange-500 text-white rounded-full w-8 h-8 items-center justify-center shadow-lg hover:bg-orange-600 transition-transform z-50">
            <i class="fa-solid fa-chevron-left transition-transform duration-300" :class="!sidebarOpen ? 'rotate-180' : ''"></i>
        </button>

        <!-- Logo Area -->
        <div class="h-20 flex items-center px-6 border-b border-white/10 overflow-hidden shrink-0">
            <a href="{{ url('/') }}" class="text-2xl font-black tracking-tight text-white flex items-center gap-3 hover:opacity-80 transition min-w-max">
                <i class="fa-brands fa-bitcoin text-orange-400 text-3xl"></i>
                <span x-show="sidebarOpen" class="transition-opacity duration-300 delay-100">AGRO<span class="text-orange-400">TRACE</span></span>
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
            
            <a href="{{ url('/verification') }}" class="flex items-center gap-4 px-6 py-3 font-medium transition {{ request()->is('verification') ? 'bg-white/10 text-white shadow-inner border-l-4 border-orange-500' : 'text-white/70 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }}" title="Preuves en Direct">
                <i class="fa-solid fa-shield-check text-xl w-6 text-center"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity duration-300 delay-100">Preuves en Direct</span>
            </a>
        </nav>

        <!-- User Profile Area -->
        @auth
        <div class="p-4 border-t border-white/10 bg-black/10 overflow-hidden shrink-0">
            <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 transition min-w-max">
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
                <form method="POST" action="{{ route('logout') }}" class="ml-auto" x-show="sidebarOpen">
                    @csrf
                    <button type="submit" class="text-white/50 hover:text-white transition px-2" title="Déconnexion">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
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
        <header class="lg:hidden h-16 bg-[#063b27] text-white flex items-center justify-between px-4 shadow-md z-30 shrink-0">
            <a href="{{ url('/') }}" class="text-xl font-black tracking-tight flex items-center gap-2">
                <i class="fa-brands fa-bitcoin text-orange-400"></i> AGRO<span class="text-orange-400">TRACE</span>
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
