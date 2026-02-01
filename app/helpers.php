<?php

use App\Models\Setting;
use App\Models\AcademicSession;
use Carbon\Carbon;

function app_setting($key, $default = null)
{
    $setting = Setting::first();
    return $setting->$key ?? $default;
}

function active_session_name(): ?string
{
    return AcademicSession::where('is_active', true)->value('name');
}
function isQuarterlyMonth(string $month): bool
{
    $m = (int) date('n', strtotime($month));
    return in_array($m, [7, 10, 1, 3]);
}

/**
 * Calculate academic session progress in %
 * Example session: 2025-26
 */
function session_progress(?string $session): int
{
    // ✅ Fresh DB / no session case
    if (!$session || !str_contains($session, '-')) {
        return 0;
    }

    [$startYear, $endShort] = explode('-', $session);

    // Safety check
    if (!is_numeric($startYear) || !is_numeric($endShort)) {
        return 0;
    }

    $endYear = strlen($endShort) === 2
        ? (int) ('20' . $endShort)
        : (int) $endShort;

    $start = Carbon::create((int) $startYear, 4, 1);   // 1 April
    $end   = Carbon::create($endYear, 3, 31);          // 31 March
    $now   = Carbon::now();

    if ($now->lt($start)) return 0;
    if ($now->gt($end)) return 100;

    $totalDays  = $start->diffInDays($end);
    $passedDays = $start->diffInDays($now);

    return $totalDays > 0
        ? (int) round(($passedDays / $totalDays) * 100)
        : 0;
}
