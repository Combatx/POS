<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard.index') }}" class="brand-link bg-primary">
        <img src="{{ asset('AdminLTE/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $appname }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (Auth::user()->foto == 'img/user1.png')
                    <img src="{{ asset(Auth::user()->foto) }}" class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="img-circle elevation-2"
                        alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="{{ route('profil.index') }}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->is('/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if (auth()->user()->hasRole('gudang'))
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
                @endif
                @if (auth()->user()->hasRole('kasir'))
                    <a href="{{ route('pelanggan.index') }}"
                        class="nav-link {{ request()->is('pelanggan*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <p>
                            Pelanggan
                        </p>
                    </a>
                    </li>
                @endif

                @if (auth()->user()->hasRole('gudang') ||
                    auth()->user()->hasRole('kasir'))
                    <li class="nav-header">Transaksi</li>
                @endif
                @if (auth()->user()->hasRole('gudang'))
                    <li class="nav-item">
                        <a href="{{ route('pembelian.index') }}"
                            class="nav-link {{ request()->is('pembelian*') ? 'active' : '' }}">
                            <i class="fas fa-cart-arrow-down"></i>
                            <p>
                                Pembelian
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('kasir'))
                    <li class="nav-item">
                        <a href="{{ route('penjualan.index') }}"
                            class="nav-link {{ request()->is('penjualan*') ? 'active' : '' }}">
                            <i class="fas fa-store"></i>
                            <p>
                                Daftar Penjualan
                            </p>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('transaksi.index') }}"
                            class="nav-link {{ request()->is('transaksi.index') ? 'active' : '' }}">
                            <i class="fas fa-calculator"></i>
                            <p>
                                Transaksi Aktif
                            </p>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="{{ route('transaksi.baru') }}"
                            class="nav-link {{ request()->is('transaksi/baru') ? 'active' : '' }} || {{ request()->is('transaksi/selesai') ? 'active' : '' }}">
                            <i class="fas fa-cash-register"></i>
                            <p>
                                Transaksi Baru
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('pengiriman.index') }}"
                            class="nav-link {{ request()->is('pengiriman*') ? 'active' : '' }}">
                            <i class="fas fa-store"></i>
                            <p>
                                Pengiriman
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('penjualan.index') }}"
                        class="nav-link {{ request()->is('penjualan*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <p>
                            Daftar Penjualan
                        </p>
                    </a>
                </li>
                @if (auth()->user()->hasRole('admin'))
                    <li class="nav-header">Konfigurasi</li>
                    <li class="nav-item">
                        <a href="{{ route('setting.index') }}"
                            class="nav-link {{ request()->is('setting') ? 'active' : '' }} ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            <p>
                                Setting
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('admin'))
                    <li class="nav-header">Administrator</li>

                    <li class="nav-item">
                        <a href="{{ route('user.index') }}"
                            class="nav-link {{ request()->is('user') ? 'active' : '' }} ? 'active' : '' }}">
                            <i class="fas fa-user"></i>
                            <p>
                                User
                            </p>
                        </a>
                    </li>
                    {{-- @endif --}}
                    {{-- <li class="nav-item">
                        <a href="{{ route('role.index') }}"
                            class="nav-link {{ request()->is('role') ? 'active' : '' }} ? 'active' : '' }}">
                            <i class="fas fa-user-tag"></i>
                            <p>
                                Role
                            </p>
                        </a>
                    </li> --}}
                @endif
                <br>
                <br>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
