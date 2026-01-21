@extends('ramadhan.layouts.app')

@section('content')
<div class="container-xl">

   <!-- Header -->
   <div class="page-header mb-4">
      <div class="row align-items-center">
         <div class="col">
            <h2 class="page-title">
               <i class="ti ti-users me-2"></i> Daftar Jamaah Ramadhan
            </h2>
            <div class="page-subtitle">
               Pilih jamaah untuk melihat jadwal takjil
            </div>
         </div>
      </div>
   </div>

   <!-- Search Card -->
   <div class="card mb-4">
      <div class="card-body">
         <label class="form-label">Cari Jamaah</label>

         <div class="row g-2 align-items-center">
            {{-- INPUT SEARCH --}}
            <div class="col">
               <div class="input-icon">
                  <span class="input-icon-addon">
                     <i class="ti ti-search"></i>
                  </span>
                  <input
                     type="text"
                     id="search-input"
                     class="form-control"
                     placeholder="Ketik nama atau alamat jamaah…"
                     value="{{ request('search') }}">
               </div>
            </div>

            {{-- BUTTON RESET --}}
            <div class="col-auto">
               <a href="{{ route('takjil-jamaah') }}"
                  class="btn btn-outline-secondary">
                  <i class="ti ti-refresh"></i>
                  Reset
               </a>
            </div>
         </div>

         <div class="form-hint mt-2">
            Pencarian berjalan otomatis saat Anda mengetik
         </div>
      </div>
   </div>


   <!-- Result -->
   <div id="jamaah-table-container">
      @include('ramadhan.guests.partials.table')
   </div>

</div>
@endsection

@push('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('search-input');
      const container = document.getElementById('jamaah-table-container');
      let searchTimer = null;

      // Fungsi untuk mengambil data dengan AJAX
      function fetchResults(search = '') {
         const url = new URL("{{ route('takjil-jamaah') }}", location.origin);

         if (search) url.searchParams.set('search', search);
         url.searchParams.set('ajax', '1'); // Untuk identifikasi request AJAX

         // Tampilkan loading
         container.innerHTML = `
            <div class="text-center py-5">
               <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
               </div>
               <p class="mt-2">Memuat data jamaah...</p>
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

               window.history.replaceState({}, '', u);
            })
            .catch(err => {
               console.error('Error:', err);
               container.innerHTML = `
                  <div class="alert alert-danger">
                     <i class="fas fa-exclamation-triangle me-2"></i>
                     Terjadi kesalahan saat memuat data. Silakan coba lagi.
                  </div>
               `;
            });
      }

      // Event listener untuk pencarian real-time
      searchInput.addEventListener('input', function() {
         clearTimeout(searchTimer);
         searchTimer = setTimeout(() => {
            fetchResults(this.value.trim());
         }, 300);
      });

      // Auto fokus ke input pencarian
      searchInput.focus();

      // Handle pagination links
      container.addEventListener('click', function(e) {
         const link = e.target.closest('a');
         if (!link) return;

         const href = link.getAttribute('href');
         if (!href || !href.includes('page=')) return;

         e.preventDefault();

         // Tambahkan parameter pencarian ke URL pagination
         const url = new URL(href, location.origin);
         if (searchInput.value.trim()) url.searchParams.set('search', searchInput.value.trim());
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

      // Auto load jika ada parameter pencarian di URL
      if (request('search'))
         fetchResults("{{ request('search') }}");
      endif
   });
</script>
<script>
   document.getElementById('reset-search')?.addEventListener('click', function() {
      const input = document.getElementById('search-input');
      input.value = '';

      // Reset URL
      const url = new URL(window.location);
      url.searchParams.delete('search');
      window.history.replaceState({}, '', url.pathname);

      // Reload data awal
      if (typeof fetchResults === 'function') {
         fetchResults('');
      }

      input.focus();
   });
</script>
@endpush