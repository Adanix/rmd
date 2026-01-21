<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="utf-8">
   <title>Login - RAMADA App</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/logo.png') }}">

   {{-- TABLER CSS CDN --}}
   <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet" />
   <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column">

   <div class="page page-center">
      <div class="container container-tight py-4">

         {{-- LOGO --}}
         <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark">
               <img src="/assets/images/logo.png" width="80" alt="Logo">
            </a>
         </div>

         {{-- CARD LOGIN --}}
         <div class="card card-md">
            <div class="card-body">
               <h2 class="h2 text-center mb-4">Login to your account</h2>

               <form action="{{ route('login.submit') }}" method="POST" autocomplete="off">
                  @csrf

                  {{-- EMAIL / USERNAME --}}
                  <div class="mb-3">
                     <label class="form-label">Email / Username</label>
                     <input type="text"
                        name="login"
                        class="form-control @error('login') is-invalid @enderror"
                        placeholder="email atau username"
                        value="{{ old('login') }}">
                     @error('login')
                     <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  {{-- PASSWORD --}}
                  <div class="mb-2">
                     <label class="form-label">Password</label>

                     <div class="input-group input-group-flat">
                        <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Password">

                        <span class="input-group-text">
                           <a href="#"
                              class="link-secondary"
                              onclick="togglePassword(event)">
                              <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <i id="passwordIcon" class="ti ti-eye icon icon-1"></i>
                              </span>
                           </a>
                        </span>
                     </div>

                     @error('password')
                     <div class="text-danger small mt-1">{{ $message }}</div>
                     @enderror
                  </div>

                  {{-- BUTTON --}}
                  <div class="form-footer">
                     <button type="submit" class="btn btn-primary w-100">
                        Sign in
                     </button>
                  </div>

               </form>
            </div>

         </div>

      </div>
   </div>

   {{-- TABLER JS CDN --}}
   <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>

   <script>
      function togglePassword(e) {
         e.preventDefault();

         const input = document.getElementById('password');
         const icon = document.getElementById('passwordIcon');

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

</body>

</html>