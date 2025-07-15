<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-gauge-high"></i> <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
            <li class="header">MASTER</li>
            <li>
                <a href="{{ route('kategori.index') }}">
                    <i class="fa-solid fa-box-archive"></i> <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa-solid fa-boxes-stacked"></i> <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa-solid fa-address-card"></i> <span>Member</span>
                </a>
            </li>
            <li>
                <a href="{{ route('supplier.index') }}">
                    <i class="fa-solid fa-truck"></i> <span>Supplier</span>
                </a>
            </li>
            <li class="header">TRANSAKSI</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa-solid fa-money-bill-wave"></i> <span>Pengeluaran</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ route('pengeluaran.index') }}">
                            <i class="fa-solid fa-circle-notch"></i> <span>Pengeluaran Umum</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pembelian.index') }}">
                            <i class="fa-solid fa-circle-notch"></i> <span>Pembelian Umum</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('penjualan.index') }}">
                    <i class="fa-solid fa-dolly"></i> <span>Penjualan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa-solid fa-cart-plus"></i> <span>Transaksi Baru</span>
                </a>
            </li>
            <li class="header">REPORT</li>
            <li>
                <a href="{{ route('laporan.index') }}">
                    <i class="fa-solid fa-file-invoice"></i> <span>Laporan</span>
                </a>
            </li>
            <li class="header">SYSTEM</li>
            <li>
                <a href="{{ route('user.index') }}">
                    <i class="fa-solid fa-users-gear"></i> <span>User</span>
                </a>
            </li>
            <li>
                <a href="{{ route("setting.index") }}">
                    <i class="fa-solid fa-gears"></i> <span>Pengaturan</span>
                </a>
            </li>
            @else
            <li>
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa-solid fa-cart-plus"></i> <span>Transaksi Baru</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
    </aside>
