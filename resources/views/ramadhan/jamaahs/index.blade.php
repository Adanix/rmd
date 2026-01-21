@extends('ramadhan.layouts.app')

@section('content')

<div class="container-xl mb-4">
   <div class="row g-2 align-items-center">
      <div class="col">
         <h2 class="page-title">Daftar Jama`ah</h2>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">

      @if(session('success'))
      <div id="alert-success" class="alert alert-success alert-dismissible" role="alert">
         <div class="alert-icon">
            <!-- Download SVG icon from http://tabler.io/icons/icon/check -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon icon-2">
               <path d="M5 12l5 5l10 -10"></path>
            </svg>
         </div>
         {{ session('success') }}
         <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
      </div>
      @elseif(session('error'))
      <div id="alert-error" class="alert alert-danger alert-dismissible" role="alert">
         <div class="alert-icon">
            <!-- Download SVG icon from http://tabler.io/icons/icon/alert-circle -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon icon-2">
               <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
               <path d="M12 8v4"></path>
               <path d="M12 16h.01"></path>
            </svg>
         </div>
         {{ session('error') }}
         <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
      </div>
      @endif

      <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
            <form method="GET" class="d-flex">
               <input id="search-input" type="text" name="q"
                  class="form-control"
                  placeholder="Cari jamaah..."
                  value="{{ $q }}">
            </form>

            <div class="d-flex gap-2">
               <a href="{{ route('jamaahs.create') }}" class="btn btn-primary">
                  + Tambah Jama`ah
               </a>
               <!-- Button trigger modal -->
               <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                  + Tambah dengan Excel
               </button>
            </div>
         </div>

         {{-- 🔥 AJAX container --}}
         <div id="cards-container">
            @include('ramadhan.jamaahs.partials.table', ['jamaahs' => $jamaahs])
         </div>
      </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="importExcelModalLabel">Import Data Jamaah dari Excel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="{{ route('jamaahs.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
               <div class="mb-3">
                  <label for="file" class="form-label">Pilih File Excel</label>
                  <input type="file" class="form-control @error('file') is-invalid @enderror"
                     id="file" name="file" accept=".xlsx,.xls,.csv" required>
                  @error('file')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="alert alert-info">
                  <strong>Format file Excel harus sesuai:</strong>
                  <ul class="mb-0 mt-2">
                     <li>Kolom A: <code>nama</code> (text)</li>
                     <li>Kolom B: <code>alamat</code> (text)</li>
                     <li>Kolom C: <code>ekonomi</code> (Mampu/Kurang Mampu)</li>
                     <li>Kolom D: <code>setoran</code> (angka)</li>
                     <li>Kolom E: <code>keterangan</code> (Makanan dan Minuman/Makanan/Minuman. optional)</li>
                     <li>Kolom F: <code>notes</code> (text, optional)</li>
                  </ul>
               </div>

               <div class="form-text">
                  <a href="https://docs.google.com/spreadsheets/d/YOUR_GOOGLE_SHEET_ID/edit?usp=sharing"
                     class="btn btn-sm btn-outline-primary"
                     id="download-template-btn"
                     target="_blank">
                     📥 Download Template Excel
                  </a>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-primary">Import Data</button>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection

@push('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('search-input');
      const container = document.getElementById('cards-container');
      let timer = null;

      function fetchResults(q = '') {
         const url = new URL("{{ route('jamaahs.index') }}", location.origin);
         if (q) url.searchParams.set('q', q);

         fetch(url.toString(), {
               headers: {
                  'X-Requested-With': 'XMLHttpRequest'
               }
            })
            .then(r => r.text())
            .then(html => container.innerHTML = html)
            .catch(err => console.error(err));
      }

      input.addEventListener('input', function() {
         clearTimeout(timer);
         timer = setTimeout(() => {
            fetchResults(this.value.trim());

            const u = new URL(window.location);
            if (this.value.trim()) u.searchParams.set('q', this.value.trim());
            else u.searchParams.delete('q');
            window.history.replaceState({}, '', u);
         }, 300);
      });

      container.addEventListener('click', function(e) {
         const a = e.target.closest('a');
         if (!a) return;

         const href = a.getAttribute('href');
         if (!href) return;

         if (href.includes('page=')) {
            e.preventDefault();

            fetch(href, {
                  headers: {
                     'X-Requested-With': 'XMLHttpRequest'
                  }
               })
               .then(r => r.text())
               .then(html => {
                  container.innerHTML = html;
                  window.history.pushState({}, '', href);
               });
         }
      });

      // Auto load jika ada q
      // @if(!empty($q))
      // fetchResults("{{ addslashes($q) }}");
      // @endif
      if (!empty($q))
         fetchResults("{{ addslashes($q) }}");
      endif
   });
</script>
<script>
   document.addEventListener("DOMContentLoaded", function() {
      const alertEl = document.getElementById("alert-success");
      if (alertEl) {
         setTimeout(() => {
            alertEl.classList.remove("show");
            setTimeout(() => alertEl.remove(), 500);
         }, 3000);
      }
   });
</script>
@endpush