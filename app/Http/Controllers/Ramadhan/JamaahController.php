<?php

namespace App\Http\Controllers\Ramadhan;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class JamaahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $q = $request->query('q');

    //     $query = Jamaah::query()->orderBy('id', 'desc');

    //     if ($q) {
    //         $query->where(function ($sub) use ($q) {
    //             $sub->where('nama', 'like', "%{$q}%")
    //                 ->orWhere('alamat', 'like', "%{$q}%")
    //                 ->orWhere('setoran', 'like', "%{$q}%")
    //                 ->orWhere('keterangan', 'like', "%{$q}%")
    //                 ->orWhere('notes', 'like', "%{$q}%");
    //         });
    //     }

    //     $jamaahs = $query->paginate(25)->withQueryString();

    //     // Jika AJAX → kirim partial
    //     if ($request->ajax()) {
    //         return view('ramadhan.jamaahs.partials.table', compact('jamaahs'))->render();
    //     }

    //     return view('ramadhan.jamaahs.index', compact('jamaahs', 'q'));
    // }
    public function index(Request $request)
    {
        $q = $request->query('q');

        // Gunakan eager loading dengan join ke day_settings
        $query = Jamaah::query()
            ->with(['takjils' => function ($q) {
                $q->join('day_settings', 'takjils.day_setting_id', '=', 'day_settings.id')
                    ->orderBy('day_settings.date')
                    ->select('takjils.jamaah_id', 'day_settings.date');
            }])
            ->withCount('takjils')
            ->orderBy('id', 'desc');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%")
                    ->orWhere('setoran', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%")
                    ->orWhere('notes', 'like', "%{$q}%");
            });
        }

        $jamaahs = $query->paginate(25)->withQueryString();

        // Format data untuk view
        $jamaahs->each(function ($jamaah) {
            $jamaah->formatted_jadwal_dates = $jamaah->takjils
                ->map(function ($takjil) {
                    // Pastikan properti date ada
                    return isset($takjil->date) ? Carbon::parse($takjil->date)->translatedFormat('d M Y') : null;
                })
                ->filter() // Hapus null values
                ->toArray();

            $jamaah->is_complete = $jamaah->takjils_count >= $jamaah->setoran;
        });

        // Jika AJAX → kirim partial
        if ($request->ajax()) {
            return view('ramadhan.jamaahs.partials.table', compact('jamaahs'))->render();
        }

        return view('ramadhan.jamaahs.index', compact('jamaahs', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ramadhan.jamaahs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'ekonomi' => ['required', 'in:Mampu,Kurang Mampu'],
            'setoran' => ['required', 'integer', 'min:1'],
            'keterangan' => ['required', 'in:Makanan dan Minuman,Makanan,Minuman'],
            'notes' => ['nullable', 'string'],
        ]);

        Jamaah::create($validated);

        return redirect()->route('jamaahs.index')
            ->with('success', 'Jama`ah berhasil ditambahkan.');
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
    public function edit(Jamaah $jamaah)
    {
        return view('ramadhan.jamaahs.edit', compact('jamaah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jamaah $jamaah)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'ekonomi' => ['required', 'in:Mampu,Kurang Mampu'],
            'setoran' => ['required', 'integer', 'min:1'],
            'keterangan' => ['required', 'in:Makanan dan Minuman,Makanan,Minuman'],
            'notes' => ['nullable', 'string'],
        ]);

        $jamaah->update($validated);

        return redirect()->route('jamaahs.index')
            ->with('success', 'Jama`ah berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jamaah $jamaah)
    {
        $jamaah->delete();

        return redirect()->route('jamaahs.index')
            ->with('success', 'Jama`ah berhasil dihapus.');
    }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls,csv'
    //     ]);

    //     // Load file ke array
    //     $data = Excel::toArray([], $request->file('file'));
    //     $rows = $data[0]; // sheet pertama

    //     if (count($rows) == 0) {
    //         return back()->with('error', 'File kosong atau tidak valid.');
    //     }

    //     // Lewati header (baris 1)
    //     foreach (array_slice($rows, 1) as $row) {

    //         // Jika baris kosong, skip
    //         if (!isset($row[0]) || $row[0] === null) {
    //             continue;
    //         }

    //         // Set default values
    //         $nama       = trim($row[0]);
    //         $alamat     = trim($row[1] ?? '');
    //         $ekonomi    = trim($row[2] ?? 'Mampu');
    //         $keterangan = trim($row[4] ?? '');
    //         $notes      = trim($row[5] ?? '');

    //         // Validasi manual tiap baris
    //         if (!in_array($ekonomi, ['Mampu', 'Kurang Mampu'])) {
    //             return back()->with('error', "Nilai ekonomi tidak valid pada baris: {$nama}");
    //         }

    //         // Hitung setoran otomatis
    //         $setoran = ($ekonomi == 'Mampu') ? 2 : 1;

    //         Jamaah::create([
    //             // 'uuid'    => (string) Str::uuid(),
    //             'nama'      => $nama,
    //             'alamat'    => $alamat,
    //             'ekonomi'   => $ekonomi,
    //             'setoran'   => $setoran,
    //             'keterangan' => $keterangan,
    //             'notes'     => $notes,
    //         ]);
    //     }

    //     return back()->with('success', 'Data jamaah berhasil di-import.');
    // }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // Load file ke array
        $data = Excel::toArray([], $request->file('file'));
        $rows = $data[0]; // sheet pertama

        if (count($rows) == 0) {
            return back()->with('error', 'File kosong atau tidak valid.');
        }

        // Lewati header (baris 1)
        foreach (array_slice($rows, 1) as $index => $row) {
            $currentRow = $index + 2;

            // Jika baris kosong, skip
            if (!isset($row[0]) || $row[0] === null) {
                continue;
            }

            // Set default values
            $nama       = trim($row[0]);
            $alamat     = trim($row[1] ?? '');
            $ekonomi    = trim($row[2] ?? 'Mampu');
            // $setoran    = trim($row[3] ?? '');
            $keterangan = trim($row[4] ?? '');
            $notes      = trim($row[5] ?? '');

            // Normalisasi ekonomi - konversi ke lowercase dulu, lalu sesuaikan
            $ekonomiLower = strtolower($ekonomi);

            if ($ekonomiLower === 'mampu') {
                $ekonomi = 'Mampu';
            } elseif ($ekonomiLower === 'kurang mampu' || $ekonomiLower === 'kurangmampu') {
                $ekonomi = 'Kurang Mampu';
            } else {
                return back()->with('error', "Nilai ekonomi tidak valid pada baris {$currentRow}: '{$row[2]}'. Harus 'Mampu' atau 'Kurang Mampu'.");
            }

            // Hitung setoran otomatis
            $setoran = ($ekonomi == 'Mampu') ? 2 : 1;

            Jamaah::create([
                'nama'       => $nama,
                'alamat'     => $alamat,
                'ekonomi'    => $ekonomi,
                'setoran'    => !empty($setoran) ? $setoran : null,
                // 'setoran'    => $setoran,
                'keterangan' => !empty($keterangan) ? $keterangan : null,
                'notes'      => !empty($notes) ? $notes : null,
            ]);
        }

        return back()->with('success', 'Data jamaah berhasil di-import.');
    }

    /**
     * DOWNLOAD TEMPLATE EXCEL
     */
}
