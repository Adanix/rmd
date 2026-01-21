@extends('ramadhan.layouts.app')

@section('content')
<div class="container-xl mb-4">
   <div class="row g-2 align-items-center">
      <div class="col">
         <h2 class="page-title">Edit Day Settings Ramadhan {{ $ramadhanSetting->start_date->year }}</h2>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">

      @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <form id="daySettingsForm"
         action="{{ route('day-settings.update', $ramadhanSetting->id) }}"
         method="POST">
         @csrf
         @method('PUT')

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
               <button type="button" id="btnAutofill" class="btn btn-info me-2">Autofill Pembagian Kuota</button>
               <button type="button" id="btnReset" class="btn btn-secondary">Reset Ke Data Awal</button>
            </div>

            <div class="table-responsive">
               <table class="table table-bordered" id="dayTable">
                  <thead class="table-light">
                     <tr>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Kuota</th>
                        <th>Makanan</th>
                        <th>Minuman</th>
                        <th>Catatan</th>
                     </tr>
                  </thead>

                  <tbody>
                     @foreach($days as $index => $day)
                     <tr data-index="{{ $index }}">
                        <td>{{ $day['date_label'] }}</td>
                        <td>{{ $day['dayname'] }}</td>

                        <input type="hidden" name="days[{{ $index }}][id]" value="{{ $day['id'] }}">
                        <input type="hidden" name="days[{{ $index }}][date]" value="{{ $day['date'] }}">

                        <td>
                           <input type="number" min="0"
                              name="days[{{ $index }}][quota]"
                              class="form-control quota-input"
                              value="{{ $day['quota'] }}">
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
               <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
         </div>
      </form>

   </div>
</div>

<script>
   document.addEventListener("DOMContentLoaded", function() {

      const totalSetoran = Number(@json($ramadhanSetting -> total_setoran)) || 0;

      const quotaInputs = document.querySelectorAll(".quota-input");
      const makananInputs = document.querySelectorAll(".makanan-input");
      const minumanInputs = document.querySelectorAll(".minuman-input");

      const sisaQuotaEl = document.getElementById("sisaQuota");
      const totalQuotaEl = document.getElementById("totalQuota");
      const statusQuotaEl = document.getElementById("statusQuota");

      const totalMakananEl = document.getElementById("totalMakanan");
      const totalMinumanEl = document.getElementById("totalMinuman");

      const btnAutofill = document.getElementById("btnAutofill");
      const btnReset = document.getElementById("btnReset");

      // SIMPAN DATA AWAL
      const initialData = [];
      document.querySelectorAll("tbody tr").forEach((tr, index) => {
         initialData.push({
            quota: tr.querySelector(".quota-input").value,
            makanan: tr.querySelector(".makanan-input").value,
            minuman: tr.querySelector(".minuman-input").value,
            notes: tr.querySelector(".notes-input").value,
         });
      });

      // TOTAL REALTIME
      function updateTotals() {

         let totalQuota = 0;
         let totalMakanan = 0;
         let totalMinuman = 0;

         quotaInputs.forEach(el => totalQuota += Number(el.value) || 0);
         makananInputs.forEach(el => totalMakanan += Number(el.value) || 0);
         minumanInputs.forEach(el => totalMinuman += Number(el.value) || 0);

         totalQuotaEl.textContent = totalQuota.toLocaleString();
         totalMakananEl.textContent = totalMakanan.toLocaleString();
         totalMinumanEl.textContent = totalMinuman.toLocaleString();

         const sisa = totalSetoran - totalQuota;

         sisaQuotaEl.textContent = sisa.toLocaleString();

         if (sisa === 0) {
            statusQuotaEl.className = "badge bg-success";
            statusQuotaEl.textContent = "PAS";
         } else if (sisa > 0) {
            statusQuotaEl.className = "badge bg-primary";
            statusQuotaEl.textContent = "SISA";
         } else {
            statusQuotaEl.className = "badge bg-danger";
            statusQuotaEl.textContent = "LEBIH";
         }

         // Warnai baris berlebih
         document.querySelectorAll("tbody tr").forEach((tr, idx) => {
            const q = Number(tr.querySelector(".quota-input").value) || 0;

            tr.style.background = (q > 300) ? "#ffe3e3" : "";
         });
      }

      updateTotals();

      [...quotaInputs, ...makananInputs, ...minumanInputs].forEach(input => {
         input.addEventListener("input", updateTotals);
      });

      // AUTOFILL
      btnAutofill?.addEventListener("click", function() {

         const rows = document.querySelectorAll("tbody tr");
         const days = rows.length;

         const bigQuota = Math.ceil(totalSetoran * 0.07);
         const normalQuota = Math.floor((totalSetoran - (bigQuota * 7)) / (days - 7));

         rows.forEach((tr, index) => {
            const quotaField = tr.querySelector(".quota-input");
            quotaField.value = (index < 7) ? bigQuota : normalQuota;
         });

         updateTotals();
      });

      // RESET
      btnReset?.addEventListener("click", function() {
         const rows = document.querySelectorAll("tbody tr");

         rows.forEach((tr, index) => {
            tr.querySelector(".quota-input").value = initialData[index].quota;
            tr.querySelector(".makanan-input").value = initialData[index].makanan;
            tr.querySelector(".minuman-input").value = initialData[index].minuman;
            tr.querySelector(".notes-input").value = initialData[index].notes;
         });

         updateTotals();
      });

   });
</script>

@endsection