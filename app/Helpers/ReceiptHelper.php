<?php

namespace App\Helpers;

use Carbon\Carbon;

function session_progress(string $session): int
{
    [$startYear, $endYear] = explode('-', $session);

    $start = Carbon::create($startYear, 4, 1);
    $end   = Carbon::create($endYear, 3, 31);
    $now   = Carbon::now();

    if ($now->lt($start)) return 0;
    if ($now->gt($end)) return 100;

    $totalDays = $start->diffInDays($end);
    $passedDays = $start->diffInDays($now);

    return (int) round(($passedDays / $totalDays) * 100);
}

