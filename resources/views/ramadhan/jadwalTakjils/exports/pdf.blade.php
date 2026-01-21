<!DOCTYPE html>
<html>

<head>
   <meta charset="UTF-8">
   <title>Jadwal Takjil Ramadhan</title>
   <style>
      body {
         font-family: 'DejaVu Sans', Arial, sans-serif;
         font-size: 10px;
      }

      .header {
         text-align: center;
         margin-bottom: 15px;
      }

      .header h1 {
         margin: 0;
         color: #2c3e50;
         font-size: 16px;
      }

      .header .subtitle {
         color: #7f8c8d;
         margin-top: 3px;
         font-size: 9px;
      }

      .section {
         margin-bottom: 15px;
         page-break-inside: avoid;
      }

      .section-title {
         background: #3498db;
         color: white;
         padding: 5px;
         border-radius: 2px;
         font-weight: bold;
         margin-bottom: 8px;
         font-size: 11px;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 8px;
         font-size: 9px;
      }

      th {
         background: #2c3e50;
         color: white;
         padding: 6px;
         text-align: left;
      }

      td {
         padding: 6px;
         border: 1px solid #ddd;
      }

      .footer {
         text-align: center;
         margin-top: 20px;
         color: #7f8c8d;
         font-size: 8px;
      }

      .page-break {
         page-break-before: always;
      }

      .keterangan {
         font-size: 8px;
         color: #666;
         font-style: italic;
      }
   </style>
</head>

<body>
   <div class="header">
      <h1>JADWAL TAKJIL RAMADHAN</h1>
      <div class="subtitle">
         Dicetak pada: {{ $exportDate }}
         @if(($search ?? false) || ($date ?? false))
         | Filter:
         @if($search ?? false) Pencarian: "{{ $search }}" @endif
         @if($date ?? false) Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }} @endif
         @endif
      </div>
   </div>

   @if($totalHari > 0)
   <div style="background: #ecf0f1; padding: 10px; border-radius: 3px; margin-bottom: 15px; font-size: 9px;">
      <strong>Ringkasan:</strong> Total Hari: {{ $totalHari }} | Total Jamaah: {{ $totalJamaah }}
   </div>

   @foreach($groupedTakjils as $tanggal => $data)
   <div class="section">
      <div class="section-title">
         {{ $tanggal }} - {{ $data['count'] }} dari {{ $data['quota'] }} kuota
      </div>

      <table>
         <thead>
            <tr>
               <th width="5%">No</th>
               <th width="25%">Nama Jamaah</th>
               <th width="20%">Alamat</th>
               <th width="50%">Detail Menu</th>
            </tr>
         </thead>
         <tbody>
            @foreach($data['takjils'] as $index => $takjil)
            <tr>
               <td>{{ $index + 1 }}</td>
               <td>{{ $takjil['nama_jamaah'] ?? '' }}</td>
               <td>{{ $takjil['alamat_jamaah'] ?? '' }}</td>
               <td>
                  @if(!empty($takjil['detail_makanan']))
                  <div>
                     <strong>Makanan:</strong> {{ $takjil['detail_makanan'] }}
                     @if(!empty($takjil['keterangan_makanan']))
                     <div class="keterangan">{{ $takjil['keterangan_makanan'] }}</div>
                     @endif
                  </div>
                  @endif
                  @if(!empty($takjil['detail_minuman']))
                  <div style="margin-top: 3px;">
                     <strong>Minuman:</strong> {{ $takjil['detail_minuman'] }}
                     @if(!empty($takjil['keterangan_minuman']))
                     <div class="keterangan">{{ $takjil['keterangan_minuman'] }}</div>
                     @endif
                  </div>
                  @endif
                  @if(!empty($takjil['keterangan_lain']))
                  <div style="margin-top: 3px; color: #e74c3c;">
                     <strong>Catatan:</strong> {{ $takjil['keterangan_lain'] }}
                  </div>
                  @endif
               </td>
            </tr>
            @endforeach
         </tbody>
      </table>
   </div>

   @if(!$loop->last)
   <div class="page-break"></div>
   @endif
   @endforeach
   @else
   <div class="section">
      <div class="section-title">Tidak Ada Data</div>
      <p>Tidak ditemukan jadwal takjil dengan filter yang digunakan.</p>
   </div>
   @endif

   <div class="footer">
      <hr style="border: 0.5px solid #ddd;">
      <p>Dokumen ini dicetak secara otomatis dari Sistem Takjil Ramadhan</p>
   </div>
</body>

</html>