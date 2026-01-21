<?php

namespace App\Http\Controllers\Ramadhan;

use App\Http\Controllers\Controller;
use App\Models\DaySetting;
use App\Models\{Jamaah, Makanan, Minuman, Takjil, TakjilMakanan, TakjilMinuman};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TakjilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $takjils = Takjil::with(['jamaah', 'daySetting'])
            ->latest()
            ->paginate(20);

        return view('ramadhan.takjil.index', compact('takjils'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('ramadhan.takjil.create', [
    //         'jamaahs' => Jamaah::all(),
    //         'makanans' => Makanan::all(),
    //         'minumans' => Minuman::all(),
    //         'daySettings' => DaySetting::orderBy('date')->get(),
    //     ]);
    // }
    // public function create($id)
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
    //     return view('ramadhan.takjil.create', compact('day', 'jamaahs', 'q', 'makanans', 'minumans'));
    // }
    // public function create($id)
    // {
    //     // CEGAH CREATE ULANG — jika takjil hari ini sudah pernah dibuat (minimal satu jamaah)
    //     if (Takjil::where('day_setting_id', $id)->exists()) {
    //         return redirect()
    //             ->route('day-settings.show', $id)
    //             ->with('error', 'Takjil untuk hari ini sudah dibuat! Silakan lakukan Edit bukan membuat ulang.');
    //     }

    //     $day = DaySetting::findOrFail($id);
    //     $today = $day->date;

    //     // Jamaah yg sudah terisi hari ini
    //     $filledToday = Takjil::where('day_setting_id', $id)
    //         ->pluck('jamaah_id')
    //         ->toArray();

    //     // Ambil tanggal terakhir jamaah pernah dapat jadwal
    //     $lastTakjil = Takjil::select(
    //         'takjils.jamaah_id',
    //         DB::raw('MAX(day_settings.date) AS last_date')
    //     )
    //         ->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
    //         ->groupBy('takjils.jamaah_id')
    //         ->pluck('last_date', 'jamaah_id');

    //     // Ambil semua jamaah yg punya setoran > 0
    //     $all = Jamaah::where('setoran', '>', 0)
    //         ->orderBy('nama')
    //         ->get();

    //     // Filter: jeda minimal 7 hari
    //     $jamaahs = $all->filter(function ($j) use ($filledToday, $lastTakjil, $today) {

    //         if (in_array($j->id, $filledToday)) {
    //             return false;
    //         }

    //         // Belum pernah dapat jadwal → OK
    //         if (!isset($lastTakjil[$j->id])) {
    //             return true;
    //         }

    //         $lastDate = Carbon::parse($lastTakjil[$j->id]);
    //         $todayDate = Carbon::parse($today);

    //         // Jarak hari
    //         $jarak = $lastDate->diffInDays($todayDate);

    //         return $jarak >= 7;
    //     });

    //     $makanans = Makanan::all();
    //     $minumans = Minuman::all();

    //     return view('ramadhan.takjil.create', compact('day', 'jamaahs', 'makanans', 'minumans'));
    // }
    // public function create($id) //! LOGIC
    // {
    //     // CEGAH CREATE ULANG — jika takjil hari ini sudah pernah dibuat (minimal satu jamaah)
    //     if (Takjil::where('day_setting_id', $id)->exists()) {
    //         return redirect()
    //             ->route('day-settings.show', $id)
    //             ->with('error', 'Takjil untuk hari ini sudah dibuat! Silakan lakukan Edit bukan membuat ulang.');
    //     }

    //     $day = DaySetting::findOrFail($id);
    //     $today = $day->date;

    //     // Jamaah yg sudah terisi hari ini
    //     $filledToday = Takjil::where('day_setting_id', $id)
    //         ->pluck('jamaah_id')
    //         ->toArray();

    //     // Ambil tanggal terakhir jamaah pernah dapat jadwal
    //     $lastTakjil = Takjil::select(
    //         'takjils.jamaah_id',
    //         DB::raw('MAX(day_settings.date) AS last_date')
    //     )
    //         ->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
    //         ->groupBy('takjils.jamaah_id')
    //         ->pluck('last_date', 'jamaah_id');

    //     // Ambil semua jamaah dengan logika setoran:
    //     // - Setoran = 2: Bisa dipilih jika memenuhi jeda 7 hari
    //     // - Setoran = 1: Tidak bisa dipilih lagi jika sudah pernah dapat jadwal
    //     $allJamaahs = Jamaah::orderBy('nama')->get();

    //     // Filter jamaah berdasarkan aturan
    //     $jamaahs = $allJamaahs->filter(function ($jamaah) use ($filledToday, $lastTakjil, $today) {

    //         // Skip jika sudah terisi hari ini
    //         if (in_array($jamaah->id, $filledToday)) {
    //             return false;
    //         }

    //         // JAMA DENGAN SETORAN = 1
    //         if ($jamaah->setoran == 1) {
    //             // Jika sudah pernah dapat jadwal, tidak boleh dipilih lagi
    //             if (isset($lastTakjil[$jamaah->id])) {
    //                 return false;
    //             }
    //             // Jika belum pernah dapat jadwal, boleh dipilih
    //             return true;
    //         }

    //         // JAMA DENGAN SETORAN = 2
    //         if ($jamaah->setoran == 2) {
    //             // Belum pernah dapat jadwal → OK
    //             if (!isset($lastTakjil[$jamaah->id])) {
    //                 return true;
    //             }

    //             // Sudah pernah dapat jadwal, cek jeda 7 hari
    //             $lastDate = Carbon::parse($lastTakjil[$jamaah->id]);
    //             $todayDate = Carbon::parse($today);
    //             $jarak = $lastDate->diffInDays($todayDate);

    //             return $jarak >= 7;
    //         }

    //         // Setoran selain 1 atau 2, tidak ditampilkan
    //         return false;
    //     });

    //     $makanans = Makanan::all();
    //     $minumans = Minuman::all();

    //     return view('ramadhan.takjil.create', compact('day', 'jamaahs', 'makanans', 'minumans'));
    // }
    // public function create($id)
    // {
    //     // CEGAH CREATE ULANG jika sudah ada data
    //     if (Takjil::where('day_setting_id', $id)->exists()) {
    //         return redirect()
    //             ->route('day-settings.show', $id)
    //             ->with('error', 'Takjil untuk hari ini sudah dibuat! Silakan lakukan Edit bukan membuat ulang.');
    //     }

    //     $day = DaySetting::findOrFail($id);

    //     // Get eligible jamaahs (sudah diurutkan berdasarkan prioritas)
    //     $eligibleData = $this->getEligibleJamaahs($day->date, [], false);

    //     // Format untuk Blade: gabungkan data jamaah dengan info tambahan
    //     $jamaahs = $eligibleData->map(function ($item) {
    //         $j = $item['jamaah'];

    //         // Hitung hari sejak terakhir
    //         $daysSinceLast = null;
    //         if ($item['last_date']) {
    //             $lastDate = Carbon::parse($item['last_date']);
    //             $today = Carbon::today();
    //             $daysSinceLast = $today->diffInDays($lastDate);
    //         }

    //         return (object) [
    //             'id' => $j->id,
    //             'nama' => $j->nama,
    //             'alamat' => $j->alamat ?? '',
    //             'ekonomi' => $j->ekonomi ?? '',
    //             'notes' => $j->notes ?? '',
    //             'keterangan' => $j->keterangan ?? '',
    //             // Tampilkan SISA setoran (bukan total setoran)
    //             'setoran' => $item['sisa_setoran'],
    //             // Info tambahan untuk debugging jika perlu
    //             '_total_setoran' => $j->setoran,
    //             '_sudah_dapat' => $item['takjil_count'],
    //             '_last_date' => $item['last_date'],
    //             '_days_since_last' => $daysSinceLast,
    //             '_priority' => $item['priority_score']
    //         ];
    //     });

    //     $makanans = Makanan::all();
    //     $minumans = Minuman::all();

    //     return view('ramadhan.takjil.create', compact(
    //         'day',
    //         'jamaahs',
    //         'makanans',
    //         'minumans'
    //     ));
    // }
    public function create($id)
    {
        // CEGAH CREATE ULANG jika sudah ada data
        if (Takjil::where('day_setting_id', $id)->exists()) {
            return redirect()
                ->route('day-settings.show', $id)
                ->with('error', 'Takjil untuk hari ini sudah dibuat! Silakan lakukan Edit bukan membuat ulang.');
        }

        $day = DaySetting::findOrFail($id);
        $targetDate = $day->date;

        // Get eligible jamaahs (sudah diurutkan berdasarkan prioritas)
        $eligibleData = $this->getEligibleJamaahs($day->date, [], false);

        // Format untuk Blade: gabungkan data jamaah dengan info tambahan
        $jamaahs = $eligibleData->map(function ($item) use ($targetDate) {
            $j = $item['jamaah'];

            // Dapatkan info schedule
            $scheduleInfo = $this->getJamaahScheduleInfo($j, $targetDate);

            return (object) [
                'id' => $j->id,
                'nama' => $j->nama,
                'alamat' => $j->alamat ?? '',
                'ekonomi' => $j->ekonomi ?? '',
                'notes' => $j->notes ?? '',
                'keterangan' => $j->keterangan ?? '',
                // Tampilkan SISA setoran (bukan total setoran)
                'setoran' => $scheduleInfo['sisa_setoran'],
                // Info tambahan untuk debugging jika perlu
                '_total_setoran' => $j->setoran,
                '_sudah_dapat' => $scheduleInfo['takjil_count'],
                '_last_date' => $scheduleInfo['last_date'],
                '_last_date_formatted' => $scheduleInfo['last_date_formatted'],
                '_days_since_last' => $scheduleInfo['days_since_last'],
                '_is_less_than_7_days' => $scheduleInfo['is_less_than_7_days'],
                '_has_reached_limit' => $scheduleInfo['has_reached_limit'],
                '_priority' => $item['priority_score']
            ];
        });

        $makanans = Makanan::all();
        $minumans = Minuman::all();

        return view('ramadhan.takjil.create', compact(
            'day',
            'jamaahs',
            'makanans',
            'minumans'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'jamaah_id' => 'required|exists:jamaahs,id',
    //         'day_setting_id' => 'required|exists:day_settings,id',

    //         'makanan.*.id' => 'nullable|exists:makanans,id',
    //         'makanan.*.jumlah' => 'nullable|integer|min:1',

    //         'minuman.*.id' => 'nullable|exists:minumans,id',
    //         'minuman.*.jumlah' => 'nullable|integer|min:1',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // Create Header
    //         $takjil = Takjil::create([
    //             'jamaah_id' => $request->jamaah_id,
    //             'day_setting_id' => $request->day_setting_id,
    //             'tanggal_hijriyah' => $request->tanggal_hijriyah,
    //             'keterangan' => json_encode($request->keterangan),
    //         ]);

    //         // Insert makanan detail
    //         if ($request->has('makanan')) {
    //             foreach ($request->makanan as $mkn) {
    //                 if (!empty($mkn['id'])) {
    //                     TakjilMakanan::create([
    //                         'takjil_id' => $takjil->id,
    //                         'makanan_id' => $mkn['id'],
    //                         'jumlah' => $mkn['jumlah'] ?? 1
    //                     ]);
    //                 }
    //             }
    //         }

    //         // Insert minuman detail
    //         if ($request->has('minuman')) {
    //             foreach ($request->minuman as $mnm) {
    //                 if (!empty($mnm['id'])) {
    //                     TakjilMinuman::create([
    //                         'takjil_id' => $takjil->id,
    //                         'minuman_id' => $mnm['id'],
    //                         'jumlah' => $mnm['jumlah'] ?? 1
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->route('takjil.index')->with('success', 'Data takjil berhasil disimpan.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'day_setting_id' => 'required|exists:day_settings,id',
    //         'jamaah_id.*' => 'nullable|exists:jamaahs,id',
    //         'tanggal_hijriyah.*' => 'nullable|string',
    //         'makanan_id.*' => 'nullable|exists:makanans,id',
    //         'minuman_id.*' => 'nullable|exists:minumans,id',
    //         'keterangan.*' => 'nullable|string',
    //     ]);

    //     $daySettingId = $request->day_setting_id;

    //     foreach ($request->jamaah_id as $index => $jamaahId) {
    //         if (!$jamaahId) continue;

    //         // CEGAH DUPLIKASI TAKJIL
    //         $exists = Takjil::where('jamaah_id', $jamaahId)
    //             ->where('day_setting_id', $daySettingId)
    //             ->exists();

    //         if ($exists) continue;

    //         // ================= INSERT KE TAKJILS =================
    //         $takjil = Takjil::create([
    //             'jamaah_id' => $jamaahId,
    //             'day_setting_id' => $daySettingId,
    //             'tanggal_hijriyah' => $request->tanggal_hijriyah[$index] ?? null,
    //             'keterangan' => $request->keterangan[$index] ?? null
    //         ]);

    //         // ================= INSERT MAKANAN =================
    //         if ($request->makanan_id[$index]) {
    //             TakjilMakanan::create([
    //                 'takjil_id' => $takjil->id,
    //                 'makanan_id' => $request->makanan_id[$index],
    //                 'jumlah' => 1
    //             ]);
    //         }

    //         // ================= INSERT MINUMAN =================
    //         if ($request->minuman_id[$index]) {
    //             TakjilMinuman::create([
    //                 'takjil_id' => $takjil->id,
    //                 'minuman_id' => $request->minuman_id[$index],
    //                 'jumlah' => 1
    //             ]);
    //         }

    //         // ================= UPDATE SETORAN (dikurangi 1) =================
    //         Jamaah::where('id', $jamaahId)->decrement('setoran', 1);
    //     }

    //     // return redirect()->back()->with('success', 'Jadwal takjil berhasil disimpan.');
    //     return redirect()
    //         ->route('day-settings.show', $request->day_setting_id)
    //         ->with('success', 'Jadwal takjil berhasil disimpan.');
    // }
    // public function store(Request $request)
    // {
    //     // Validasi dasar
    //     $request->validate([
    //         'day_setting_id' => 'required|exists:day_settings,id',
    //         'jamaah_id' => 'required|array',
    //         'jamaah_id.*' => 'nullable|exists:jamaahs,id',
    //         'tanggal_hijriyah' => 'required|array',
    //         'tanggal_hijriyah.*' => 'nullable|string',
    //         'makanan_id' => 'required|array',
    //         'makanan_id.*' => 'nullable|exists:makanans,id',
    //         'minuman_id' => 'required|array',
    //         'minuman_id.*' => 'nullable|exists:minumans,id',
    //         'keterangan' => 'nullable|array',
    //         'keterangan.*' => 'nullable|string',
    //     ]);

    //     $daySettingId = $request->day_setting_id;
    //     $hasSavedData = false; // Flag untuk mengecek apakah ada data yang disimpan

    //     // Validasi setiap baris
    //     foreach ($request->jamaah_id as $index => $jamaahId) {

    //         // Skip jika jamaah_id kosong
    //         if (!$jamaahId) {
    //             continue;
    //         }

    //         // ================= VALIDASI MINIMAL 1 =================
    //         $makanan = $request->makanan_id[$index] ?? null;
    //         $minuman = $request->minuman_id[$index] ?? null;

    //         if (!$makanan && !$minuman) {
    //             return back()
    //                 ->withInput()
    //                 ->with('error', "Baris ke-" . ($index + 1) . " wajib memilih minimal makanan atau minuman.");
    //         }

    //         // CEGAH DUPLIKASI TAKJIL
    //         $exists = Takjil::where('jamaah_id', $jamaahId)
    //             ->where('day_setting_id', $daySettingId)
    //             ->exists();

    //         if ($exists) {
    //             // Bisa tambahkan pesan warning jika perlu
    //             continue;
    //         }

    //         // ===== INSERT KE TAKJILS =====
    //         $takjil = Takjil::create([
    //             'jamaah_id' => $jamaahId,
    //             'day_setting_id' => $daySettingId,
    //             'tanggal_hijriyah' => $request->tanggal_hijriyah[$index] ?? null,
    //             'keterangan' => $request->keterangan[$index] ?? null
    //         ]);

    //         // ===== INSERT MAKANAN =====
    //         if ($makanan) {
    //             TakjilMakanan::create([
    //                 'takjil_id' => $takjil->id,
    //                 'makanan_id' => $makanan,
    //                 'jumlah' => 1
    //             ]);
    //         }

    //         // ===== INSERT MINUMAN =====
    //         if ($minuman) {
    //             TakjilMinuman::create([
    //                 'takjil_id' => $takjil->id,
    //                 'minuman_id' => $minuman,
    //                 'jumlah' => 1
    //             ]);
    //         }

    //         // ===== KURANGI SETORAN =====
    //         Jamaah::where('id', $jamaahId)->decrement('setoran', 1);

    //         $hasSavedData = true; // Set flag ke true karena ada data yang disimpan
    //     }

    //     // Cek apakah ada data yang berhasil disimpan
    //     if (!$hasSavedData) {
    //         return back()
    //             ->withInput()
    //             ->with('error', 'Tidak ada data jamaah yang dipilih untuk disimpan.');
    //     }

    //     return redirect()
    //         ->route('day-settings.show', $request->day_setting_id)
    //         ->with('success', 'Jadwal takjil berhasil disimpan.');
    // }
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'day_setting_id' => 'required|exists:day_settings,id',
            'jamaah_id' => 'required|array',
            'jamaah_id.*' => 'nullable|exists:jamaahs,id',
            'tanggal_hijriyah' => 'required|array',
            'tanggal_hijriyah.*' => 'nullable|string',
            'makanan_id' => 'required|array',
            'makanan_id.*' => 'nullable|exists:makanans,id',
            'minuman_id' => 'required|array',
            'minuman_id.*' => 'nullable|exists:minumans,id',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
        ]);

        $daySettingId = $request->day_setting_id;
        $hasSavedData = false;
        $errors = [];

        // Validasi semua baris
        foreach ($request->jamaah_id as $index => $jamaahId) {
            if (!$jamaahId) {
                continue;
            }

            $makanan = $request->makanan_id[$index] ?? null;
            $minuman = $request->minuman_id[$index] ?? null;

            if (!$makanan && !$minuman) {
                $errors[] = "Baris ke-" . ($index + 1) . " wajib memilih minimal makanan atau minuman.";
            }

            $exists = Takjil::where('jamaah_id', $jamaahId)
                ->where('day_setting_id', $daySettingId)
                ->exists();

            if ($exists) {
                $jamaahName = Jamaah::find($jamaahId)->nama ?? 'Jamaah';
                $errors[] = "Baris ke-" . ($index + 1) . ": $jamaahName sudah terdaftar pada hari ini.";
            }
        }

        // Jika ada error, tampilkan sebagai string
        if (!empty($errors)) {
            $errorMessage = '<ul class="mb-0"><li>' . implode('</li><li>', $errors) . '</li></ul>';
            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }

        // **KEDUA: Proses penyimpanan jika semua validasi lolos**
        foreach ($request->jamaah_id as $index => $jamaahId) {
            // Skip jika jamaah_id kosong
            if (!$jamaahId) {
                continue;
            }

            $makanan = $request->makanan_id[$index] ?? null;
            $minuman = $request->minuman_id[$index] ?? null;

            // ===== INSERT KE TAKJILS =====
            $takjil = Takjil::create([
                'jamaah_id' => $jamaahId,
                'day_setting_id' => $daySettingId,
                'tanggal_hijriyah' => $request->tanggal_hijriyah[$index] ?? null,
                'keterangan' => $request->keterangan[$index] ?? null
            ]);

            // ===== INSERT MAKANAN =====
            if ($makanan) {
                TakjilMakanan::create([
                    'takjil_id' => $takjil->id,
                    'makanan_id' => $makanan,
                    'jumlah' => 1
                ]);
            }

            // ===== INSERT MINUMAN =====
            if ($minuman) {
                TakjilMinuman::create([
                    'takjil_id' => $takjil->id,
                    'minuman_id' => $minuman,
                    'jumlah' => 1
                ]);
            }

            // ===== KURANGI SETORAN =====
            // Jamaah::where('id', $jamaahId)->decrement('setoran', 1);

            $hasSavedData = true;
        }

        // Cek apakah ada data yang berhasil disimpan
        if (!$hasSavedData) {
            return back()
                ->withInput()
                ->with('warning', 'Tidak ada data jamaah yang dipilih untuk disimpan.');
        }

        return redirect()
            ->route('day-settings.show', $request->day_setting_id)
            ->with('success', 'Jadwal takjil berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     $takjil = Takjil::with(['makanans', 'minumans'])->findOrFail($id);

    //     return view('takjil.edit', [
    //         'takjil' => $takjil,
    //         'jamaahs' => Jamaah::all(),
    //         'makanans' => Makanan::all(),
    //         'minumans' => Minuman::all(),
    //         'daySettings' => DaySetting::orderBy('date')->get(),
    //     ]);
    // }
    // public function edit($id)
    // {
    //     // CEGAH EDIT JIKA BELUM ADA DATA TAKJIL SAMA SEKALI
    //     $existingTakjilsCount = Takjil::where('day_setting_id', $id)->count();

    //     if ($existingTakjilsCount === 0) {
    //         return redirect()
    //             ->route('day-settings.show', $id)
    //             ->with('error', 'Belum ada data takjil untuk hari ini! Silakan buat data terlebih dahulu.');
    //     }

    //     $day = DaySetting::findOrFail($id);
    //     $today = $day->date;

    //     // Ambil takjil hari ini lengkap dengan relasi makanan/minuman
    //     $takjils = Takjil::where('day_setting_id', $id)
    //         ->with(['jamaah', 'makanans.makanan', 'minumans.minuman'])
    //         ->get();

    //     // Jamaah sudah mengisi slot hari ini
    //     $filledToday = $takjils->pluck('jamaah_id')->toArray();

    //     // Ambil tanggal terakhir jamaah mendapat takjil
    //     $lastTakjil = Takjil::select(
    //         'takjils.jamaah_id',
    //         DB::raw('MAX(day_settings.date) AS last_date')
    //     )
    //         ->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
    //         ->groupBy('takjils.jamaah_id')
    //         ->pluck('last_date', 'jamaah_id');

    //     // Ambil jamaah yang masih punya setoran
    //     $all = Jamaah::where('setoran', '>', 0)
    //         ->orderBy('nama')
    //         ->get();

    //     // Filter jamaah
    //     $jamaahs = $all->filter(function ($j) use ($filledToday, $lastTakjil, $today) {

    //         // ✔ JIKA sudah terisi slot hari ini → tetap tampil
    //         if (in_array($j->id, $filledToday)) return true;

    //         // ✔ Jika belum pernah dapat takjil → boleh
    //         if (!isset($lastTakjil[$j->id])) return true;

    //         // ✔ Boleh jika sudah 7 hari dari terakhir takjil
    //         $lastDate = Carbon::parse($lastTakjil[$j->id]);
    //         $todayDate = Carbon::parse($today);

    //         return $lastDate->diffInDays($todayDate) >= 7;
    //     });


    //     $makanans = Makanan::all();
    //     $minumans = Minuman::all();

    //     return view('ramadhan.takjil.edit', compact(
    //         'day',
    //         'takjils',
    //         'jamaahs',
    //         'makanans',
    //         'minumans'
    //     ));
    // }
    // public function edit($id)
    // {
    //     // CEGAH EDIT JIKA BELUM ADA DATA TAKJIL SAMA SEKALI
    //     $existingTakjilsCount = Takjil::where('day_setting_id', $id)->count();

    //     if ($existingTakjilsCount === 0) {
    //         return redirect()
    //             ->route('day-settings.show', $id)
    //             ->with('error', 'Belum ada data takjil untuk hari ini! Silakan buat data terlebih dahulu.');
    //     }

    //     $day = DaySetting::findOrFail($id);
    //     $today = $day->date;

    //     // Ambil takjil hari ini lengkap dengan relasi makanan/minuman
    //     $takjils = Takjil::where('day_setting_id', $id)
    //         ->with(['jamaah', 'makanans.makanan', 'minumans.minuman'])
    //         ->get();

    //     // Jamaah sudah mengisi slot hari ini (yang sudah terpilih)
    //     $filledToday = $takjils->pluck('jamaah_id')->toArray();

    //     // Ambil tanggal terakhir jamaah mendapat takjil (kecuali hari ini)
    //     $lastTakjil = Takjil::select(
    //         'takjils.jamaah_id',
    //         DB::raw('MAX(day_settings.date) AS last_date')
    //     )
    //         ->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
    //         // EXCLUDE hari ini agar jamaah yang terpilih hari ini tidak dianggap "baru dapat"
    //         ->where('day_settings.date', '<>', $today)
    //         ->groupBy('takjils.jamaah_id')
    //         ->pluck('last_date', 'jamaah_id');

    //     // Ambil semua jamaah yang masih punya setoran
    //     $all = Jamaah::where('setoran', '>', 0)
    //         ->orderBy('nama')
    //         ->get();

    //     // Filter jamaah - REVISI LOGIKA
    //     $jamaahs = $all->filter(function ($j) use ($filledToday, $lastTakjil, $today) {

    //         // ✔ SELALU tampilkan jamaah yang sudah terpilih hari ini
    //         // Ini memungkinkan mereka diganti/diubah
    //         if (in_array($j->id, $filledToday)) {
    //             return true;
    //         }

    //         // ✔ Jika belum pernah dapat takjil (selain hari ini) → boleh
    //         if (!isset($lastTakjil[$j->id])) {
    //             return true;
    //         }

    //         // ✔ Boleh jika sudah 7 hari dari terakhir takjil
    //         $lastDate = Carbon::parse($lastTakjil[$j->id]);
    //         $todayDate = Carbon::parse($today);

    //         return $lastDate->diffInDays($todayDate) >= 7;
    //     });

    //     $makanans = Makanan::all();
    //     $minumans = Minuman::all();

    //     return view('ramadhan.takjil.edit', compact(
    //         'day',
    //         'takjils',
    //         'jamaahs',
    //         'makanans',
    //         'minumans'
    //     ));
    // }
    // public function edit($id)
    // {
    //     // CEGAH EDIT JIKA BELUM ADA DATA TAKJIL
    //     $existingTakjilsCount = Takjil::where('day_setting_id', $id)->count();

    //     if ($existingTakjilsCount === 0) {
    //         return redirect()
    //             ->route('day-settings.show', $id)
    //             ->with('error', 'Belum ada data takjil untuk hari ini! Silakan buat data terlebih dahulu.');
    //     }

    //     $day = DaySetting::findOrFail($id);
    //     $today = $day->date;

    //     // Ambil takjil hari ini lengkap dengan relasi
    //     $takjils = Takjil::where('day_setting_id', $id)
    //         ->with(['jamaah', 'makanans.makanan', 'minumans.minuman'])
    //         ->get();

    //     // Jamaah yang sudah terpilih hari ini (AMBIL LANGSUNG DARI RELASI)
    //     $jamaahTerpilihHariIni = $takjils->pluck('jamaah_id')
    //         ->filter() // Hapus null
    //         ->unique() // Hapus duplikat
    //         ->values()
    //         ->toArray();

    //     // =============== LOGIKA FILTER: TAMPILKAN SEMUA YANG PERLU ===============

    //     // 1. Ambil jamaah yang sudah terpilih hari ini (TERMASUK YANG SETORAN 0)
    //     $jamaahTerpilih = Jamaah::whereIn('id', $jamaahTerpilihHariIni)
    //         ->orderBy('nama')
    //         ->get();

    //     // 2. Ambil jamaah yang masih punya setoran > 0
    //     $jamaahBerSetoran = Jamaah::where('setoran', '>', 0)
    //         ->whereNotIn('id', $jamaahTerpilihHariIni) // Jangan ambil yang sudah terpilih
    //         ->orderBy('nama')
    //         ->get();

    //     // 3. Ambil tanggal terakhir jamaah mendapat takjil (EXCLUDE hari ini)
    //     $lastTakjil = Takjil::select(
    //         'takjils.jamaah_id',
    //         DB::raw('MAX(day_settings.date) AS last_date')
    //     )
    //         ->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
    //         // EXCLUDE hari ini - jamaah yang sedang di-edit tidak dihitung
    //         ->where('day_settings.date', '<>', $today)
    //         ->whereNotNull('takjils.jamaah_id')
    //         ->groupBy('takjils.jamaah_id')
    //         ->pluck('last_date', 'jamaah_id');

    //     // 4. Filter jamaahBerSetoran berdasarkan 7 hari
    //     $jamaahEligible = $jamaahBerSetoran->filter(function ($jamaah) use ($lastTakjil, $today) {
    //         // Jika belum pernah dapat takjil (selain hari ini) → eligible
    //         if (!isset($lastTakjil[$jamaah->id])) {
    //             return true;
    //         }

    //         // Cek apakah sudah 7 hari dari terakhir takjil
    //         $lastDate = Carbon::parse($lastTakjil[$jamaah->id]);
    //         $todayDate = Carbon::parse($today);

    //         return $todayDate->diffInDays($lastDate) >= 7;
    //     });

    //     // 5. Gabungkan: jamaah yang sudah terpilih + jamaah eligible
    //     $jamaahs = $jamaahTerpilih->merge($jamaahEligible);

    //     // 6. Siapkan data untuk view
    //     $makanans = Makanan::all();
    //     $minumans = Minuman::all();

    //     return view('ramadhan.takjil.edit', compact(
    //         'day',
    //         'takjils',
    //         'jamaahs',
    //         'makanans',
    //         'minumans',
    //         'jamaahTerpilihHariIni'
    //     ));
    // }
    // public function edit($id)
    // {
    //     // CEGAH EDIT JIKA BELUM ADA DATA TAKJIL
    //     $existingTakjilsCount = Takjil::where('day_setting_id', $id)->count();

    //     if ($existingTakjilsCount === 0) {
    //         return redirect()
    //             ->route('day-settings.show', $id)
    //             ->with('error', 'Belum ada data takjil untuk hari ini! Silakan buat data terlebih dahulu.');
    //     }

    //     $day = DaySetting::findOrFail($id);
    //     $today = $day->date;

    //     // Ambil takjil hari ini lengkap dengan relasi
    //     $takjils = Takjil::where('day_setting_id', $id)
    //         ->with(['jamaah', 'makanans.makanan', 'minumans.minuman'])
    //         ->get();

    //     // Jamaah yang sudah terpilih hari ini (AMBIL LANGSUNG DARI RELASI)
    //     $jamaahTerpilihHariIni = $takjils->pluck('jamaah_id')
    //         ->filter() // Hapus null
    //         ->unique() // Hapus duplikat
    //         ->values()
    //         ->toArray();

    //     // =============== LOGIKA FILTER YANG BARU ===============

    //     // 1. Ambil semua jamaah untuk filtering
    //     $allJamaahs = Jamaah::orderBy('nama')->get();

    //     // 2. Ambil tanggal terakhir jamaah mendapat takjil (EXCLUDE hari ini)
    //     $lastTakjil = Takjil::select(
    //         'takjils.jamaah_id',
    //         DB::raw('MAX(day_settings.date) AS last_date')
    //     )
    //         ->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
    //         // EXCLUDE hari ini - jamaah yang sedang di-edit tidak dihitung
    //         ->where('day_settings.date', '<>', $today)
    //         ->whereNotNull('takjils.jamaah_id')
    //         ->groupBy('takjils.jamaah_id')
    //         ->pluck('last_date', 'jamaah_id');

    //     // 3. Filter jamaah berdasarkan logika setoran
    //     $jamaahs = $allJamaahs->filter(function ($jamaah) use (
    //         $jamaahTerpilihHariIni,
    //         $lastTakjil,
    //         $today
    //     ) {
    //         // JAMA YANG SUDAH TERPILIH HARI INI → SELALU DITAMPILKAN
    //         if (in_array($jamaah->id, $jamaahTerpilihHariIni)) {
    //             return true;
    //         }

    //         // LOGIKA BERDASARKAN SETORAN:

    //         // JAMA SETORAN = 1
    //         if ($jamaah->setoran == 1) {
    //             // Jika sudah pernah dapat jadwal (selain hari ini), tidak boleh dipilih lagi
    //             if (isset($lastTakjil[$jamaah->id])) {
    //                 return false;
    //             }
    //             // Jika belum pernah dapat jadwal, boleh dipilih
    //             return true;
    //         }

    //         // JAMA SETORAN = 2
    //         if ($jamaah->setoran == 2) {
    //             // Belum pernah dapat jadwal → OK
    //             if (!isset($lastTakjil[$jamaah->id])) {
    //                 return true;
    //             }

    //             // Sudah pernah dapat jadwal, cek jeda 7 hari
    //             $lastDate = Carbon::parse($lastTakjil[$jamaah->id]);
    //             $todayDate = Carbon::parse($today);
    //             $jarak = $todayDate->diffInDays($lastDate);

    //             return $jarak >= 7;
    //         }

    //         // Setoran selain 1 atau 2, tidak ditampilkan
    //         return false;
    //     })->sortBy('nama'); // Urutkan berdasarkan nama

    //     // 4. Siapkan data untuk view
    //     $makanans = Makanan::all();
    //     $minumans = Minuman::all();

    //     return view('ramadhan.takjil.edit', compact(
    //         'day',
    //         'takjils',
    //         'jamaahs',
    //         'makanans',
    //         'minumans',
    //         'jamaahTerpilihHariIni'
    //     ));
    // }
    // public function edit($id)
    // {
    //     // CEGAH EDIT jika belum ada data
    //     if (!Takjil::where('day_setting_id', $id)->exists()) {
    //         return redirect()
    //             ->route('day-settings.show', $id)
    //             ->with('error', 'Belum ada data takjil untuk hari ini! Silakan buat data terlebih dahulu.');
    //     }

    //     $day = DaySetting::findOrFail($id);

    //     // Ambil takjil hari ini dengan relasi
    //     $takjils = Takjil::where('day_setting_id', $id)
    //         ->with(['jamaah', 'makanans.makanan', 'minumans.minuman'])
    //         ->get();

    //     // Jamaah yang sudah terpilih hari ini (untuk JavaScript)
    //     $jamaahTerpilihHariIni = $takjils->pluck('jamaah_id')
    //         ->filter()
    //         ->unique()
    //         ->values()
    //         ->toArray();

    //     // Get semua jamaah (untuk edit, tampilkan semua dengan flag eligibility)
    //     $allJamaahData = $this->getEligibleJamaahs($day->date, [], true);

    //     // Format untuk Blade
    //     $jamaahs = $allJamaahData->map(function ($item) use ($jamaahTerpilihHariIni) {
    //         $j = $item['jamaah'];
    //         $isSelectedToday = in_array($j->id, $jamaahTerpilihHariIni);

    //         // Hitung hari sejak terakhir (kecuali hari ini jika selected)
    //         $daysSinceLast = null;
    //         if ($item['last_date'] && !$isSelectedToday) {
    //             $lastDate = Carbon::parse($item['last_date']);
    //             $today = Carbon::today();
    //             $daysSinceLast = $today->diffInDays($lastDate);
    //         }

    //         // TAMPILKAN SISA SETORAN (untuk yang belum terpilih)
    //         // Untuk yang sudah terpilih, tampilkan sisa+1 karena dia sudah terpilih hari ini
    //         $displaySetoran = $isSelectedToday ? $item['sisa_setoran'] + 1 : $item['sisa_setoran'];

    //         // Pastikan tidak minus
    //         if ($displaySetoran < 0) {
    //             $displaySetoran = 0;
    //         }

    //         return (object) [
    //             'id' => $j->id,
    //             'nama' => $j->nama,
    //             'alamat' => $j->alamat ?? '',
    //             'ekonomi' => $j->ekonomi ?? '',
    //             'notes' => $j->notes ?? '',
    //             'keterangan' => $j->keterangan ?? '',
    //             'setoran' => $displaySetoran, // Yang ditampilkan di blade
    //             // Info tambahan untuk debugging jika perlu
    //             '_is_selected_today' => $isSelectedToday,
    //             '_is_eligible' => $item['is_eligible'],
    //             '_total_setoran' => $j->setoran,
    //             '_sudah_dapat' => $item['takjil_count'],
    //             '_sisa_asli' => $item['sisa_setoran'],
    //             '_last_date' => $item['last_date'],
    //             '_days_since_last' => $daysSinceLast,
    //             '_priority' => $item['priority_score']
    //         ];
    //     });

    //     $makanans = Makanan::all();
    //     $minumans = Minuman::all();

    //     return view('ramadhan.takjil.edit', compact(
    //         'day',
    //         'takjils',
    //         'jamaahs',
    //         'makanans',
    //         'minumans',
    //         'jamaahTerpilihHariIni' // Nama variable sesuai blade
    //     ));
    // }
    public function edit($id)
    {
        // CEGAH EDIT jika belum ada data
        if (!Takjil::where('day_setting_id', $id)->exists()) {
            return redirect()
                ->route('day-settings.show', $id)
                ->with('error', 'Belum ada data takjil untuk hari ini! Silakan buat data terlebih dahulu.');
        }

        $day = DaySetting::findOrFail($id);
        $targetDate = $day->date;

        // Ambil takjil hari ini dengan relasi
        $takjils = Takjil::where('day_setting_id', $id)
            ->with(['jamaah', 'makanans.makanan', 'minumans.minuman'])
            ->get();

        // Jamaah yang sudah terpilih hari ini (untuk JavaScript)
        $jamaahTerpilihHariIni = $takjils->pluck('jamaah_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Get semua jamaah (untuk edit, tampilkan semua dengan flag eligibility)
        $allJamaahData = $this->getEligibleJamaahs($targetDate, [], true);

        // Format untuk Blade
        $jamaahs = $allJamaahData->map(function ($item) use ($targetDate, $jamaahTerpilihHariIni) {
            $j = $item['jamaah'];
            $isSelectedToday = in_array($j->id, $jamaahTerpilihHariIni);

            // Gunakan function getJamaahScheduleInfo untuk konsistensi
            $scheduleInfo = $this->getJamaahScheduleInfo($j, $targetDate);

            // TAMPILKAN SISA SETORAN (untuk yang belum terpilih)
            // Untuk yang sudah terpilih, tampilkan sisa+1 karena dia sudah terpilih hari ini
            $displaySetoran = $isSelectedToday ?
                $scheduleInfo['sisa_setoran'] + 1 :
                $scheduleInfo['sisa_setoran'];

            // Pastikan tidak minus
            if ($displaySetoran < 0) {
                $displaySetoran = 0;
            }

            return (object) [
                'id' => $j->id,
                'nama' => $j->nama,
                'alamat' => $j->alamat ?? '',
                'ekonomi' => $j->ekonomi ?? '',
                'notes' => $j->notes ?? '',
                'keterangan' => $j->keterangan ?? '',
                'setoran' => $displaySetoran, // Yang ditampilkan di blade

                // Info tambahan dari scheduleInfo
                '_is_selected_today' => $isSelectedToday,
                '_is_eligible' => $item['is_eligible'],
                '_total_setoran' => $j->setoran,
                '_sudah_dapat' => $scheduleInfo['takjil_count'],
                '_sisa_asli' => $scheduleInfo['sisa_setoran'],
                '_last_date' => $scheduleInfo['last_date'],
                '_last_date_formatted' => $scheduleInfo['last_date_formatted'],
                '_days_since_last' => $scheduleInfo['days_since_last'],
                '_is_less_than_7_days' => $scheduleInfo['is_less_than_7_days'],
                '_has_reached_limit' => $scheduleInfo['has_reached_limit'],
                '_priority' => $item['priority_score']
            ];
        });

        $makanans = Makanan::all();
        $minumans = Minuman::all();

        return view('ramadhan.takjil.edit', compact(
            'day',
            'takjils',
            'jamaahs',
            'makanans',
            'minumans',
            'jamaahTerpilihHariIni' // Nama variable sesuai blade
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $takjil = Takjil::findOrFail($id);

    //     $request->validate([
    //         'jamaah_id' => 'required|exists:jamaahs,id',
    //         'day_setting_id' => 'required|exists:day_settings,id',

    //         'makanan.*.id' => 'nullable|exists:makanans,id',
    //         'makanan.*.jumlah' => 'nullable|integer|min:1',

    //         'minuman.*.id' => 'nullable|exists:minumans,id',
    //         'minuman.*.jumlah' => 'nullable|integer|min:1',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $takjil->update([
    //             'jamaah_id' => $request->jamaah_id,
    //             'day_setting_id' => $request->day_setting_id,
    //             'tanggal_hijriyah' => $request->tanggal_hijriyah,
    //             'keterangan' => json_encode($request->keterangan),
    //         ]);

    //         // Hapus detail lama
    //         $takjil->makanans()->delete();
    //         $takjil->minumans()->delete();

    //         // Insert makanan baru
    //         if ($request->has('makanan')) {
    //             foreach ($request->makanan as $mkn) {
    //                 if (!empty($mkn['id'])) {
    //                     TakjilMakanan::create([
    //                         'takjil_id' => $takjil->id,
    //                         'makanan_id' => $mkn['id'],
    //                         'jumlah' => $mkn['jumlah'] ?? 1
    //                     ]);
    //                 }
    //             }
    //         }

    //         // Insert minuman baru
    //         if ($request->has('minuman')) {
    //             foreach ($request->minuman as $mnm) {
    //                 if (!empty($mnm['id'])) {
    //                     TakjilMinuman::create([
    //                         'takjil_id' => $takjil->id,
    //                         'minuman_id' => $mnm['id'],
    //                         'jumlah' => $mnm['jumlah'] ?? 1
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->route('ramadhan.takjil.index')->with('success', 'Data takjil berhasil diperbarui.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Error: ' . $e->getMessage());
    //     }
    // }
    // public function update(Request $request, $id)
    // {
    //     $takjil = Takjil::findOrFail($id);

    //     $request->validate([
    //         'jamaah_id' => 'required|exists:jamaahs,id',
    //         'tanggal_hijriyah' => 'nullable|string',
    //         'makanan_id' => 'nullable|exists:makanans,id',
    //         'minuman_id' => 'nullable|exists:minumans,id',
    //         'keterangan' => 'nullable|string',
    //     ]);

    //     $daySettingId = $takjil->day_setting_id;
    //     $newJamaahId = $request->jamaah_id;
    //     $oldJamaahId = $takjil->jamaah_id;

    //     // ============================================
    //     // CEK DUPLIKASI JIKA JAMA'AH DIGANTI
    //     // ============================================
    //     if ($oldJamaahId != $newJamaahId) {
    //         $exists = Takjil::where('jamaah_id', $newJamaahId)
    //             ->where('day_setting_id', $daySettingId)
    //             ->exists();

    //         if ($exists) {
    //             return back()->with('error', 'Jamaah tersebut sudah memiliki jadwal di hari ini.');
    //         }
    //     }

    //     // ============================================
    //     // UPDATE FIELD TAKJIL
    //     // ============================================
    //     $takjil->update([
    //         'jamaah_id'        => $newJamaahId,
    //         'tanggal_hijriyah' => $request->tanggal_hijriyah,
    //         'keterangan'       => $request->keterangan,
    //     ]);

    //     // ============================================
    //     // UPDATE RELASI MAKANAN
    //     // ============================================
    //     $takjil->makanans()->delete(); // hapus semua dulu

    //     if ($request->makanan_id) {
    //         TakjilMakanan::create([
    //             'takjil_id'  => $takjil->id,
    //             'makanan_id' => $request->makanan_id,
    //             'jumlah'     => 1
    //         ]);
    //     }

    //     // ============================================
    //     // UPDATE RELASI MINUMAN
    //     // ============================================
    //     $takjil->minumans()->delete();

    //     if ($request->minuman_id) {
    //         TakjilMinuman::create([
    //             'takjil_id'  => $takjil->id,
    //             'minuman_id' => $request->minuman_id,
    //             'jumlah'     => 1
    //         ]);
    //     }

    //     // ============================================
    //     // UPDATE SETORAN (Jika Jamaah diganti)
    //     // ============================================
    //     if ($oldJamaahId != $newJamaahId) {
    //         Jamaah::where('id', $oldJamaahId)->increment('setoran', 1);
    //         Jamaah::where('id', $newJamaahId)->decrement('setoran', 1);
    //     }

    //     return redirect()
    //         ->route('day-settings.show', $daySettingId)
    //         ->with('success', 'Jadwal takjil berhasil diperbarui.');
    // }
    // public function update(Request $request, $id)
    // {
    //     $takjil = Takjil::findOrFail($id);

    //     $request->validate([
    //         'jamaah_id' => 'required|exists:jamaahs,id',
    //         'tanggal_hijriyah' => 'nullable|string',
    //         'makanan_id' => 'nullable|exists:makanans,id',
    //         'minuman_id' => 'nullable|exists:minumans,id',
    //         'keterangan' => 'nullable|string',
    //     ]);

    //     $daySettingId = $takjil->day_setting_id;
    //     $newJamaahId = $request->jamaah_id;
    //     $oldJamaahId = $takjil->jamaah_id;

    //     $makanan = $request->makanan_id;
    //     $minuman = $request->minuman_id;

    //     // ============================================
    //     // VALIDASI MINIMAL MAKANAN ATAU MINUMAN
    //     // ============================================
    //     if (!$makanan && !$minuman) {
    //         return back()
    //             ->withInput()
    //             ->with('error', 'Wajib memilih minimal makanan atau minuman.');
    //     }

    //     // ============================================
    //     // CEK DUPLIKASI JIKA JAMA'AH DIGANTI
    //     // ============================================
    //     if ($oldJamaahId != $newJamaahId) {
    //         $exists = Takjil::where('jamaah_id', $newJamaahId)
    //             ->where('day_setting_id', $daySettingId)
    //             ->exists();

    //         if ($exists) {
    //             $jamaahName = Jamaah::find($newJamaahId)->nama ?? 'Jamaah';
    //             return back()
    //                 ->withInput()
    //                 ->with('error', "$jamaahName sudah memiliki jadwal di hari ini.");
    //         }
    //     }

    //     // ============================================
    //     // UPDATE FIELD TAKJIL
    //     // ============================================
    //     $takjil->update([
    //         'jamaah_id'        => $newJamaahId,
    //         'tanggal_hijriyah' => $request->tanggal_hijriyah,
    //         'keterangan'       => $request->keterangan,
    //     ]);

    //     // ============================================
    //     // UPDATE RELASI MAKANAN (gunakan sync untuk lebih efisien)
    //     // ============================================
    //     if ($makanan) {
    //         $takjil->makanans()->delete(); // hapus semua dulu
    //         TakjilMakanan::create([
    //             'takjil_id'  => $takjil->id,
    //             'makanan_id' => $makanan,
    //             'jumlah'     => 1
    //         ]);
    //     } else {
    //         $takjil->makanans()->delete(); // hapus jika tidak ada makanan
    //     }

    //     // ============================================
    //     // UPDATE RELASI MINUMAN
    //     // ============================================
    //     if ($minuman) {
    //         $takjil->minumans()->delete();
    //         TakjilMinuman::create([
    //             'takjil_id'  => $takjil->id,
    //             'minuman_id' => $minuman,
    //             'jumlah'     => 1
    //         ]);
    //     } else {
    //         $takjil->minumans()->delete();
    //     }

    //     // ============================================
    //     // UPDATE SETORAN (Jika Jamaah diganti)
    //     // ============================================
    //     if ($oldJamaahId != $newJamaahId) {
    //         // Kembalikan setoran jamaah lama
    //         Jamaah::where('id', $oldJamaahId)->increment('setoran', 1);

    //         // Kurangi setoran jamaah baru
    //         Jamaah::where('id', $newJamaahId)->decrement('setoran', 1);
    //     }

    //     return redirect()
    //         ->route('day-settings.show', $daySettingId)
    //         ->with('success', 'Jadwal takjil berhasil diperbarui.');
    // }
    // public function update(Request $request, $id)
    // {
    //     // $id adalah day_setting_id
    //     $daySettingId = $id;

    //     // Validasi dasar
    //     $request->validate([
    //         'day_setting_id' => 'required|exists:day_settings,id',
    //         'jamaah_id' => 'required|array',
    //         'jamaah_id.*' => 'nullable|exists:jamaahs,id',
    //         'tanggal_hijriyah' => 'required|array',
    //         'tanggal_hijriyah.*' => 'nullable|string',
    //         'makanan_id' => 'required|array',
    //         'makanan_id.*' => 'nullable|exists:makanans,id',
    //         'minuman_id' => 'required|array',
    //         'minuman_id.*' => 'nullable|exists:minumans,id',
    //         'keterangan' => 'nullable|array',
    //         'keterangan.*' => 'nullable|string',
    //     ]);

    //     // Ambil semua takjil yang sudah ada untuk day ini
    //     $existingTakjils = Takjil::where('day_setting_id', $daySettingId)
    //         ->get()
    //         ->keyBy('id');

    //     $errors = [];
    //     $processedTakjils = []; // Untuk tracking takjil yang diproses
    //     $jamaahIdsInRequest = []; // Untuk tracking jamaah_id di request

    //     // Validasi semua baris
    //     foreach ($request->jamaah_id as $index => $jamaahId) {
    //         // Skip jika jamaah_id kosong
    //         if (!$jamaahId) {
    //             continue;
    //         }

    //         // Cek duplikasi jamaah dalam request yang sama
    //         if (in_array($jamaahId, $jamaahIdsInRequest)) {
    //             $jamaahName = Jamaah::find($jamaahId)->nama ?? 'Jamaah';
    //             $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName terdaftar di lebih dari satu slot.";
    //             continue;
    //         }
    //         $jamaahIdsInRequest[] = $jamaahId;

    //         // ================= VALIDASI MINIMAL 1 =================
    //         $makanan = $request->makanan_id[$index] ?? null;
    //         $minuman = $request->minuman_id[$index] ?? null;

    //         if (!$makanan && !$minuman) {
    //             $jamaahName = Jamaah::find($jamaahId)->nama ?? 'Jamaah';
    //             $errors[] = "Slot ke-" . ($index + 1) . " ($jamaahName) wajib memilih minimal makanan atau minuman.";
    //         }

    //         // ================= CEK DUPLIKASI JIKA BUKAN UPDATE SENDIRI =================
    //         // Cari takjil yang sudah ada untuk jamaah ini di day yang sama
    //         $existingTakjilForJamaah = Takjil::where('jamaah_id', $jamaahId)
    //             ->where('day_setting_id', $daySettingId)
    //             ->first();

    //         // Jika ada takjil untuk jamaah ini, dan bukan takjil yang sedang kita edit di slot ini
    //         if ($existingTakjilForJamaah) {
    //             // Cari takjil yang seharusnya ada di slot ini (berdasarkan data lama)
    //             // Kita asumsikan slot ke-$index seharusnya berisi takjil ke-$index dari existingTakjils
    //             $existingTakjilsArray = $existingTakjils->values()->toArray();
    //             $expectedTakjilId = $existingTakjilsArray[$index]['id'] ?? null;

    //             if ($existingTakjilForJamaah->id != $expectedTakjilId) {
    //                 $jamaahName = Jamaah::find($jamaahId)->nama ?? 'Jamaah';
    //                 $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName sudah terdaftar pada hari ini.";
    //             }
    //         }
    //     }

    //     // Jika ada error
    //     if (!empty($errors)) {
    //         $errorMessage = '<ul class="mb-0"><li>' . implode('</li><li>', $errors) . '</li></ul>';
    //         return back()
    //             ->withInput()
    //             ->with('error', $errorMessage);
    //     }

    //     $hasUpdatedData = false;
    //     $updatedJamaahIds = []; // Untuk tracking perubahan setoran

    //     // Proses update per slot
    //     foreach ($request->jamaah_id as $index => $jamaahId) {
    //         // Skip jika jamaah_id kosong
    //         if (!$jamaahId) {
    //             continue;
    //         }

    //         $makanan = $request->makanan_id[$index] ?? null;
    //         $minuman = $request->minuman_id[$index] ?? null;
    //         $tanggalHijriyah = $request->tanggal_hijriyah[$index] ?? null;
    //         $keterangan = $request->keterangan[$index] ?? null;

    //         // Cari takjil yang sudah ada untuk slot ini
    //         // Menggunakan urutan berdasarkan index
    //         $existingTakjilsArray = $existingTakjils->values()->toArray();
    //         $existingTakjil = isset($existingTakjilsArray[$index])
    //             ? Takjil::find($existingTakjilsArray[$index]['id'])
    //             : null;

    //         if ($existingTakjil) {
    //             // UPDATE TAKJIL YANG SUDAH ADA
    //             $oldJamaahId = $existingTakjil->jamaah_id;

    //             // Update data takjil
    //             $existingTakjil->update([
    //                 'jamaah_id' => $jamaahId,
    //                 'tanggal_hijriyah' => $tanggalHijriyah,
    //                 'keterangan' => $keterangan
    //             ]);

    //             // Update setoran jika jamaah berubah
    //             if ($oldJamaahId != $jamaahId) {
    //                 $updatedJamaahIds[] = [
    //                     'old' => $oldJamaahId,
    //                     'new' => $jamaahId
    //                 ];
    //             }

    //             $takjil = $existingTakjil;
    //         } else {
    //             // BUAT TAKJIL BARU (jika slot kosong diisi)
    //             $takjil = Takjil::create([
    //                 'jamaah_id' => $jamaahId,
    //                 'day_setting_id' => $daySettingId,
    //                 'tanggal_hijriyah' => $tanggalHijriyah,
    //                 'keterangan' => $keterangan
    //             ]);

    //             // Kurangi setoran untuk jamaah baru
    //             $updatedJamaahIds[] = [
    //                 'old' => null,
    //                 'new' => $jamaahId
    //             ];
    //         }

    //         // ===== UPDATE MAKANAN =====
    //         $takjil->makanans()->delete();
    //         if ($makanan) {
    //             TakjilMakanan::create([
    //                 'takjil_id' => $takjil->id,
    //                 'makanan_id' => $makanan,
    //                 'jumlah' => 1
    //             ]);
    //         }

    //         // ===== UPDATE MINUMAN =====
    //         $takjil->minumans()->delete();
    //         if ($minuman) {
    //             TakjilMinuman::create([
    //                 'takjil_id' => $takjil->id,
    //                 'minuman_id' => $minuman,
    //                 'jumlah' => 1
    //             ]);
    //         }

    //         $hasUpdatedData = true;
    //     }

    //     // Proses update setoran setelah semua perubahan selesai
    //     foreach ($updatedJamaahIds as $update) {
    //         if ($update['old']) {
    //             // Kembalikan setoran jamaah lama
    //             Jamaah::where('id', $update['old'])->increment('setoran', 1);
    //         }
    //         if ($update['new']) {
    //             // Kurangi setoran jamaah baru
    //             Jamaah::where('id', $update['new'])->decrement('setoran', 1);
    //         }
    //     }

    //     // Hapus takjil yang tidak lagi digunakan (jika ada slot yang dikosongkan)
    //     // Ambil semua takjil yang masih ada di database untuk day ini
    //     $currentTakjilIds = [];
    //     foreach ($request->jamaah_id as $index => $jamaahId) {
    //         if ($jamaahId) {
    //             $existingTakjilsArray = $existingTakjils->values()->toArray();
    //             if (isset($existingTakjilsArray[$index])) {
    //                 $currentTakjilIds[] = $existingTakjilsArray[$index]['id'];
    //             }
    //         }
    //     }

    //     // Hapus takjil yang tidak ada di request (slot dikosongkan)
    //     $takjilsToDelete = Takjil::where('day_setting_id', $daySettingId)
    //         ->whereNotIn('id', $currentTakjilIds)
    //         ->get();

    //     foreach ($takjilsToDelete as $takjilToDelete) {
    //         // Kembalikan setoran sebelum menghapus
    //         Jamaah::where('id', $takjilToDelete->jamaah_id)->increment('setoran', 1);
    //         $takjilToDelete->delete();
    //     }

    //     if (!$hasUpdatedData && $takjilsToDelete->isEmpty()) {
    //         return back()
    //             ->withInput()
    //             ->with('warning', 'Tidak ada perubahan data.');
    //     }

    //     return redirect()
    //         ->route('day-settings.show', $daySettingId)
    //         ->with('success', 'Jadwal takjil berhasil diperbarui.');
    // }
    // public function update(Request $request, $id)
    // {
    //     // $id adalah day_setting_id
    //     $daySettingId = $id;
    //     $daySetting = DaySetting::findOrFail($daySettingId);
    //     $quota = $daySetting->quota;

    //     // Validasi dasar
    //     $request->validate([
    //         'day_setting_id' => 'required|exists:day_settings,id',
    //         'jamaah_id' => 'required|array',
    //         'jamaah_id.*' => 'nullable|exists:jamaahs,id',
    //         'tanggal_hijriyah' => 'required|array',
    //         'tanggal_hijriyah.*' => 'nullable|string',
    //         'makanan_id' => 'required|array',
    //         'makanan_id.*' => 'nullable|exists:makanans,id',
    //         'minuman_id' => 'required|array',
    //         'minuman_id.*' => 'nullable|exists:minumans,id',
    //         'keterangan' => 'nullable|array',
    //         'keterangan.*' => 'nullable|string',
    //     ]);

    //     // Ambil semua takjil yang sudah ada untuk day ini
    //     $existingTakjils = Takjil::where('day_setting_id', $daySettingId)
    //         ->get()
    //         ->keyBy('id');

    //     $errors = [];
    //     $jamaahIdsInRequest = []; // Untuk tracking jamaah_id di request
    //     $jamaahSlots = []; // Untuk mapping jamaah ke slot

    //     // Validasi jumlah jamaah tidak melebihi quota
    //     $jamaahCount = count(array_filter($request->jamaah_id));
    //     if ($jamaahCount > $quota) {
    //         $errors[] = "Jumlah jamaah ($jamaahCount) melebihi kuota hari ini ($quota).";
    //     }

    //     // Validasi semua baris
    //     foreach ($request->jamaah_id as $index => $jamaahId) {
    //         // Skip jika jamaah_id kosong
    //         if (!$jamaahId) {
    //             continue;
    //         }

    //         // Track jamaah dan slotnya
    //         $jamaahSlots[$index] = $jamaahId;

    //         // Cek duplikasi jamaah dalam request yang sama
    //         if (in_array($jamaahId, $jamaahIdsInRequest)) {
    //             $jamaahName = Jamaah::find($jamaahId)->nama ?? 'Jamaah';
    //             $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName terdaftar di lebih dari satu slot.";
    //             continue;
    //         }
    //         $jamaahIdsInRequest[] = $jamaahId;

    //         // ================= VALIDASI MINIMAL 1 =================
    //         $makanan = $request->makanan_id[$index] ?? null;
    //         $minuman = $request->minuman_id[$index] ?? null;

    //         if (!$makanan && !$minuman) {
    //             $jamaahName = Jamaah::find($jamaahId)->nama ?? 'Jamaah';
    //             $errors[] = "Slot ke-" . ($index + 1) . " ($jamaahName) wajib memilih minimal makanan atau minuman.";
    //         }

    //         // ================= CEK VALIDASI JAMAAH BARU =================
    //         // Cari apakah jamaah ini sudah ada di hari ini
    //         $existingTakjilForJamaah = Takjil::where('jamaah_id', $jamaahId)
    //             ->where('day_setting_id', $daySettingId)
    //             ->first();

    //         // Jika jamaah ini sudah ada di database untuk hari ini
    //         if ($existingTakjilForJamaah) {
    //             // Cari slot yang seharusnya berisi takjil ini
    //             $expectedSlot = null;
    //             foreach ($existingTakjils as $takjil) {
    //                 if ($takjil->jamaah_id == $jamaahId) {
    //                     // Cari index takjil ini di array existingTakjils
    //                     $existingArray = $existingTakjils->values()->toArray();
    //                     foreach ($existingArray as $i => $item) {
    //                         if ($item['id'] == $takjil->id) {
    //                             $expectedSlot = $i;
    //                             break;
    //                         }
    //                     }
    //                     break;
    //                 }
    //             }

    //             // Jika jamaah dipindahkan ke slot yang berbeda
    //             if ($expectedSlot !== null && $expectedSlot != $index) {
    //                 $jamaahName = Jamaah::find($jamaahId)->nama ?? 'Jamaah';
    //                 $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName sudah terdaftar di slot lain.";
    //             }
    //         } else {
    //             // ================= VALIDASI JAMAAH BARU YANG INGIN DITAMBAHKAN =================
    //             // Logika setoran untuk jamaah baru yang ingin ditambahkan
    //             $jamaah = Jamaah::find($jamaahId);

    //             if ($jamaah) {
    //                 // Cek kapan terakhir jamaah ini dapat takjil (kecuali hari ini)
    //                 $lastTakjil = Takjil::select('day_settings.date')
    //                     ->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
    //                     ->where('takjils.jamaah_id', $jamaahId)
    //                     ->where('day_settings.date', '<>', $daySetting->date)
    //                     ->orderBy('day_settings.date', 'desc')
    //                     ->first();

    //                 // JAMA SETORAN = 1
    //                 if ($jamaah->setoran == 1) {
    //                     // Jika sudah pernah dapat jadwal (selain hari ini), tidak boleh
    //                     if ($lastTakjil) {
    //                         $jamaahName = $jamaah->nama;
    //                         $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName (setoran 1) sudah pernah dapat jadwal sebelumnya.";
    //                     }
    //                 }

    //                 // JAMA SETORAN = 2
    //                 elseif ($jamaah->setoran == 2) {
    //                     // Jika pernah dapat jadwal, cek jeda 7 hari
    //                     if ($lastTakjil) {
    //                         $lastDate = Carbon::parse($lastTakjil->date);
    //                         $todayDate = Carbon::parse($daySetting->date);
    //                         $jarak = $todayDate->diffInDays($lastDate);

    //                         if ($jarak < 7) {
    //                             $jamaahName = $jamaah->nama;
    //                             $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName (setoran 2) belum mencapai jeda 7 hari dari jadwal terakhir.";
    //                         }
    //                     }
    //                 }

    //                 // Setoran selain 1 atau 2
    //                 else {
    //                     $jamaahName = $jamaah->nama;
    //                     $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName tidak memenuhi syarat setoran.";
    //                 }
    //             }
    //         }
    //     }

    //     // Jika ada error
    //     if (!empty($errors)) {
    //         $errorMessage = '<ul class="mb-0"><li>' . implode('</li><li>', $errors) . '</li></ul>';
    //         return back()
    //             ->withInput()
    //             ->with('error', $errorMessage);
    //     }

    //     $hasUpdatedData = false;

    //     // Proses update per slot berdasarkan mapping
    //     foreach ($jamaahSlots as $index => $jamaahId) {
    //         if (!$jamaahId) {
    //             continue;
    //         }

    //         $makanan = $request->makanan_id[$index] ?? null;
    //         $minuman = $request->minuman_id[$index] ?? null;
    //         $tanggalHijriyah = $request->tanggal_hijriyah[$index] ?? null;
    //         $keterangan = $request->keterangan[$index] ?? null;

    //         // Cari takjil yang sudah ada untuk jamaah ini di hari ini
    //         $existingTakjilForJamaah = Takjil::where('jamaah_id', $jamaahId)
    //             ->where('day_setting_id', $daySettingId)
    //             ->first();

    //         if ($existingTakjilForJamaah) {
    //             // UPDATE TAKJIL YANG SUDAH ADA
    //             $existingTakjilForJamaah->update([
    //                 'tanggal_hijriyah' => $tanggalHijriyah,
    //                 'keterangan' => $keterangan
    //             ]);

    //             $takjil = $existingTakjilForJamaah;
    //         } else {
    //             // BUAT TAKJIL BARU (untuk jamaah yang baru ditambahkan)
    //             $takjil = Takjil::create([
    //                 'jamaah_id' => $jamaahId,
    //                 'day_setting_id' => $daySettingId,
    //                 'tanggal_hijriyah' => $tanggalHijriyah,
    //                 'keterangan' => $keterangan
    //             ]);
    //         }

    //         // ===== UPDATE MAKANAN =====
    //         $takjil->makanans()->delete();
    //         if ($makanan) {
    //             TakjilMakanan::create([
    //                 'takjil_id' => $takjil->id,
    //                 'makanan_id' => $makanan,
    //                 'jumlah' => 1
    //             ]);
    //         }

    //         // ===== UPDATE MINUMAN =====
    //         $takjil->minumans()->delete();
    //         if ($minuman) {
    //             TakjilMinuman::create([
    //                 'takjil_id' => $takjil->id,
    //                 'minuman_id' => $minuman,
    //                 'jumlah' => 1
    //             ]);
    //         }

    //         $hasUpdatedData = true;
    //     }

    //     // Hapus takjil yang tidak ada di request (jamaah yang dihapus)
    //     $jamaahIdsToKeep = array_values(array_filter($request->jamaah_id));

    //     $takjilsToDelete = Takjil::where('day_setting_id', $daySettingId)
    //         ->whereNotIn('jamaah_id', $jamaahIdsToKeep)
    //         ->get();

    //     foreach ($takjilsToDelete as $takjilToDelete) {
    //         $takjilToDelete->delete();
    //         $hasUpdatedData = true;
    //     }

    //     if (!$hasUpdatedData) {
    //         return back()
    //             ->withInput()
    //             ->with('warning', 'Tidak ada perubahan data.');
    //     }

    //     return redirect()
    //         ->route('day-settings.show', $daySettingId)
    //         ->with('success', 'Jadwal takjil berhasil diperbarui.');
    // }
    public function update(Request $request, $id)
    {
        // $id adalah day_setting_id
        $daySettingId = $id;
        $daySetting = DaySetting::findOrFail($daySettingId);
        $quota = $daySetting->quota;

        // Validasi dasar
        $request->validate([
            'day_setting_id' => 'required|exists:day_settings,id',
            'jamaah_id' => 'required|array',
            'jamaah_id.*' => 'nullable|exists:jamaahs,id',
            'tanggal_hijriyah' => 'required|array',
            'tanggal_hijriyah.*' => 'nullable|string',
            'makanan_id' => 'required|array',
            'makanan_id.*' => 'nullable|exists:makanans,id',
            'minuman_id' => 'required|array',
            'minuman_id.*' => 'nullable|exists:minumans,id',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
        ]);

        // Ambil semua takjil yang sudah ada untuk day ini
        $existingTakjils = Takjil::where('day_setting_id', $daySettingId)
            ->get()
            ->keyBy('id');

        $errors = [];
        $jamaahIdsInRequest = []; // Untuk tracking jamaah_id di request
        $jamaahSlots = []; // Untuk mapping jamaah ke slot

        // Validasi jumlah jamaah tidak melebihi quota
        $jamaahCount = count(array_filter($request->jamaah_id));
        if ($jamaahCount > $quota) {
            $errors[] = "Jumlah jamaah ($jamaahCount) melebihi kuota hari ini ($quota).";
        }

        // Validasi semua baris
        foreach ($request->jamaah_id as $index => $jamaahId) {
            // Skip jika jamaah_id kosong
            if (!$jamaahId) {
                continue;
            }

            // Track jamaah dan slotnya
            $jamaahSlots[$index] = $jamaahId;

            // Cek duplikasi jamaah dalam request yang sama
            if (in_array($jamaahId, $jamaahIdsInRequest)) {
                $jamaahName = Jamaah::find($jamaahId)->nama ?? 'Jamaah';
                $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName terdaftar di lebih dari satu slot.";
                continue;
            }
            $jamaahIdsInRequest[] = $jamaahId;

            // ================= VALIDASI MINIMAL 1 =================
            $makanan = $request->makanan_id[$index] ?? null;
            $minuman = $request->minuman_id[$index] ?? null;

            if (!$makanan && !$minuman) {
                $jamaahName = Jamaah::find($jamaahId)->nama ?? 'Jamaah';
                $errors[] = "Slot ke-" . ($index + 1) . " ($jamaahName) wajib memilih minimal makanan atau minuman.";
            }

            // ================= VALIDASI SETORAN TIDAK MELEBIHI BATAS =================
            $jamaah = Jamaah::find($jamaahId);
            if ($jamaah) {
                // Hitung total takjil yang sudah didapat jamaah (tidak termasuk hari ini jika belum ada)
                $totalTakjil = Takjil::where('jamaah_id', $jamaahId)
                    ->whereHas('daySetting', function ($query) use ($daySetting) {
                        $query->where('date', '<', $daySetting->date);
                    })
                    ->count();

                // Cek apakah jamaah ini sudah ada di hari ini
                $alreadyInToday = Takjil::where('jamaah_id', $jamaahId)
                    ->where('day_setting_id', $daySettingId)
                    ->exists();

                // Jika jamaah belum ada di hari ini, maka kita akan menambahkannya
                // Jadi total takjil akan bertambah 1
                if (!$alreadyInToday) {
                    $totalTakjil += 1;
                }

                // Validasi jika melebihi setoran
                if ($totalTakjil > $jamaah->setoran) {
                    $jamaahName = $jamaah->nama;
                    $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName sudah mencapai batas setoran "
                        . "({$jamaah->setoran}x). Sudah mendapatkan {$totalTakjil} jadwal.";
                }
            }

            // ================= CEK VALIDASI JAMAAH BARU =================
            // Cari apakah jamaah ini sudah ada di hari ini
            $existingTakjilForJamaah = Takjil::where('jamaah_id', $jamaahId)
                ->where('day_setting_id', $daySettingId)
                ->first();

            // Jika jamaah ini sudah ada di database untuk hari ini
            if ($existingTakjilForJamaah) {
                // Cari slot yang seharusnya berisi takjil ini
                $expectedSlot = null;
                foreach ($existingTakjils as $takjil) {
                    if ($takjil->jamaah_id == $jamaahId) {
                        // Cari index takjil ini di array existingTakjils
                        $existingArray = $existingTakjils->values()->toArray();
                        foreach ($existingArray as $i => $item) {
                            if ($item['id'] == $takjil->id) {
                                $expectedSlot = $i;
                                break;
                            }
                        }
                        break;
                    }
                }

                // Jika jamaah dipindahkan ke slot yang berbeda
                if ($expectedSlot !== null && $expectedSlot != $index) {
                    $jamaahName = Jamaah::find($jamaahId)->nama ?? 'Jamaah';
                    $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName sudah terdaftar di slot lain.";
                }
            } else {
                // ================= VALIDASI JAMAAH BARU YANG INGIN DITAMBAHKAN =================
                // Logika setoran untuk jamaah baru yang ingin ditambahkan
                $jamaah = Jamaah::find($jamaahId);

                if ($jamaah) {
                    // Cek kapan terakhir jamaah ini dapat takjil (kecuali hari ini)
                    $lastTakjil = Takjil::select('day_settings.date')
                        ->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
                        ->where('takjils.jamaah_id', $jamaahId)
                        ->where('day_settings.date', '<>', $daySetting->date)
                        ->orderBy('day_settings.date', 'desc')
                        ->first();

                    // JAMA SETORAN = 1
                    if ($jamaah->setoran == 1) {
                        // Jika sudah pernah dapat jadwal (selain hari ini), tidak boleh
                        if ($lastTakjil) {
                            $jamaahName = $jamaah->nama;
                            $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName (setoran 1) sudah pernah dapat jadwal sebelumnya.";
                        }
                    }

                    // JAMA SETORAN = 2
                    elseif ($jamaah->setoran == 2) {
                        // Jika pernah dapat jadwal, cek jeda 7 hari
                        if ($lastTakjil) {
                            $lastDate = Carbon::parse($lastTakjil->date);
                            $todayDate = Carbon::parse($daySetting->date);
                            $jarak = $todayDate->diffInDays($lastDate);

                            if ($jarak < 7) {
                                $jamaahName = $jamaah->nama;
                                $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName (setoran 2) belum mencapai jeda 7 hari dari jadwal terakhir.";
                            }
                        }
                    }

                    // Setoran lebih dari 2 - gunakan rule 7 hari
                    elseif ($jamaah->setoran > 2) {
                        // Jika pernah dapat jadwal, cek jeda 7 hari
                        if ($lastTakjil) {
                            $lastDate = Carbon::parse($lastTakjil->date);
                            $todayDate = Carbon::parse($daySetting->date);
                            $jarak = $todayDate->diffInDays($lastDate);

                            if ($jarak < 7) {
                                $jamaahName = $jamaah->nama;
                                $errors[] = "Slot ke-" . ($index + 1) . ": $jamaahName belum mencapai jeda minimal 7 hari dari jadwal terakhir (masih $jarak hari).";
                            }
                        }
                    }
                }
            }
        }

        // Jika ada error
        if (!empty($errors)) {
            $errorMessage = '<ul class="mb-0"><li>' . implode('</li><li>', $errors) . '</li></ul>';
            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }

        $hasUpdatedData = false;

        // Proses update per slot berdasarkan mapping
        foreach ($jamaahSlots as $index => $jamaahId) {
            if (!$jamaahId) {
                continue;
            }

            $makanan = $request->makanan_id[$index] ?? null;
            $minuman = $request->minuman_id[$index] ?? null;
            $tanggalHijriyah = $request->tanggal_hijriyah[$index] ?? null;
            $keterangan = $request->keterangan[$index] ?? null;

            // Cari takjil yang sudah ada untuk jamaah ini di hari ini
            $existingTakjilForJamaah = Takjil::where('jamaah_id', $jamaahId)
                ->where('day_setting_id', $daySettingId)
                ->first();

            if ($existingTakjilForJamaah) {
                // UPDATE TAKJIL YANG SUDAH ADA
                $existingTakjilForJamaah->update([
                    'tanggal_hijriyah' => $tanggalHijriyah,
                    'keterangan' => $keterangan
                ]);

                $takjil = $existingTakjilForJamaah;
            } else {
                // BUAT TAKJIL BARU (untuk jamaah yang baru ditambahkan)
                $takjil = Takjil::create([
                    'jamaah_id' => $jamaahId,
                    'day_setting_id' => $daySettingId,
                    'tanggal_hijriyah' => $tanggalHijriyah,
                    'keterangan' => $keterangan
                ]);
            }

            // ===== UPDATE MAKANAN =====
            $takjil->makanans()->delete();
            if ($makanan) {
                TakjilMakanan::create([
                    'takjil_id' => $takjil->id,
                    'makanan_id' => $makanan,
                    'jumlah' => 1
                ]);
            }

            // ===== UPDATE MINUMAN =====
            $takjil->minumans()->delete();
            if ($minuman) {
                TakjilMinuman::create([
                    'takjil_id' => $takjil->id,
                    'minuman_id' => $minuman,
                    'jumlah' => 1
                ]);
            }

            $hasUpdatedData = true;
        }

        // Hapus takjil yang tidak ada di request (jamaah yang dihapus)
        $jamaahIdsToKeep = array_values(array_filter($request->jamaah_id));

        $takjilsToDelete = Takjil::where('day_setting_id', $daySettingId)
            ->whereNotIn('jamaah_id', $jamaahIdsToKeep)
            ->get();

        foreach ($takjilsToDelete as $takjilToDelete) {
            $takjilToDelete->delete();
            $hasUpdatedData = true;
        }

        if (!$hasUpdatedData) {
            return back()
                ->withInput()
                ->with('warning', 'Tidak ada perubahan data.');
        }

        return redirect()
            ->route('day-settings.show', $daySettingId)
            ->with('success', 'Jadwal takjil berhasil diperbarui.');
    }

    /**
     * Delete semua takjil untuk hari tertentu
     */
    // public function destroy(string $id)
    // {
    //     $takjil = Takjil::findOrFail($id);
    //     $takjil->delete();

    //     return back()->with('success', 'Data berhasil dihapus.');
    // }
    // public function destroy($id) //! error
    // {
    //     $daySettingId = $id;

    //     // Hapus semua takjil untuk day ini
    //     $takjils = Takjil::where('day_setting_id', $daySettingId)->get();

    //     foreach ($takjils as $takjil) {
    //         $takjil->makanans()->delete();
    //         $takjil->minumans()->delete();
    //         $takjil->delete();
    //     }

    //     return redirect()
    //         ->route('day-settings.show', $daySettingId)
    //         ->with('success', 'Semua jadwal takjil untuk hari ini telah dihapus.');
    // }
    public function destroy(string $id)
    {
        try {
            $takjil = Takjil::findOrFail($id);

            $jamaahId = $takjil->jamaah_id;
            $jamaah = $takjil->jamaah;

            $takjil->makanans()->delete();
            $takjil->minumans()->delete();
            $takjil->delete();

            return back()->with('success', 'Data Dia berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    private function isJamaahEligibleForDate(Jamaah $jamaah, string $targetDate): bool
    {
        // Ambil semua takjil jamaah dengan relasi daySetting
        $takjils = $jamaah->takjils()
            ->with('daySetting')
            ->get()
            ->filter(function ($takjil) {
                return $takjil->daySetting && $takjil->daySetting->date;
            });

        $takjilCount = $takjils->count();

        // 1. Cek apakah sudah mencapai batas setoran
        if ($takjilCount >= $jamaah->setoran) {
            return false;
        }

        // 2. Jika sudah pernah dapat jadwal, cek jeda minimal 7 hari
        if ($takjilCount > 0) {
            // Cari tanggal terakhir dapat takjil
            $lastDate = null;
            foreach ($takjils as $takjil) {
                if ($takjil->daySetting) {
                    $date = Carbon::parse($takjil->daySetting->date);
                    if (!$lastDate || $date->greaterThan($lastDate)) {
                        $lastDate = $date;
                    }
                }
            }

            if ($lastDate) {
                $targetCarbonDate = Carbon::parse($targetDate);
                $jarakHari = $targetCarbonDate->diffInDays($lastDate);

                if ($jarakHari < 7) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Hitung skor prioritas untuk sorting
     */
    private function calculateJamaahPriority(Jamaah $jamaah, string $targetDate): int
    {
        $score = 0;

        // 1. Prioritas utama: setoran lebih besar (maks 5x multiplier)
        $score += $jamaah->setoran * 100;

        // 2. Hitung takjil yang sudah didapat
        $takjils = $jamaah->takjils()
            ->with('daySetting')
            ->get()
            ->filter(fn($t) => $t->daySetting && $t->daySetting->date);

        $takjilCount = $takjils->count();
        $sisaSetoran = $jamaah->setoran - $takjilCount;

        // 3. Prioritas tinggi: belum pernah dapat jadwal sama sekali
        if ($takjilCount === 0) {
            $score += 200;
        }

        // 4. Prioritas: sisa setoran lebih banyak
        $score += $sisaSetoran * 50;

        // 5. Prioritas: lama tidak dapat jadwal
        if ($takjilCount > 0) {
            $lastDate = null;
            foreach ($takjils as $takjil) {
                if ($takjil->daySetting) {
                    $date = Carbon::parse($takjil->daySetting->date);
                    if (!$lastDate || $date->greaterThan($lastDate)) {
                        $lastDate = $date;
                    }
                }
            }

            if ($lastDate) {
                $targetCarbonDate = Carbon::parse($targetDate);
                $hariSejakTerakhir = $targetCarbonDate->diffInDays($lastDate);

                // Bonus maksimal 100 poin untuk yang lama tidak dapat
                $score += min($hariSejakTerakhir * 5, 100);
            }
        }

        return $score;
    }

    /**
     * Get jamaah yang eligible untuk tanggal tertentu
     */
    private function getEligibleJamaahs(string $targetDate, array $excludeIds = [], bool $forEdit = false)
    {
        // Query dasar: jamaah dengan setoran > 0
        $query = Jamaah::where('setoran', '>', 0)
            ->when(!empty($excludeIds), function ($q) use ($excludeIds) {
                return $q->whereNotIn('id', $excludeIds);
            });

        // Untuk edit, kita perlu tampilkan SEMUA jamaah (baik eligible maupun tidak)
        // Tapi beri flag eligibility
        if ($forEdit) {
            $jamaahs = $query->orderBy('nama')->get();

            return $jamaahs->map(function ($jamaah) use ($targetDate) {
                $isEligible = $this->isJamaahEligibleForDate($jamaah, $targetDate);
                $takjilCount = $jamaah->takjils()
                    ->whereHas('daySetting')
                    ->count();
                $sisaSetoran = $jamaah->setoran - $takjilCount;

                // Cari tanggal terakhir dapat takjil
                $lastTakjil = $jamaah->takjils()
                    ->with('daySetting')
                    ->get()
                    ->filter(fn($t) => $t->daySetting && $t->daySetting->date)
                    ->sortByDesc(fn($t) => $t->daySetting->date)
                    ->first();

                return [
                    'jamaah' => $jamaah,
                    'is_eligible' => $isEligible,
                    'takjil_count' => $takjilCount,
                    'sisa_setoran' => $sisaSetoran,
                    'last_date' => $lastTakjil?->daySetting?->date,
                    'priority_score' => $this->calculateJamaahPriority($jamaah, $targetDate)
                ];
            })->sortByDesc('priority_score')->values();
        }

        // Untuk create: hanya ambil yang eligible
        $jamaahs = $query->get();

        return $jamaahs->filter(function ($jamaah) use ($targetDate) {
            return $this->isJamaahEligibleForDate($jamaah, $targetDate);
        })->map(function ($jamaah) use ($targetDate) {
            $takjilCount = $jamaah->takjils()
                ->whereHas('daySetting')
                ->count();
            $sisaSetoran = $jamaah->setoran - $takjilCount;

            // Cari tanggal terakhir dapat takjil
            $lastTakjil = $jamaah->takjils()
                ->with('daySetting')
                ->get()
                ->filter(fn($t) => $t->daySetting && $t->daySetting->date)
                ->sortByDesc(fn($t) => $t->daySetting->date)
                ->first();

            return [
                'jamaah' => $jamaah,
                'takjil_count' => $takjilCount,
                'sisa_setoran' => $sisaSetoran,
                'last_date' => $lastTakjil?->daySetting?->date,
                'priority_score' => $this->calculateJamaahPriority($jamaah, $targetDate)
            ];
        })->sortByDesc('priority_score')->values();
    }

    private function getJamaahScheduleInfo(Jamaah $jamaah, string $targetDate): array
    {
        // Ambil semua takjil jamaah dengan relasi daySetting
        $takjils = $jamaah->takjils()
            ->with('daySetting')
            ->get()
            ->filter(function ($takjil) {
                return $takjil->daySetting && $takjil->daySetting->date;
            });

        $takjilCount = $takjils->count();

        // Cari tanggal terakhir dapat takjil
        $lastDate = null;
        foreach ($takjils as $takjil) {
            if ($takjil->daySetting) {
                $date = Carbon::parse($takjil->daySetting->date);
                if (!$lastDate || $date->greaterThan($lastDate)) {
                    $lastDate = $date;
                }
            }
        }

        // Hitung hari sejak terakhir
        $daysSinceLast = null;
        if ($lastDate) {
            $targetCarbonDate = Carbon::parse($targetDate);
            $daysSinceLast = $targetCarbonDate->diffInDays($lastDate);
        }

        // Cek apakah kurang dari 7 hari
        $isLessThan7Days = $daysSinceLast !== null && $daysSinceLast < 7;

        return [
            'takjil_count' => $takjilCount,
            'last_date' => $lastDate?->toDateString(),
            'last_date_formatted' => $lastDate?->format('d M Y'),
            'days_since_last' => $daysSinceLast,
            'is_less_than_7_days' => $isLessThan7Days,
            'has_reached_limit' => $takjilCount >= $jamaah->setoran,
            'sisa_setoran' => $jamaah->setoran - $takjilCount,
        ];
    }
}
