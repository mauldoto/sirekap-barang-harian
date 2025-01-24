<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                {{-- <li class="menu-title" key="t-masters">Log Aktivitas</li>

                <li>
                    <a href="{{route('aktivitas.index')}}" class="waves-effect">
                        <i class='bx bx-calendar-event'></i>
                        <span key="t-kursus">Aktivitas/Job</span>
                    </a>
                </li> --}}

                <li class="menu-title" key="t-menu">Finance</li>

                <li>
                    <a href="{{route('akomodasi.index')}}" class="waves-effect">
                        <i class='bx bx-dollar-circle' ></i>
                        <span key="t-kursus">Rekapan Akomodasi</span>
                    </a>
                </li>

                <li class="menu-title" key="t-masters">Report</li>

                <li>
                    <a href="{{route('report')}}" class="waves-effect">
                        <i class='bx bxs-report'></i>
                        <span key="t-guru">Report</span>
                    </a>
                </li>

                <li class="menu-title" key="t-masters">
                    Akun
                </li>

                <li>
                    <a class="waves-effect logout-link">
                        <i class='bx bx-log-out'></i>
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
