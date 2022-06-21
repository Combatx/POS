<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link bg-primary">
        <img src="{{ asset('AdminLTE/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('AdminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}"
                        class="nav-link {{ request()->is('/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">MASTER</li>
                <li class="nav-item">
                    <a href="{{ route('kategori.index') }}"
                        class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cube"></i>
                        <p>
                            Kategori
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('satuan.index') }}"
                        class="nav-link {{ request()->is('satuan*') ? 'active' : '' }}">
                        <i class="fas fa-balance-scale"></i>
                        <p>
                            Satuan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('produk.index') }}"
                        class="nav-link {{ request()->is('produk*') ? 'active' : '' }}">
                        <i class="fas fa-boxes"></i>
                        <p>
                            Produk
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('supplier.index') }}"
                        class="nav-link {{ request()->is('supplier*') ? 'active' : '' }}">
                        <i class="fas fa-truck"></i>
                        <p>
                            Supplier
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link {{ request()->is('pelanggan*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <p>
                            Pelanggan
                        </p>
                    </a>
                </li>
                <li class="nav-header">Transaksi</li>
                <li class="nav-item">
                    <a href="{{ route('pembelian.index') }}"
                        class="nav-link {{ request()->is('pembelian*') ? 'active' : '' }}">
                        <i class="fas fa-cart-arrow-down"></i>
                        <p>
                            Pembelian
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link {{ request()->is('penjualan*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <p>
                            Penjualan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link {{ request()->is('transaksi-pembelian*') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i>
                        <p>
                            Penjualan
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
