<!doctype html>
<html lang="en">

<head>
    @include('admin.incloudes.head')
    @stack('css')
</head>


<body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('admin.incloudes.header')
        <!-- ========== Left Sidebar Start ========== -->
        @include('admin.incloudes.sidebar')
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">


                    @yield('content')


                </div>

            </div>
            <!-- End Page-content -->

            @include('admin.incloudes.footer')

        </div>
        <!-- end main content-->

    </div>


    <!-- JAVASCRIPT -->
    @include('admin.incloudes.scripts')
    @stack('scripts')
</body>

</html>
