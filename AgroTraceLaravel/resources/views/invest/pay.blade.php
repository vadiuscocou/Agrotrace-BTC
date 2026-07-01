@extends('layouts.app')
@section('title', 'Payer l\'investissement')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden" x-data="paymentPoller('{{ $investment->payment_hash }}', {{ $investment->id }})">
        <div class="bg-slate-900 px-6 py-8 text-center text-white">
            <i class="fa-brands fa-bitcoin text-5xl text-orange-500 mb-4"></i>
            <h2 class="text-2xl font-black">Finaliser l'investissement</h2>
            <p class="text-slate-400 mt-2 text-sm">Scannez ce QR Code avec votre portefeuille Lightning (ex: Wallet of Satoshi, Strike).</p>
        </div>
        
        <div class="p-8 text-center">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 inline-block mb-6 relative">
                <!-- QR Code via API QRServer -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=lightning:{{ strtolower($investment->payment_request) }}" alt="Lightning Invoice" class="mx-auto rounded-lg">
                
                <!-- Loading overlay when paid -->
                <div x-show="paid" x-transition style="display: none;" class="absolute inset-0 bg-white/90 backdrop-blur-sm flex items-center justify-center rounded-2xl">
                    <div class="text-green-500">
                        <i class="fa-solid fa-circle-check text-6xl mb-2"></i>
                        <p class="font-bold">Paiement Reçu !</p>
                    </div>
                </div>
            </div>

            <div class="space-y-3 mb-8 text-left bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Montant :</span>
                    <span class="font-bold text-slate-800">{{ number_format($investment->amount_fcfa) }} FCFA</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Équivalent :</span>
                    <span class="font-bold text-orange-500">{{ number_format($investment->amount_sats) }} SATS</span>
                </div>
                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <span class="text-sm font-bold text-slate-500">Frais de réseau & Plateforme (2%)</span>
                    <span class="font-bold text-slate-800">{{ number_format($investment->fee_sats) }} SATS</span>
                </div>
            </div>

            <div class="flex items-center justify-center gap-2 text-sm text-slate-500 font-medium" x-show="!paid">
                <i class="fa-solid fa-spinner fa-spin text-orange-500"></i> En attente du paiement...
            </div>
            
            <div class="mt-6">
                <input type="text" readonly value="{{ $investment->payment_request }}" class="w-full text-xs text-slate-400 bg-white border border-slate-200 rounded-lg p-3 text-center cursor-pointer hover:bg-slate-50 transition" @click="$el.select(); document.execCommand('copy'); alert('Facture copiée dans le presse-papiers !')">
                <p class="text-[10px] text-slate-400 mt-2 uppercase tracking-widest">Cliquez pour copier la facture</p>
            </div>
            
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-400 hover:text-slate-600">Annuler et retourner au tableau de bord</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('paymentPoller', (hash, id) => ({
            paid: false,
            init() {
                const interval = setInterval(async () => {
                    if (this.paid) {
                        clearInterval(interval);
                        return;
                    }
                    
                    try {
                        const response = await fetch(`/invest/${hash}/status`);
                        const data = await response.json();
                        
                        if (data.paid) {
                            this.paid = true;
                            clearInterval(interval);
                            
                            // Redirect after 2 seconds
                            setTimeout(() => {
                                window.open(`/investments/${id}/invoice?auto_print=1`, '_blank');
                                window.location.href = '/invoices';
                            }, 2500);
                        }
                    } catch (e) {
                        console.error('Polling error', e);
                    }
                }, 3000);
            }
        }));
    });
</script>
@endsection
