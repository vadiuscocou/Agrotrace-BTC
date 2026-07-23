<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Identité Numérique (DID) -->
            <div class="p-6 sm:p-8 bg-gradient-to-r from-slate-900 to-[#063b27] shadow-md sm:rounded-2xl flex items-center justify-between text-white relative overflow-hidden border border-slate-800">
                <div class="absolute -right-4 -top-10 opacity-10">
                    <i class="fa-brands fa-bitcoin text-9xl"></i>
                </div>
                <div class="relative z-10">
                    <h3 class="text-xs uppercase tracking-widest text-slate-300 font-bold mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-fingerprint text-orange-500"></i> Identité Numérique AgroTrace
                    </h3>
                    <div class="font-mono text-sm sm:text-base text-green-400 font-bold mt-3 bg-black/30 px-4 py-2 rounded-xl inline-block border border-white/10 shadow-inner">
                        did:agro:btc:{{ substr(hash('sha256', Auth::user()->email . Auth::user()->created_at), 0, 16) }}
                    </div>
                </div>
                <div class="relative z-10 shrink-0 ml-4 hidden sm:block">
                    <!-- Petit QR Code élégant -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=did:agro:btc:{{ substr(hash('sha256', Auth::user()->email . Auth::user()->created_at), 0, 16) }}&color=fff&bgcolor=063b27" alt="DID QR" class="rounded-lg border border-white/20 p-1 bg-white/5 backdrop-blur-sm">
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-2xl border border-slate-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
