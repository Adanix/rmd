@extends('ramadhan.layouts.app')

@section('content')

<div class="container-xl mb-4">
   <div class="row g-2 align-items-center">
      <div class="col">
         <h2 class="page-title">Tambah Makanan</h2>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">

      <div class="card">
         <form action="{{ route('makanans.store') }}" method="POST">
            @csrf

            <div class="card-body">

               <div class="mb-3">
                  <label class="form-label">Nama</label>
                  <input type="text" name="nama" value="{{ old('nama') }}"
                     class="form-control @error('nama') is-invalid @enderror">
                  @error('nama')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Keterangan</label>
                  <textarea name="keterangan"
                     class="form-control @error('keterangan') is-invalid @enderror"
                     rows="3">{{ old('keterangan') }}</textarea>
                  @error('keterangan')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

            </div>

            <div class="card-footer text-end">
               <a href="{{ route('makanans.index') }}" class="btn btn-secondary">Kembali</a>
               <button class="btn btn-primary">Simpan</button>
            </div>

         </form>
      </div>

   </div>
</div>
@endsection