<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | Sirekap JPN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="JPN Sirekap" name="description" />
    <meta content="bayemsore" name="author" />
    <meta content="{{env('APP_URL')}}" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/logo-jpn.png')}}">
    @include('layouts.head-css')
</head>

@section('body')
<body data-sidebar="dark">
    @show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')

        @if (auth()->user()->role == 'jpn')
        @include('layouts.sidebar-report')
        @elseif (auth()->user()->role == 'finance')
        @include('layouts.sidebar-finance')
        @else
        @include('layouts.sidebar')
        @endif
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @include('layouts.notification')

                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    {{-- @include('layouts.right-sidebar') --}}
    <!-- /Right-bar -->

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
</body>

</html>
