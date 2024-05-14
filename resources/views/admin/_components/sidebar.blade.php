<?php
use App\Common\Acl;

if (!isset($menu_active)) {
    $menu_active = null;
}

?>
<aside class="main-sidebar sidebar-light-primary elevation-4">
  <a href="{{ url('/admin/') }}" class="brand-link">
    <img src="{{ url('dist/img/logo.png') }}" alt="App Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-flat nav-collapse-hide-child" data-widget="treeview"
        role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ url('/admin/') }}" class="nav-link {{ $nav_active == 'dashboard' ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        @if (Auth::user()->is_admin)
          <li class="nav-item {{ $menu_active == 'system' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $menu_active == 'system' ? 'active' : '' }}">
              <i class="nav-icon fas fa-gears"></i>
              <p>
                Sistem
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/admin/sys-events') }}" class="nav-link {{ $nav_active == 'sys-events' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-file-waveform"></i>
                  <p>Log Aktivitas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/users') }}" class="nav-link {{ $nav_active == 'users' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Pengguna</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/user-groups') }}"
                  class="nav-link {{ $nav_active == 'user-groups' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user-group"></i>
                  <p>Grup Pengguna</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/settings') }}"
                  class="nav-link {{ $nav_active == 'settings' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-gear"></i>
                  <p>Pengaturan</p>
                </a>
              </li>
            </ul>
          </li>
        @endif
        <li class="nav-item">
          <a href="{{ url('/admin/users/profile/') }}"
            class="nav-link {{ $nav_active == 'profile' ? 'active' : '' }}">
            <i class="nav-icon fas fa-user"></i>
            <p>{{ Auth::user()->username }}</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('admin/logout') }}" class="nav-link">
            <i class="nav-icon fas fa-right-from-bracket"></i>
            <p>Keluar</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
