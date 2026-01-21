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
            <th></th>
         </tr>
      </thead>

      <tbody>
         @forelse ($daySettings as $daySetting)
         <tr>
            <td>{{ $daySetting->ramadhan_setting_id }}</td>
            <td>{{ $daySetting->date->format('d M Y') }}</td>
            <td>
               <div class="d-flex align-items-center">
                  <span class="me-2">{{ $daySetting->takjils_count }}/{{ $daySetting->quota }}</span>
                  <div class="progress flex-grow-1" style="height: 6px;">
                     <div class="progress-bar {{ $daySetting->takjils_count >= $daySetting->quota ? 'bg-success' : 'bg-primary' }}"
                        role="progressbar"
                        style="width: {{ min(100, ($daySetting->takjils_count / $daySetting->quota) * 100) }}%">
                     </div>
                  </div>
                  @if($daySetting->takjils_count >= $daySetting->quota)
                  <span class="badge bg-success ms-2">Full</span>
                  @endif
               </div>
            </td>
            <td>{{ $daySetting->total_makanan_planned }}</td>
            <td>{{ $daySetting->total_minuman_planned }}</td>
            <td>{{ $daySetting->notes }}</td>
            <td class="text-end">
               <a href="{{ route('day-settings.show', $daySetting) }}" class="btn btn-sm btn-primary">Add Jadwal</a>
               <a href="{{ route('day-settings.edit', $daySetting->ramadhan_setting_id) }}" class="btn btn-sm btn-warning">Edit</a>
               {{-- <button type="button" class="btn btn-danger btn-sm delete-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#confirmDelete"
                  data-id="{{ $daySetting->id }}"
               data-name="{{ $daySetting->date }}">
               Delete
               </button> --}}
            </td>
         </tr>
         @empty
         <tr>
            <td colspan="7" class="text-center">Tidak ada data.</td>
         </tr>
         @endforelse
      </tbody>
   </table>
</div>

{{-- Single Modal --}}
@if($daySettings->count() > 0)
<div class="modal fade" id="confirmDelete" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body text-center">
            <p>Are you sure you want to delete "<strong><span id="food-name">Loading...</span></strong>"?</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form id="delete-form" method="POST" class="d-inline">
               @csrf
               @method('DELETE')
               <button type="submit" class="btn btn-danger">Delete</button>
            </form>
         </div>
      </div>
   </div>
</div>
@endif

@if($daySettings->count() > 0)
<div class="card-footer">
   {{ $daySettings->links() }}
</div>
@endif

{{-- Pastikan jQuery atau vanilla JS bekerja --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function() {
      // Gunakan event delegation untuk handle dynamic content
      $(document).on('click', '.delete-btn', function() {
         const foodId = $(this).data('id');
         const foodName = $(this).data('name');

         console.log('Delete button clicked:', foodId, foodName); // Debug

         // Update modal content
         $('#food-name').text(foodName);

         // Update form action
         const baseUrl = "{{ route('day-settings.destroy', ':id') }}";
         const deleteUrl = baseUrl.replace(':id', foodId);
         $('#delete-form').attr('action', deleteUrl);
      });

      // Reset modal ketika ditutup
      $('#confirmDelete').on('hidden.bs.modal', function() {
         $('#food-name').text('Loading...');
      });
   });
</script>