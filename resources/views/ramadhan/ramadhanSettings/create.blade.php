@extends('ramadhan.layouts.app')

@section('content')
<div class="container-xl mb-4">
   <div class="row g-2 align-items-center">
      <div class="col">
         <h2 class="page-title">Ramadhan Setting</h2>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">

      <div class="card">
         <form action="{{ route('ramadhan-settings.store') }}" method="POST">
            @csrf

            <div class="card-body">
               <div class="mb-3">
                  <label class="form-label">Start Date</label>
                  <input type="date" name="start_date" value="{{ old('start_date') }}"
                     class="form-control @error('start_date') is-invalid @enderror" required>
                  @error('start_date')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">End Date</label>
                  <input type="date" name="end_date" value="{{ old('end_date') }}"
                     class="form-control @error('end_date') is-invalid @enderror" required>
                  @error('end_date')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Days (total hari Ramadhan tanpa Jumat)</label>
                  <input type="number" name="days" value="{{ old('days') }}"
                     class="form-control @error('days') is-invalid @enderror" required>
                  @error('days')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Total Setoran</label>
                  <input type="number" name="total_setoran" value="{{ old('total_setoran', 0) }}"
                     class="form-control @error('total_setoran') is-invalid @enderror" readonly>
                  <small class="text-muted">* Nilai ini akan dihitung otomatis dari data jamaah</small>
                  @error('total_setoran')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Special Quotas (JSON)</label>
                  <textarea name="special_quotas"
                     class="form-control @error('special_quotas') is-invalid @enderror"
                     rows="3" placeholder='{"key": "value"}'>{{ old('special_quotas') }}</textarea>
                  @error('special_quotas')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Holidays (JSON)</label>
                  <textarea name="holidays"
                     class="form-control @error('holidays') is-invalid @enderror"
                     rows="3" placeholder='["2024-04-10", "2024-04-17"]'>{{ old('holidays') }}</textarea>
                  @error('holidays')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Notes</label>
                  <textarea name="notes"
                     class="form-control @error('notes') is-invalid @enderror"
                     rows="3">{{ old('notes') }}</textarea>
                  @error('notes')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            <div class="card-footer text-end">
               <a href="{{ route('ramadhan-settings.index') }}" class="btn btn-secondary">Kembali</a>
               <button class="btn btn-primary">Simpan</button>
            </div>

         </form>
      </div>

   </div>
</div>
@endsection