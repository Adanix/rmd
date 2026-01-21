@extends('ramadhan.layouts.app')

@section('content')

<div class="page-header">
   <div class="container-xl">
      <h2 class="page-title">
         Generate Day Settings Ramadhan {{ $ramadhanSetting->start_date->year }}
      </h2>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">

      @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <form id="daySettingsForm" action="{{ route('day-settings.store', $ramadhanSetting->id) }}" method="POST">
         @csrf

         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <h3 class="card-title">Pengaturan Hari</h3>

               <div>
                  <div>Total Setoran: <strong id="displayTotalSetoran">{{ number_format($ramadhanSetting->total_setoran) }}</strong></div>
                  <div>Sisa Kuota: <strong id="sisaQuota">0</strong> <span id="statusQuota" class="badge bg-secondary ms-1">-</span></div>
                  <div>Total Kuota Input: <strong id="totalQuota">0</strong></div>
                  <div>Total Makanan: <strong id="totalMakanan">0</strong></div>
                  <div>Total Minuman: <strong id="totalMinuman">0</strong></div>
               </div>
            </div>

            <div class="card-body">
               <div class="mb-3">
                  <button type="button" id="btnAutofill" class="btn btn-info me-2">Autofill Pembagian Kuota</button>
                  <button type="button" id="btnReset" class="btn btn-secondary">Reset Ke Default</button>
               </div>
            </div>

            <div class="table-responsive">
               <table class="table table-bordered" id="dayTable">
                  <thead class="table-light">
                     <tr>
                        <th style="width:140px">Tanggal</th>
                        <th style="width:120px">Hari</th>
                        <th style="width:120px">Kuota</th>
                        <th style="width:120px">Makanan</th>
                        <th style="width:120px">Minuman</th>
                        <th>Catatan</th>
                     </tr>
                  </thead>

                  <tbody>
                     @foreach($days as $index => $day)
                     <tr data-index="{{ $index }}">
                        <td>{{ $day['date'] }}</td>
                        <td>{{ $day['dayname'] }}</td>

                        <td>
                           <input type="number" min="0"
                              name="days[{{ $index }}][quota]"
                              class="form-control quota-input"
                              value="{{ $day['quota'] }}">
                           <input type="hidden" name="days[{{ $index }}][date]" value="{{ $day['date'] }}">
                        </td>

                        <td>
                           <input type="number" min="0"
                              name="days[{{ $index }}][total_makanan_planned]"
                              class="form-control makanan-input"
                              value="{{ $day['total_makanan_planned'] }}">
                        </td>

                        <td>
                           <input type="number" min="0"
                              name="days[{{ $index }}][total_minuman_planned]"
                              class="form-control minuman-input"
                              value="{{ $day['total_minuman_planned'] }}">
                        </td>

                        <td>
                           <input type="text"
                              name="days[{{ $index }}][notes]"
                              class="form-control notes-input"
                              value="{{ $day['notes'] }}">
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>

            <div class="card-footer text-end">
               <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Kembali</a>
               <button type="submit" class="btn btn-primary">Kirim Data</button>
            </div>
         </div>
      </form>

   </div>
</div>

<style>
   /* highlight row over quota */
   .row-over {
      background-color: #ffe6e6 !important;
   }
