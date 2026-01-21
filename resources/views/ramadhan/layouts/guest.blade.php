<header class="navbar-expand-md">
   <div class="collapse navbar-collapse" id="navbar-menu">
      <div class="navbar">
         <div class="container-xl">
            <div class="row flex-column flex-md-row flex-fill align-items-center">
               <div class="col">
                  <!-- BEGIN NAVBAR MENU -->
                  <ul class="navbar-nav">
                     <li class="nav-item {{ request()->routeIs('takjil-jamaah') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('takjil-jamaah') }}">
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <i class="ti ti-calendar-smile icon icon-1"></i>
                           </span>
                           <span class="nav-link-title">Jadwal Takjil</span>
                        </a>
                     </li>

                     <li class="nav-item {{ request()->routeIs('#') ? 'active' : '' }}">
                        <a class="nav-link"
                           href="/jamaahs"
                           role="button">
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <i class="ti ti-users-group icon icon-1"></i>
                           </span>
                           <span class="nav-link-title">Penceramah Tarawih</span>
                        </a>
                     </li>

                     <li class="nav-item {{ request()->routeIs('#') ? 'active' : '' }}">
                        <a class="nav-link"
                           href="/jamaahs"
                           role="button">
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <i class="ti ti-users-group icon icon-1"></i>
                           </span>
                           <span class="nav-link-title">Kuliah Subuh</span>
                        </a>
                     </li>
                  </ul>
                  <!-- END NAVBAR MENU -->
               </div>
            </div>
         </div>
      </div>
   </div>
</header>