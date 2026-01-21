<!doctype html>
<html lang="en" data-bs-theme="light" data-bs-theme-primary="purple">

<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <title>{{ $title ?? 'RAMADA App' }}</title>
   <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/logo.png') }}">
   <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />
   <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler-vendors.min.css" />
   <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
   @stack('head')
</head>

<body>
   <div class="page">
      <header class="navbar navbar-expand-md d-print-none">
         <div class="container-xl">
            <!-- BEGIN NAVBAR TOGGLER -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>
            <!-- END NAVBAR TOGGLER -->
            <!-- BEGIN NAVBAR LOGO -->
            <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
               <a href="."
                  class="brand-wrap text-decoration-none link-primary"
                  aria-label="Logo">
                  <img src="/assets/images/logo.png" alt="Logo" width="32">
                  <span class="ms-2 fw-semibold">
                     RAMADA
                  </span>
               </a>
            </div>
            <!-- END NAVBAR LOGO -->
            <div class="navbar-nav flex-row order-md-last">
               <div class="d-none d-md-flex">
                  <div class="nav-item">
                     <a href="?theme=dark"
                        class="nav-link px-0 hide-theme-dark"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        aria-label="Enable dark mode"
                        data-bs-original-title="Enable dark mode">
                        <i class="ti ti-moon icon icon-1" style="font-size:24px"></i>
                     </a>
                     <a href="?theme=light"
                        class="nav-link px-0 hide-theme-light"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        aria-label="Enable light mode"
                        data-bs-original-title="Enable light mode">
                        <i class="ti ti-sun icon icon-1" style="font-size:24px"></i>
                     </a>
                  </div>
               </div>
               @if(auth()->check())
               <div class="nav-item dropdown">
                  <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
                     <span class="avatar avatar-sm" style="background-image: url(./assets/images/cowo.jpg)"> </span>
                     <div class="d-none d-xl-block ps-2">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="mt-1 small text-secondary">{{ Auth::user()->username }}</div>
                     </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                     <a href="#" class="dropdown-item disabled">Status
                        <span class="badge badge-sm bg-warning-lt text-uppercase ms-auto">Coming soon</span>
                     </a>
                     <a href="#" class="dropdown-item disabled">Profile
                        <span class="badge badge-sm bg-warning-lt text-uppercase ms-auto">Coming soon</span>
                     </a>
                     <a href="#" class="dropdown-item disabled">Feedback
                        <span class="badge badge-sm bg-warning-lt text-uppercase ms-auto">Coming soon</span>
                     </a>
                     <div class="dropdown-divider disabled"></div>
                     <a href="#" class="dropdown-item disabled">Settings
                        <span class="badge badge-sm bg-warning-lt text-uppercase ms-auto">Coming soon</span>
                     </a>
                     <a href="#"
                        class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                     </a>

                     {{-- Hidden logout form --}}
                     <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                     </form>
                  </div>
               </div>
               @endif
            </div>
         </div>
      </header>
      {{-- Menu khusus Guest --}}
      @if(auth()->guest())
      @include('ramadhan.layouts.guest')
      @endif

      @if(auth()->check())
      <header class="navbar-expand-md">
         <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="navbar">
               <div class="container-xl">
                  <div class="row flex-column flex-md-row flex-fill align-items-center">
                     <div class="col">
                        <!-- BEGIN NAVBAR MENU -->
                        <ul class="navbar-nav">
                           <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                              <a class="nav-link" href="{{ route('dashboard') }}">
                                 <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler.io/icons/icon/home -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                       <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                       <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                       <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                                    </svg></span>
                                 <span class="nav-link-title"> Dashboard </span>
                              </a>
                           </li>
                           <li class="nav-item {{ request()->routeIs('jamaahs.*') ? 'active' : '' }}">
                              <a class="nav-link"
                                 href="/jamaahs"
                                 role="button">
                                 <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-users-group icon icon-1"></i>
                                 </span>
                                 <span class="nav-link-title">Jamaah</span>
                              </a>
                           </li>
                           <li class="nav-item {{ request()->routeIs('makanans.*') || request()->routeIs('minumans.*') ? 'active' : '' }} dropdown">
                              <a class="nav-link dropdown-toggle"
                                 href="#navbar-form"
                                 data-bs-toggle="dropdown"
                                 data-bs-auto-close="outside"
                                 role="button"
                                 aria-expanded="false">
                                 <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-tools-kitchen-3 icon icon-1"></i>
                                 </span>
                                 <span class="nav-link-title">Menu Setoran</span>
                              </a>
                              <div class="dropdown-menu">
                                 <a class="dropdown-item {{ request()->routeIs('makanans.*') ? 'active' : '' }}"
                                    href="{{ route('makanans.index') }}">
                                    Makanan
                                 </a>
                                 <a class="dropdown-item {{ request()->routeIs('minumans.*') ? 'active' : '' }}"
                                    href="{{ route('minumans.index') }}">
                                    Minuman
                                    <span class="badge badge-sm bg-green-lt text-uppercase ms-auto">New</span>
                                 </a>
                              </div>
                           </li>

                           <li class="nav-item dropdown
                              {{ request()->routeIs('ramadhan-settings.*')
                                 || request()->routeIs('day-settings.*')
                                 || request()->routeIs('takjils.*')
                                 ? 'active' : '' }}">
                              <a class="nav-link dropdown-toggle"
                                 href="#navbar-form"
                                 data-bs-toggle="dropdown"
                                 data-bs-auto-close="outside"
                                 role="button"
                                 aria-expanded="false">
                                 <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-moon-stars icon icon-1"></i>
                                 </span>
                                 <span class="nav-link-title">Ramadhan</span>
                              </a>
                              <div class="dropdown-menu">
                                 {{-- Ramadhan Settings --}}
                                 <a class="dropdown-item {{ request()->routeIs('ramadhan-settings.*') ? 'active' : '' }}"
                                    href="{{ route('ramadhan-settings.index') }}">
                                    Ramadhan Settings
                                 </a>
                                 {{-- Day Settings --}}
                                 <a class="dropdown-item {{ request()->routeIs('day-settings.*') ? 'active' : '' }}"
                                    href="{{ route('day-settings.index') }}">
                                    Day Settings
                                 </a>
                                 {{-- Takjil --}}
                                 <a class="dropdown-item {{ request()->routeIs('takjils.*') ? 'active' : '' }} disabled"
                                    href="{{ route('takjils.index') }}">
                                    Takjil
                                    <span class="badge badge-sm bg-green-lt text-uppercase ms-auto">New</span>
                                 </a>
                              </div>
                           </li>

                           <li class="nav-item {{ request()->routeIs('jadwal-takjil.*') ? 'active' : '' }}">
                              <a class="nav-link" href="{{ route('jadwal-takjil.index') }}">
                                 <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-calendar-smile icon icon-1"></i>
                                 </span>
                                 <span class="nav-link-title">Jadwal Takjil</span>
                              </a>
                           </li>


                           {{-- Menu khusus OWNER --}}
                           @if(auth()->check() && auth()->user()->role === 'owner')
                           @include('ramadhan.layouts.owner')
                           @endif

                        </ul>
                        <!-- END NAVBAR MENU -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </header>
      @endif
      <div class="page-wrapper">
         <div class="page-body">
            <div class="container-xl">
               @yield('content')
            </div>
         </div>
         <footer class="footer footer-transparent d-print-none">
            <div class="container-xl">
               <div class="row text-center align-items-center flex-row-reverse">
                  <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                     <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item">
                           Copyright © 2026
                           <a href="." class="link-secondary">RAMADA</a>. All rights reserved.
                        </li>
                        <li class="list-inline-item">
                           <a href="./changelog.html" class="link-secondary" rel="noopener"> v1.0 </a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </footer>
      </div>
   </div>
   @stack('scripts')
   <script
      src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js">
   </script>
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         // Cek preferensi user dari localStorage
         const currentTheme = localStorage.getItem('theme') || 'light';

         // Set theme awal
         document.documentElement.setAttribute('data-bs-theme', currentTheme);

         // Tampilkan tombol yang sesuai dengan tema saat ini
         updateThemeButtons(currentTheme);

         // Fungsi untuk toggle theme
         function toggleTheme() {
            const htmlElement = document.documentElement;
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            // Update attribute di html
            htmlElement.setAttribute('data-bs-theme', newTheme);

            // Simpan preferensi ke localStorage
            localStorage.setItem('theme', newTheme);

            // Update tampilan tombol
            updateThemeButtons(newTheme);
         }

         // Fungsi untuk update tombol theme
         function updateThemeButtons(theme) {
            const darkBtn = document.querySelector('.hide-theme-dark');
            const lightBtn = document.querySelector('.hide-theme-light');

            if (theme === 'dark') {
               darkBtn.style.display = 'none';
               lightBtn.style.display = 'block';
            } else {
               darkBtn.style.display = 'block';
               lightBtn.style.display = 'none';
            }
         }

         // Tambahkan event listener ke tombol theme
         document.querySelectorAll('[href*="theme="]').forEach(button => {
            button.addEventListener('click', function(e) {
               e.preventDefault();
               toggleTheme();
            });
         });

         // Cek preferensi sistem jika tidak ada preferensi di localStorage
         if (!localStorage.getItem('theme')) {
            const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

            if (prefersDarkScheme.matches) {
               document.documentElement.setAttribute('data-bs-theme', 'dark');
               updateThemeButtons('dark');
            }

            // Listen untuk perubahan preferensi sistem
            prefersDarkScheme.addEventListener('change', e => {
               if (!localStorage.getItem('theme')) {
                  const newTheme = e.matches ? 'dark' : 'light';
                  document.documentElement.setAttribute('data-bs-theme', newTheme);
                  updateThemeButtons(newTheme);
               }
            });
         }
      });
   </script>
</body>

</html>