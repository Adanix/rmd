@extends('ramadhan.layouts.app')

@section('content')
<div class="container-xl mt-4">
   <h2 class="mb-4">Jadwal Takjil – Tanggal: {{ $day->date }}</h2>

   <div class="row g-3">

      {{-- ====================== LEFT: LIST JAMAAH ====================== --}}
      <div class="col-md-4">

         <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
               <span>Daftar Jamaah</span>
               <span class="badge bg-primary">{{ $jamaahs->count() }} orang</span>
            </div>

            {{-- 🔵 SEARCH REALTIME --}}
            <div class="p-2 border-bottom">
               <input type="text" id="searchInput" class="form-control"
                  placeholder="Cari nama/alamat/ekonomi...">
            </div>

            <div class="card-body" style="max-height: 520px; overflow-y:auto;" id="jamaahList">

               @foreach ($jamaahs as $j)
               <div class="p-2 border rounded mb-2 jamaah-item"
                  data-id="{{ $j->id }}"
                  data-nama="{{ $j->nama }}"
                  data-search="{{ strtolower($j->nama . ' ' . $j->alamat . ' ' . $j->ekonomi . ' ' . $j->notes) }}"
                  style="cursor:pointer;">

                  <strong>{{ $j->nama }}</strong><br>
                  <small>{{ $j->alamat }}</small><br>
                  <small>Ekonomi: {{ $j->ekonomi }}</small><br>
                  <small>Setoran sisa: {{ $j->setoran }}</small><br>
                  <small>Keterangan: {{ $j->keterangan }}</small><br>

                  @if ($j->notes)
                  <small class="text-muted">{{ $j->notes }}</small>
                  @endif
               </div>
               @endforeach

            </div>
         </div>

      </div>


      {{-- ====================== RIGHT: FORM INPUT JADWAL ====================== --}}
      <div class="col-md-8">
         <form action="{{ route('takjils.store') }}" method="POST">
            @csrf
            <input type="hidden" name="day_setting_id" value="{{ $day->id }}">

            <div class="card shadow-sm">
               <div class="card-header d-flex justify-content-between">
                  <span>Input Jadwal Takjil</span>
                  <button type="button" id="btnReset" class="btn btn-sm btn-warning">Reset Slot</button>
               </div>

               <div class="card-body">

                  @for ($i = 1; $i <= $day->quota; $i++)
                     <div class="slot-box border rounded p-3 mb-3 bg-light" id="slot-box-{{ $i }}">

                        <div class="d-flex justify-content-between">
                           <h5>Slot #{{ $i }}</h5>
                           <span class="badge bg-secondary" id="slot-status-{{ $i }}">Kosong</span>
                        </div>

                        <div class="row g-2">

                           <div class="col-md-6">
                              <label>Jamaah</label>
                              <input readonly class="form-control slot-nama" id="slot-nama-{{ $i }}">
                              <input type="hidden" name="jamaah_id[]" class="slot-id" id="slot-id-{{ $i }}">
                           </div>

                           <div class="col-md-6">
                              <label>Tanggal Hijriyah</label>
                              <input type="text" class="form-control" name="tanggal_hijriyah[]">
                           </div>

                           {{-- 🔶 Dropdown makanan --}}
                           <div class="col-md-6">
                              <label>Makanan</label>
                              <select name="makanan_id[]" class="form-control">
                                 <option value="">Pilih Makanan</option>
                                 @foreach ($makanans as $m)
                                 <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                 @endforeach
                              </select>
                           </div>

                           {{-- 🔶 Dropdown Minuman --}}
                           <div class="col-md-6">
                              <label>Minuman</label>
                              <select name="minuman_id[]" class="form-control">
                                 <option value="">Pilih Minuman</option>
                                 @foreach ($minumans as $mn)
                                 <option value="{{ $mn->id }}">{{ $mn->nama }}</option>
                                 @endforeach
                              </select>
                           </div>

                           <div class="col-12">
                              <label>Keterangan</label>
                              <textarea name="keterangan[]" class="form-control" rows="2"></textarea>
                           </div>

                        </div>
                     </div>
                     @endfor

               </div>

               <div class="card-footer text-end">
                  <button class="btn btn-primary">Simpan Jadwal</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>


{{-- ====================== JAVASCRIPT ====================== --}}
<script>
   document.addEventListener('DOMContentLoaded', () => {

      const maxSlot = Number(@json($day->quota)); // ← aman, tanpa warning

      // ========================= 🔵 Realtime Search =========================
      const searchInput = document.getElementById('searchInput');
      const jamaahItems = document.querySelectorAll('.jamaah-item');

      searchInput.addEventListener('keyup', () => {
         const q = searchInput.value.toLowerCase();

         jamaahItems.forEach(item => {
            const data = item.dataset.search || "";
            item.style.display = data.includes(q) ? '' : 'none';
         });
      });

      // ========================= 🟢 Klik Jamaah → Isi Slot =========================
      jamaahItems.forEach(item => {
         item.addEventListener('click', () => assignJamaah(item));
      });

      function assignJamaah(el) {
         const id = el.dataset.id;
         const nama = el.dataset.nama;

         for (let i = 1; i <= maxSlot; i++) {
            const slotId = document.getElementById(`slot-id-${i}`);

            if (slotId.value === "") {
               const slotNama = document.getElementById(`slot-nama-${i}`);
               const slotBox = document.getElementById(`slot-box-${i}`);
               const slotStatus = document.getElementById(`slot-status-${i}`);

               slotId.value = id;
               slotNama.value = nama;

               slotBox.classList.add('bg-success-subtle');
               slotStatus.innerText = "Terisi";
               slotStatus.classList.replace('bg-secondary', 'bg-success');

               el.classList.add("bg-success", "text-white");
               break;
            }
         }
      }

      // ========================= 🔴 Reset Slot =========================
      const btnReset = document.getElementById('btnReset');

      btnReset.addEventListener('click', () => {
         for (let i = 1; i <= maxSlot; i++) {
            document.getElementById(`slot-id-${i}`).value = "";
            document.getElementById(`slot-nama-${i}`).value = "";

            const slotBox = document.getElementById(`slot-box-${i}`);
            const slotStatus = document.getElementById(`slot-status-${i}`);

            slotBox.classList.remove('bg-success-subtle');
            slotStatus.innerText = "Kosong";
            slotStatus.classList.replace('bg-success', 'bg-secondary');
         }

         jamaahItems.forEach(j => j.classList.remove("bg-success", "text-white"));
      });

   });
</script>

@endsection