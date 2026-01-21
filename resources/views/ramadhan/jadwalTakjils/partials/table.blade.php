@if(count($groupedTakjils) > 0)
@foreach($groupedTakjils as $tanggal => $data)
<div class="card mb-4">
   <div class="card-header">
      <h5 class="mb-0 d-flex justify-content-between align-items-center">
         <span>
            {{ $tanggal }}
            <span class="badge bg-success-lt">{{ $data['count'] }} / {{ $data['quota'] }} Jamaah</span>
         </span>
         <span class="text-muted">
            {{ $data['date']->translatedFormat('l') }}
         </span>
      </h5>
   </div>
   <div class="table-responsive">
      <table class="table table-hover mb-0">
         <thead class="table-light">
            <tr>
               <th width="50">#</th>
               <th>Nama Jamaah</th>
               <th>Alamat</th>
               <th>Keterangan</th>
               <th>Detail Menu</th>
               <th width="100">Aksi</th>
            </tr>
         </thead>
         <tbody>
            @foreach($data['takjils'] as $index => $takjil)
            <tr>
               <td>{{ $index + 1 }}</td>
               <td>
                  <strong>{{ $takjil['nama_jamaah'] }}</strong>
               </td>
               <td>{{ $takjil['alamat_jamaah'] }}</td>
               <td>
                  <span class="badge 
                                 @if($takjil['keterangan'] == 'Makanan & Minuman') badge bg-success-lt
                                 @elseif($takjil['keterangan'] == 'Makanan') badge bg-warning-lt
                                 @elseif($takjil['keterangan'] == 'Minuman') badge bg-info-lt
                                 @else bg-secondary
                                 @endif">
                     {{ $takjil['keterangan'] }}
                  </span>
                  @if($takjil['keterangan_lain'])
                  <br>
                  <small class="text-muted">{{ $takjil['keterangan_lain'] }}</small>
                  @endif
               </td>
               <!-- Di file partials/table.blade.php -->
               <td>
                  @if($takjil['detail_makanan'])
                  <div class="mb-1">
                     <small class="text-muted">Makanan:</small><br>
                     <span>{{ $takjil['detail_makanan'] }}</span>
                     @if($takjil['keterangan_makanan'])
                     <div class="text-muted small">
                        <em>{{ $takjil['keterangan_makanan'] }}</em>
                     </div>
                     @endif
                  </div>
                  @endif
                  @if($takjil['detail_minuman'])
                  <div>
                     <small class="text-muted">Minuman:</small><br>
                     <span>{{ $takjil['detail_minuman'] }}</span>
                     @if($takjil['keterangan_minuman'])
                     <div class="text-muted small">
                        <em>{{ $takjil['keterangan_minuman'] }}</em>
                     </div>
                     @endif
                  </div>
                  @endif
                  @if($takjil['keterangan_lain'])
                  <div class="text-muted small mt-1">
                     <em>Catatan: {{ $takjil['keterangan_lain'] }}</em>
                  </div>
                  @endif
               </td>
               <td>
                  <a href="{{ route('jadwal-takjil-detail', $takjil['jamaah_uuid']) }}"
                     class="btn btn-sm btn-outline-primary" title="Detail">
                     <span>Detail</span>
                  </a>
               </td>
            </tr>
            @endforeach
         </tbody>
      </table>
   </div>
</div>
@endforeach

<div class="alert alert-info">
   <i class="fas fa-info-circle me-2"></i>
   Total hari dengan jadwal: <strong>{{ count($groupedTakjils) }} hari</strong>
</div>
@else
<div class="text-center py-5">
   <div class="empty-state">
      <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
      <h4>Tidak ada jadwal takjil</h4>
      <p class="text-muted">
         @if(request('date') || request('search'))
         Tidak ditemukan jadwal dengan filter yang Anda pilih.
         @else
         Belum ada jadwal takjil yang dibuat. Silakan atur jadwal terlebih dahulu.
         @endif
      </p>
      @if(request('date') || request('search'))
      <a href="{{ route('jadwal-takjil.index') }}" class="btn btn-primary">
         <i class="fas fa-redo me-1"></i> Reset Filter
      </a>
      @endif
   </div>
</div>
@endif