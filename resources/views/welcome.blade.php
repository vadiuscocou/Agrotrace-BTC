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
                    <a href="{{ url('/verification') }}" class="text-sm font-semibold text-slate-600 hover:text-[#063b27] transition">Live Proofs</a>
                    
                    <div class="h-6 w-px bg-slate-200"></div>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-[#063b27] hover:text-orange-500 transition">Go to Dashboard <i class="fa-solid fa-arrow-right ml-1"></i></a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-[#063b27] transition">Log in</a>
                        <a href="{{ route('register') }}" class="bg-[#063b27] text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-[#0a4b33] hover:shadow-lg hover:-translate-y-0.5 transition-all">Start Investing</a>
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
                <a href="{{ url('/verification') }}" class="text-base font-semibold text-slate-600 hover:text-[#063b27]">Live Proofs</a>
                <div class="h-px w-full bg-slate-100 my-2"></div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-base font-bold text-[#063b27]">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-base font-semibold text-slate-600">Log in</a>
                    <a href="{{ route('register') }}" class="bg-[#063b27] text-white px-5 py-3 rounded-xl text-center font-bold mt-2">Start Investing</a>
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
                Track Every <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Satoshi</span> <br class="hidden md:block">
                to the Green Fields.
            </h1>
            
            <p class="mt-6 text-lg md:text-xl leading-8 text-slate-600 max-w-2xl mx-auto mb-10 font-medium">
                Eliminating the "Trust Gap" in agricultural investment. We use the <b class="text-slate-900">Lightning Network</b> 
                for instant funding and <b class="text-slate-900">OP_RETURN</b> for immutable on-chain proof of impact.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-[#063b27] text-white px-8 py-4 rounded-2xl text-lg font-bold hover:bg-[#0a4b33] hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    Invest via Lightning
                </a>
                <a href="{{ url('/verification') }}" class="w-full sm:w-auto bg-white border-2 border-slate-200 text-slate-700 px-8 py-4 rounded-2xl text-lg font-bold hover:border-orange-400 hover:text-orange-600 transition-all duration-300 group">
                    View Live Proofs <i class="fa-solid fa-arrow-up-right-from-square ml-2 opacity-50 group-hover:opacity-100 transition-opacity"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Features / The "Why" -->
    <div class="bg-slate-50 py-24 sm:py-32 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center mb-16">
                <h2 class="text-base font-bold leading-7 text-orange-500 tracking-widest uppercase">The Paradigm Shift</h2>
                <p class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">How AgroTrace changes everything</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">Instant Micropayments</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Diaspora investors can fund agricultural inputs from anywhere in the world instantly using the Lightning Network. Zero banking friction.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-link"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">Immutable Proofs</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Every milestone achieved (e.g. seeds planted) is hashed and anchored to the Bitcoin base chain via OP_RETURN. Absolute transparency.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-[#063b27]">ESG Monetization</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Verified impacts generate On-Chain Certificates that can be sold to international ESG funds, creating a sustainable revenue loop.
                    </p>
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