</style>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      // Data dari server (default initial values)
      const defaultDays = @json($days);
      const totalSetoran = Number(@json($ramadhanSetting->total_setoran)) || 0;

      // Element refs
      const table = document.getElementById('dayTable');
      const tbody = table.querySelector('tbody');
      const btnAutofill = document.getElementById('btnAutofill');
      const btnReset = document.getElementById('btnReset');

      const elTotalQuota = document.getElementById('totalQuota');
      const elSisaQuota = document.getElementById('sisaQuota');
      const elStatusQuota = document.getElementById('statusQuota');
      const elTotalMakanan = document.getElementById('totalMakanan');
      const elTotalMinuman = document.getElementById('totalMinuman');

      // Helper: get fresh rows NodeList
      function getRows() {
         return Array.from(tbody.querySelectorAll('tr'));
      }

      // Calculate current totals and update UI
      function refreshTotals() {
         const rows = getRows();
         let sumQuota = 0;
         let sumMakanan = 0;
         let sumMinuman = 0;

         rows.forEach(row => {
            const qEl = row.querySelector('.quota-input');
            const mEl = row.querySelector('.makanan-input');
            const nEl = row.querySelector('.minuman-input');

            const q = Number(qEl.value) || 0;
            const m = Number(mEl.value) || 0;
            const n = Number(nEl.value) || 0;

            sumQuota += q;
            sumMakanan += m;
            sumMinuman += n;
         });

         const sisa = totalSetoran - sumQuota;

         elTotalQuota.textContent = sumQuota;
         elTotalMakanan.textContent = sumMakanan;
         elTotalMinuman.textContent = sumMinuman;
         elSisaQuota.textContent = sisa;

         // status badge
         elStatusQuota.className = 'badge ms-1';
         if (sisa === 0) {
            elStatusQuota.classList.add('bg-success');
            elStatusQuota.textContent = 'Sudah Pas';
         } else if (sisa > 0) {
            elStatusQuota.classList.add('bg-warning');
            elStatusQuota.textContent = 'Belum Terpenuhi';
         } else {
            elStatusQuota.classList.add('bg-danger');
            elStatusQuota.textContent = 'Kelebihan';
         }

         // highlight rows where daily quota is too high relative to normal average
         const rataNormal = (rows.length > 0) ? (totalSetoran / rows.length) : 0;
         rows.forEach(row => {
            const q = Number(row.querySelector('.quota-input').value) || 0;
            // condition: > 150% rataNormal -> mark
            if (rataNormal > 0 && q > rataNormal * 1.5) {
               row.classList.add('row-over');
            } else {
               row.classList.remove('row-over');
            }
         });
      }

      // Attach input listeners (delegated)
      function bindInputEvents() {
         // use event delegation on tbody
         tbody.addEventListener('input', function(ev) {
            const target = ev.target;
            if (target.matches('.quota-input, .makanan-input, .minuman-input')) {
               // ensure non-negative integers (for quota enforce integer)
               if (target.matches('.quota-input')) {
                  // prevent negative and decimal by rounding down
                  let val = Math.floor(Number(target.value) || 0);
                  if (val < 0) val = 0;
                  target.value = val;
               } else {
                  // for makanan/minuman allow integer >=0
                  let val = Math.floor(Number(target.value) || 0);
                  if (val < 0) val = 0;
                  target.value = val;
               }
               refreshTotals();
            }
         });
      }

      // Autofill: distribute totalSetoran with 7-day-weight on first 7 rows
      function autofillQuotas() {
         const rows = getRows();
         const n = rows.length;
         if (n === 0) return;

         // create weights: first min(7,n) => weight 2, rest => weight 1
         const weights = Array.from({
            length: n
         }, (_, i) => (i < 7 ? 2 : 1));
         const totalWeight = weights.reduce((a, b) => a + b, 0);

         // compute quota as floor of weighted share, accumulate remainder and add to earliest rows
         const quotas = weights.map(w => Math.floor((w / totalWeight) * totalSetoran));
         let allocated = quotas.reduce((a, b) => a + b, 0);
         let remainder = totalSetoran - allocated;

         // distribute remainder starting from first row
         for (let i = 0; i < n && remainder > 0; i++) {
            quotas[i]++;
            remainder--;
         }

         // set values
         rows.forEach((row, i) => {
            row.querySelector('.quota-input').value = quotas[i];
         });

         refreshTotals();
      }

      // Reset to defaults coming from server
      function resetToDefault() {
         const rows = getRows();
         rows.forEach((row, i) => {
            const d = defaultDays[i] || {};
            row.querySelector('.quota-input').value = (d.quota !== undefined) ? d.quota : 0;
            row.querySelector('.makanan-input').value = (d.total_makanan_planned !== undefined) ? d.total_makanan_planned : 0;
            row.querySelector('.minuman-input').value = (d.total_minuman_planned !== undefined) ? d.total_minuman_planned : 0;
            row.querySelector('.notes-input').value = (d.notes !== undefined) ? d.notes : '';
         });
         refreshTotals();
      }

      // Initialize bindings
      bindInputEvents();
      btnAutofill.addEventListener('click', autofillQuotas);
      btnReset.addEventListener('click', resetToDefault);

      // initial render
      resetToDefault();

      // Prevent form submit if totalQuota > totalSetoran
      document.getElementById('daySettingsForm').addEventListener('submit', function(ev) {
         const totalQuota = Number(elTotalQuota.textContent) || 0;
         if (totalQuota > totalSetoran) {
            ev.preventDefault();
            // show inline warning instead of alert
            elStatusQuota.className = 'badge bg-danger ms-1';
            elStatusQuota.textContent = 'Kelebihan: tidak boleh submit';
            // scroll to top of card
            table.scrollIntoView({
               behavior: 'smooth',
               block: 'center'
            });
         }
      });
   });
</script>

@endsection