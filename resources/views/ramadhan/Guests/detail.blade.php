<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
   <title>Detail Jadwal Takjil - {{ $jamaah->nama }}</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
   <style>
      /* Reset untuk mobile */
      * {
         -webkit-tap-highlight-color: transparent;
      }

      body {
         background-color: #f5f7fb;
         padding: 12px;
         padding-bottom: 120px;
         font-size: 14px;
         -webkit-text-size-adjust: 100%;
      }

      .container {
         max-width: 100%;
         padding: 0;
      }

      /* Header Card - Mobile Optimized */
      .header-card {
         background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
         color: white;
         border-radius: 12px;
         margin-bottom: 16px;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      }

      .header-card .card-body {
         padding: 20px 16px;
      }

      .header-card h1 {
         font-size: 1.4rem;
         margin-bottom: 12px;
         line-height: 1.3;
      }

      .header-card p {
         font-size: 0.9rem;
         margin-bottom: 0;
         opacity: 0.9;
      }

      .header-card .badge {
         font-size: 0.75rem;
         padding: 6px 10px;
         margin: 4px;
         border-radius: 20px;
         backdrop-filter: blur(10px);
         background: rgba(255, 255, 255, 0.2);
      }

      /* Takjil Cards - Mobile Optimized */
      .kartu-takjil {
         border-radius: 12px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
         border: none;
         margin-bottom: 16px;
         overflow: hidden;
      }

      .kartu-takjil .card-header {
         padding: 16px 16px 12px;
         background: white;
         border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      }

      .kartu-takjil .card-title {
         font-size: 1.1rem;
         margin-bottom: 0;
         line-height: 1.4;
      }

      .kartu-takjil .card-body {
         padding: 16px;
      }

      /* Status Badge */
      .status-badge {
         font-size: 0.75rem;
         font-weight: 600;
         padding: 5px 10px;
         border-radius: 20px;
         white-space: nowrap;
      }

      /* Status Jadwal Badge */
      .status-jadwal-badge {
         font-size: 0.7rem;
         font-weight: 600;
         padding: 4px 8px;
         border-radius: 12px;
         margin-left: 8px;
         vertical-align: middle;
      }

      /* Detail Items - Mobile Grid */
      .detail-item {
         margin-bottom: 12px;
         padding-bottom: 12px;
         border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      }

      .detail-item:last-child {
         border-bottom: none;
      }

      .detail-label {
         font-size: 0.75rem;
         font-weight: 600;
         color: #6c757d;
         text-transform: uppercase;
         letter-spacing: 0.5px;
         margin-bottom: 4px;
      }

      .detail-value {
         font-size: 0.95rem;
         font-weight: 600;
         color: #212529;
         line-height: 1.4;
      }

      /* Info Jamaah di Kartu */
      .jamaah-info {
         background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
         color: white;
         padding: 16px;
         border-radius: 10px 10px 0 0;
         margin: -16px -16px 16px -16px;
      }

      .jamaah-info h5 {
         font-size: 1.1rem;
         margin-bottom: 4px;
      }

      .jamaah-info p {
         font-size: 0.85rem;
         opacity: 0.9;
         margin-bottom: 0;
      }

      /* Makanan/Minuman Lists */
      .makanan-item,
      .minuman-item {
         padding: 12px;
         margin-bottom: 8px;
         border-radius: 8px;
         border: 1px solid rgba(0, 0, 0, 0.05);
         background: white;
      }

      .makanan-item .badge,
      .minuman-item .badge {
         font-size: 0.8rem;
         padding: 4px 8px;
         min-width: 32px;
         text-align: center;
      }

      /* Floating Action Buttons - Mobile Optimized */
      .fab-container {
         position: fixed;
         bottom: 20px;
         right: 20px;
         z-index: 1000;
         display: flex;
         flex-direction: column;
         gap: 10px;
      }

      .fab-btn {
         width: 56px;
         height: 56px;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
         border: none;
         transition: all 0.2s ease;
      }

      .fab-btn:active {
         transform: scale(0.95);
         box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
      }

      .fab-btn-primary {
         background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
         color: white;
      }

      .fab-btn-secondary {
         background: white;
         color: #495057;
         border: 1px solid #dee2e6;
      }

      .fab-btn .ti {
         font-size: 1.5rem;
      }

      /* Modal Mobile Optimized */
      .modal-dialog {
         margin: 16px;
         max-height: calc(100vh - 32px);
         display: flex;
         align-items: center;
      }

      .modal-content {
         border-radius: 16px;
         overflow: hidden;
         max-height: 90vh;
         overflow-y: auto;
      }

      .modal-header {
         padding: 20px 16px;
         background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
         color: white;
         border-bottom: none;
      }

      .modal-title {
         font-size: 1.2rem;
      }

      .modal-body {
         padding: 16px;
         max-height: 60vh;
         overflow-y: auto;
      }

      .list-group-item {
         padding: 16px 0;
         border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      }

      .list-group-item:last-child {
         border-bottom: none;
      }

      /* Toast Notifications Mobile */
      .toast {
         position: fixed;
         top: 20px;
         left: 16px;
         right: 16px;
         z-index: 9999;
         border-radius: 12px;
         padding: 14px 16px;
         box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
         animation: slideDown 0.3s ease-out;
         backdrop-filter: blur(10px);
         background: rgba(255, 255, 255, 0.95);
         border: 1px solid rgba(0, 0, 0, 0.1);
      }

      @keyframes slideDown {
         from {
            transform: translateY(-20px);
            opacity: 0;
         }

         to {
            transform: translateY(0);
            opacity: 1;
         }
      }

      /* Responsive Typography */
      @media (max-width: 576px) {
         body {
            font-size: 13px;
         }

         .header-card h1 {
            font-size: 1.3rem;
         }

         .kartu-takjil .card-title {
            font-size: 1rem;
         }

         .detail-value {
            font-size: 0.9rem;
         }

         .fab-container {
            bottom: 16px;
            right: 16px;
         }

         .fab-btn {
            width: 52px;
            height: 52px;
         }
      }

      /* Safe Area untuk iPhone */
      @supports (padding: max(0px)) {
         body {
            padding-left: max(12px, env(safe-area-inset-left));
            padding-right: max(12px, env(safe-area-inset-right));
            padding-bottom: max(120px, env(safe-area-inset-bottom));
         }

         .fab-container {
            bottom: max(20px, env(safe-area-inset-bottom));
            right: max(20px, env(safe-area-inset-right));
         }
      }

      /* Touch Improvements */
      button,
      .btn {
         touch-action: manipulation;
         min-height: 44px;
         min-width: 44px;
      }

      .btn-sm {
         min-height: 36px;
         min-width: 36px;
      }

      /* Smooth Scrolling */
      html {
         scroll-behavior: smooth;
      }

      /* Loading State */
      .loading {
         opacity: 0.6;
         pointer-events: none;
      }

      /* Empty State */
      .empty-state {
         text-align: center;
         padding: 40px 20px;
      }

      .empty-state .ti {
         font-size: 3rem;
         opacity: 0.3;
         margin-bottom: 16px;
      }

      /* Highlight kartu yang terlewat */
      .border-left-terlewat {
         border-left: 4px solid #dc3545 !important;
      }

      .border-left-hari-ini {
         border-left: 4px solid #28a745 !important;
         animation: pulse 2s infinite;
      }

      .border-left-besok {
         border-left: 4px solid #ffc107 !important;
      }

      @keyframes pulse {
         0% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4);
         }

         70% {
            box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
         }

         100% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
         }
      }
   </style>
