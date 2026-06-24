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
<body class="h-full overflow-hidden flex bg-slate-50 text-slate-800 antialiased selection:bg-orange-500 selection:text-white" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-40 md:hidden" 
         @click="sidebarOpen = false" 
         style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:relative inset-y-0 left-0 w-72 bg-[#063b27] text-white flex flex-col transition-transform duration-300 ease-in-out z-50 md:translate-x-0 flex-shrink-0 shadow-2xl">
        
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-8 border-b border-white/10">
            <a href="{{ url('/') }}" class="text-2xl font-black tracking-tight text-white flex items-center gap-2 hover:opacity-80 transition">
                <i class="fa-brands fa-bitcoin text-orange-400 text-3xl"></i>
                <span>AGRO<span class="text-orange-400">TRACE</span></span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
            <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-wider mb-4">Menu</p>
            
            <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->is('dashboard') ? 'bg-white/10 text-white shadow-inner' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                Dashboard
            </a>
            
            <a href="{{ url('/projects') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->is('projects') ? 'bg-white/10 text-white shadow-inner' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-seedling w-5 text-center"></i>
                Explorer Projects
            </a>
            
            <a href="{{ url('/verification') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->is('verification') ? 'bg-white/10 text-white shadow-inner' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-shield-check w-5 text-center"></i>
                Live Proofs
            </a>
        </nav>

        <!-- User Profile Area -->
        @auth
        <div class="p-4 border-t border-white/10 bg-black/10">
            <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition cursor-pointer group">
                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-orange-500 to-amber-400 flex items-center justify-center text-white font-bold shadow-lg group-hover:scale-105 transition">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-orange-400 font-medium">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                    @csrf
                    <button type="submit" class="text-white/50 hover:text-white transition" title="Log out">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50 relative">
        
        <!-- Mobile Header -->
        <header class="md:hidden h-16 bg-[#063b27] text-white flex items-center justify-between px-4 shadow-md z-30">
            <a href="{{ url('/') }}" class="text-xl font-black tracking-tight flex items-center gap-2">
                <i class="fa-brands fa-bitcoin text-orange-400"></i> AGRO<span class="text-orange-400">TRACE</span>
            </a>
            <button @click="sidebarOpen = true" class="text-white/80 hover:text-white text-2xl">
                <i class="fa-solid fa-bars"></i>
            </button>
        </header>

        <!-- Page Header (Optional Slot) -->
        @isset($header)
            <header class="bg-white border-b border-slate-200 shadow-sm z-10">
                <div class="px-8 py-6">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Scrollable Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <div class="mx-auto w-full max-w-7xl">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
        
    </div>

</body>
</html>
