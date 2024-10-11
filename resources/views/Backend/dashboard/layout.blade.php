<!DOCTYPE html>
<html>

<head>
    @include('Backend.dashboard.component.head')

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        @include('Backend.dashboard.component.sidebar')
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                @include('Backend.dashboard.component.nav')
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @include($template)
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                @include('Backend.dashboard.component.footer')
            </footer>
            <!-- End of Footer -->   
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    @include('Backend.dashboard.component.scroll')

    <!-- Logout Modal-->
    @include('Backend.dashboard.component.logoutModel')

    @include('Backend.dashboard.component.script')
</body>

</html>