<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Detail Jadwal Takjil - {{ $jamaah->nama }}</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
   <style>
      body {
         background-color: #f5f7fb;
         padding: 20px;
      }

      .card {
         border-radius: 0.75rem;
         box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
         border: none;
         margin-bottom: 20px;
      }

      .header-card {
         background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
         color: white;
      }

      .status-badge {
         font-size: 0.85rem;
         font-weight: 700;
         padding: 0.4rem 0.8rem;
         border-radius: 50rem;
      }

      .detail-item {
         margin-bottom: 0.5rem;
         padding: 0.5rem 0;
      }

      .detail-label {
         font-size: 0.8rem;
         font-weight: 500;
         color: #6c757d;
         text-transform: uppercase;
      }

      .detail-value {
         font-size: 1rem;
         font-weight: 600;
         color: #212529;
      }

      .menu-header {
         background-color: #e9ecef;
         color: #495057;
         font-weight: 600;
         border-top-left-radius: 0.75rem;
         border-top-right-radius: 0.75rem;
      }

      .menu-item {
         border-left: none;
         border-right: none;
         display: flex;
         justify-content: space-between;
         align-items: center;
      }

      .menu-item:last-child {
         border-bottom: none;
      }

      .back-btn {
         position: fixed;
         bottom: 20px;
         right: 20px;
         z-index: 1000;
      }
   </style>
</head>

