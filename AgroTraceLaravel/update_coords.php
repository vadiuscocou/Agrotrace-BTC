<?php
App\Models\Project::whereNull('latitude')->get()->each(function($p) {
    $p->update([
        'latitude' => round(6.5 + (mt_rand() / mt_getrandmax()) * 5.0, 6), 
        'longitude' => round(1.5 + (mt_rand() / mt_getrandmax()) * 2.0, 6)
    ]);
});
echo "OK\n";
