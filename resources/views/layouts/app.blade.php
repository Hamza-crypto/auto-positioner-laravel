
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ env('APP_NAME') }} </title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.png"/>

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons.min.css') }}" type="text/css">
    <!-- Bootstrap Docs -->
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-docs.css') }}" type="text/css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <!-- Main style file -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" type="text/css">


    @yield('styles')
    @yield('scripts')
</head>
<body>

<!-- preloader -->
{{--<div class="preloader">--}}
{{--    <img src=" {{ asset('assets/img/logo.svg') }}" alt="logo">--}}

{{--    <div class="preloader-icon"></div>--}}
{{--</div>--}}
<!-- ./ preloader -->

<!-- sidebars -->

<!-- notifications sidebar -->

<!-- ./ notifications sidebar -->

<!-- settings sidebar -->

<!-- ./ settings sidebar -->

<!-- search sidebar -->

<!-- ./ search sidebar -->

<!-- ./ sidebars -->

<!-- menu -->

@include('includes.aside')
<!-- ./  menu -->

<!-- layout-wrapper -->
<div class="layout-wrapper">

    <!-- header -->

    @include('includes.header')
    <!-- ./ header -->

    <!-- content -->

    @yield('content')
    <!-- ./ content -->

    <!-- content-footer -->
@include('includes.footer')
    <!-- ./ content-footer -->

</div>
<!-- ./ layout-wrapper -->

<!-- Bundle scripts -->


<!-- Examples -->
<script src="{{ asset('assets/js/users.js') }}"></script>

<!-- Main Javascript file -->
<script src="{{ asset('assets/js/app.js') }}"></script>



<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.18/dist/sweetalert2.min.css
" rel="stylesheet">

<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.18/dist/sweetalert2.all.min.js
"></script>
</body>
</html>
