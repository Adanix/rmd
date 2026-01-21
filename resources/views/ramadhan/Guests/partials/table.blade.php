{{-- MOBILE VIEW --}}
<div class="d-md-none">
   @forelse($jamaah as $item)
   <div class="card mb-3">
      <div class="card-body">

         {{-- NAMA JAMAAH --}}
         <div class="fs-2 fw-bold mb-2">
            <i class="ti ti-user text-primary me-1"></i>
            {{ $item->nama }}
         </div>

         {{-- ALAMAT --}}
         <div class="fs-2 text-body mb-3">
            <i class="ti ti-map-pin me-1 text-muted"></i>
            {{ $item->alamat }}
         </div>

         <a href="{{ route('takjil-detail', $item->uuid) }}"
            class="btn btn-primary btn-lg w-100">
            Lihat Jadwal Takjil
         </a>

      </div>
   </div>
   @empty
   <div class="empty py-5 text-center">
      <div class="empty-icon mb-3">
         <i class="ti ti-users-off fs-1 text-muted"></i>
      </div>
      <p class="empty-title">Data tidak ditemukan</p>
      <p class="empty-subtitle text-muted">
         @if(request('search'))
         Tidak ada jamaah sesuai pencarian
         @else
         Belum ada data jamaah
         @endif
      </p>
   </div>
   @endforelse

   {{-- PAGINATION MOBILE --}}
   @if($jamaah->hasPages())
   <div class="card">
      <div class="card-body">
         {{-- Info jumlah data --}}
         <div class="text-center mb-3 text-muted small">
            Menampilkan {{ $jamaah->firstItem() }}–{{ $jamaah->lastItem() }} dari {{ $jamaah->total() }} jamaah
         </div>

         {{-- Pagination mobile-friendly --}}
         <div class="d-flex justify-content-between align-items-center">
            {{-- Previous button --}}
            @if($jamaah->onFirstPage())
            <button class="btn btn-outline-secondary" disabled>
               <i class="ti ti-chevron-left"></i> Sebelumnya
            </button>
            @else
            <a href="{{ $jamaah->previousPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}"
               class="btn btn-outline-primary">
               <i class="ti ti-chevron-left"></i> Sebelumnya
            </a>
            @endif

            {{-- Current page info --}}
            <div class="text-muted">
               Halaman {{ $jamaah->currentPage() }} / {{ $jamaah->lastPage() }}
            </div>

            {{-- Next button --}}
            @if($jamaah->hasMorePages())
            <a href="{{ $jamaah->nextPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}"
               class="btn btn-outline-primary">
               Selanjutnya <i class="ti ti-chevron-right"></i>
            </a>
            @else
            <button class="btn btn-outline-secondary" disabled>
               Selanjutnya <i class="ti ti-chevron-right"></i>
            </button>
            @endif
         </div>

         {{-- Page numbers (optional, for more precise navigation) --}}
         @if($jamaah->lastPage() > 1)
         <div class="mt-3 text-center">
            <div class="btn-group" role="group">
               @foreach(range(1, min(5, $jamaah->lastPage())) as $page)
               @if($page == $jamaah->currentPage())
               <button class="btn btn-primary" disabled>{{ $page }}</button>
               @else
               <a href="{{ $jamaah->url($page) }}{{ request('search') ? '&search=' . request('search') : '' }}"
                  class="btn btn-outline-secondary">{{ $page }}</a>
               @endif
               @endforeach
               @if($jamaah->lastPage() > 5)
               <button class="btn btn-outline-secondary" disabled>...</button>
               @endif
            </div>
         </div>
         @endif
      </div>
   </div>
   @endif
</div>

{{-- DESKTOP VIEW --}}
<div class="d-none d-md-block">
   <div class="card">
      <div class="table-responsive">
         <table class="table table-vcenter card-table">
            <thead>
               <tr>
                  <th width="60">No</th>
                  <th>Nama Jamaah</th>
                  <th>Alamat</th>
                  <th>Status</th>
                  <th class="text-center" width="160">Aksi</th>
               </tr>
            </thead>
            <tbody>
               @forelse($jamaah as $index => $item)
               <tr>
                  <td>
                     {{ ($jamaah->currentPage() - 1) * $jamaah->perPage() + $index + 1 }}
                  </td>
                  <td>
                     <div class="d-flex align-items-center">
                        <div class="avatar bg-primary-lt text-primary rounded-circle me-3">
                           {{ substr($item->nama, 0, 1) }}
                        </div>
                        <div>
                           <strong class="d-block">{{ $item->nama }}</strong>
                           <small class="text-muted">
                              Setoran: Rp {{ number_format($item->setoran, 0, ',', '.') }}
                           </small>
                        </div>
                     </div>
                  </td>
                  <td class="text-muted">{{ $item->alamat }}</td>
                  <td>
                     @if($item->ekonomi)
                     <span class="badge bg-{{ $item->ekonomi == 'sangat mampu' ? 'success' : ($item->ekonomi == 'mampu' ? 'primary' : ($item->ekonomi == 'kurang mampu' ? 'warning' : 'danger')) }}-lt">
                        {{ ucfirst($item->ekonomi) }}
                     </span>
                     @else
                     <span class="text-muted">-</span>
                     @endif
                  </td>
                  <td class="text-center">
                     <a href="{{ route('takjil-detail', $item->uuid) }}"
                        class="btn btn-sm btn-primary">
                        <i class="ti ti-calendar-event me-1"></i> Lihat Jadwal
                     </a>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="5" class="text-center py-5 text-muted">
                     <i class="ti ti-users-off fs-2 mb-2"></i>
                     <div>
                        @if(request('search'))
                        Tidak ada jamaah sesuai pencarian "{{ request('search') }}"
                        @else
                        Belum ada data jamaah
                        @endif
                     </div>
                  </td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>

      @if($jamaah->hasPages())
      <div class="card-footer d-flex justify-content-between align-items-center">
         <div class="text-muted">
            Menampilkan {{ $jamaah->firstItem() }}–{{ $jamaah->lastItem() }} dari {{ $jamaah->total() }} jamaah
         </div>
         {{ $jamaah->links() }}
      </div>
      @endif
   </div>
</div>