<?php
use App\Models\AclResource;

if (!isset($menu_active)) {
    $menu_active = null;
}

?>
<aside class="main-sidebar sidebar-light-primary elevation-4">
  <a href="{{ url('admin/') }}" class="brand-link">
    <img src="{{ url('dist/img/logo.png') }}" alt="App Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-flat nav-collapse-hide-child" data-widget="treeview"
        role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ url('admin/') }}" class="nav-link {{ $nav_active == 'dashboard' ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        {{-- Sales Menu Begin --}}
        @if (Auth::user()->canAccess(AclResource::SALES_MENU))
          <li class="nav-item {{ $menu_active == 'sales' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $menu_active == 'sales' ? 'active' : '' }}">
              <i class="nav-icon fas fa-store"></i>
              <p>
                Penjualan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (Auth::user()->canAccess(AclResource::SALES_ORDER_LIST))
                <li class="nav-item">
                  <a href="{{ url('/admin/sales-order') }}"
                    class="nav-link {{ $nav_active == 'sales-order' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cart-arrow-down"></i>
                    <p>Order Penjualan</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::SERVICE_ORDER_LIST))
                <li class="nav-item">
                  <a href="{{ url('/admin/service-order') }}"
                    class="nav-link {{ $nav_active == 'service-order' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-hand-holding-medical"></i>
                    <p>Order Servis</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::CUSTOMER_LIST))
                <li class="nav-item">
                  <a href="{{ url('/admin/customer') }}"
                    class="nav-link {{ $nav_active == 'customer' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>Pelanggan</p>
                  </a>
                </li>
              @endif
            </ul>
          </li>
        @endif
        {{-- Sales Menu End --}}
        {{-- Inventory Menu Begin --}}
        @if (Auth::user()->canAccess(AclResource::INVENTORY_MENU))
          <li class="nav-item {{ $menu_active == 'inventory' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $menu_active == 'inventory' ? 'active' : '' }}">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                Inventori
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (true)
                <li class="nav-item">
                  <a href="{{ url('/admin/stock-adjustment') }}"
                    class="nav-link {{ $nav_active == 'stock-adjustment' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-right-left"></i>
                    <p>Penyesuaian Stok</p>
                  </a>
                </li>
              @endif
              @if (true)
                <li class="nav-item">
                  <a href="{{ url('/admin/stock-update') }}"
                    class="nav-link {{ $nav_active == 'stock-update' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-waveform"></i>
                    <p>Riwayat Stok</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::PRODUCT_LIST))
                <li class="nav-item">
                  <a href="{{ url('/admin/product') }}"
                    class="nav-link {{ $nav_active == 'product' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-box"></i>
                    <p>Produk</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::PRODUCT_CATEGORY_MANAGEMENT))
                <li class="nav-item">
                  <a href="{{ url('/admin/product-category') }}"
                    class="nav-link {{ $nav_active == 'product-category' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>Kategori Produk</p>
                  </a>
                </li>
              @endif
            </ul>
          </li>
        @endif
        {{-- Inventory Menu End --}}

        {{-- Purchasing Menu Begin --}}
        @if (Auth::user()->canAccess(AclResource::PURCHASING_MENU))
          <li class="nav-item {{ $menu_active == 'purchasing' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $menu_active == 'purchasing' ? 'active' : '' }}">
              <i class="nav-icon fas fa-truck"></i>
              <p>
                Pembelian
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (Auth::user()->canAccess(AclResource::PURCHASE_ORDER_LIST))
                <li class="nav-item">
                  <a href="{{ url('/admin/purchase-order') }}"
                    class="nav-link {{ $nav_active == 'purchase-order' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cart-shopping"></i>
                    <p>Order Pembelian</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::SUPPLIER_LIST))
                <li class="nav-item">
                  <a href="{{ url('/admin/supplier') }}"
                    class="nav-link {{ $nav_active == 'supplier' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>Pemasok</p>
                  </a>
                </li>
              @endif
            </ul>
          </li>
        @endif
        {{-- End of Purchasing Menu --}}

        {{-- Expense Menu Begin --}}
        @if (Auth::user()->canAccess(AclResource::EXPENSE_MENU))
          <li class="nav-item {{ $menu_active == 'expense' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $menu_active == 'expense' ? 'active' : '' }}">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                Pengeluaran
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/admin/expense') }}"
                  class="nav-link {{ $nav_active == 'expense' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-money-bills"></i>
                  <p>Pengeluaran</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/expense-category') }}"
                  class="nav-link {{ $nav_active == 'expense-category' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-boxes"></i>
                  <p>Kategori Pengeluaran</p>
                </a>
              </li>
            </ul>
          </li>
          
        @endif
        {{-- End of Expense Menu --}}

        {{-- Report Menu --}}
        @if (Auth::user()->canAccess(AclResource::REPORT_MENU))
          <li class="nav-item {{ $menu_active == 'report' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $menu_active == 'report' ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-waveform"></i>
              <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/admin/report/inventory-stock') }}"
                  class="nav-link {{ $nav_active == 'report-inventory-stock' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-file-waveform"></i>
                  <p>Stok Inventori</p>
                </a>
              </li>
            </ul>
          </li>
        @endif
        {{-- End Report Menu --}}

        {{-- System Menu --}}
        @if (Auth::user()->canAccess(AclResource::SYSTEM_MENU))
          <li class="nav-item {{ $menu_active == 'system' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $menu_active == 'system' ? 'active' : '' }}">
              <i class="nav-icon fas fa-gears"></i>
              <p>
                Sistem
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (Auth::user()->canAccess(AclResource::USER_ACTIVITY))
                <li class="nav-item">
                  <a href="{{ url('/admin/user-activity') }}"
                    class="nav-link {{ $nav_active == 'user-activity' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-waveform"></i>
                    <p>Log Aktivitas</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::USER_MANAGEMENT))
                <li class="nav-item">
                  <a href="{{ url('/admin/user') }}" class="nav-link {{ $nav_active == 'user' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Pengguna</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::USER_GROUP_MANAGEMENT))
                <li class="nav-item">
                  <a href="{{ url('/admin/user-group') }}"
                    class="nav-link {{ $nav_active == 'user-group' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-group"></i>
                    <p>Grup Pengguna</p>
                  </a>
                </li>
              @endif
              @if (Auth::user()->canAccess(AclResource::SETTINGS))
                <li class="nav-item">
                  <a href="{{ url('/admin/settings') }}"
                    class="nav-link {{ $nav_active == 'settings' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-gear"></i>
                    <p>Pengaturan</p>
                  </a>
                </li>
              @endif
            </ul>
          </li>
        @endif
        {{-- End of System  menu --}}

        <li class="nav-item">
          <a href="{{ url('/admin/user/profile/') }}"
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
