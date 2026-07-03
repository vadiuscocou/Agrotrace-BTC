@extends('layouts.app')
@section('title', 'Retirer mes gains')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="bg-slate-900 px-6 py-8 text-center text-white">
            <i class="fa-solid fa-bolt text-5xl text-yellow-400 mb-4 drop-shadow-[0_0_15px_rgba(250,204,21,0.5)]"></i>
            <h2 class="text-2xl font-black">Retour sur Investissement</h2>
            <p class="text-slate-400 mt-2 text-sm">Scannez ce QR Code avec votre portefeuille Lightning pour retirer vos gains instantanément.</p>
        </div>
        
        <div class="p-8 text-center">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 inline-block mb-6">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode('lightning:' . $lnurl) }}" alt="LNURL Withdraw" class="mx-auto rounded-lg">
            </div>

            <div class="space-y-3 mb-8 text-left bg-green-50 p-4 rounded-xl border border-green-100">
                <div class="flex justify-between items-center">
                    <span class="text-green-700 font-bold">Montant retiré :</span>
                    <span class="font-black text-green-600 text-xl">{{ number_format($amount_sats) }} SATS</span>
                </div>
            </div>
            
            <div class="mt-6">
                <input type="text" readonly value="{{ $lnurl }}" class="w-full text-xs text-slate-400 bg-white border border-slate-200 rounded-lg p-3 text-center cursor-pointer hover:bg-slate-50 transition" @click="$el.select(); document.execCommand('copy'); alert('Lien LNURL copié !')">
            </div>
            
            <div class="mt-8 pt-4 border-t border-slate-100">
                <a href="{{ url('/dashboard') }}" class="w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-4 py-3 bg-white text-base font-bold text-slate-700 hover:bg-slate-50 transition">Retour au tableau de bord</a>
            </div>
        </div>
    </div>
</div>
@endsection
