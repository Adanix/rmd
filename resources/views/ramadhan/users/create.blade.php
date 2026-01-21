@extends('ramadhan.layouts.app')

@section('content')

<div class="container-xl mb-4">
   <div class="row g-2 align-items-center">
      <div class="col">
         <h2 class="page-title">Tambah User</h2>
      </div>
   </div>
</div>

<div class="page-body">
   <div class="container-xl">

      <div class="card">
         <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="card-body">

               <div class="mb-3">
                  <label class="form-label">Nama</label>
                  <input type="text" name="name" value="{{ old('name') }}"
                     class="form-control @error('name') is-invalid @enderror"
                     placeholder="Masukkan nama lengkap">
                  @error('name')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input type="text" name="username" value="{{ old('username') }}"
                     class="form-control @error('username') is-invalid @enderror"
                     placeholder="Masukkan username">
                  @error('username')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <!-- Opsional: Tambahkan field email jika diperlukan -->
               <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" value="{{ old('email') }}"
                     class="form-control @error('email') is-invalid @enderror"
                     placeholder="Masukkan email">
                  @error('email')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Password</label>
                  <div class="input-group">
                     <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Masukkan password">
                     <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', 'passwordIcon')">
                        <i id="passwordIcon" class="ti ti-eye"></i>
                     </button>
                  </div>
                  @error('password')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="mb-3">
                  <label class="form-label">Konfirmasi Password</label>
                  <div class="input-group">
                     <input type="password" name="password_confirmation" id="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="Ulangi password">
                     <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', 'confirmPasswordIcon')">
                        <i id="confirmPasswordIcon" class="ti ti-eye"></i>
                     </button>
                  </div>
                  @error('password_confirmation')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

            </div>

            <div class="card-footer text-end">
               <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
               <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

         </form>
      </div>

   </div>
</div>

<script>
   function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);

      if (input.type === 'password') {
         input.type = 'text';
         icon.classList.remove('ti-eye');
         icon.classList.add('ti-eye-off');
      } else {
         input.type = 'password';
         icon.classList.remove('ti-eye-off');
         icon.classList.add('ti-eye');
      }
   }
</script>
@endsection