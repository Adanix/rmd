<li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
   <a class="nav-link" href="{{ route('users.index') }}">
      <span class="nav-link-icon d-md-none d-lg-inline-block">
         <i class="ti ti-users icon icon-1"></i>
      </span>
      <span class="nav-link-title">User</span>
   </a>
</li>