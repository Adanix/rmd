@extends('ramadhan.layouts.app')

@section('content')
<div class="container-xl mb-4">
   <div class="row g-2 align-items-center">
      <div class="col">
         <h2 class="page-title">Dashboard</h2>
         <div class="text-muted mt-1">
            <span class="text-primary">{{ now()->translatedFormat('l, d F Y') }}</span>
         </div>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">
      <!-- Ringkasan Stats -->
      <div class="row row-deck row-cards mb-4">
         <div class="col-sm-6 col-lg-3">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex align-items-center">
                     <div class="subheader">Total Jamaah</div>
                  </div>
                  <div class="h1 mb-3">{{ $totalJamaah }}</div>
                  <div class="d-flex mb-2">
                     <div>Total Setoran:</div>
                     <div class="ms-auto">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                           {{ number_format($totalSetoran) }}
                        </span>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-sm-6 col-lg-3">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex align-items-center">
                     <div class="subheader">Takjil Hari Ini</div>
                  </div>
                  <div class="h1 mb-3">{{ $takjilHariIni }}</div>
                  <div class="d-flex mb-2">
                     <div>Kuota Tersedia:</div>
                     <div class="ms-auto">
                        <span class="{{ $quotaTersedia > 0 ? 'text-green' : 'text-danger' }} d-inline-flex align-items-center lh-1">
                           {{ $quotaTersedia }}
                        </span>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-sm-6 col-lg-3">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex align-items-center">
                     <div class="subheader">Jamaah Mampu</div>
                  </div>
                  <div class="h1 mb-3">{{ $jamaahMampu }}</div>
                  <div class="d-flex mb-2">
                     <div>Setoran:</div>
                     <div class="ms-auto">
                        <span class="text-blue d-inline-flex align-items-center lh-1">
                           {{ number_format($setoranMampu) }}
                        </span>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-sm-6 col-lg-3">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex align-items-center">
                     <div class="subheader">Jamaah Kurang Mampu</div>
                  </div>
                  <div class="h1 mb-3">{{ $jamaahKurangMampu }}</div>
                  <div class="d-flex mb-2">
                     <div>Setoran:</div>
                     <div class="ms-auto">
                        <span class="text-orange d-inline-flex align-items-center lh-1">
                           {{ number_format($setoranKurangMampu) }}
                        </span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Chart dan Distribusi -->
      <div class="row row-deck row-cards mb-4">
         <!-- Chart Trend 7 Hari -->
         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Trend Takjil 7 Hari Terakhir</h3>
               </div>
               <div class="card-body">
                  <div id="chart-trend" style="height: 300px;"></div>
               </div>
            </div>
         </div>

         <!-- Distribusi Ekonomi -->
         <div class="col-lg-4">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Distribusi Ekonomi Jamaah</h3>
               </div>
               <div class="card-body">
                  <div class="row">
                     @foreach($ekonomiStats as $stat)
                     <div class="col-6">
                        <div class="mb-3">
                           <div class="d-flex mb-1 align-items-center">
                              <span>{{ $stat->ekonomi }}</span>
                              <span class="ms-auto">{{ $stat->total }}</span>
                           </div>
                           <div class="progress progress-sm">
                              <div class="progress-bar"
                                 style="width: {{ ($stat->total / $totalJamaah) * 100 }}%"
                                 role="progressbar">
                              </div>
                           </div>
                        </div>
                     </div>
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Makanan/Minuman Populer -->
      <div class="row row-deck row-cards">
         <div class="col-lg-6">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">5 Makanan Terpopuler</h3>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-vcenter">
                        <thead>
                           <tr>
                              <th>Makanan</th>
                              <th>Jumlah</th>
                              <th class="w-1"></th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($makananPopuler as $item)
                           <tr>
                              <td>{{ $item->makanan->nama }}</td>
                              <td class="text-muted">{{ $item->total }}</td>
                              <td>
                                 <span class="badge bg-primary-lt">{{ $takjilHariIni > 0 ? round(($item->total / $takjilHariIni) * 100) : 0 }}%</span>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-lg-6">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">5 Minuman Terpopuler</h3>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-vcenter">
                        <thead>
                           <tr>
                              <th>Minuman</th>
                              <th>Jumlah</th>
                              <th class="w-1"></th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($minumanPopuler as $item)
                           <tr>
                              <td>{{ $item->minuman->nama }}</td>
                              <td class="text-muted">{{ $item->total }}</td>
                              <td>
                                 <span class="badge bg-green-lt">{{ $takjilHariIni > 0 ? round(($item->total / $takjilHariIni) * 100) : 0 }}%</span>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Quick Actions -->
      <div class="row mt-4">
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Aksi Cepat</h3>
               </div>
               <div class="card-body">
                  <div class="btn-list">
                     <a href="{{ route('jamaahs.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i>
                        Tambah Jamaah
                     </a>
                     <a href="{{ route('day-settings.index') }}" class="btn btn-success">
                        <i class="ti ti-notes"></i>
                        Input Takjil
                     </a>
                     <a href="{{ route('day-settings.index') }}" class="btn btn-info">
                        <i class="ti ti-calendar"></i>
                        Atur Kuota Harian
                     </a>
                     <a href="{{ route('makanans.index') }}" class="btn btn-warning">
                        <i class="ti ti-pizza"></i>
                        Kelola Makanan
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
      var trendData = @json($trendHarian->pluck('total_takjil'));
      var trendLabels = @json($trendHarian->pluck('tanggal'));

      var options = {
         chart: {
            type: 'line',
            height: 300,
            toolbar: {
               show: false
            }
         },
         series: [{
            name: 'Takjil',
            data: trendData
         }],
         xaxis: {
            categories: trendLabels
         },
         colors: ['#206bc4'],
         stroke: {
            width: 3,
            curve: 'smooth'
         },
         markers: {
            size: 5
         }
      };

      var chart = new ApexCharts(document.querySelector("#chart-trend"), options);
      chart.render();
   });
</script>
@endpush