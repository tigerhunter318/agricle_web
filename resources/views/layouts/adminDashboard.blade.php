<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>
    @yield('links')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <x-navbar></x-navbar>

    <x-sidebar></x-sidebar>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <footer class="main-footer">
        {{__('messages.footer.secure')}}
    </footer>
</div>

@yield('scripts')
</body>
</html>
