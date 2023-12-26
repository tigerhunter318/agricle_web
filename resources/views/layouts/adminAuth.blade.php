<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('admin_assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/adminlte.css') }}">

</head>
<body class="hold-transition login-page">

@yield('content')

<script src="{{ asset('admin_assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin_assets/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('admin_assets/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
