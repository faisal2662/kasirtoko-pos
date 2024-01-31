<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ Request::is('/dashboard') ? '' : 'collapsed' }}" href="/dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard/sale') ? '' : 'collapsed' }}" href="/dashboard/sale">
                <i class="bi bi-pc-display-horizontal"></i>
                <span>Kasir</span>
            </a>
        </li>

        {{-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#kasir-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Kasir</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="kasir-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/dashboard/sale" class="{{ Request::is('dashboard/penjualan') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Penjualan</span>
                    </a>
                </li>
            </ul>
        </li> --}}
        <!-- End Components Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#konsumen-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Konsumen</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="konsumen-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/dashboard/customer" class="{{ Request::is('dashboard/customer') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Pelanggan</span>
                    </a>
                </li>
                <li>
                    <a href="/dashboard/general" class="{{ Request::is('dashboard/general') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Umum</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Forms Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Barang</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/dashboard/productIn" @if (Request::is('dashboard/productIn') || Request::is('dashboard/historyProduct/{slug}')) class="active" @endif>
                        <i class="bi bi-circle"></i><span>Barang Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="/dashboard/product" class="{{ Request::is('dashboard/product') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Daftar Barang</span>
                    </a>
                </li>
                <li>
                    <a href="/dashboard/category" class="{{ Request::is('dashboard/category') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Category</span>
                    </a>
                </li>
                <li>
                    <a href="/dashboard/unit" class="{{ Request::is('dashboard/unit') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Satuan</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Tables Nav -->

        {{-- barang keluar --}}
        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard/productOut') ? '' : 'collapsed' }} "
                href="/dashboard/productOut">
                <i class="bi bi-person"></i>
                <span>Barang Keluar</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard/user') ? '' : 'collapsed' }} " href="/dashboard/user">
                <i class="bi bi-person"></i>
                <span>User</span>
            </a>
        </li>


        <li class="nav-heading">Laporan</li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#laporan" data-bs-toggle="collapse" href="#">
                <i class="bi bi-bar-chart"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="laporan" class="nav-content collapse" data-bs-parent="#laporan">
                <li>
                    <a href="/dashboard/report" class="{{ Request::is('dashboard/report') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Laporan Keuangan</span>
                    </a>
                </li>
                <li>
                    <a href="/dashboard/report/unit"
                        class="{{ Request::is('dashboard/report/unit') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Laporan Barang Keluar</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Charts Nav -->





    </ul>

</aside><!-- End Sidebar-->
