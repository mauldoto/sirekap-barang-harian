<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                {{-- <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{url('/')}}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li> --}}

                <li class="menu-title" key="t-masters">Master Data</li>

                <li>
                    <a href="{{route('barang.index')}}" class="waves-effect">
                        <i class='bx bx-book-bookmark'></i>
                        <span key="t-kursus">Barang</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('karyawan.index')}}" class="waves-effect">
                        <i class='bx bx-group'></i>
                        <span key="t-siswa">Karyawan</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('lokasi.index')}}" class="waves-effect">
                        <i class='bx bx-group'></i>
                        <span key="t-siswa">Lokasi</span>
                    </a>
                </li>

                <li class="menu-title" key="t-masters">Log Stok & Aktivitas</li>

                <li>
                    <a href="{{route('stok.index')}}" class="waves-effect">
                        <i class='bx bx-archive'></i>
                        <span key="t-guru">Stok</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('aktivitas.index')}}" class="waves-effect">
                        <i class='bx bx-calendar-event'></i>
                        <span key="t-kursus">Aktivitas/Job</span>
                    </a>
                </li>

                <li class="menu-title" key="t-masters">
                    Akun
                </li>

                <li>
                    <a class="waves-effect logout-link">
                        <i class='bx bx-calendar-event'></i>
                        <span key="t-kursus">Logout</span>
                    </a>

                    <form id="logoutForm" action="{{route('logout')}}" method="POST">
                        @csrf
                    </form>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
