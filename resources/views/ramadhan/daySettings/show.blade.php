@extends('ramadhan.layouts.app')

@section('content')
<div class="container-xl mb-4">
   <div class="row g-2 align-items-center">
      <div class="col">
         <h2 class="page-title">Detail Pengaturan Hari Ramadhan</h2>
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
            <!-- Icon error -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
               viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               class="icon alert-icon icon-2">
               <circle cx="12" cy="12" r="10"></circle>
               <line x1="12" y1="8" x2="12" y2="12"></line>
               <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
         </div>
         {{ session('error') }}
         <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
      </div>
      @endif

      {{-- DETAIL DAY SETTING --}}
      <div class="card mb-4">
         <div class="card-body">

            <h3>Informasi Hari</h3>
            <table class="table">
               <tr>
                  <th>Tanggal</th>
                  <td>{{ $daySetting->date->translatedFormat('d F Y') }}</td>
               </tr>
               <tr>
                  <th>Quota</th>
                  <td>{{ number_format($daySetting->quota) }}</td>
               </tr>
               <tr>
                  <th>Total Makanan Planned</th>
                  <td>{{ $daySetting->total_makanan_planned ?? '-' }}</td>
               </tr>
               <tr>
                  <th>Total Minuman Planned</th>
                  <td>{{ $daySetting->total_minuman_planned ?? '-' }}</td>
               </tr>
               <tr>
                  <th>Catatan</th>
                  <td>{{ $daySetting->notes ?? '-' }}</td>
               </tr>
            </table>

         </div>
      </div>

      <div class="mb-3 d-flex gap-2">
         {{-- BUTTON CREATE --}}
         <a href="{{ route('takjils.create', $daySetting->id) }}"
            class="btn btn-primary">
            + Buat Jadwal Takjil
         </a>

         {{-- BUTTON EDIT --}}
         <a href="{{ route('takjils.edit', $daySetting->id) }}"
            class="btn btn-warning">
            Edit Jadwal Takjil
         </a>
      </div>


      {{-- LIST TAKJIL PER HARI --}}
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Daftar Takjil Hari Ini</h3>
         </div>

         <div class="card-body">
            <table class="table table-bordered table-striped">
               <thead>
                  <tr>
                     <th>Nama Jamaah</th>
                     <th>Alamat</th>
                     <th>Tanggal Masehi</th>
                     <th>Setoran</th>
                     <th>Keterangan</th>
                     <th>Aksi</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($daySetting->takjils as $takjil)
                  <tr>
                     <td>{{ $takjil->jamaah->nama }}</td>
                     <td>{{ $takjil->jamaah->alamat }}</td>
                     <td>{{ $daySetting->date->translatedFormat('d F Y') }}</td>
                     <td>
                        @php
                        $makananList = $takjil->makanans->map(function($item) {
                        return $item->makanan->nama . ($item->jumlah > 1 ? ' (' . $item->jumlah . ')' : '');
                        })->implode(', ');

                        $minumanList = $takjil->minumans->map(function($item) {
                        return $item->minuman->nama . ($item->jumlah > 1 ? ' (' . $item->jumlah . ')' : '');
                        })->implode(', ');
                        @endphp

                        @if($makananList && $minumanList)
                        Makanan: {{ $makananList }}<br>
                        Minuman: {{ $minumanList }}
                        @elseif($makananList)
                        Makanan: {{ $makananList }}
                        @elseif($minumanList)
                        Minuman: {{ $minumanList }}
                        @else
                        -
                        @endif
                     </td>
                     <td>{{ $takjil->keterangan ?? '-' }}</td>
                     <td>
                        <form action="{{ route('takjils.destroy', $takjil->id) }}" method="POST" class="d-inline">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="btn btn-sm btn-danger"
                              onclick="return confirm('Apakah Anda yakin ingin menghapus data takjil untuk {{ $takjil->jamaah->nama }}?')">
                              <i class="fas fa-trash"></i> Hapus
                           </button>
                        </form>
                     </td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="6" class="text-center">Belum ada data takjil.</td>
                  </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>

      {{-- Tombol Kembali di pojok kanan bawah --}}
      <div class="d-flex justify-content-end mt-3">
         <a href="{{ route('day-settings.index') }}"
            class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
         </a>
      </div>
   </div>
</div>

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
@endsection