</head>

<body>
   <!-- Library html2canvas -->
   <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

   <div class="container">
      <!-- Header Card -->
      <div class="card header-card">
         <div class="card-body text-center">
            <h1 class="card-title mb-2">
               <i class="ti ti-user-circle me-1"></i> {{ $jamaah->nama }}
            </h1>
            <p class="mb-3">
               <i class="ti ti-map-pin me-1"></i> {{ $jamaah->alamat }}
            </p>
            <div class="d-flex flex-wrap justify-content-center">
               <span class="badge me-2 mb-1">
                  <i class="ti ti-calendar-event me-1"></i> Total: {{ $total_jadwal }}
               </span>
               @if($total_terlewat > 0)
               <span class="badge me-2 mb-1 bg-danger">
                  <i class="ti ti-clock-off me-1"></i> Terlewat: {{ $total_terlewat }}
               </span>
               @endif
               @if($total_hari_ini > 0)
               <span class="badge me-2 mb-1 bg-success">
                  <i class="ti ti-sun me-1"></i> Hari Ini: {{ $total_hari_ini }}
               </span>
               @endif
               @if($total_besok > 0)
               <span class="badge me-2 mb-1 bg-warning">
                  <i class="ti ti-moon me-1"></i> Besok: {{ $total_besok }}
               </span>
               @endif
               <span class="badge mb-1">
                  <i class="ti ti-coin me-1"></i> Setoran: {{ number_format($jamaah->setoran, 0, ',', '.') }} Kali
               </span>
            </div>
         </div>
      </div>

      @forelse($takjils as $index => $takjil)
      <div class="card kartu-takjil 
            @if($takjil['is_terlewat']) border-left-terlewat
            @elseif($takjil['is_hari_ini']) border-left-hari-ini
            @elseif($takjil['is_besok']) border-left-besok @endif"
         data-index="{{ $index }}" id="kartu-{{ $index }}">
         <!-- Card Header -->
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-start">
               <div>
                  <h3 class="card-title">
                     <i class="ti ti-calendar me-1"></i> Kartu Takjil #{{ $index + 1 }}
                     <!-- Badge Status Jadwal -->
                     <span class="status-jadwal-badge d-block mt-1
                                 @if($takjil['is_terlewat']) bg-danger
                                 @elseif($takjil['is_hari_ini']) bg-success
                                 @elseif($takjil['is_besok']) bg-warning
                                 @else bg-secondary text-white @endif">
                        <i class="ti ti-@if($takjil['is_terlewat']) clock-off
                                                @elseif($takjil['is_hari_ini']) sun
                                                @elseif($takjil['is_besok']) moon
                                                @else calendar @endif me-1"></i>
                        {{ $takjil['status_jadwal'] }}
                     </span>
                  </h3>
                  @if($takjil['tanggal_masehi'])
                  <small class="text-muted">
                     @php
                     $tanggalTakjil = \Carbon\Carbon::parse($takjil['tanggal_masehi']);
                     $diffInDays = $tanggalTakjil->diffInDays(now(), false);
                     @endphp
                     @if($takjil['is_terlewat'])
                     <i class="ti ti-clock me-1"></i> {{ abs($diffInDays) }} hari yang lalu
                     @elseif($takjil['is_hari_ini'])
                     <i class="ti ti-check me-1"></i> Sedang berjalan
                     @elseif($takjil['is_besok'])
                     <i class="ti ti-clock me-1"></i> Besok
                     @else
                     <i class="ti ti-clock me-1"></i> Dalam {{ abs($diffInDays) }} hari
                     @endif
                  </small>
                  @endif
               </div>
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
         </div>

         <!-- Card Body dengan Info Jamaah -->
         <div class="card-body">
            <!-- Info Jamaah - Tampil di gambar -->
            <div class="jamaah-info d-none d-print-block">
               <h5><i class="ti ti-user-circle me-1"></i> {{ $jamaah->nama }}</h5>
               <p><i class="ti ti-map-pin me-1"></i> {{ $jamaah->alamat }}</p>
            </div>

            <div class="mb-3">
               <h6 class="text-primary mb-3">
                  <i class="ti ti-calendar-event me-2"></i> Detail Jadwal
               </h6>

               <div class="row">
                  <div class="col-12 detail-item">
                     <div class="detail-label">Tanggal Masehi</div>
                     <div class="detail-value">
                        <i class="ti ti-calendar me-1"></i>
                        @if($takjil['tanggal_masehi'])
                        {{ \Carbon\Carbon::parse($takjil['tanggal_masehi'])->translatedFormat('d F Y') }}
                        @else
                        -
                        @endif
                     </div>
                  </div>

                  <div class="col-12 detail-item">
                     <div class="detail-label">Tanggal Hijriyah</div>
                     <div class="detail-value">
                        <i class="ti ti-moon me-1"></i>
                        {{ $takjil['tanggal_hijriyah'] ?? '-' }}
                     </div>
                  </div>

                  @if($takjil['keterangan'])
                  <div class="col-12 detail-item">
                     <div class="detail-label">Keterangan</div>
                     <div class="detail-value text-muted">
                        <i class="ti ti-note me-1"></i> {{ $takjil['keterangan'] }}
                     </div>
                  </div>
                  @endif
               </div>
            </div>

            <!-- Makanan -->
            @if(count($takjil['makanan']) > 0)
            <div class="mb-3">
               <h6 class="text-primary mb-2">
                  <i class="ti ti-bowl me-2"></i> Makanan
                  <span class="badge bg-primary ms-1">{{ $takjil['total_makanan'] }}</span>
               </h6>

               <div class="d-grid gap-2">
                  @foreach($takjil['makanan'] as $makanan)
                  <div class="makanan-item">
                     <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">{{ $makanan['jumlah'] }}x</span>
                        <span class="fw-semibold">{{ $makanan['nama'] }}</span>
                     </div>
                     @if($makanan['keterangan'] && $makanan['keterangan'] != '-')
                     <div class="mt-2 ms-4 ps-2 border-start border-2 border-danger">
                        <small class="text-muted">{{ $makanan['keterangan'] }}</small>
                     </div>
                     @endif
                  </div>
                  @endforeach
               </div>
            </div>
            @endif

            <!-- Minuman -->
            @if(count($takjil['minuman']) > 0)
            <div class="mb-2">
               <h6 class="text-primary mb-2">
                  <i class="ti ti-glass me-2"></i> Minuman
                  <span class="badge bg-info ms-1">{{ $takjil['total_minuman'] }}</span>
               </h6>

               <div class="d-grid gap-2">
                  @foreach($takjil['minuman'] as $minuman)
                  <div class="minuman-item">
                     <div class="d-flex align-items-center">
                        <span class="badge bg-info me-2">{{ $minuman['jumlah'] }}x</span>
                        <span class="fw-semibold">{{ $minuman['nama'] }}</span>
                     </div>
                     @if($minuman['keterangan'] && $minuman['keterangan'] != '-')
                     <div class="mt-2 ms-4 ps-2 border-start border-2 border-primary">
                        <small class="text-muted">{{ $minuman['keterangan'] }}</small>
                     </div>
                     @endif
                  </div>
                  @endforeach
               </div>
            </div>
            @endif
         </div>
      </div>
      @empty
      <div class="card kartu-takjil">
         <div class="card-body empty-state">
            <i class="ti ti-calendar-off"></i>
            <h4 class="mt-3 text-muted">Belum Ada Jadwal Takjil</h4>
            <p class="text-muted mb-0">Jamaah ini belum memiliki jadwal takjil.</p>
         </div>
      </div>
      @endforelse
   </div>

   <!-- Floating Action Buttons -->
   <div class="fab-container">
      <!-- Download All Button -->
      <button class="fab-btn fab-btn-primary" onclick="showDownloadModal()">
         <i class="ti ti-download"></i>
      </button>

      <!-- Back Button -->
      <a href="{{ route('takjil-jamaah') }}" class="fab-btn fab-btn-secondary">
         <i class="ti ti-arrow-left"></i>
      </a>
   </div>

   <!-- Download Modal -->
   <div class="modal modal-blur fade" id="downloadModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">
                  <i class="ti ti-download me-2"></i> Download Kartu Takjil
               </h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <!-- Quick Actions -->
               <div class="mb-4">
                  <h6 class="text-muted mb-3">Download Cepat</h6>
                  <div class="d-grid gap-2">
                     <button onclick="downloadAllCards()" class="btn btn-primary btn-lg">
                        <i class="ti ti-download me-2"></i> Download Semua Kartu
                     </button>
                  </div>
               </div>

               <!-- Individual Cards -->
               <div>
                  <h6 class="text-muted mb-3">Pilih Kartu</h6>
                  <div class="list-group list-group-flush">
                     @forelse($takjils as $index => $takjil)
                     <div class="list-group-item px-0">
                        <div class="row align-items-center">
                           <div class="col-auto">
                              <span class="badge 
                                             @if($takjil['is_terlewat']) bg-danger
                                             @elseif($takjil['is_hari_ini']) bg-success
                                             @elseif($takjil['is_besok']) bg-warning
                                             @else bg-primary @endif 
                                             rounded-circle"
                                 style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                 {{ $index + 1 }}
                              </span>
                           </div>
                           <div class="col">
                              <div class="fw-semibold">
                                 Kartu Takjil #{{ $index + 1 }}
                              </div>
                              <div class="small text-muted">
                                 @if($takjil['tanggal_masehi'])
                                 {{ \Carbon\Carbon::parse($takjil['tanggal_masehi'])->format('d/m/Y') }}
                                 @else
                                 Tanpa Tanggal
                                 @endif
                                 • {{ ucfirst($takjil['jenis_takjil']) }}
                                 <span class="badge 
                                                @if($takjil['is_terlewat']) bg-danger
                                                @elseif($takjil['is_hari_ini']) bg-success
                                                @elseif($takjil['is_besok']) bg-warning
                                                @else bg-secondary @endif 
                                                ms-2">
                                    {{ $takjil['status_jadwal'] }}
                                 </span>
                              </div>
                           </div>
                           <div class="col-auto">
                              <button
                                 type="button"
                                 class="btn btn-sm btn-primary"
                                 data-index="{{ $index }}"
                                 onclick="downloadSingleCard(this)">
                                 <i class="ti ti-download"></i> Unduh
                              </button>
                           </div>
                        </div>
                     </div>
                     @empty
                     <div class="text-center py-3">
                        <i class="ti ti-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0">Tidak ada kartu tersedia</p>
                     </div>
                     @endforelse
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-link" data-bs-dismiss="modal">
                  Tutup
               </button>
            </div>
         </div>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
   <script>
      // Modal Instance
      let downloadModal = null;

      // Inisialisasi saat DOM siap
      document.addEventListener('DOMContentLoaded', function() {
         downloadModal = new bootstrap.Modal(document.getElementById('downloadModal'));
      });

      // Fungsi untuk menampilkan modal download
      function showDownloadModal() {
         if (downloadModal) {
            downloadModal.show();
         }
      }

      // Fungsi untuk menampilkan toast notifikasi
      function showToast(type, message) {
         // Hapus toast sebelumnya
         const oldToast = document.querySelector('.toast');
         if (oldToast) oldToast.remove();

         // Buat toast baru
         const toast = document.createElement('div');
         toast.className = `toast toast-${type}`;

         const icons = {
            success: {
               icon: 'circle-check',
               color: 'text-success'
            },
            error: {
               icon: 'alert-circle',
               color: 'text-danger'
            },
            info: {
               icon: 'info-circle',
               color: 'text-info'
            }
         };

         const iconConfig = icons[type] || icons.info;

         toast.innerHTML = `
                  <div class="d-flex align-items-center">
                     <i class="ti ti-${iconConfig.icon} me-3 ${iconConfig.color}" style="font-size: 1.2rem;"></i>
                     <div class="flex-fill">${message}</div>
                     <button class="btn btn-sm btn-link p-0" onclick="this.parentElement.parentElement.remove()">
                     <i class="ti ti-x"></i>
                     </button>
                  </div>
            `;

         document.body.appendChild(toast);

         // Auto remove setelah 3 detik
         setTimeout(() => {
            if (toast.parentNode) {
               toast.remove();
            }
         }, 3000);
      }

      // Fungsi untuk membuat kartu dengan info jamaah untuk download
      function createCardForDownload(cardIndex) {
         const card = document.querySelector(`#kartu-${cardIndex}`);
         if (!card) return null;

         // Clone kartu
         const clonedCard = card.cloneNode(true);

         // Tambahkan header info jamaah ke dalam kartu
         const headerCard = document.querySelector('.header-card');
         const jamaahInfo = headerCard.cloneNode(true);

         // Hapus badge total dari clone
         const badges = jamaahInfo.querySelectorAll('.badge');
         badges.forEach(badge => badge.remove());

         // Ubah style header untuk tampilan di dalam kartu
         jamaahInfo.classList.remove('header-card');
         jamaahInfo.style.cssText = `
               background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
               color: white;
               padding: 20px 16px;
               border-radius: 12px 12px 0 0;
               margin: -16px -16px 20px -16px;
            `;

         // Masukkan header info jamaah ke dalam kartu clone
         clonedCard.insertBefore(jamaahInfo, clonedCard.firstChild);

         return clonedCard;
      }

      // Fungsi untuk mendownload kartu tunggal
      function downloadSingleCard(el) {
         const cardIndex = Number(el.dataset.index);

         if (downloadModal) downloadModal.hide();

         document.body.classList.add('loading');
         showToast('info', 'Menyiapkan download...');

         const cardForDownload = createCardForDownload(cardIndex);
         if (!cardForDownload) {
            showToast('error', 'Kartu tidak ditemukan');
            document.body.classList.remove('loading');
            return;
         }

         // Siapkan container untuk capture
         const tempContainer = document.createElement('div');
         tempContainer.style.cssText = `
                  position: fixed;
                  left: -9999px;
                  top: 0;
                  width: 100%;
                  max-width: 400px;
                  background: #f5f7fb;
                  padding: 20px;
               `;

         tempContainer.appendChild(cardForDownload);
         document.body.appendChild(tempContainer);

         // Capture dengan html2canvas
         html2canvas(cardForDownload, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff',
            logging: false,
            windowWidth: 400,
            onclone: function(clonedDoc) {
               // Pastikan semua konten terlihat
               const card = clonedDoc.querySelector('.kartu-takjil');
               if (card) {
                  card.style.boxShadow = 'none';
                  card.style.margin = '0';
               }
            }
         }).then(canvas => {
            // Cleanup
            document.body.removeChild(tempContainer);
            document.body.classList.remove('loading');

            // Buat download link
            const imageData = canvas.toDataURL('image/png');
            const downloadLink = document.createElement('a');
            downloadLink.href = imageData;

            // Format nama file dengan nama jamaah
            const jamaahName = "{{ $jamaah->nama }}".replace(/\s+/g, '_').toLowerCase();
            const date = new Date();
            const dateStr = `${date.getFullYear()}-${(date.getMonth()+1).toString().padStart(2,'0')}-${date.getDate().toString().padStart(2,'0')}`;
            downloadLink.download = `Takjil_${jamaahName}_${cardIndex + 1}_${dateStr}.png`;

            // Trigger download
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            showToast('success', `Kartu #${cardIndex + 1} berhasil diunduh`);

         }).catch(error => {
            console.error('Error:', error);
            document.body.classList.remove('loading');
            showToast('error', 'Gagal mengunduh kartu');

            // Cleanup jika error
            if (tempContainer.parentNode) {
               document.body.removeChild(tempContainer);
            }
         });
      }

      // Fungsi untuk mendownload semua kartu
      async function downloadAllCards() {
         const cards = document.querySelectorAll('.kartu-takjil');

         if (cards.length === 0) {
            showToast('error', 'Tidak ada kartu untuk diunduh');
            return;
         }

         if (!confirm(`Download ${cards.length} kartu sebagai file terpisah?`)) {
            return;
         }

         if (downloadModal) downloadModal.hide();

         document.body.classList.add('loading');
         showToast('info', `Memulai download ${cards.length} kartu...`);

         const jamaahName = "{{ $jamaah->nama }}".replace(/\s+/g, '_').toLowerCase();

         // Download kartu satu per satu dengan delay
         for (let i = 0; i < cards.length; i++) {
            try {
               await new Promise(resolve => setTimeout(resolve, 800)); // Delay

               // Buat kartu dengan info jamaah
               const cardForDownload = createCardForDownload(i);
               if (!cardForDownload) continue;

               // Siapkan container
               const tempContainer = document.createElement('div');
               tempContainer.style.cssText = `
                        position: fixed;
                        left: -9999px;
                        top: 0;
                        width: 100%;
                        max-width: 400px;
                        background: #f5f7fb;
                        padding: 20px;
                     `;

               tempContainer.appendChild(cardForDownload);
               document.body.appendChild(tempContainer);

               const canvas = await html2canvas(cardForDownload, {
                  scale: 2,
                  useCORS: true,
                  backgroundColor: '#ffffff',
                  logging: false,
                  windowWidth: 400
               });

               document.body.removeChild(tempContainer);

               const link = document.createElement('a');
               link.href = canvas.toDataURL('image/png');
               const date = new Date();
               const dateStr = `${date.getFullYear()}-${(date.getMonth()+1).toString().padStart(2,'0')}-${date.getDate().toString().padStart(2,'0')}`;
               link.download = `Takjil_${jamaahName}_${i + 1}_${dateStr}.png`;
               document.body.appendChild(link);
               link.click();
               document.body.removeChild(link);

            } catch (error) {
               console.error(`Error pada kartu ${i + 1}:`, error);
            }
         }

         document.body.classList.remove('loading');
         showToast('success', 'Semua kartu berhasil diunduh');
      }
   </script>
</body>

</html>