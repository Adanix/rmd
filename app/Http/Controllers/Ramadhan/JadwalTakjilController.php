<?php

namespace App\Http\Controllers\Ramadhan;

use App\Http\Controllers\Controller;
use App\Models\DaySetting;
use App\Models\Jamaah;
use App\Models\Takjil;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JadwalTakjilController extends Controller
{
    // public function index(Request $request)
    // {
    //     // Ambil parameter pencarian
    //     $date = $request->query('date');
    //     $search = $request->query('search');

    //     // Query untuk DaySetting dengan eager loading
    //     $query = DaySetting::with(['takjils.jamaah', 'takjils.makanans.makanan', 'takjils.minumans.minuman'])
    //         ->orderBy('date', 'asc');

    //     // Filter berdasarkan tanggal jika ada
    //     if ($date) {
    //         $query->whereDate('date', $date);
    //     }

    //     // Ambil semua data day setting yang sudah diisi jadwal
    //     $daySettings = $query->get();

    //     // Kelompokkan takjil berdasarkan tanggal
    //     $groupedTakjils = [];
    //     $totalTakjilPerHari = [];

    //     foreach ($daySettings as $day) {
    //         $tanggal = Carbon::parse($day->date)->translatedFormat('d F Y');
    //         $hijriyah = $day->date; // Anda bisa konversi ke Hijriyah jika diperlukan

    //         // Jika ada pencarian, filter takjil berdasarkan nama jamaah
    //         $takjils = $day->takjils;

    //         if ($search) {
    //             $takjils = $takjils->filter(function ($takjil) use ($search) {
    //                 return stripos($takjil->jamaah->nama, $search) !== false ||
    //                     stripos($takjil->jamaah->alamat, $search) !== false;
    //             });
    //         }

    //         if ($takjils->count() > 0) {
    //             // Format data takjil
    //             $formattedTakjils = [];

    //             foreach ($takjils as $takjil) {
    //                 // Tentukan keterangan (makanan/minuman/keduanya)
    //                 $keterangan = '';

    //                 if ($takjil->makanans->count() > 0 && $takjil->minumans->count() > 0) {
    //                     $keterangan = 'Makanan & Minuman';
    //                 } elseif ($takjil->makanans->count() > 0) {
    //                     $keterangan = 'Makanan';
    //                 } elseif ($takjil->minumans->count() > 0) {
    //                     $keterangan = 'Minuman';
    //                 } else {
    //                     $keterangan = 'Belum ditentukan';
    //                 }

    //                 // Format detail makanan/minuman
    //                 $detailMakanan = $takjil->makanans->map(function ($takjilMakanan) {
    //                     return $takjilMakanan->makanan->nama . ' (' . $takjilMakanan->jumlah . ')';
    //                 })->implode(', ');

    //                 $detailMinuman = $takjil->minumans->map(function ($takjilMinuman) {
    //                     return $takjilMinuman->minuman->nama . ' (' . $takjilMinuman->jumlah . ')';
    //                 })->implode(', ');

    //                 $formattedTakjils[] = [
    //                     'id' => $takjil->id,
    //                     'jamaah_id' => $takjil->jamaah_id,
    //                     'jamaah_uuid' => $takjil->jamaah->uuid,
    //                     'nama_jamaah' => $takjil->jamaah->nama,
    //                     'alamat_jamaah' => $takjil->jamaah->alamat,
    //                     'keterangan' => $keterangan,
    //                     'detail_makanan' => $detailMakanan,
    //                     'detail_minuman' => $detailMinuman,
    //                     'keterangan_lain' => $takjil->keterangan,
    //                     'tanggal_hijriyah' => $takjil->tanggal_hijriyah,
    //                 ];
    //             }

    //             $groupedTakjils[$tanggal] = [
    //                 'date' => $day->date,
    //                 'date_formatted' => $tanggal,
    //                 'quota' => $day->quota,
    //                 'takjils' => $formattedTakjils,
    //                 'count' => count($formattedTakjils),
    //             ];

    //             $totalTakjilPerHari[$tanggal] = count($formattedTakjils);
    //         }
    //     }

    //     // Jika AJAX request, kembalikan partial view
    //     if ($request->ajax()) {
    //         return view('ramadhan.jadwal-takjil.partials.table', compact('groupedTakjils'))->render();
    //     }

    //     return view('ramadhan.jadwalTakjils.index', compact('groupedTakjils', 'totalTakjilPerHari', 'date', 'search'));
    // }
    // public function index(Request $request)
    // {
    //     // Ambil parameter pencarian
    //     $date = $request->query('date');
    //     $search = $request->query('search');

    //     // Query untuk DaySetting dengan eager loading
    //     $query = DaySetting::with([
    //         'takjils.jamaah',
    //         'takjils.makanans.makanan',
    //         'takjils.minumans.minuman'
    //     ])
    //         ->has('takjils') // Hanya ambil day setting yang punya takjil
    //         ->orderBy('date', 'asc');

    //     // Filter berdasarkan tanggal jika ada
    //     if ($date) {
    //         $query->whereDate('date', $date);
    //     }

    //     // Ambil data
    //     $daySettings = $query->get();

    //     // Kelompokkan takjil berdasarkan tanggal
    //     $groupedTakjils = [];

    //     foreach ($daySettings as $day) {
    //         $tanggal = Carbon::parse($day->date)->translatedFormat('d F Y');

    //         // Filter takjil berdasarkan pencarian
    //         $takjils = $day->takjils;

    //         if ($search) {
    //             $takjils = $takjils->filter(function ($takjil) use ($search) {
    //                 $nama = strtolower($takjil->jamaah->nama ?? '');
    //                 $alamat = strtolower($takjil->jamaah->alamat ?? '');
    //                 $searchTerm = strtolower($search);

    //                 return str_contains($nama, $searchTerm) ||
    //                     str_contains($alamat, $searchTerm);
    //             });
    //         }

    //         // Jika ada takjil setelah difilter
    //         if ($takjils->count() > 0) {
    //             $formattedTakjils = [];

    //             foreach ($takjils as $takjil) {
    //                 // Tentukan keterangan
    //                 $keterangan = $this->getKeteranganTakjil($takjil);

    //                 // Format detail makanan/minuman
    //                 $detailMakanan = $takjil->makanans->map(function ($takjilMakanan) {
    //                     return $takjilMakanan->makanan->nama . ' (' . $takjilMakanan->jumlah . ')';
    //                 })->implode(', ');

    //                 $detailMinuman = $takjil->minumans->map(function ($takjilMinuman) {
    //                     return $takjilMinuman->minuman->nama . ' (' . $takjilMinuman->jumlah . ')';
    //                 })->implode(', ');

    //                 $formattedTakjils[] = [
    //                     'id' => $takjil->id,
    //                     'jamaah_id' => $takjil->jamaah_id,
    //                     'jamaah_uuid' => $takjil->jamaah->uuid ?? '',
    //                     'nama_jamaah' => $takjil->jamaah->nama ?? 'Tidak diketahui',
    //                     'alamat_jamaah' => $takjil->jamaah->alamat ?? '',
    //                     'keterangan' => $keterangan,
    //                     'detail_makanan' => $detailMakanan,
    //                     'detail_minuman' => $detailMinuman,
    //                     'keterangan_lain' => $takjil->keterangan,
    //                     'tanggal_hijriyah' => $takjil->tanggal_hijriyah,
    //                 ];
    //             }

    //             $groupedTakjils[$tanggal] = [
    //                 'date' => $day->date,
    //                 'date_formatted' => $tanggal,
    //                 'quota' => $day->quota,
    //                 'takjils' => $formattedTakjils,
    //                 'count' => count($formattedTakjils),
    //             ];
    //         }
    //     }

    //     // Jika AJAX request, kembalikan partial view
    //     if ($request->ajax() || $request->query('ajax')) {
    //         return view('ramadhan.jadwalTakjils.partials.table', compact('groupedTakjils', 'search', 'date'))->render();
    //     }

    //     return view('ramadhan.jadwalTakjils.index', compact('groupedTakjils', 'search', 'date'));
    // }
    public function index(Request $request)
    {
        // Ambil parameter pencarian
        $date = $request->query('date');
        $search = $request->query('search');

        // Query untuk DaySetting dengan eager loading
        $query = DaySetting::with([
            'takjils.jamaah',
            'takjils.makanans.makanan',
            'takjils.minumans.minuman'
        ])
            ->has('takjils') // Hanya ambil day setting yang punya takjil
            ->orderBy('date', 'asc');

        // Filter berdasarkan tanggal jika ada
        if ($date) {
            $query->whereDate('date', $date);
        }

        // Ambil data
        $daySettings = $query->get();

        // Kelompokkan takjil berdasarkan tanggal
        $groupedTakjils = [];

        foreach ($daySettings as $day) {
            $tanggal = Carbon::parse($day->date)->translatedFormat('d F Y');

            // Filter takjil berdasarkan pencarian
            $takjils = $day->takjils;

            if ($search) {
                $takjils = $takjils->filter(function ($takjil) use ($search) {
                    $nama = strtolower($takjil->jamaah->nama ?? '');
                    $alamat = strtolower($takjil->jamaah->alamat ?? '');
                    $searchTerm = strtolower($search);

                    return str_contains($nama, $searchTerm) ||
                        str_contains($alamat, $searchTerm);
                });
            }

            // Jika ada takjil setelah difilter
            if ($takjils->count() > 0) {
                $formattedTakjils = [];

                foreach ($takjils as $takjil) {
                    // Tentukan keterangan
                    $keterangan = $this->getKeteranganTakjil($takjil);

                    // Format detail makanan - HILANGKAN ANGKA (1)
                    $detailMakanan = $takjil->makanans->map(function ($takjilMakanan) {
                        return $takjilMakanan->makanan->nama; // Hanya nama, tanpa jumlah
                    })->implode(', ');

                    // Format detail minuman - HILANGKAN ANGKA (1)
                    $detailMinuman = $takjil->minumans->map(function ($takjilMinuman) {
                        return $takjilMinuman->minuman->nama; // Hanya nama, tanpa jumlah
                    })->implode(', ');

                    // Ambil keterangan dari makanan dan minuman
                    $keteranganMakanan = $takjil->makanans->map(function ($takjilMakanan) {
                        return $takjilMakanan->makanan->keterangan;
                    })->filter()->implode(', ');

                    $keteranganMinuman = $takjil->minumans->map(function ($takjilMinuman) {
                        return $takjilMinuman->minuman->keterangan;
                    })->filter()->implode(', ');

                    // Gabungkan keterangan makanan dan minuman
                    $keteranganGabungan = '';
                    if ($keteranganMakanan && $keteranganMinuman) {
                        $keteranganGabungan = "Makanan: {$keteranganMakanan} | Minuman: {$keteranganMinuman}";
                    } elseif ($keteranganMakanan) {
                        $keteranganGabungan = $keteranganMakanan;
                    } elseif ($keteranganMinuman) {
                        $keteranganGabungan = $keteranganMinuman;
                    }

                    $formattedTakjils[] = [
                        'id' => $takjil->id,
                        'jamaah_id' => $takjil->jamaah_id,
                        'jamaah_uuid' => $takjil->jamaah->uuid ?? '',
                        'nama_jamaah' => $takjil->jamaah->nama ?? 'Tidak diketahui',
                        'alamat_jamaah' => $takjil->jamaah->alamat ?? '',
                        'keterangan' => $keterangan,
                        'detail_makanan' => $detailMakanan,
                        'detail_minuman' => $detailMinuman,
                        'keterangan_makanan' => $keteranganMakanan,
                        'keterangan_minuman' => $keteranganMinuman,
                        'keterangan_gabungan' => $keteranganGabungan,
                        'keterangan_lain' => $takjil->keterangan,
                        'tanggal_hijriyah' => $takjil->tanggal_hijriyah,
                    ];
                }

                $groupedTakjils[$tanggal] = [
                    'date' => $day->date,
                    'date_formatted' => $tanggal,
                    'quota' => $day->quota,
                    'takjils' => $formattedTakjils,
                    'count' => count($formattedTakjils),
                ];
            }
        }

        // Jika AJAX request, kembalikan partial view
        if ($request->ajax() || $request->query('ajax')) {
            return view('ramadhan.jadwalTakjils.partials.table', compact('groupedTakjils', 'search', 'date'))->render();
        }

        return view('ramadhan.jadwalTakjils.index', compact('groupedTakjils', 'search', 'date'));
    }

    /**
     * Tentukan keterangan takjil berdasarkan makanan/minuman
     */
    private function getKeteranganTakjil($takjil)
    {
        $makananCount = $takjil->makanans->count();
        $minumanCount = $takjil->minumans->count();

        if ($makananCount > 0 && $minumanCount > 0) {
            return 'Makanan & Minuman';
        } elseif ($makananCount > 0) {
            return 'Makanan';
        } elseif ($minumanCount > 0) {
            return 'Minuman';
        } else {
            return 'Belum ditentukan';
        }
    }

    /**
     * Export ke PDF
     */
    public function exportPdf(Request $request)
    {
        $data = $this->getExportData($request);

        $pdf = Pdf::loadView('ramadhan.jadwalTakjils.exports.pdf', $data)
            ->setPaper('legal', 'portrait') // UKURAN KERTAS LEGAL
            ->setOption('defaultFont', 'Arial');

        $filename = 'jadwal-takjil-' . date('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export ke Excel (CSV format sederhana)
     */
    // public function exportExcel(Request $request)
    // {
    //     $data = $this->getExportData($request);

    //     $filename = 'jadwal-takjil-' . date('Y-m-d-H-i-s') . '.csv';

    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    //     ];

    //     return new StreamedResponse(function () use ($data) {
    //         $output = fopen('php://output', 'w');

    //         // Header utama
    //         fputcsv($output, ['JADWAL TAKJIL RAMADHAN']);
    //         fputcsv($output, ['Dicetak pada: ' . $data['exportDate']]);
    //         fputcsv($output, []);

    //         // Filter info
    //         if ($data['search'] || $data['date']) {
    //             fputcsv($output, ['Filter yang digunakan:']);
    //             if ($data['search']) {
    //                 fputcsv($output, ['Pencarian:', $data['search']]);
    //             }
    //             if ($data['date']) {
    //                 fputcsv($output, ['Tanggal:', Carbon::parse($data['date'])->translatedFormat('d F Y')]);
    //             }
    //             fputcsv($output, []);
    //         }

    //         // Ringkasan
    //         fputcsv($output, ['RINGKASAN']);
    //         fputcsv($output, ['Total Hari dengan Jadwal:', $data['totalHari']]);
    //         fputcsv($output, ['Total Jamaah Terjadwal:', $data['totalJamaah']]);
    //         fputcsv($output, []);

    //         // Data per hari
    //         foreach ($data['groupedTakjils'] as $tanggal => $dayData) {
    //             fputcsv($output, []);
    //             fputcsv($output, ['================================================================']);
    //             fputcsv($output, [$tanggal . ' - ' . $dayData['count'] . ' dari ' . $dayData['quota'] . ' kuota']);
    //             fputcsv($output, ['================================================================']);

    //             // Header tabel
    //             fputcsv($output, ['No', 'Nama Jamaah', 'Alamat', 'Jenis', 'Detail Makanan', 'Detail Minuman', 'Keterangan']);

    //             // Data
    //             $no = 1;
    //             foreach ($dayData['takjils'] as $takjil) {
    //                 fputcsv($output, [
    //                     $no++,
    //                     $takjil['nama_jamaah'],
    //                     $takjil['alamat_jamaah'],
    //                     $takjil['keterangan'],
    //                     $takjil['detail_makanan'],
    //                     $takjil['detail_minuman'],
    //                     $takjil['keterangan_lain'] ?? ''
    //                 ]);
    //             }

    //             fputcsv($output, []);
    //             fputcsv($output, ['Total untuk hari ini:', '', '', $dayData['count'] . ' jamaah']);
    //         }

    //         fclose($output);
    //     }, 200, $headers);
    // }
    // public function exportExcel(Request $request)
    // {
    //     $data = $this->getExportData($request);

    //     $filename = 'jadwal-takjil-' . date('Y-m-d-H-i-s') . '.csv';

    //     $headers = [
    //         'Content-Type' => 'text/csv; charset=utf-8',
    //         'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    //     ];

    //     return new StreamedResponse(function () use ($data) {
    //         $output = fopen('php://output', 'w');

    //         // Tambahkan BOM untuk UTF-8
    //         fwrite($output, "\xEF\xBB\xBF");

    //         // Header utama
    //         fputcsv($output, ['JADWAL TAKJIL RAMADHAN']);
    //         fputcsv($output, ['Dicetak pada: ' . $data['exportDate']]);
    //         fputcsv($output, []);

    //         // Filter info
    //         if ($data['search'] || $data['date']) {
    //             fputcsv($output, ['Filter yang digunakan:']);
    //             if ($data['search']) {
    //                 fputcsv($output, ['Pencarian:', $data['search']]);
    //             }
    //             if ($data['date']) {
    //                 fputcsv($output, ['Tanggal:', Carbon::parse($data['date'])->translatedFormat('d F Y')]);
    //             }
    //             fputcsv($output, []);
    //         }

    //         // Ringkasan
    //         fputcsv($output, ['RINGKASAN']);
    //         fputcsv($output, ['Total Hari dengan Jadwal:', $data['totalHari']]);
    //         fputcsv($output, ['Total Jamaah Terjadwal:', $data['totalJamaah']]);

    //         // Tambahkan info setoran
    //         fputcsv($output, ['Total Setoran Semua Jamaah:', 'Rp ' . number_format($data['totalSetoran'], 0, ',', '.')]);
    //         fputcsv($output, []);

    //         // Data per hari
    //         foreach ($data['groupedTakjils'] as $tanggal => $dayData) {
    //             fputcsv($output, []);
    //             fputcsv($output, ['================================================================']);
    //             fputcsv($output, [$tanggal . ' - ' . $dayData['count'] . ' dari ' . $dayData['quota'] . ' kuota']);
    //             fputcsv($output, ['================================================================']);

    //             // Header tabel - TAMBAHKAN UUID, SETORAN, dan INFO JAMAAH LAINNYA
    //             fputcsv($output, [
    //                 'No',
    //                 'UUID Jamaah',
    //                 'Nama Jamaah',
    //                 'Alamat',
    //                 'Ekonomi',
    //                 'Setoran (Rp)',
    //                 'Keterangan Jamaah',
    //                 'Jenis Takjil',
    //                 'Makanan',
    //                 'Keterangan Makanan',
    //                 'Minuman',
    //                 'Keterangan Minuman',
    //                 'Catatan Takjil',
    //                 'Tanggal Hijriyah',
    //                 'Status' // Tambahan: status setoran vs jadwal
    //             ]);

    //             // Data
    //             $no = 1;
    //             foreach ($dayData['takjils'] as $takjil) {
    //                 // Hitung status: apakah jamaah sudah memenuhi setoran?
    //                 $status = '';
    //                 if (isset($takjil['setoran_jamaah']) && $takjil['total_takjil'] > 0) {
    //                     if ($takjil['total_takjil'] >= $takjil['setoran_jamaah']) {
    //                         $status = 'LUNAS';
    //                     } else {
    //                         $sisa = $takjil['setoran_jamaah'] - $takjil['total_takjil'];
    //                         $status = 'KURANG ' . $sisa . ' hari';
    //                     }
    //                 }

    //                 fputcsv($output, [
    //                     $no++,
    //                     $takjil['jamaah_uuid'] ?? '',
    //                     $takjil['nama_jamaah'] ?? '',
    //                     $takjil['alamat_jamaah'] ?? '',
    //                     $takjil['ekonomi_jamaah'] ?? '',
    //                     isset($takjil['setoran_jamaah']) ? number_format($takjil['setoran_jamaah'], 0, ',', '.') : '0',
    //                     $takjil['keterangan_jamaah'] ?? '',
    //                     $takjil['keterangan'] ?? '',
    //                     $takjil['detail_makanan'] ?? '',
    //                     $takjil['keterangan_makanan'] ?? '',
    //                     $takjil['detail_minuman'] ?? '',
    //                     $takjil['keterangan_minuman'] ?? '',
    //                     $takjil['keterangan_lain'] ?? '',
    //                     $takjil['tanggal_hijriyah'] ?? '',
    //                     $status
    //                 ]);
    //             }

    //             fputcsv($output, []);
    //             fputcsv($output, ['Total untuk hari ini:', '', '', '', '', '', '', $dayData['count'] . ' jamaah']);

    //             // Tambahkan separator antar hari
    //             fputcsv($output, ['----------------------------------------------------------------']);
    //         }

    //         // Tambahkan summary semua hari
    //         fputcsv($output, []);
    //         fputcsv($output, ['TOTAL SEMUA HARI:']);
    //         fputcsv($output, ['Jumlah Hari:', $data['totalHari']]);
    //         fputcsv($output, ['Jumlah Jamaah:', $data['totalJamaah']]);
    //         fputcsv($output, ['Total Setoran:', 'Rp ' . number_format($data['totalSetoran'], 0, ',', '.')]);

    //         // Rata-rata per hari
    //         if ($data['totalHari'] > 0) {
    //             $rataRata = round($data['totalJamaah'] / $data['totalHari'], 2);
    //             fputcsv($output, ['Rata-rata Jamaah per Hari:', $rataRata]);
    //         }

    //         // Persentase kuota terisi (jika ada kuota)
    //         if ($data['totalKuota'] > 0) {
    //             $persentase = round(($data['totalJamaah'] / $data['totalKuota']) * 100, 2);
    //             fputcsv($output, ['Persentase Kuota Terisi:', $persentase . '%']);
    //         }

    //         fclose($output);
    //     }, 200, $headers);
    // }
    public function exportExcel(Request $request)
    {
        $data = $this->getExportData($request);

        $filename = 'jadwal-takjil-' . date('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return new StreamedResponse(function () use ($data) {
            $output = fopen('php://output', 'w');

            // Tambahkan BOM untuk UTF-8
            fwrite($output, "\xEF\xBB\xBF");

            // Header utama
            fputcsv($output, ['JADWAL TAKJIL RAMADHAN']);
            fputcsv($output, ['Dicetak pada: ' . $data['exportDate']]);
            fputcsv($output, []);

            // Filter info
            if ($data['search'] || $data['date']) {
                fputcsv($output, ['Filter yang digunakan:']);
                if ($data['search']) {
                    fputcsv($output, ['Pencarian:', $data['search']]);
                }
                if ($data['date']) {
                    fputcsv($output, ['Tanggal:', Carbon::parse($data['date'])->translatedFormat('d F Y')]);
                }
                fputcsv($output, []);
            }

            // Ringkasan
            fputcsv($output, ['RINGKASAN']);
            fputcsv($output, ['Total Hari dengan Jadwal:', $data['totalHari']]);
            fputcsv($output, ['Total Jamaah Terjadwal:', $data['totalJamaah']]);
            fputcsv($output, []);

            // Header tabel utama - KOLOM SESUAI PERMINTAAN
            fputcsv($output, [
                'No',
                'UUID Jamaah',
                'Nama Jamaah',
                'Alamat',
                'Jenis Takjil',
                'Makanan',
                'Keterangan Makanan',
                'Minuman',
                'Keterangan Minuman',
                'Tanggal Hijriyah',
                'Tanggal'  // Tanggal Masehi
            ]);

            // Data semua takjil (tidak dikelompokkan per hari)
            $no = 1;
            $currentDate = null;

            foreach ($data['groupedTakjils'] as $tanggal => $dayData) {
                foreach ($dayData['takjils'] as $takjil) {
                    // Tambahkan separator jika tanggal berbeda
                    if ($currentDate !== $tanggal) {
                        if ($currentDate !== null) {
                            fputcsv($output, ['---', '---', '---', '---', '---', '---', '---', '---', '---', '---', '---']);
                        }
                        $currentDate = $tanggal;
                    }

                    fputcsv($output, [
                        $no++,
                        $takjil['jamaah_uuid'] ?? '',
                        $takjil['nama_jamaah'] ?? '',
                        $takjil['alamat_jamaah'] ?? '',
                        $takjil['keterangan'] ?? '',
                        $takjil['detail_makanan'] ?? '',
                        $takjil['keterangan_makanan'] ?? '',
                        $takjil['detail_minuman'] ?? '',
                        $takjil['keterangan_minuman'] ?? '',
                        $takjil['tanggal_hijriyah'] ?? '',
                        $tanggal  // Tanggal Masehi dari tanggal yang sudah diformat
                    ]);
                }
            }

            // Tambahkan summary akhir
            fputcsv($output, []);
            fputcsv($output, ['TOTAL:']);
            fputcsv($output, ['Jumlah Data:', $no - 1]);
            fputcsv($output, ['Jumlah Hari:', $data['totalHari']]);

            fclose($output);
        }, 200, $headers);
    }

    /**
     * Ambil data untuk export
     */
    // private function getExportData(Request $request)
    // {
    //     $date = $request->query('date');
    //     $search = $request->query('search');

    //     $query = DaySetting::with([
    //         'takjils.jamaah',
    //         'takjils.makanans.makanan',
    //         'takjils.minumans.minuman'
    //     ])
    //         ->has('takjils')
    //         ->orderBy('date', 'asc');

    //     if ($date) {
    //         $query->whereDate('date', $date);
    //     }

    //     $daySettings = $query->get();

    //     $groupedTakjils = [];
    //     $totalJamaah = 0;
    //     $totalHari = 0;

    //     foreach ($daySettings as $day) {
    //         $tanggal = Carbon::parse($day->date)->translatedFormat('d F Y');

    //         $takjils = $day->takjils;

    //         if ($search) {
    //             $takjils = $takjils->filter(function ($takjil) use ($search) {
    //                 $nama = strtolower($takjil->jamaah->nama ?? '');
    //                 $alamat = strtolower($takjil->jamaah->alamat ?? '');
    //                 $searchTerm = strtolower($search);

    //                 return str_contains($nama, $searchTerm) ||
    //                     str_contains($alamat, $searchTerm);
    //             });
    //         }

    //         if ($takjils->count() > 0) {
    //             $formattedTakjils = [];

    //             foreach ($takjils as $takjil) {
    //                 $keterangan = $this->getKeteranganTakjil($takjil);

    //                 // HILANGKAN ANGKA (1) dari nama makanan/minuman
    //                 $detailMakanan = $takjil->makanans->map(function ($takjilMakanan) {
    //                     return $takjilMakanan->makanan->nama; // Hanya nama
    //                 })->implode(', ');

    //                 $detailMinuman = $takjil->minumans->map(function ($takjilMinuman) {
    //                     return $takjilMinuman->minuman->nama; // Hanya nama
    //                 })->implode(', ');

    //                 // Ambil keterangan dari makanan dan minuman
    //                 $keteranganMakanan = $takjil->makanans->map(function ($takjilMakanan) {
    //                     return $takjilMakanan->makanan->keterangan;
    //                 })->filter()->implode(', ');

    //                 $keteranganMinuman = $takjil->minumans->map(function ($takjilMinuman) {
    //                     return $takjilMinuman->minuman->keterangan;
    //                 })->filter()->implode(', ');

    //                 // Gabungkan keterangan
    //                 $keteranganGabungan = '';
    //                 if ($keteranganMakanan && $keteranganMinuman) {
    //                     $keteranganGabungan = "Makanan: {$keteranganMakanan} | Minuman: {$keteranganMinuman}";
    //                 } elseif ($keteranganMakanan) {
    //                     $keteranganGabungan = $keteranganMakanan;
    //                 } elseif ($keteranganMinuman) {
    //                     $keteranganGabungan = $keteranganMinuman;
    //                 }

    //                 $formattedTakjils[] = [
    //                     'nama_jamaah' => $takjil->jamaah->nama ?? 'Tidak diketahui',
    //                     'alamat_jamaah' => $takjil->jamaah->alamat ?? '',
    //                     'keterangan' => $keterangan,
    //                     'detail_makanan' => $detailMakanan,
    //                     'detail_minuman' => $detailMinuman,
    //                     'keterangan_makanan' => $keteranganMakanan,
    //                     'keterangan_minuman' => $keteranganMinuman,
    //                     'keterangan_gabungan' => $keteranganGabungan,
    //                     'keterangan_lain' => $takjil->keterangan,
    //                     'tanggal_hijriyah' => $takjil->tanggal_hijriyah,
    //                 ];
    //             }

    //             $groupedTakjils[$tanggal] = [
    //                 'date' => $day->date,
    //                 'date_formatted' => $tanggal,
    //                 'quota' => $day->quota,
    //                 'takjils' => $formattedTakjils,
    //                 'count' => count($formattedTakjils),
    //             ];

    //             $totalJamaah += count($formattedTakjils);
    //             $totalHari++;
    //         }
    //     }

    //     return [
    //         'groupedTakjils' => $groupedTakjils,
    //         'search' => $search,
    //         'date' => $date,
    //         'totalJamaah' => $totalJamaah,
    //         'totalHari' => $totalHari,
    //         'exportDate' => Carbon::now()->translatedFormat('d F Y H:i:s'),
    //     ];
    // }
    // private function getExportData(Request $request)
    // {
    //     $date = $request->query('date');
    //     $search = $request->query('search');

    //     $query = DaySetting::with([
    //         'takjils.jamaah', // PASTIKAN JAMAAH DI-LOAD
    //         'takjils.makanans.makanan',
    //         'takjils.minumans.minuman'
    //     ])
    //         ->has('takjils')
    //         ->orderBy('date', 'asc');

    //     if ($date) {
    //         $query->whereDate('date', $date);
    //     }

    //     $daySettings = $query->get();

    //     $groupedTakjils = [];
    //     $totalJamaah = 0;
    //     $totalHari = 0;
    //     $totalSetoran = 0;
    //     $totalKuota = 0;

    //     foreach ($daySettings as $day) {
    //         $tanggal = Carbon::parse($day->date)->translatedFormat('d F Y');

    //         $takjils = $day->takjils;

    //         if ($search) {
    //             $takjils = $takjils->filter(function ($takjil) use ($search) {
    //                 $nama = strtolower($takjil->jamaah->nama ?? '');
    //                 $alamat = strtolower($takjil->jamaah->alamat ?? '');
    //                 $searchTerm = strtolower($search);

    //                 return str_contains($nama, $searchTerm) ||
    //                     str_contains($alamat, $searchTerm);
    //             });
    //         }

    //         if ($takjils->count() > 0) {
    //             $formattedTakjils = [];

    //             foreach ($takjils as $takjil) {
    //                 $keterangan = $this->getKeteranganTakjil($takjil);

    //                 // HILANGKAN ANGKA (1) dari nama makanan/minuman
    //                 $detailMakanan = $takjil->makanans->map(function ($takjilMakanan) {
    //                     return $takjilMakanan->makanan->nama; // Hanya nama
    //                 })->implode(', ');

    //                 $detailMinuman = $takjil->minumans->map(function ($takjilMinuman) {
    //                     return $takjilMinuman->minuman->nama; // Hanya nama
    //                 })->implode(', ');

    //                 // Ambil keterangan dari makanan dan minuman
    //                 $keteranganMakanan = $takjil->makanans->map(function ($takjilMakanan) {
    //                     return $takjilMakanan->makanan->keterangan;
    //                 })->filter()->implode(', ');

    //                 $keteranganMinuman = $takjil->minumans->map(function ($takjilMinuman) {
    //                     return $takjilMinuman->minuman->keterangan;
    //                 })->filter()->implode(', ');

    //                 // Ambil data lengkap jamaah
    //                 $jamaah = $takjil->jamaah;

    //                 // Hitung total takjil jamaah ini
    //                 $totalTakjilJamaah = $jamaah ? $jamaah->takjils()->count() : 0;

    //                 // Tambahkan ke total setoran
    //                 $setoranJamaah = $jamaah->setoran ?? 0;
    //                 $totalSetoran += $setoranJamaah;

    //                 $formattedTakjils[] = [
    //                     'nama_jamaah' => $jamaah->nama ?? 'Tidak diketahui',
    //                     'alamat_jamaah' => $jamaah->alamat ?? '',
    //                     'ekonomi_jamaah' => $jamaah->ekonomi ?? '',
    //                     'setoran_jamaah' => $setoranJamaah,
    //                     'keterangan_jamaah' => $jamaah->keterangan ?? '',
    //                     'notes_jamaah' => $jamaah->notes ?? '',
    //                     'jamaah_uuid' => $jamaah->uuid ?? '',
    //                     'keterangan' => $keterangan,
    //                     'detail_makanan' => $detailMakanan,
    //                     'detail_minuman' => $detailMinuman,
    //                     'keterangan_makanan' => $keteranganMakanan,
    //                     'keterangan_minuman' => $keteranganMinuman,
    //                     'keterangan_lain' => $takjil->keterangan,
    //                     'tanggal_hijriyah' => $takjil->tanggal_hijriyah,
    //                     'total_takjil' => $totalTakjilJamaah,
    //                 ];
    //             }

    //             $groupedTakjils[$tanggal] = [
    //                 'date' => $day->date,
    //                 'date_formatted' => $tanggal,
    //                 'quota' => $day->quota,
    //                 'takjils' => $formattedTakjils,
    //                 'count' => count($formattedTakjils),
    //             ];

    //             $totalJamaah += count($formattedTakjils);
    //             $totalHari++;
    //             $totalKuota += $day->quota;
    //         }
    //     }

    //     return [
    //         'groupedTakjils' => $groupedTakjils,
    //         'search' => $search,
    //         'date' => $date,
    //         'totalJamaah' => $totalJamaah,
    //         'totalHari' => $totalHari,
    //         'totalSetoran' => $totalSetoran,
    //         'totalKuota' => $totalKuota,
    //         'exportDate' => Carbon::now()->translatedFormat('d F Y H:i:s'),
    //     ];
    // }
    private function getExportData(Request $request)
    {
        $date = $request->query('date');
        $search = $request->query('search');

        $query = DaySetting::with([
            'takjils.jamaah',
            'takjils.makanans.makanan',
            'takjils.minumans.minuman'
        ])
            ->has('takjils')
            ->orderBy('date', 'asc');

        if ($date) {
            $query->whereDate('date', $date);
        }

        $daySettings = $query->get();

        $groupedTakjils = [];
        $totalJamaah = 0;
        $totalHari = 0;

        foreach ($daySettings as $day) {
            $tanggal = Carbon::parse($day->date)->translatedFormat('d F Y');
            $tanggalShort = Carbon::parse($day->date)->translatedFormat('Y-m-d'); // Format untuk sorting

            $takjils = $day->takjils;

            if ($search) {
                $takjils = $takjils->filter(function ($takjil) use ($search) {
                    $nama = strtolower($takjil->jamaah->nama ?? '');
                    $alamat = strtolower($takjil->jamaah->alamat ?? '');
                    $searchTerm = strtolower($search);

                    return str_contains($nama, $searchTerm) ||
                        str_contains($alamat, $searchTerm);
                });
            }

            if ($takjils->count() > 0) {
                $formattedTakjils = [];

                foreach ($takjils as $takjil) {
                    $keterangan = $this->getKeteranganTakjil($takjil);

                    // HILANGKAN ANGKA (1) dari nama makanan/minuman
                    $detailMakanan = $takjil->makanans->map(function ($takjilMakanan) {
                        return $takjilMakanan->makanan->nama; // Hanya nama
                    })->implode(', ');

                    $detailMinuman = $takjil->minumans->map(function ($takjilMinuman) {
                        return $takjilMinuman->minuman->nama; // Hanya nama
                    })->implode(', ');

                    // Ambil keterangan dari makanan dan minuman
                    $keteranganMakanan = $takjil->makanans->map(function ($takjilMakanan) {
                        return $takjilMakanan->makanan->keterangan;
                    })->filter()->implode(', ');

                    $keteranganMinuman = $takjil->minumans->map(function ($takjilMinuman) {
                        return $takjilMinuman->minuman->keterangan;
                    })->filter()->implode(', ');

                    // Ambil data jamaah
                    $jamaah = $takjil->jamaah;

                    $formattedTakjils[] = [
                        'nama_jamaah' => $jamaah->nama ?? 'Tidak diketahui',
                        'alamat_jamaah' => $jamaah->alamat ?? '',
                        'jamaah_uuid' => $jamaah->uuid ?? '',
                        'keterangan' => $keterangan,
                        'detail_makanan' => $detailMakanan,
                        'detail_minuman' => $detailMinuman,
                        'keterangan_makanan' => $keteranganMakanan,
                        'keterangan_minuman' => $keteranganMinuman,
                        'tanggal_hijriyah' => $takjil->tanggal_hijriyah,
                        'tanggal_masehi' => $tanggal, // Simpan juga tanggal masehi
                        'tanggal_sort' => $tanggalShort, // Untuk sorting
                    ];
                }

                // Sort by nama jamaah untuk tampilan rapi
                usort($formattedTakjils, function ($a, $b) {
                    return strcmp($a['nama_jamaah'], $b['nama_jamaah']);
                });

                $groupedTakjils[$tanggal] = [
                    'date' => $day->date,
                    'date_formatted' => $tanggal,
                    'date_sort' => $tanggalShort,
                    'quota' => $day->quota,
                    'takjils' => $formattedTakjils,
                    'count' => count($formattedTakjils),
                ];

                $totalJamaah += count($formattedTakjils);
                $totalHari++;
            }
        }

        // Sort groupedTakjils by date
        uksort($groupedTakjils, function ($a, $b) use ($groupedTakjils) {
            return strtotime($groupedTakjils[$a]['date_sort']) - strtotime($groupedTakjils[$b]['date_sort']);
        });

        return [
            'groupedTakjils' => $groupedTakjils,
            'search' => $search,
            'date' => $date,
            'totalJamaah' => $totalJamaah,
            'totalHari' => $totalHari,
            'exportDate' => Carbon::now()->translatedFormat('d F Y H:i:s'),
        ];
    }

    public function detailJadwal($uuid)
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
            ];
        }

        return view('ramadhan.jadwalTakjils.detail', [
            'jamaah' => $jamaah,
            'takjils' => $formattedTakjils,
            'total_jadwal' => $takjils->count()
        ]);
    }
}
