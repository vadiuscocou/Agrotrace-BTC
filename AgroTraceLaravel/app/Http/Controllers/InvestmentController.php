<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LNbitsService;

class InvestmentController extends Controller
{
    public function store(Request $request, $project_id)
    {
        $amountFcfa = (int) $request->input('amount_fcfa', 50000);

        $amountSats  = $amountFcfa * 6;
        $feeSats     = (int) ($amountSats * 0.02);
        $totalToPay  = $amountSats + $feeSats;

        try {
            $lnbits = new LNbitsService();
            $invoice = $lnbits->createInvoice($totalToPay, "AgroTrace Inv. Projet #{$project_id}");

            $investment = Investment::create([
                'project_id'      => $project_id,
                'user_id'         => Auth::id(),
                'amount_fcfa'     => $amountFcfa,
                'amount_sats'     => $amountSats,
                'fee_sats'        => $feeSats,
                'payment_request' => $invoice['payment_request'],
                'payment_hash'    => $invoice['payment_hash'],
                'status'          => 'pending',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'redirect' => url('/invest/' . $investment->id . '/pay'),
                ]);
            }

            return redirect()->to('/invest/' . $investment->id . '/pay');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Erreur API LNbits: ' . $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Erreur API LNbits: ' . $e->getMessage());
        }
    }

    public function pay(Investment $investment)
    {
        return view('invest.pay', compact('investment'));
    }
}
