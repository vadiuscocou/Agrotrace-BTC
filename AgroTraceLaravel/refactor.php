<?php

function refactorView($file) {
    $content = file_get_contents($file);
    
    // Remove head, html, body
    $content = preg_replace('/<!DOCTYPE html>.*<body[^>]*>/is', '', $content);
    $content = str_replace('</body>', '', $content);
    $content = str_replace('</html>', '', $content);
    
    // Remove sidebar from dashboard if exists
    $content = preg_replace('/<!-- SIDEBAR -->.*?<\/aside>/is', '', $content);
    
    // Remove <main class="flex-grow"> wrapping from dashboard if exists
    $content = preg_replace('/<!-- MAIN CONTENT -->\s*<main[^>]*>/is', '', $content);
    $content = preg_replace('/<\/main>\s*$/is', '', $content);
    
    $newContent = "@extends('layouts.app')\n\n@section('title', 'AgroTrace BTC')\n\n@section('content')\n" . trim($content) . "\n@endsection\n";
    
    file_put_contents($file, $newContent);
}

$views = [
    __DIR__ . '/resources/views/welcome.blade.php',
    __DIR__ . '/resources/views/dashboard.blade.php',
    __DIR__ . '/resources/views/projects.blade.php',
    __DIR__ . '/resources/views/verification.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        refactorView($view);
        echo "Refactored: " . basename($view) . "\n";
    }
}
