@extends('ramadhan.layouts.app')

@section('content')
<div class="container-xl mb-4">
   <div class="row g-2 align-items-center">
      <div class="col">
         <h2 class="page-title">Tambah Jama`ah</h2>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">

      <div class="card">
         <form action="{{ route('jamaahs.store') }}" method="POST">
            @csrf

            <div class="card-body">
               <div class="mb-3">
                  <label class="form-label">Nama</label>
                  <input type="text" name="nama" value="{{ old('nama') }}"
                     class="form-control @error('nama') is-invalid @enderror" required>
                  @error('nama')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Alamat</label>
                  <input type="text" name="alamat" value="{{ old('alamat') }}"
                     class="form-control @error('alamat') is-invalid @enderror" required>
                  @error('alamat')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Ekonomi</label>
                  <select name="ekonomi" class="form-select @error('ekonomi') is-invalid @enderror" required>
                     @php
                     $selectedEkonomi = old('ekonomi');
                     @endphp
                     <option value="" {{ $selectedEkonomi == '' ? 'selected' : '' }}>-- Pilih Status Ekonomi --</option>
                     <option value="Mampu" {{ $selectedEkonomi == 'Mampu' ? 'selected' : '' }}>Mampu</option>
                     <option value="Kurang Mampu" {{ $selectedEkonomi == 'Kurang Mampu' ? 'selected' : '' }}>Kurang Mampu</option>
                  </select>
                  @error('ekonomi')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Setoran</label>
                  <input type="number" name="setoran" value="{{ old('setoran') }}"
                     class="form-control @error('setoran') is-invalid @enderror"
                     min="1" required>
                  @error('setoran')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
                  <small class="text-muted">Setoran: Mampu = 2, Kurang Mampu = 1</small>
               </div>

               <div class="mb-3">
                  <label class="form-label">Keterangan</label>
                  <select name="keterangan" class="form-select @error('keterangan') is-invalid @enderror" required>
                     @php
                     $selectedKeterangan = old('keterangan');
                     @endphp
                     <option value="" {{ $selectedKeterangan == '' ? 'selected' : '' }}>-- Pilih Keterangan --</option>
                     <option value="Makanan dan Minuman" {{ $selectedKeterangan == 'Makanan dan Minuman' ? 'selected' : '' }}>Makanan dan Minuman</option>
                     <option value="Makanan" {{ $selectedKeterangan == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                     <option value="Minuman" {{ $selectedKeterangan == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                  </select>
                  @error('keterangan')
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
               <a href="{{ route('jamaahs.index') }}" class="btn btn-secondary">Kembali</a>
               <button class="btn btn-primary">Simpan</button>
            </div>

         </form>
      </div>

   </div>
</div>
@endsection