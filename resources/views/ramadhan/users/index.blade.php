@extends('ramadhan.layouts.app')

@section('content')

<div class="container-xl mb-4">
   <div class="row g-2 align-items-center">
      <div class="col">
         <h2 class="page-title">Daftar Users</h2>
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

      @endif

      <div class="card">
         <div class="card-header d-flex justify-content-between">

            <form method="GET" class="d-flex">
               <input id="search-input" type="text" name="q"
                  class="form-control"
                  placeholder="Cari Siapa..."
                  value="{{ $q }}">
               <!-- <button class="btn btn-primary ms-2">Cari</button> -->
            </form>

            <a href="{{ route('users.create') }}" class="btn btn-primary">
               + Tambah User
            </a>
         </div>

         {{-- 🔥 AJAX container --}}
         <div id="cards-container">
            @include('ramadhan.users.partials.table', ['users' => $users])
         </div>

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
         const url = new URL("{{ route('users.index') }}", location.origin);
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