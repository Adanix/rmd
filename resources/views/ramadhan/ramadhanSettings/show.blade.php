@extends('ramadhan.layouts.app')

@section('content')
<div class="container-xl mb-4">
   <div class="row g-2 align-items-center">
      <div class="col">
         <h2 class="page-title">Detail Pengaturan Ramadhan</h2>
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

      @if(session('error'))
      <div id="alert-error" class="alert alert-danger alert-dismissible" role="alert">
         <div class="alert-icon">
            <!-- Download SVG icon from http://tabler.io/icons/icon/check -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon icon-2">
               <path d="M5 12l5 5l10 -10"></path>
            </svg>
         </div>
         {{ session('error') }}
         <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
      </div>
      @endif

      {{-- DETAIL --}}
      <div class="card mb-4">
         <div class="card-body">

            <h3>Informasi Ramadhan</h3>
            <table class="table">
               <tr>
                  <th>Start Date</th>
                  <td>{{ $ramadhanSetting->start_date->format('d M Y') }}</td>
               </tr>
               <tr>
                  <th>End Date</th>
                  <td>{{ $ramadhanSetting->end_date->format('d M Y') }}</td>
               </tr>
               <tr>
                  <th>Total Days</th>
                  <td>{{ $ramadhanSetting->days }}</td>
               </tr>
               <tr>
                  <th>Total Setoran</th>
                  <td>{{ number_format($ramadhanSetting->total_setoran) }}</td>
               </tr>
               <tr>
                  <th>Special Quotas</th>
                  <td>
                     @if (is_array($ramadhanSetting->special_quotas))
                     {{ implode(', ', $ramadhanSetting->special_quotas) ?: '-' }}
                     @else
                     -
                     @endif
                  </td>
               </tr>
               <tr>
                  <th>Holidays</th>
                  <td>
                     @if (is_array($ramadhanSetting->holidays))
                     {{ implode(', ', $ramadhanSetting->holidays) ?: '-' }}
                     @else
                     -
                     @endif
                  </td>
               </tr>
               <tr>
                  <th>Catatan</th>
                  <td>{{ $ramadhanSetting->notes ?? '-' }}</td>
               </tr>
            </table>

         </div>
      </div>

      <div class="mb-3 d-flex gap-2">
         {{-- BUTTON CREATE DAY SETTING --}}
         <a href="{{ route('day-settings.create', $ramadhanSetting->id) }}" class="btn btn-primary">
            + Buat Day Setting
         </a>

         {{-- BUTTON UPDATE DAY SETTING --}}
         <a href="{{ route('day-settings.edit', $ramadhanSetting->id) }}" class="btn btn-warning">
            + Edit Day Setting
         </a>
      </div>


      {{-- LIST DAY SETTINGS --}}
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Daftar Day Setting</h3>
         </div>

         <div class="card-body">
            <table class="table table-bordered table-striped">
               <thead>
                  <tr>
                     <th>Tanggal</th>
                     <th>Quota</th>
                     <th>Makanan Planned</th>
                     <th>Minuman Planned</th>
                     <th>Catatan</th>
                  </tr>
               </thead>

               <tbody>
                  @forelse($ramadhanSetting->daySettings as $day)
                  <tr>
                     <td>{{ $day->date->format('d M Y') }}</td>
                     <td>{{ $day->quota }}</td>
                     <td>{{ $day->total_makanan_planned ?? '-' }}</td>
                     <td>{{ $day->total_minuman_planned ?? '-' }}</td>
                     <td>{{ $day->notes ?? '-' }}</td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="6" class="text-center">Belum ada day setting.</td>
                  </tr>
                  @endforelse
               </tbody>

            </table>
         </div>
      </div>

   </div>
</div>
@endsection

@push('scripts')
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