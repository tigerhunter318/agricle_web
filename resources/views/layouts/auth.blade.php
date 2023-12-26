<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <title>
        {{ env('APP_NAME') }}
    </title>
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="{{ asset('assets/css/material-kit.css?v=3.0.2') }}" rel="stylesheet" />
</head>

<body class="sign-in-basic">
<div class="page-header align-items-start min-vh-100" style="background-image: url({{ asset('assets/img/auth/login_bg.jpg') }});" loading="lazy">
    <span class="mask bg-gradient-dark opacity-6"></span>

    @yield('content')

    <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-12 col-md-6 my-auto">
                    <div class="copyright text-center text-sm text-white text-lg-start">
                        © <script>
                            document.write(new Date().getFullYear())
                        </script>,
                        {{__('messages.footer.secure')}}
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<script src="{{ asset('assets/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/parallax.min.js') }}"></script>
<script src="{{ asset('assets/js/material-kit.min.js?v=3.0.2') }}" type="text/javascript"></script>

</body>
</html>
