@extends('ramadhan.layouts.app')
@section('content')

<div class="container-xl">
   <div class="row g-2 align-items-center">
      <div class="col">
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
            {!! session('error') !!}
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
         </div>
         @endif

         <h2 class="mb-4">Jadwal Takjil – Tanggal: {{ $day->date }}</h2>

         <div class="row g-3">

            {{-- ====================== LEFT: LIST JAMAAH ====================== --}}
            <div class="col-md-4">

               <div class="card shadow-sm">
                  <div class="card-header d-flex justify-content-between">
                     <span>Daftar Jamaah</span>
                     <span class="badge bg-primary-lt">{{ $jamaahs->count() }} orang</span>
                  </div>

                  {{-- 🔵 SEARCH REALTIME --}}
                  <div class="p-2 border-bottom">
                     <input type="text" id="searchInput" class="form-control"
                        placeholder="Cari nama/alamat/ekonomi...">
                  </div>

                  {{-- Dalam loop jamaahs di edit.blade.php --}}
                  <div class="card-body" style="max-height: 520px; overflow-y:auto;" id="jamaahList">

                     @foreach ($jamaahs as $j)
                     <div class="p-2 border rounded mb-2 jamaah-item"
                        data-id="{{ $j->id }}"
                        data-nama="{{ $j->nama }}"
                        data-search="{{ strtolower($j->nama . ' ' . $j->alamat . ' ' . $j->ekonomi . ' ' . $j->notes) }}"
                        style="cursor:pointer;">

                        <strong>{{ $j->nama }}</strong>
                        @if($j->_is_selected_today)
                        <span class="badge bg-success-lt float-end">Terpilih</span>
                        @endif
                        <br>

                        <small>{{ $j->alamat }}</small><br>
                        <small>Ekonomi: {{ $j->ekonomi }}</small><br>
                        <small>Setoran: {{ $j->setoran }}
                           @if($j->_is_selected_today)
                           <span class="text-muted">(asli: {{ $j->_sisa_asli }})</span>
                           @endif
                        </small><br>
                        <small>Keterangan: {{ $j->keterangan }}</small><br>

                        {{-- Tambahkan info jadwal terakhir --}}
                        <small>
                           @if($j->_last_date_formatted)
                           Terjadwal terakhir: {{ $j->_last_date_formatted }}
                           @if($j->_days_since_last < 7)
                              <span class="badge bg-warning-lt ms-1">Baru {{ $j->_days_since_last }} hari lalu</span>
                              @else
                              <span class="text-muted">({{ $j->_days_since_last }} hari yang lalu)</span>
                              @endif
                              @else
                              <span class="badge bg-success-lt">Belum pernah terjadwal</span>
                              @endif
                        </small><br>

                        {{-- Tampilkan status eligibility --}}
                        @if(!$j->_is_eligible && !$j->_is_selected_today)
                        <small class="text-danger">
                           <i class="fas fa-exclamation-circle"></i> Tidak eligible untuk tanggal ini
                        </small>
                        @endif

                        @if ($j->notes)
                        <small class="text-muted">{{ $j->notes }}</small>
                        @endif
                     </div>
                     @endforeach

                  </div>
               </div>

            </div>


            {{-- RIGHT: FORM INPUT JADWAL --}}
            <div class="col-md-8">
               <form action="{{ route('takjils.update', $day->id) }}" method="POST">
                  @csrf
                  @method('PUT')

                  <input type="hidden" name="day_setting_id" value="{{ $day->id }}">

                  <div class="card shadow-sm">
                     <div class="card-header d-flex justify-content-between">
                        <span>Edit Jadwal Takjil</span>
                        <button type="button" id="btnReset" class="btn btn-sm btn-warning">Reset Slot</button>
                     </div>

                     <div class="card-body">
                        @php
                        // Re-index $takjils untuk memastikan urutan slot
                        $takjilsArray = $takjils->values()->all();
                        @endphp

                        @for ($i = 1; $i <= $day->quota; $i++)
                           @php
                           $slot = $takjilsArray[$i - 1] ?? null;

                           // Pastikan $slot adalah object
                           if ($slot) {
                           // ambil makanan dan minuman (ambil 1 pertama)
                           $makananSelected = $slot->makanans->first()->makanan_id ?? null;
                           $minumanSelected = $slot->minumans->first()->minuman_id ?? null;

                           $ket = is_array($slot->keterangan)
                           ? implode(', ', $slot->keterangan)
                           : ($slot->keterangan ?? '');
                           } else {
                           $makananSelected = null;
                           $minumanSelected = null;
                           $ket = '';
                           }
                           @endphp

                           <div class="slot-box border rounded p-3 mb-3" id="slot-box-{{ $i }}">
                              <div class="d-flex justify-content-between mb-2">
                                 <h5>Slot #{{ $i }}</h5>
                                 <span id="slot-status-{{ $i }}" class="badge {{ $slot ? 'bg-success-lt' : 'bg-secondary-lt' }}">
                                    {{ $slot ? 'Terisi' : 'Kosong' }}
                                 </span>
                              </div>

                              <div class="row g-2">
                                 {{-- Jamaah --}}
                                 <div class="col-md-6">
                                    <label class="form-label">Jamaah</label>
                                    <input readonly id="slot-nama-{{ $i }}" class="form-control"
                                       value="{{ $slot?->jamaah?->nama ?? '' }}">
                                    <input type="hidden" id="slot-id-{{ $i }}" name="jamaah_id[]"
                                       value="{{ $slot?->jamaah_id ?? '' }}">
                                 </div>

                                 {{-- Hijriyah --}}
                                 <div class="col-md-6">
                                    <label class="form-label">Tanggal Hijriyah</label>
                                    <input type="text" class="form-control" name="tanggal_hijriyah[]"
                                       value="{{ $slot?->tanggal_hijriyah ?? '' }}">
                                 </div>

                                 {{-- Makanan --}}
                                 <div class="col-md-6">
                                    <label class="form-label">Makanan</label>
                                    <select name="makanan_id[]" class="form-control">
                                       <option value="">Pilih Makanan</option>
                                       @foreach ($makanans as $m)
                                       <option value="{{ $m->id }}"
                                          {{ $makananSelected == $m->id ? 'selected' : '' }}>
                                          {{ $m->nama }}
                                       </option>
                                       @endforeach
                                    </select>
                                 </div>

                                 {{-- Minuman --}}
                                 <div class="col-md-6">
                                    <label class="form-label">Minuman</label>
                                    <select name="minuman_id[]" class="form-control">
                                       <option value="">Pilih Minuman</option>
                                       @foreach ($minumans as $mn)
                                       <option value="{{ $mn->id }}"
                                          {{ $minumanSelected == $mn->id ? 'selected' : '' }}>
                                          {{ $mn->nama }}
                                       </option>
                                       @endforeach
                                    </select>
                                 </div>

                                 {{-- Keterangan --}}
                                 <div class="col-12">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan[]" class="form-control" rows="2">{{ $ket }}</textarea>
                                 </div>
                              </div>
                           </div>
                           @endfor
                     </div>
                     <div class="card-footer text-end">
                        <button class="btn btn-primary">Update Jadwal</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', () => {
      const maxSlot = Number(@json($day -> quota));
      const jamaahItems = document.querySelectorAll('.jamaah-item');
      const searchInput = document.getElementById('searchInput');

      // Data jamaah yang sudah terpilih hari ini
      const jamaahTerpilih = @json($jamaahTerpilihHariIni);

      // AUTO-HIGHLIGHT jamaah yang sudah terpilih
      jamaahTerpilih.forEach(id => {
         const item = document.querySelector(`.jamaah-item[data-id="${id}"]`);
         if (item) {
            item.classList.add("bg-success", "text-white");
         }
      });

      // ===================== 🔍 Realtime Search =====================
      searchInput.addEventListener('keyup', () => {
         const q = searchInput.value.toLowerCase();
         jamaahItems.forEach(item => {
            const data = item.dataset.search || "";
            item.style.display = data.includes(q) ? '' : 'none';
         });
      });

      // ===================== 🟦 Klik Jamaah =====================
      jamaahItems.forEach(item => {
         item.addEventListener('click', () => toggleAssign(item));
      });

      function toggleAssign(el) {
         const id = el.dataset.id;
         const nama = el.dataset.nama;

         // --- cek apakah jamaah sudah menempati slot ---
         let existingSlot = null;
         for (let i = 1; i <= maxSlot; i++) {
            const sId = document.getElementById(`slot-id-${i}`).value;
            if (sId == id) {
               existingSlot = i;
               break;
            }
         }

         // =============== 🔴 JIKA SUDAH ADA = REMOVE ===============
         if (existingSlot !== null) {

            document.getElementById(`slot-id-${existingSlot}`).value = "";
            document.getElementById(`slot-nama-${existingSlot}`).value = "";

            const box = document.getElementById(`slot-box-${existingSlot}`);
            const status = document.getElementById(`slot-status-${existingSlot}`);

            box.classList.remove('bg-success-subtle');
            status.innerText = "Kosong";
            status.classList.replace('bg-success', 'bg-secondary');

            el.classList.remove("bg-success", "text-white");
            return;
         }

         // =============== 🟢 JIKA BELUM → masukkan slot kosong pertama ===============
         for (let i = 1; i <= maxSlot; i++) {
            const slotId = document.getElementById(`slot-id-${i}`);

            if (slotId.value === "") {

               slotId.value = id;
               document.getElementById(`slot-nama-${i}`).value = nama;

               const box = document.getElementById(`slot-box-${i}`);
               const status = document.getElementById(`slot-status-${i}`);

               box.classList.add('bg-success-subtle');
               status.innerText = "Terisi";
               status.classList.replace('bg-secondary', 'bg-success');

               el.classList.add("bg-success", "text-white");

               break;
            }
         }
      }

      // ===================== 🔴 Reset All Slot =====================
      document.getElementById('btnReset').addEventListener('click', () => {
         for (let i = 1; i <= maxSlot; i++) {
            document.getElementById(`slot-id-${i}`).value = "";
            document.getElementById(`slot-nama-${i}`).value = "";

            const box = document.getElementById(`slot-box-${i}`);
            const status = document.getElementById(`slot-status-${i}`);

            box.classList.remove('bg-success-subtle');
            status.innerText = "Kosong";
            status.classList.replace('bg-success', 'bg-secondary');
         }

         jamaahItems.forEach(j => j.classList.remove("bg-success", "text-white"));
      });

   });
</script>


@endsection