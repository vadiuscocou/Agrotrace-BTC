@extends('layouts.app')
@section('title', 'Rembourser la Tranche')
@section('content')
<div class="min-h-[calc(100vh-120px)] flex flex-col items-center justify-center py-4 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden" x-data="paymentPoller('{{ $repayment->payment_hash }}', {{ $repayment->id }})">
        <div class="bg-slate-900 px-6 py-8 text-center text-white">
            <i class="fa-brands fa-bitcoin text-5xl text-orange-500 mb-4"></i>
            <h2 class="text-2xl font-black">Rembourser la Tranche</h2>
            <p class="text-slate-400 mt-2 text-sm">Scannez ce QR Code avec votre portefeuille Lightning pour effectuer le remboursement.</p>
        </div>
        
        <div class="p-8 text-center">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 inline-block mb-6 relative">
                <!-- QR Code rendered via local JS -->
                <div id="qrcode" class="mx-auto flex justify-center p-2"></div>
                <script>
                    new QRCode(document.getElementById("qrcode"), {
                        text: "lightning:{{ $repayment->payment_request }}",
                        width: 250,
                        height: 250,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.L
                    });
                </script>
                
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
                    <span class="font-bold text-slate-800">{{ number_format($repayment->amount_fcfa) }} FCFA</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Équivalent :</span>
                    <span class="font-bold text-orange-500">{{ number_format($repayment->amount_sats) }} SATS</span>
                </div>
            </div>

            <div class="flex items-center justify-center gap-2 text-sm text-slate-500 font-medium" x-show="!paid">
                <i class="fa-solid fa-spinner fa-spin text-orange-500"></i> En attente du paiement...
            </div>
            
            <div class="mt-6">
                <input type="text" readonly value="{{ $repayment->payment_request }}" class="w-full text-xs text-slate-400 bg-white border border-slate-200 rounded-lg p-3 text-center cursor-pointer hover:bg-slate-50 transition" @click="$el.select(); document.execCommand('copy'); alert('Facture copiée dans le presse-papiers !')">
            </div>
            
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-400 hover:text-slate-600">Retour au tableau de bord</a>
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
                    if (this.paid) return clearInterval(interval);
                    try {
                        const response = await fetch(`/repayments/${hash}/status`);
                        const data = await response.json();
                        if (data.paid) {
                            this.paid = true;
                            clearInterval(interval);
                            setTimeout(() => window.location.href = '/dashboard', 2500);
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
