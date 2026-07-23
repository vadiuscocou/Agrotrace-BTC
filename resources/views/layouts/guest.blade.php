<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AgroTrace-BTC') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased selection:bg-orange-500 selection:text-white relative bg-slate-50">

    <!-- Background Decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] rounded-full bg-gradient-to-b from-green-100/50 to-transparent blur-3xl"></div>
        <div class="absolute bottom-[10%] -left-[10%] w-[50%] h-[50%] rounded-full bg-gradient-to-t from-orange-50/50 to-transparent blur-3xl"></div>
    </div>

    <div class="min-h-[100vh] flex flex-col justify-center items-center py-6 relative z-10">
        
        <!-- Logo -->
        <div class="mb-8">
            <a href="/" class="flex flex-col items-center group">
                <div class="h-16 w-16 bg-[#063b27] rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300 mb-3">
                    <i class="fa-brands fa-bitcoin text-orange-400 text-4xl"></i>
                </div>
                <span class="text-2xl font-black tracking-tight text-[#063b27]">AGRO<span class="text-orange-500">TRACE-BTC</span></span>
            </a>
        </div>

        <!-- Form Container -->
        <div class="w-full sm:max-w-md mt-6 px-10 py-8 bg-white/80 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-[2rem] border border-white/50">
            {{ $slot }}
        </div>
        
        <!-- Footer Link -->
        <div class="mt-8 text-sm text-slate-500 font-medium">
            <a href="{{ url('/') }}" class="hover:text-[#063b27] transition"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Homepage</a>
        </div>
    </div>
    
</body>
</html>
