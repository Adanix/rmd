@extends('ramadhan.layouts.app')

@section('content')
<div class="container-fluid">
   <div class="row mb-3">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Jadwal Takjil Ramadhan</h4>
               <div class="btn-group">
                  <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="fas fa-file-export me-1"></i> Export Data
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                     <li>
                        <a class="dropdown-item" href="#" id="export-pdf-btn">
                           <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                        </a>
                     </li>
                     <li>
                        <a class="dropdown-item" href="#" id="export-excel-btn">
                           <i class="fas fa-file-excel text-success me-2"></i> Excel
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="card-body">
               <form method="GET" action="{{ route('jadwal-takjil.index') }}" id="search-form">
                  <div class="row g-3">
                     <div class="col-md-5">
                        <label for="date" class="form-label">Filter Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date"
                           value="{{ request('date') }}">
                     </div>
                     <div class="col-md-5">
                        <label for="search" class="form-label">Cari Jamaah</label>
                        <input type="text" class="form-control" id="search-input" name="search"
                           value="{{ request('search') }}" placeholder="Nama atau alamat jamaah...">
                     </div>
                     <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                           <i class="fas fa-search me-1"></i> Filter
                        </button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-body p-0">
               <div id="jadwal-table-container">
                  @include('ramadhan.jadwalTakjils.partials.table')
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDelete" tabindex="-1">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Konfirmasi Hapus</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>
         <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus jadwal takjil untuk <strong id="jamaah-name"></strong>?</p>
            <p class="text-danger"><small>Data yang dihapus tidak dapat dikembalikan.</small></p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <form id="delete-form" method="POST" style="display: inline;">
               @csrf
               @method('DELETE')
               <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection

@push('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('search-input');
      const dateInput = document.getElementById('date');
      const container = document.getElementById('jadwal-table-container');
      let searchTimer = null;
      let dateTimer = null;

      // Fungsi untuk mengambil data dengan AJAX
      function fetchResults(search = '', date = '') {
         const url = new URL("{{ route('jadwal-takjil.index') }}", location.origin);

         if (search) url.searchParams.set('search', search);
         if (date) url.searchParams.set('date', date);

         url.searchParams.set('ajax', '1'); // Tambahkan parameter untuk identifikasi request AJAX

         // Tampilkan loading
         container.innerHTML = `
            <div class="text-center py-5">
               <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
               </div>
               <p class="mt-2">Memuat data...</p>
            </div>
         `;

         fetch(url.toString(), {
               headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'text/html'
               }
            })
            .then(response => {
               if (!response.ok) throw new Error('Network response was not ok');
               return response.text();
            })
            .then(html => {
               container.innerHTML = html;

               // Update URL di browser tanpa reload
               const u = new URL(window.location);
               if (search) u.searchParams.set('search', search);
               else u.searchParams.delete('search');

               if (date) u.searchParams.set('date', date);
               else u.searchParams.delete('date');

               window.history.replaceState({}, '', u);
            })
            .catch(err => {
               console.error('Error:', err);
               container.innerHTML = `
                  <div class="alert alert-danger">
                     Terjadi kesalahan saat memuat data. Silakan coba lagi.
                  </div>
               `;
            });
      }

      // Event listener untuk pencarian real-time
      searchInput.addEventListener('input', function() {
         clearTimeout(searchTimer);
         searchTimer = setTimeout(() => {
            fetchResults(this.value.trim(), dateInput.value);
         }, 300);
      });

      // Event listener untuk filter tanggal
      dateInput.addEventListener('change', function() {
         clearTimeout(dateTimer);
         dateTimer = setTimeout(() => {
            fetchResults(searchInput.value.trim(), this.value);
         }, 300);
      });

      // Handle klik tombol hapus di dalam kontainer
      container.addEventListener('click', function(e) {
         // Handle delete button
         if (e.target.closest('.delete-btn')) {
            const btn = e.target.closest('.delete-btn');
            const takjilId = btn.dataset.id;
            const jamaahName = btn.dataset.name;

            // Set modal content
            document.getElementById('jamaah-name').textContent = jamaahName;

            // Set form action
            const deleteUrl = "{{ url('takjils') }}/" + takjilId;
            document.getElementById('delete-form').action = deleteUrl;

            // Show modal
            new bootstrap.Modal(document.getElementById('confirmDelete')).show();
            e.preventDefault();
         }

         // Handle pagination links
         const link = e.target.closest('a');
         if (!link) return;

         const href = link.getAttribute('href');
         if (!href || !href.includes('page=')) return;

         e.preventDefault();

         // Tambahkan parameter pencarian ke URL pagination
         const url = new URL(href, location.origin);
         if (searchInput.value.trim()) url.searchParams.set('search', searchInput.value.trim());
         if (dateInput.value) url.searchParams.set('date', dateInput.value);
         url.searchParams.set('ajax', '1');

         fetch(url.toString(), {
               headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'text/html'
               }
            })
            .then(r => r.text())
            .then(html => {
               container.innerHTML = html;
               window.history.pushState({}, '', url);
            })
            .catch(err => console.error(err));
      });

      // Handle form submit (jika ada yang masih menggunakannya)
      document.getElementById('search-form')?.addEventListener('submit', function(e) {
         e.preventDefault();
         fetchResults(searchInput.value.trim(), dateInput.value);
      });

      // Reset modal ketika ditutup
      document.getElementById('confirmDelete')?.addEventListener('hidden.bs.modal', function() {
         document.getElementById('jamaah-name').textContent = '';
         document.getElementById('delete-form').action = '#';
      });

      // Auto load jika ada parameter pencarian di URL
      // @if(request('search') || request('date'))
      // fetchResults("{{ request('search') }}", "{{ request('date') }}");
      // @endif
      if (request('search') || request('date'))
         fetchResults("{{ request('search') }}", "{{ request('date') }}");
      endif
   });
</script>
<script>
   // Export PDF
   document.getElementById('export-pdf-btn')?.addEventListener('click', function(e) {
      e.preventDefault();
      const search = document.getElementById('search-input').value;
      const date = document.getElementById('date').value;

      let url = "{{ route('jadwal-takjil.export.pdf') }}";
      const params = new URLSearchParams();

      if (search) params.append('search', search);
      if (date) params.append('date', date);

      if (params.toString()) {
         url += '?' + params.toString();
      }

      window.open(url, '_blank');
   });

   // Export Excel
   document.getElementById('export-excel-btn')?.addEventListener('click', function(e) {
      e.preventDefault();
      const search = document.getElementById('search-input').value;
      const date = document.getElementById('date').value;

      let url = "{{ route('jadwal-takjil.export.excel') }}";
      const params = new URLSearchParams();

      if (search) params.append('search', search);
      if (date) params.append('date', date);

      if (params.toString()) {
         url += '?' + params.toString();
      }

      window.open(url, '_blank');
   });
</script>
@endpush