<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('assets/img/favicon.png')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ env('APP_NAME') }}
    </title>

    @yield('links')
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            justify-content: space-between;
        }
        .navbar {
            position: fixed;
            top: 0px;
            width: 100%;
            z-index: 100;
        }
    </style>
</head>

<body class="features-sections">

<x-header></x-header>

<div class="container mt-7" style="flex: 1 0 auto;">
    <div class="row">
        <div class="col-lg-12 mx-auto">

            @yield('content')

        </div>
    </div>
</div>

<x-footer></x-footer>

@yield('scripts')
</body>
</html>
