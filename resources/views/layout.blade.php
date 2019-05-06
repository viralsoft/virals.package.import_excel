<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Virals Excel</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('vendor/viralslaravelexcel/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('vendor/viralslaravelexcel/css/simple-sidebar.css') }}" rel="stylesheet">

    @yield('custom_styles')
    @stack('custom_styles')
</head>
<body>
<!-- Site wrapper -->
<div class="d-flex" id="wrapper">
    @include('viralslaravelexcel::sidebar')

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <button class="btn btn-info btn-lg" id="menu-toggle" style="padding: 0.5rem 0.8rem; font-size: 0.5em">
                <span class="navbar-toggler-icon" style="color: #ffffff;"></span>
            </button>
            <div class="ml-auto mr-20 mt-lg-0" style="margin-right: 80%">
                @stack('stack_btn_top')
            </div>
        </nav>

        <div class="container-fluid">
            @yield('content')
        </div>

        <div class="container-fluid">
            @yield('footer')
        </div>
    </div>
    <!-- /#page-content-wrapper -->
</div>

<!-- Bootstrap core JavaScript -->
<script src="{{ asset('vendor/viralslaravelexcel/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/viralslaravelexcel/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
@yield('custom_scripts')
@stack('custom_scripts')
</body>
</html>
