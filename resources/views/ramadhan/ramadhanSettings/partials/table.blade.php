<div class="table-responsive">
   <table class="table card-table table-vcenter">
      <thead>
         <tr>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Day</th>
            <th>Total Setoran</th>
            <th>Special Quota</th>
            <th>Holidays</th>
            <th>Notes</th>
            <th></th>
         </tr>
      </thead>

      <tbody>
         @forelse ($ramadhanSettings as $ramadhanSetting)
         <tr>
            <td>{{ $ramadhanSetting->start_date->format('d M Y') }}</td>
            <td>{{ $ramadhanSetting->end_date->format('d M Y') }}</td>
            <td>{{ $ramadhanSetting->days }}</td>
            <td>{{ number_format($ramadhanSetting->total_setoran) }}</td>

            {{-- Special Quotas --}}
            <td>
               @if (is_array($ramadhanSetting->special_quotas) && count($ramadhanSetting->special_quotas))
               {{ implode(', ', $ramadhanSetting->special_quotas) }}
               @else
               -
               @endif
            </td>

            {{-- Holidays --}}
            <td>
               @if (is_array($ramadhanSetting->holidays) && count($ramadhanSetting->holidays))
               {{ implode(', ', $ramadhanSetting->holidays) }}
               @else
               -
               @endif
            </td>

            <td>{{ $ramadhanSetting->notes ?? '-' }}</td>

            <td class="text-end">
               <a href="{{ route('ramadhan-settings.show', $ramadhanSetting) }}"
                  class="btn btn-sm btn-info">Day Settings</a>

               <a href="{{ route('ramadhan-settings.edit', $ramadhanSetting) }}"
                  class="btn btn-sm btn-warning">Edit</a>

               <button type="button"
                  class="btn btn-danger btn-sm delete-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#confirmDelete"
                  data-id="{{ $ramadhanSetting->id }}"
                  data-name="{{ $ramadhanSetting->start_date }}">
                  Delete
               </button>
            </td>
         </tr>
         @empty
         <tr>
            <td colspan="8" class="text-center">Tidak ada data.</td>
         </tr>
         @endforelse
      </tbody>
   </table>
</div>

{{-- Single Modal --}}
@if($ramadhanSettings->count() > 0)
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

@if($ramadhanSettings->count() > 0)
<div class="card-footer">
   {{ $ramadhanSettings->links() }}
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
         const baseUrl = "{{ route('ramadhan-settings.destroy', ':id') }}";
         const deleteUrl = baseUrl.replace(':id', foodId);
         $('#delete-form').attr('action', deleteUrl);
      });

      // Reset modal ketika ditutup
      $('#confirmDelete').on('hidden.bs.modal', function() {
         $('#food-name').text('Loading...');
      });
   });
</script>