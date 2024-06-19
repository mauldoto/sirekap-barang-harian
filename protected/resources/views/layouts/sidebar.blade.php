<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{url('/')}}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                @if(Auth()->user()->type != 'admin')
                <li>
                    <a href="{{url('data-personal')}}" class="waves-effect">
                        <i class="bx bx-user"></i>
                        <span key="t-data-personal">Data Personal</span>
                    </a>
                </li>
                @endif

                <li class="menu-title" key="t-masters">Master Data</li>

                <li>
                    <a href="{{route('kursus.index')}}" class="waves-effect">
                        <i class='bx bx-book-bookmark'></i>
                        <span key="t-kursus">Pelatihan</span>
                    </a>
                </li>

                @if(Auth()->user()->type == 'admin')
                <li>
                    <a href="{{route('guru.index')}}" class="waves-effect">
                        <i class='bx bx-user-voice'></i>
                        <span key="t-guru">Fasilitator</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('siswa.index')}}" class="waves-effect">
                        <i class='bx bx-group'></i>
                        <span key="t-siswa">Peserta</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('kategori.index')}}" class="waves-effect">
                        <i class='bx bxs-grid'></i>
                        <span key="t-kursus">Kategori</span>
                    </a>
                </li>



                {{-- <li>
                    <a href="{{route('kelas.index')}}" class="waves-effect">
                <i class='bx bx-buildings'></i>
                <span key="t-kelas">Kelas</span>
                </a>
                </li> --}}

                <li>
                    <a href="{{route('sekolah.index')}}" class="waves-effect">
                        <i class='bx bxs-school'></i>
                        <span key="t-kursus">Instansi</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('tahun-ajaran.index')}}" class="waves-effect">
                        <i class='bx bx-calendar'></i>
                        <span key="t-tahun">Tahun</span>
                    </a>
                </li>
                @endif


            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
