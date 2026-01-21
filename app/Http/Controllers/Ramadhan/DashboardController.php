<?php

namespace App\Http\Controllers\Ramadhan;

use App\Http\Controllers\Controller;
use App\Models\DaySetting;
use App\Models\Jamaah;
use App\Models\Takjil;
use App\Models\TakjilMakanan;
use App\Models\TakjilMinuman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     // Ringkasan Umum
    //     $totalJamaah = Jamaah::count();
    //     $totalSetoran = Jamaah::sum('setoran');

    //     // Statistik Ekonomi
    //     $ekonomiStats = Jamaah::select('ekonomi', DB::raw('count(*) as total'))
    //         ->groupBy('ekonomi')
    //         ->get();

    //     $jamaahMampu = Jamaah::where('ekonomi', 'Mampu')->count();
    //     $jamaahKurangMampu = Jamaah::where('ekonomi', 'Kurang Mampu')->count();
    //     $setoranMampu = Jamaah::where('ekonomi', 'Mampu')->sum('setoran');
    //     $setoranKurangMampu = Jamaah::where('ekonomi', 'Kurang Mampu')->sum('setoran');

    //     // Takjil Hari Ini
    //     $hariIni = Carbon::today()->toDateString();
    //     $takjilHariIni = Takjil::whereHas('daySetting', function ($q) use ($hariIni) {
    //         $q->where('date', $hariIni);
    //     })->count();

    //     $daySettingHariIni = DaySetting::where('date', $hariIni)->first();
    //     $quotaHariIni = $daySettingHariIni ? $daySettingHariIni->quota : 0;
    //     $quotaTersedia = $daySettingHariIni ? $quotaHariIni - $takjilHariIni : 0;

    //     // Makanan/Minuman Populer
    //     $makananPopuler = TakjilMakanan::select('makanan_id', DB::raw('count(*) as total'))
    //         ->with('makanan')
    //         ->groupBy('makanan_id')
    //         ->orderByDesc('total')
    //         ->limit(5)
    //         ->get();

    //     $minumanPopuler = TakjilMinuman::select('minuman_id', DB::raw('count(*) as total'))
    //         ->with('minuman')
    //         ->groupBy('minuman_id')
    //         ->orderByDesc('total')
    //         ->limit(5)
    //         ->get();

    //     // Trend 7 Hari Terakhir
    //     $startDate = Carbon::today()->subDays(6)->toDateString();
    //     $endDate = Carbon::today()->toDateString();

    //     $trendHarian = Takjil::select(
    //         DB::raw('DATE(day_settings.date) as tanggal'),
    //         DB::raw('count(takjils.id) as total_takjil')
    //     )
    //         ->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
    //         ->whereBetween('day_settings.date', [$startDate, $endDate])
    //         ->groupBy('tanggal')
    //         ->orderBy('tanggal')
    //         ->get();

    //     return view('ramadhan.Dashboard.index', compact(
    //         'totalJamaah',
    //         'totalSetoran',
    //         'ekonomiStats',
    //         'jamaahMampu',
    //         'jamaahKurangMampu',
    //         'setoranMampu',
    //         'setoranKurangMampu',
    //         'takjilHariIni',
    //         'quotaHariIni',
    //         'quotaTersedia',
    //         'makananPopuler',
    //         'minumanPopuler',
    //         'trendHarian'
    //     ));
    // }

    public function index()
    {
        /* =====================
         * Ringkasan Umum
         * ===================== */
        $totalJamaah   = Jamaah::count();
        $totalSetoran = Jamaah::sum('setoran') ?? 0;

        /* =====================
         * Statistik Ekonomi
         * ===================== */
        $ekonomiStats = Jamaah::select('ekonomi', DB::raw('COUNT(*) as total'))
            ->whereNotNull('ekonomi')
            ->groupBy('ekonomi')
            ->get();

        $jamaahMampu        = Jamaah::where('ekonomi', 'Mampu')->count();
        $jamaahKurangMampu  = Jamaah::where('ekonomi', 'Kurang Mampu')->count();

        $setoranMampu       = Jamaah::where('ekonomi', 'Mampu')->sum('setoran') ?? 0;
        $setoranKurangMampu = Jamaah::where('ekonomi', 'Kurang Mampu')->sum('setoran') ?? 0;

        /* =====================
         * Takjil Hari Ini
         * ===================== */
        $hariIni = Carbon::today()->toDateString();

        $daySettingHariIni = DaySetting::whereDate('date', $hariIni)->first();

        $takjilHariIni = $daySettingHariIni
            ? Takjil::where('day_setting_id', $daySettingHariIni->id)->count()
            : 0;

        $quotaHariIni = $daySettingHariIni->quota ?? 0;

        // Pastikan tidak minus
        $quotaTersedia = max($quotaHariIni - $takjilHariIni, 0);

        /* =====================
         * Makanan / Minuman Populer
         * ===================== */
        $makananPopuler = TakjilMakanan::select('makanan_id', DB::raw('COUNT(*) as total'))
            ->with('makanan')
            ->groupBy('makanan_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $minumanPopuler = TakjilMinuman::select('minuman_id', DB::raw('COUNT(*) as total'))
            ->with('minuman')
            ->groupBy('minuman_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        /* =====================
         * Trend 7 Hari Terakhir
         * ===================== */
        $startDate = Carbon::today()->subDays(6)->toDateString();
        $endDate   = Carbon::today()->toDateString();

        $trendHarian = Takjil::select(
            DB::raw('DATE(day_settings.date) as tanggal'),
            DB::raw('COUNT(takjils.id) as total_takjil')
        )
            ->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
            ->whereBetween('day_settings.date', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        /* =====================
         * 🔒 FIX UTAMA: Anti Division by Zero
         * ===================== */
        $takjilHariIniSafe = max($takjilHariIni, 1);

        return view('ramadhan.Dashboard.index', compact(
            'totalJamaah',
            'totalSetoran',
            'ekonomiStats',
            'jamaahMampu',
            'jamaahKurangMampu',
            'setoranMampu',
            'setoranKurangMampu',
            'takjilHariIni',
            'takjilHariIniSafe', // ⬅ penting
            'quotaHariIni',
            'quotaTersedia',
            'makananPopuler',
            'minumanPopuler',
            'trendHarian'
        ));
    }
}