<body>
   <!-- //!DURUNG FIX -->
   <div class="container" style="max-width: 800px;">
      <!-- Header Card -->
      <div class="card header-card">
         <div class="card-body text-center py-4">
            <h1 class="card-title mb-3">
               <i class="ti ti-user-circle me-2"></i> {{ $jamaah->nama }}
            </h1>
            <p class="mb-0">
               <i class="ti ti-map-pin me-1"></i> {{ $jamaah->alamat }}
            </p>
            <div class="mt-3">
               <span class="badge bg-light text-dark me-2">
                  <i class="ti ti-calendar-event me-1"></i> Total Jadwal: {{ $total_jadwal }}
               </span>
               <span class="badge bg-light text-dark">
                  <i class="ti ti-coin me-1"></i> Jadwal Setoran: {{ number_format($jamaah->setoran, 0, ',', '.') }} Kali Takjil
               </span>
            </div>
         </div>
      </div>

      @forelse($takjils as $index => $takjil)
      <div class="card">
         <div class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0 fw-bold">
               <i class="ti ti-calendar me-2"></i> Kartu Takjil #{{ $index + 1 }}
            </h3>
            <span class="badge status-badge 
                        @if($takjil['jenis_takjil'] == 'makanan') text-bg-success
                        @elseif($takjil['jenis_takjil'] == 'minuman') text-bg-info
                        @else text-bg-warning @endif">
               <i class="ti ti-@if($takjil['jenis_takjil'] == 'makanan') bowl
                                     @elseif($takjil['jenis_takjil'] == 'minuman') glass
                                     @else bowl-spoon @endif me-1"></i>
               {{ ucfirst($takjil['jenis_takjil']) }}
            </span>
         </div>

         <div class="card-body pb-2">
            <div class="row">
               <div class="col-12 mb-3">
                  <h5 class="fw-bold text-primary">
                     <i class="ti ti-calendar-event me-2"></i> Detail Jadwal
                  </h5>
               </div>

               <div class="col-lg-6 col-md-6 col-12 detail-item">
                  <div class="detail-label ms-3">Tanggal Masehi</div>
                  <div class="detail-value ms-3">
                     <i class="ti ti-calendar me-1"></i>
                     @if($takjil['tanggal_masehi'])
                     {{ \Carbon\Carbon::parse($takjil['tanggal_masehi'])->translatedFormat('d F Y') }}
                     @else
                     -
                     @endif
                  </div>
               </div>

               <div class="col-lg-6 col-md-6 col-12 detail-item">
                  <div class="detail-label">Tanggal Hijriyah</div>
                  <div class="detail-value">
                     <i class="ti ti-moon me-1"></i>
                     {{ $takjil['tanggal_hijriyah'] ?? '-' }}
                  </div>
               </div>

               @if($takjil['keterangan'])
               <div class="col-12 detail-item">
                  <div class="detail-label">Keterangan Tambahan</div>
                  <div class="detail-value text-muted">
                     <i class="ti ti-note me-1"></i> {{ $takjil['keterangan'] }}
                  </div>
               </div>
               @endif
            </div>
         </div>

         <hr class="mx-3 my-0">

         <!-- Makanan -->
         @if(count($takjil['makanan']) > 0)
         <div class="card-body pt-3">
            <h5 class="fw-bold text-primary mb-3">
               <i class="ti ti-bowl me-2"></i> Daftar Makanan
               <span class="badge bg-primary ms-2">{{ $takjil['total_makanan'] }} jenis</span>
            </h5>

            <div class="d-grid gap-2">
               @foreach($takjil['makanan'] as $makanan)
               <div class="p-3 @if($loop->odd) bg-light @else bg-white border @endif rounded-3 d-flex flex-column gap-1">
                  <div class="d-flex justify-content-between align-items-center">
                     <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2 fw-bold">{{ $makanan['jumlah'] }}x</span>
                        <span class="fw-bold fs-6">{{ $makanan['nama'] }}</span>
                     </div>
                  </div>
                  @if($makanan['keterangan'] && $makanan['keterangan'] != '-')
                  <div class="mt-1 ms-4 ps-1 border-start border-danger border-3">
                     <span class="text-muted small">
                        {{ $makanan['keterangan'] }}
                     </span>
                  </div>
                  @endif
               </div>
               @endforeach
            </div>
         </div>
         @endif

         <!-- Minuman -->
         @if(count($takjil['minuman']) > 0)
         <div class="card-body pt-3">
            <h5 class="fw-bold text-primary mb-3">
               <i class="ti ti-glass me-2"></i> Daftar Minuman
               <span class="badge bg-info ms-2">{{ $takjil['total_minuman'] }} jenis</span>
            </h5>

            <div class="d-grid gap-2">
               @foreach($takjil['minuman'] as $minuman)
               <div class="p-3 @if($loop->odd) bg-light @else bg-white border @endif rounded-3 d-flex flex-column gap-1">
                  <div class="d-flex justify-content-between align-items-center">
                     <div class="d-flex align-items-center">
                        <span class="badge bg-info me-2 fw-bold">{{ $minuman['jumlah'] }}x</span>
                        <span class="fw-bold fs-6">{{ $minuman['nama'] }}</span>
                     </div>
                  </div>
                  @if($minuman['keterangan'] && $minuman['keterangan'] != '-')
                  <div class="mt-1 ms-4 ps-1 border-start border-primary border-3">
                     <span class="text-muted small">
                        {{ $minuman['keterangan'] }}
                     </span>
                  </div>
                  @endif
               </div>
               @endforeach
            </div>
         </div>
         @endif
      </div>
      @empty
      <div class="card">
         <div class="card-body text-center py-5">
            <i class="ti ti-calendar-off text-muted" style="font-size: 3rem;"></i>
            <h4 class="mt-3 text-muted">Belum Ada Jadwal Takjil</h4>
            <p class="text-muted">Jamaah ini belum memiliki jadwal takjil yang ditetapkan.</p>
         </div>
      </div>
      @endforelse
   </div>

   <!-- Back Button -->
   <a href="{{ url()->previous() }}" class="btn btn-primary back-btn">
      <i class="ti ti-arrow-left me-1"></i> Kembali
   </a>
   <!-- Tambahkan sebelum script Bootstrap Anda -->
   <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
   <script>
      // Print functionality jika diperlukan
      function printTakjilCards() {
         window.print();
      }
   </script>
</body>

</html>