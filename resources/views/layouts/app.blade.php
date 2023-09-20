<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ env('APP_NAME') }} </title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.png" />

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Bootstrap Docs -->
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-docs.css') }}" type="text/css">

    <!-- Slick -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <!-- Main style file -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" type="text/css">

    @yield('styles')
    @yield('scripts')


</head>

<body>

    {{-- <!-- preloader -->
    <div class="preloader">
        <div class="preloader-icon"></div>
    </div>
    <!-- ./ preloader --> --}}
    
    @include('includes.aside')

    <div class="layout-wrapper">

        @include('includes.header')

        <!-- content -->
        <div class="content">
            @yield('content')
        </div>
        <!-- ./ content -->

        @include('includes.footer')

    </div>

    {{-- Bundle scripts --}}
    <script src="{{ asset('assets/js/bundle.js') }}"></script>

    {{-- place onload bundling scripts here  --}}

    @yield("bundlingScripts")

    {{-- Slick --}}
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    {{-- Examples --}}
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.18/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.18/dist/sweetalert2.all.min.js"></script>

    {{-- Main Javascript file --}}
    <script src="{{ asset('assets/js/app_min.js') }}"></script>



</body>

< /html>
