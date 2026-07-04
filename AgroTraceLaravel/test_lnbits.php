<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://demo.lnbits.com/api/v1/payments';
$key = 'randomkey';

$response = Illuminate\Support\Facades\Http::withHeaders([
    'X-Api-Key' => $key,
    'Content-Type' => 'application/json'
])->post($url, [
    'out' => false,
    'amount' => 100,
    'memo' => 'test'
]);

echo "Status: " . $response->status() . "\n";
echo "Body: " . $response->body() . "\n";
