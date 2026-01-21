<?php

namespace App\Http\Controllers\Ramadhan;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Takjil;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = Jamaah::query()->orderBy('id');

        // Filter berdasarkan nama atau alamat
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('alamat', 'LIKE', "%{$search}%");
            });
        }

        $jamaah = $query->paginate(15);

        // Jika request AJAX, kembalikan partial view
        if ($request->ajax()) {
            return view('ramadhan.guests.partials.table', compact('jamaah'));
        }

        return view('ramadhan.Guests.index', compact('jamaah'));
    }

    public function detailTakjil($uuid)
    {
        // Cari jamaah berdasarkan UUID
        $jamaah = Jamaah::where('uuid', $uuid)->firstOrFail();

        // Ambil semua takjil jamaah ini dengan relasi lengkap
        $takjils = Takjil::where('jamaah_id', $jamaah->id)
            ->with([
                'daySetting',
                'makanans.makanan',
                'minumans.minuman'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Format data untuk view
        $formattedTakjils = [];
        $now = now(); // Tanggal sekarang

        foreach ($takjils as $takjil) {
            // Tentukan jenis takjil
            $jenisTakjil = '';
            $hasMakanan = $takjil->makanans->count() > 0;
            $hasMinuman = $takjil->minumans->count() > 0;

            if ($hasMakanan && $hasMinuman) {
                $jenisTakjil = 'makanan dan minuman';
            } elseif ($hasMakanan) {
                $jenisTakjil = 'makanan';
            } elseif ($hasMinuman) {
                $jenisTakjil = 'minuman';
            }

            // Format makanan
            $makananItems = [];
            foreach ($takjil->makanans as $takjilMakanan) {
                $makananItems[] = [
                    'nama' => $takjilMakanan->makanan->nama,
                    'jumlah' => $takjilMakanan->jumlah,
                    'keterangan' => $takjilMakanan->makanan->keterangan ?? '-'
                ];
            }

            // Format minuman
            $minumanItems = [];
            foreach ($takjil->minumans as $takjilMinuman) {
                $minumanItems[] = [
                    'nama' => $takjilMinuman->minuman->nama,
                    'jumlah' => $takjilMinuman->jumlah,
                    'keterangan' => $takjilMinuman->minuman->keterangan ?? '-'
                ];
            }

            // Tentukan status jadwal
            $statusJadwal = '';
            $isTerlewat = false;

            if ($takjil->daySetting && $takjil->daySetting->date) {
                $tanggalTakjil = Carbon::parse($takjil->daySetting->date);

                // Jika tanggal takjil sudah lewat dari hari ini
                if ($tanggalTakjil->isPast()) {
                    $statusJadwal = 'Terlewat';
                    $isTerlewat = true;
                }
                // Jika tanggal takjil adalah hari ini
                elseif ($tanggalTakjil->isToday()) {
                    $statusJadwal = 'Hari Ini';
                }
                // Jika tanggal takjil adalah besok
                elseif ($tanggalTakjil->isTomorrow()) {
                    $statusJadwal = 'Besok';
                }
                // Jika tanggal takjil masih akan datang
                else {
                    $statusJadwal = 'Akan Datang';
                }
            } else {
                $statusJadwal = 'Tanpa Tanggal';
            }

            $formattedTakjils[] = [
                'id' => $takjil->id,
                'tanggal_masehi' => $takjil->daySetting->date ?? null,
                'tanggal_hijriyah' => $takjil->tanggal_hijriyah,
                'jenis_takjil' => $jenisTakjil,
                'keterangan' => $takjil->keterangan,
                'makanan' => $makananItems,
                'minuman' => $minumanItems,
                'total_makanan' => count($makananItems),
                'total_minuman' => count($minumanItems),
                'status_jadwal' => $statusJadwal,
                'is_terlewat' => $isTerlewat,
                'is_hari_ini' => ($statusJadwal === 'Hari Ini'),
                'is_besok' => ($statusJadwal === 'Besok'),
            ];
        }

        return view('ramadhan.Guests.detail', [
            'jamaah' => $jamaah,
            'takjils' => $formattedTakjils,
            'total_jadwal' => $takjils->count(),
            'total_terlewat' => collect($formattedTakjils)->where('is_terlewat', true)->count(),
            'total_hari_ini' => collect($formattedTakjils)->where('is_hari_ini', true)->count(),
            'total_besok' => collect($formattedTakjils)->where('is_besok', true)->count(),
        ]);
    }
}
