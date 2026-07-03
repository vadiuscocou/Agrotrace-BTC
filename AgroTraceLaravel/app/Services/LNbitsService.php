<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class LNbitsService
{
    private $url;
    private $invoiceReadKey;
    private $adminKey;

    public function __construct()
    {
        $this->url = rtrim(env('LNBITS_URL', 'https://legend.lnbits.com'), '/');
        $this->invoiceReadKey = env('LNBITS_INVOICE_READ_KEY');
        $this->adminKey = env('LNBITS_ADMIN_KEY');
    }

    /**
     * Create a Lightning invoice.
     * Returns array with 'payment_request' and 'payment_hash'.
     */
    public function createInvoice($amountSats, $memo = 'AgroTrace Investment', $webhook = '')
    {
        if (!$this->invoiceReadKey) {
            throw new Exception("LNBITS_INVOICE_READ_KEY is not set in .env.");
        }

        $amountSats = (int) $amountSats;

        $response = Http::withHeaders([
            'X-Api-Key' => $this->invoiceReadKey,
            'Content-Type' => 'application/json'
        ])->post($this->url . '/api/v1/payments', [
            'out' => false,
            'amount' => $amountSats,
            'memo' => $memo,
            'webhook' => $webhook
        ]);

        if (!$response->successful()) {
            throw new Exception("Failed to create LNbits invoice ({$response->status()}): " . $response->body());
        }

        $data = $response->json();
        if (empty($data['payment_request']) || empty($data['payment_hash'])) {
            throw new Exception("LNbits response missing payment_request/payment_hash: " . $response->body());
        }

        return $data;
    }

    /**
     * Check if an invoice has been paid.
     */
    public function checkPaymentStatus($paymentHash)
    {
        if (!$this->invoiceReadKey) {
            return false;
        }

        $response = Http::withHeaders([
            'X-Api-Key' => $this->invoiceReadKey
        ])->get($this->url . '/api/v1/payments/' . $paymentHash);

        if ($response->successful()) {
            return $response->json('paid') === true;
        }

        return false;
    }

    /**
     * Pay a BOLT11 invoice.
     */
    public function payInvoice($bolt11)
    {
        if (!$this->adminKey) {
            throw new Exception("LNBITS_ADMIN_KEY is not set in .env. Cannot pay out.");
        }

        $response = Http::withHeaders([
            'X-Api-Key' => $this->adminKey,
            'Content-Type' => 'application/json'
        ])->post($this->url . '/api/v1/payments', [
            'out' => true,
            'bolt11' => $bolt11
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new Exception("Failed to pay LNbits invoice: " . $response->body());
    }
}
