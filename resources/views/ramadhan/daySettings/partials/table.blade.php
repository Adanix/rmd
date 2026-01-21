<div class="table-responsive">
   <table class="table card-table table-vcenter">
      <thead>
         <tr>
            <th>Ramadhan Setting ID</th>
            <th>Date</th>
            <th>Quota</th>
            <th>Total Makanan</th>
            <th>Total Minuman</th>
            <th>Notes</th>
            <th class="text-end">Actions</th>
         </tr>
      </thead>
      <tbody>
         @forelse ($daySettings as $daySetting)
         <tr>
            <td>{{ $daySetting->ramadhan_setting_id }}</td>
            <td>{{ $daySetting->date->format('d M Y') }}</td>
            <td>
               <div class="d-flex align-items-center">
                  <span class="me-2">{{ $daySetting->quota_status }}</span>

                  <div class="progress flex-grow-1" style="height: 6px;">
                     <div class="progress-bar {{ $daySetting->is_quota_full ? 'bg-success' : 'bg-primary' }}"
                        role="progressbar"
                        style="width: {{ $daySetting->quota_percentage }}%"
                        aria-valuenow="{{ $daySetting->filled_count }}"
                        aria-valuemin="0"
                        aria-valuemax="{{ $daySetting->quota }}">
                     </div>
                  </div>

                  @if ($daySetting->is_quota_full)
                  <span class="badge bg-success-lt ms-2">Full</span>
                  @elseif ($daySetting->filled_count > 0)
                  <span class="badge bg-info-lt ms-2">
                     {{ $daySetting->quota - $daySetting->filled_count }} tersisa
                  </span>
                  @endif
               </div>
            </td>
            <td>{{ $daySetting->total_makanan_planned ?? '0' }}</td>
            <td>{{ $daySetting->total_minuman_planned ?? '0' }}</td>
            <td>
               @if($daySetting->notes)
               <span class="text-truncate d-inline-block" style="max-width: 150px;"
                  title="{{ $daySetting->notes }}">
                  {{ $daySetting->notes }}
               </span>
               @else
               <span class="text-muted">-</span>
               @endif
            </td>
            <td class="text-end">
               <div class="d-inline-flex gap-2">
                  <a href="{{ route('day-settings.show', $daySetting) }}"
                     class="btn btn-sm btn-primary"
                     title="Tambah jadwal takjil">
                     <i class="ti ti-plus me-1"></i> Add Jadwal
                  </a>

                  <a href="{{ route('day-settings.edit', $daySetting->ramadhan_setting_id) }}"
                     class="btn btn-sm btn-warning"
                     title="Edit pengaturan harian">
                     <i class="ti ti-edit me-1"></i> Edit
                  </a>
               </div>
            </td>
         </tr>
         @empty
         <tr>
            <td colspan="7" class="text-center py-4">
               <div class="text-muted">
                  <i class="fas fa-inbox fa-2x mb-2"></i>
                  <p class="mb-0">Tidak ada data.</p>
               </div>
            </td>
         </tr>
         @endforelse
      </tbody>
   </table>
</div>

@if($daySettings->count() > 0)
<div class="card-footer d-flex justify-content-between align-items-center">
   <div class="text-muted small">
      @if($daySettings->total() > 0)
      Menampilkan {{ $daySettings->firstItem() }} - {{ $daySettings->lastItem() }} dari {{ $daySettings->total() }} data
      @endif
   </div>
   <div>
      {{ $daySettings->links() }}
   </div>
</div>
@endif

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function() {
      // Tooltips untuk notes yang dipotong
      $('[title]').tooltip({
         trigger: 'hover',
         placement: 'top'
      });
   });
</script>
@endpush