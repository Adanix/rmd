<?php

namespace App\Http\Controllers\Ramadhan;

use App\Http\Controllers\Controller;
use App\Models\DaySetting;
// use App\Models\Jamaah;
// use App\Models\Makanan;
// use App\Models\Minuman;
use App\Models\RamadhanSetting;
// use App\Models\Takjil;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;

class DaySettController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $daySettings = DaySetting::with('ramadhanSetting')
            ->withCount('takjils')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {

                    // search tanggal (aman walau bertipe DATE)
                    $sub->where('date', 'like', "%{$q}%")

                        // search catatan
                        ->orWhere('notes', 'like', "%{$q}%");
                });
            })
            ->orderBy('date', 'asc')
            ->paginate(30)
            ->withQueryString();

        if ($request->ajax()) {
            return view('ramadhan.daySettings.partials.table', compact('daySettings'))->render();
        }

        return view('ramadhan.daySettings.index', compact('daySettings', 'q'));
    }
    // public function index(RamadhanSetting $ramadhanSetting)
    // {
    //     $days = $ramadhanSetting->daySettings()->orderBy('date')->get();

    //     return view('ramadhan.daySettings.index', compact('ramadhanSetting', 'days'));
    // }


    /**
     * Show the form for creating a new resource.
     */
    // public function create(RamadhanSetting $ramadhanSetting)
    // {
    //     // Generate semua hari kecuali Jumat
    //     $days = $this->generateRamadhanDays($ramadhanSetting);

    //     // Debug information
    //     logger('Ramadhan Setting ID: ' . $ramadhanSetting->id);
    //     logger('Start Date: ' . $ramadhanSetting->start_date);
    //     logger('End Date: ' . $ramadhanSetting->end_date);
    //     logger('Expected Days: ' . $ramadhanSetting->days);
    //     logger('Generated Days Count: ' . count($days));

    //     // Tambahkan debug info ke view
    //     $debugInfo = [
    //         'expected_days' => $ramadhanSetting->days,
    //         'generated_days' => count($days),
    //         'start_date' => $ramadhanSetting->start_date,
    //         'end_date' => $ramadhanSetting->end_date
    //     ];

    //     return view('ramadhan.daySettings.create', compact('ramadhanSetting', 'days', 'debugInfo'));
    // }
    // public function create($ramadhanSettingId)
    // {
    //     $ramadhanSetting = RamadhanSetting::findOrFail($ramadhanSettingId);

    //     $start = Carbon::parse($ramadhanSetting->start_date)->locale('id');
    //     $end   = Carbon::parse($ramadhanSetting->end_date)->locale('id');

    //     $period = CarbonPeriod::create($start, $end);

    //     $dates = [];
    //     foreach ($period as $date) {
    //         if ($date->isFriday()) continue; // exclude Jumat
    //         $dates[] = $date;
    //     }

    //     $totalDays = count($dates);
    //     $totalSetoran = $ramadhanSetting->total_setoran;

    //     // Pembagian kuota
    //     $quotaBase = intdiv($totalSetoran, $totalDays);
    //     $remainder = $totalSetoran % $totalDays;

    //     $days = [];
    //     foreach ($dates as $i => $date) {
    //         $quota = $quotaBase;

    //         if ($i < min(7, $totalDays) && $remainder > 0) {
    //             $quota++;
    //             $remainder--;
    //         }

    //         $days[] = [
    //             'date' => $date->translatedFormat('d F Y'),
    //             'dayname' => $date->translatedFormat('l'),
    //             'quota' => $quota,
    //             'total_makanan_planned' => 0,
    //             'total_minuman_planned' => 0,
    //             'notes' => ''
    //         ];
    //     }

    //     return view('ramadhan.daySettings.create', compact('ramadhanSetting', 'days'));
    // }
    public function create($ramadhanSettingId)
    {
        $ramadhanSetting = RamadhanSetting::findOrFail($ramadhanSettingId);

        // CEGAH CREATE ULANG
        if (DaySetting::where('ramadhan_setting_id', $ramadhanSettingId)->exists()) {
            return redirect()
                ->route('ramadhan-settings.show', $ramadhanSettingId)
                ->with('error', 'Day Setting sudah ada! Silakan lakukan Edit, bukan membuat ulang. Gok dlogok !');
        }

        $start = Carbon::parse($ramadhanSetting->start_date)->locale('id');
        $end   = Carbon::parse($ramadhanSetting->end_date)->locale('id');

        $period = CarbonPeriod::create($start, $end);

        $dates = [];
        foreach ($period as $date) {
            if ($date->isFriday()) continue;
            $dates[] = $date;
        }

        $totalDays = count($dates);
        $totalSetoran = $ramadhanSetting->total_setoran;

        // Pembagian kuota default
        $quotaBase = intdiv($totalSetoran, $totalDays);
        $remainder = $totalSetoran % $totalDays;

        $days = [];
        foreach ($dates as $i => $date) {

            $quota = $quotaBase;

            if ($i < min(7, $totalDays) && $remainder > 0) {
                $quota += 1;
                $remainder--;
            }

            $days[] = [
                'date_value' => $date->format('Y-m-d'),               // untuk input
                'date_label' => $date->translatedFormat('d F Y'),     // untuk tampilan
                'dayname' => $date->translatedFormat('l'),
                'quota' => $quota,
                'total_makanan_planned' => 0,
                'total_minuman_planned' => 0,
                'notes' => null
            ];
        }

        return view('ramadhan.daySettings.create', compact(
            'ramadhanSetting',
            'days',
            'totalSetoran'
        ));
    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request, RamadhanSetting $ramadhanSetting)
    // {
    //     $validated = $request->validate([
    //         'days' => 'required|array',
    //         'days.*.date' => 'required|date',
    //         'days.*.quota' => 'required|integer|min:1',
    //         'days.*.total_makanan_planned' => 'nullable|integer',
    //         'days.*.total_minuman_planned' => 'nullable|integer',
    //         'days.*.notes' => 'nullable|string',
    //     ]);

    //     foreach ($validated['days'] as $dayData) {
    //         $ramadhanSetting->days()->create($dayData);
    //     }

    //     return redirect()
    //         ->route('ramadhan-settings.show', $ramadhanSetting)
    //         ->with('success', 'Semua pengaturan hari Ramadhan berhasil dibuat.');
    // }
    // public function store(Request $request, $ramadhanSettingId)
    // {
    //     $ramadhanSetting = RamadhanSetting::findOrFail($ramadhanSettingId);

    //     $request->validate([
    //         'days' => 'required|array',
    //         'days.*.date' => 'required|date',
    //         'days.*.quota' => 'required|integer|min:1',
    //         'days.*.total_makanan_planned' => 'nullable|integer|min:0',
    //         'days.*.total_minuman_planned' => 'nullable|integer|min:0',
    //         'days.*.notes' => 'nullable|string|max:255',
    //     ]);

    //     $totalQuota = array_sum(array_column($request->days, 'quota'));

    //     if ($totalQuota > $ramadhanSetting->total_setoran) {
    //         return back()->with('error', 'Total kuota melebihi total setoran!')->withInput();
    //     }

    //     // Hapus data lama
    //     DaySetting::where('ramadhan_setting_id', $ramadhanSettingId)->delete();

    //     foreach ($request->days as $day) {
    //         DaySetting::create([
    //             'ramadhan_setting_id' => $ramadhanSettingId,
    //             'date' => $day['date'],
    //             'quota' => $day['quota'],
    //             'total_makanan_planned' => $day['total_makanan_planned'] ?? 0,
    //             'total_minuman_planned' => $day['total_minuman_planned'] ?? 0,
    //             'notes' => $day['notes'] ?? null,
    //         ]);
    //     }

    //     return redirect()->route('ramadhan-settings.show', $ramadhanSettingId)
    //         ->with('success', 'Day Settings berhasil disimpan!');
    // }
    // public function store(Request $request, $ramadhanSettingId)
    // {
    //     $ramadhanSetting = RamadhanSetting::findOrFail($ramadhanSettingId);

    //     $request->validate([
    //         'days' => 'required|array',
    //         'days.*.date' => 'required|date',
    //         'days.*.quota' => 'required|integer|min:1',
    //         'days.*.total_makanan_planned' => 'nullable|integer|min:0',
    //         'days.*.total_minuman_planned' => 'nullable|integer|min:0',
    //         'days.*.notes' => 'nullable|string|max:255',
    //     ]);

    //     // Hitung total kuota
    //     $totalQuota = array_sum(array_column($request->days, 'quota'));

    //     // VALIDASI BARU — total quota harus SAMA dengan total_setoran
    //     if ($totalQuota != $ramadhanSetting->total_setoran) {

    //         return back()
    //             ->with('error', "Total kuota ($totalQuota) harus sama dengan total setoran ($ramadhanSetting->total_setoran)!")
    //             ->withInput();
    //     }

    //     // Validasi lama — tambahan jika ingin tetap mengecek
    //     if ($totalQuota > $ramadhanSetting->total_setoran) {
    //         return back()
    //             ->with('error', 'Total kuota melebihi total setoran!')
    //             ->withInput();
    //     }

    //     // Hapus data lama
    //     DaySetting::where('ramadhan_setting_id', $ramadhanSettingId)->delete();

    //     // Simpan data baru
    //     foreach ($request->days as $day) {
    //         DaySetting::create([
    //             'ramadhan_setting_id' => $ramadhanSettingId,
    //             'date' => $day['date'],
    //             'quota' => $day['quota'],
    //             'total_makanan_planned' => $day['total_makanan_planned'] ?? 0,
    //             'total_minuman_planned' => $day['total_minuman_planned'] ?? 0,
    //             'notes' => $day['notes'] ?? null,
    //         ]);
    //     }

    //     return redirect()
    //         ->route('ramadhan-settings.show', $ramadhanSettingId)
    //         ->with('success', 'Day Settings berhasil disimpan!');
    // }
    public function store(Request $request, $ramadhanSettingId)
    {
        $ramadhanSetting = RamadhanSetting::findOrFail($ramadhanSettingId);

        $request->validate([
            'days' => 'required|array',
            'days.*.date' => 'required|date',
            'days.*.quota' => 'required|integer|min:1',
            'days.*.total_makanan_planned' => 'nullable|integer|min:0',
            'days.*.total_minuman_planned' => 'nullable|integer|min:0',
            'days.*.notes' => 'nullable|string|max:255',
        ]);

        $days = $request->input('days');

        // hitung total kuota
        $totalQuota = array_sum(array_column($days, 'quota'));

        if ($totalQuota != $ramadhanSetting->total_setoran) {
            return back()
                ->with('error', "Total kuota ($totalQuota) harus sama dengan total setoran ($ramadhanSetting->total_setoran)!")
                ->withInput();
        }

        // Hapus data lama
        DaySetting::where('ramadhan_setting_id', $ramadhanSettingId)->delete();

        // Simpan data baru
        foreach ($days as $day) {
            DaySetting::create([
                'ramadhan_setting_id' => $ramadhanSettingId,
                'date' => $day['date'],
                'quota' => $day['quota'],
                'total_makanan_planned' => $day['total_makanan_planned'] ?? 0,
                'total_minuman_planned' => $day['total_minuman_planned'] ?? 0,
                'notes' => $day['notes'] ?? null,
            ]);
        }

        return redirect()
            ->route('ramadhan-settings.show', $ramadhanSettingId)
            ->with('success', 'Day Settings berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     // Todo jarak a ke b
    //     // Filter jamaaah
    //     // validate liane mbuh

    //     $day = DaySetting::findOrFail($id);

    //     $filledToday = Takjil::where('day_setting_id', $id)
    //         ->pluck('jamaah_id')
    //         ->toArray();

    //     // Search query
    //     $q = request('q');

    //     $jamaahs = Jamaah::whereNotIn('id', $filledToday)
    //         ->where('setoran', '>', 0)  // auto disable jamaah yang setoran habis
    //         ->when($q, function ($query) use ($q) {
    //             $query->where(function ($q2) use ($q) {
    //                 $q2->where('nama', 'like', "%$q%")
    //                     ->orWhere('alamat', 'like', "%$q%")
    //                     ->orWhere('ekonomi', 'like', "%$q%")
    //                     ->orWhere('keterangan', 'like', "%$q%")
    //                     ->orWhere('notes', 'like', "%$q%");
    //             });
    //         })
    //         ->get();

    //     $makanans = Makanan::all();
    //     $minumans = Minuman::all();
    //     return view('ramadhan.daySettings.show', compact('day', 'jamaahs', 'q', 'makanans', 'minumans'));
    // }
    // public function show(DaySetting $daySetting)
    // {
    //     // Load relasi yang diperlukan
    //     $daySetting->load([
    //         'ramadhanSetting',     // induk ramadhan_settings
    //         'takjils.jamaah'       // untuk menampilkan nama jamaah jika perlu
    //     ]);

    //     return view('ramadhan.daySettings.show', [
    //         'daySetting' => $daySetting
    //     ]);
    // }
    public function show(DaySetting $daySetting)
    {
        // Load relasi yang diperlukan dengan eager loading
        $daySetting->load([
            'ramadhanSetting',
            'takjils.jamaah',
            'takjils.makanans.makanan', // Load relasi makanan
            'takjils.minumans.minuman'  // Load relasi minuman
        ]);

        return view('ramadhan.daySettings.show', [
            'daySetting' => $daySetting
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(RamadhanSetting $ramadhanSetting, DaySetting $daySetting)
    // {
    //     return view('ramadhan.daySettings.edit', compact('ramadhanSetting', 'daySetting'));
    // }
    // public function edit($ramadhanSettingId)
    // {
    //     $ramadhanSetting = RamadhanSetting::findOrFail($ramadhanSettingId);

    //     // Ambil data day_settings yang sudah tersimpan
    //     $daySettings = DaySetting::where('ramadhan_setting_id', $ramadhanSettingId)
    //         ->orderBy('date', 'asc')
    //         ->get();

    //     if ($daySettings->isEmpty()) {
    //         return redirect()->route('day-settings.create', $ramadhanSettingId)
    //             ->with('error', 'Day Setting belum dibuat, silakan generate terlebih dahulu.');
    //     }

    //     // Format agar mirip $days dari create()
    //     $days = $daySettings->map(function ($d) {
    //         return [
    //             'id' => $d->id,
    //             'date' => $d->date->translatedFormat('d F Y'),
    //             'dayname' => $d->date->translatedFormat('l'),
    //             'quota' => $d->quota,
    //             'total_makanan_planned' => $d->total_makanan_planned,
    //             'total_minuman_planned' => $d->total_minuman_planned,
    //             'notes' => $d->notes,
    //         ];
    //     });

    //     return view('ramadhan.daySettings.edit', compact('ramadhanSetting', 'days'));
    // }
    public function edit($ramadhanSettingId)
    {
        $ramadhanSetting = RamadhanSetting::findOrFail($ramadhanSettingId);

        $daySettings = DaySetting::where('ramadhan_setting_id', $ramadhanSettingId)
            ->orderBy('date', 'asc')
            ->get();

        if ($daySettings->isEmpty()) {
            return redirect()->route('day-settings.create', $ramadhanSettingId)
                ->with('error', 'Day Setting belum dibuat, silakan generate terlebih dahulu.');
        }

        // JANGAN kirim tanggal format lokal — kirim Y-m-d ke form hidden
        $days = $daySettings->map(function ($d) {
            return [
                'id' => $d->id,
                'date' => $d->date->format('Y-m-d'), // <-- FIX
                'date_label' => $d->date->translatedFormat('d F Y'),
                'dayname' => $d->date->translatedFormat('l'),
                'quota' => $d->quota,
                'total_makanan_planned' => $d->total_makanan_planned,
                'total_minuman_planned' => $d->total_minuman_planned,
                'notes' => $d->notes,
            ];
        });

        return view('ramadhan.daySettings.edit', compact('ramadhanSetting', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $ramadhanSettingId)
    // {
    //     $ramadhanSetting = RamadhanSetting::findOrFail($ramadhanSettingId);

    //     // VALIDASI — sama dengan store()
    //     $request->validate([
    //         'days' => 'required|array',
    //         'days.*.date' => 'required|date',
    //         'days.*.quota' => 'required|integer|min:1',
    //         'days.*.total_makanan_planned' => 'nullable|integer|min:0',
    //         'days.*.total_minuman_planned' => 'nullable|integer|min:0',
    //         'days.*.notes' => 'nullable|string|max:255',
    //     ]);

    //     // Hitung total kuota
    //     $totalQuota = array_sum(array_column($request->days, 'quota'));

    //     // VALIDASI BARU — total quota harus SAMA dengan total_setoran
    //     if ($totalQuota != $ramadhanSetting->total_setoran) {
    //         return back()
    //             ->with('error', "Total kuota ($totalQuota) harus sama dengan total setoran ($ramadhanSetting->total_setoran)!")
    //             ->withInput();
    //     }

    //     // Validasi lama — jika ingin tetap mengecek
    //     if ($totalQuota > $ramadhanSetting->total_setoran) {
    //         return back()
    //             ->with('error', 'Total kuota melebihi total setoran!')
    //             ->withInput();
    //     }

    //     // Hapus semua DaySetting sebelumnya
    //     DaySetting::where('ramadhan_setting_id', $ramadhanSettingId)->delete();

    //     // Simpan data baru
    //     foreach ($request->days as $day) {
    //         DaySetting::create([
    //             'ramadhan_setting_id' => $ramadhanSettingId,
    //             'date' => $day['date'],
    //             'quota' => $day['quota'],
    //             'total_makanan_planned' => $day['total_makanan_planned'] ?? 0,
    //             'total_minuman_planned' => $day['total_minuman_planned'] ?? 0,
    //             'notes' => $day['notes'] ?? null,
    //         ]);
    //     }

    //     return redirect()
    //         ->route('ramadhan-settings.show', $ramadhanSettingId)
    //         ->with('success', 'Day Settings berhasil diperbarui!');
    // }
    public function update(Request $request, $ramadhanSettingId)
    {
        $ramadhanSetting = RamadhanSetting::findOrFail($ramadhanSettingId);

        $request->validate([
            'days' => 'required|array',
            'days.*.id' => 'required|integer|exists:day_settings,id',
            'days.*.date' => 'required|date', // sekarang valid
            'days.*.quota' => 'required|integer|min:1',
            'days.*.total_makanan_planned' => 'nullable|integer|min:0',
            'days.*.total_minuman_planned' => 'nullable|integer|min:0',
            'days.*.notes' => 'nullable|string|max:255',
        ]);

        $days = $request->days;

        $totalQuota = array_sum(array_column($days, 'quota'));

        // Validasi wajib sama
        if ($totalQuota != $ramadhanSetting->total_setoran) {
            return back()->with('error', "Total kuota ($totalQuota) harus sama dengan total setoran ($ramadhanSetting->total_setoran)")
                ->withInput();
        }

        // Update tiap hari
        foreach ($days as $d) {
            DaySetting::where('id', $d['id'])->update([
                'date' => $d['date'],
                'quota' => $d['quota'],
                'total_makanan_planned' => $d['total_makanan_planned'],
                'total_minuman_planned' => $d['total_minuman_planned'],
                'notes' => $d['notes'],
            ]);
        }

        // return redirect()
        //     ->route('day-settings.edit', $ramadhanSettingId)
        //     ->with('success', 'Day Setting berhasil diperbarui!');
        return redirect()
            ->route('ramadhan-settings.show', $ramadhanSettingId)
            ->with('success', 'Day Settings berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RamadhanSetting $ramadhanSetting, DaySetting $daySetting)
    {
        $daySetting->delete();

        return back()->with('success', 'Hari berhasil dihapus.');
    }

    // private function generateDays(RamadhanSetting $ramadhanSetting)
    // {
    //     $start = Carbon::parse($ramadhanSetting->start_date);
    //     $end   = Carbon::parse($ramadhanSetting->end_date);

    //     $dates = [];

    //     while ($start->lte($end)) {

    //         // Lewati hari Jumat
    //         if ($start->isFriday()) {
    //             $start->addDay();
    //             continue;
    //         }

    //         $dates[] = [
    //             'date' => $start->toDateString(),
    //             'quota' => 0, // default
    //         ];

    //         $start->addDay();
    //     }

    //     return $dates;
    // }
}
