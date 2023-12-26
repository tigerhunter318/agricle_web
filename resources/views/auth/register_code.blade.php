<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        {{ env('APP_NAME') }}
    </title>

    <link href="{{ asset('assets/css/webkit.scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/fonts/kit.fontawesome.js') }}" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/fonts/material.icon.css') }}" rel="stylesheet">
    <link id="pagestyle" href="{{ asset('assets/css/material-kit.css?v=3.0.2') }}" rel="stylesheet" />
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance:textfield;
            text-align: center;
            font-size: 18pt;
        }
    </style>
</head>
<body class="sign-up-illustration">
<section>
    <div class="page-header min-vh-100" style="background-image: url({{ asset('assets/img/auth/register_bg.jpg') }});" loading="lazy">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-8 col-sm-12 d-flex flex-column ms-auto me-auto me-lg-auto ms-lg-5">
                    <div class="card">
                        <div class="card-header text-center pb-0">
                            <a href="{{ route('welcome') }}"><h4 class="font-weight-bolder text-success">{{__('messages.auth.register')}}</h4></a>
                            <form id="emailForm" method="post" action="{{ route('resend_code', ['user_id' => $user['id']]) }}">
                                @csrf
                            </form>
                            <a href="#" onclick="document.getElementById('emailForm').submit()">Resend code</a>
                        </div>
                        <div class="card-body">
                            <form role="form" id="form" class="text-start" method="POST" action="{{ route('register_code_check', ['user_id' => $user['id']]) }}" autocomplete="off">
                                @csrf
                                <div class="row max-height-vh-50" style="overflow: auto">
                                    @if (session('msg') || $errors->any())
                                        <div class="alert alert-danger text-center text-white">
                                            {{ session('msg') }}
                                            {{ $errors->first() }}
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="input-group input-group-outline my-3">
                                                <input type="number" class="form-control code-digit" min="0" max="9" id="1" name="code-digit1">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-outline my-3">
                                                <input type="number" class="form-control code-digit" min="0" max="9" id="2" name="code-digit2">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-outline my-3">
                                                <input type="number" class="form-control code-digit" min="0" max="9" id="3" name="code-digit3">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-outline my-3">
                                                <input type="number" class="form-control code-digit" min="0" max="9" id="4" name="code-digit4">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-outline my-3">
                                                <input type="number" class="form-control code-digit" min="0" max="9" id="5" name="code-digit5">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-outline my-3">
                                                <input type="number" class="form-control code-digit" min="0" max="9" id="5" name="code-digit6">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-success w-100 my-4 mb-2"> {{__('messages.action.register')}} </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{asset('assets/js/core/jquery-3.6.0.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/parallax.min.js') }}"></script>
<script src="{{ asset('assets/js/material-kit.min.js?v=3.0.2') }}" type="text/javascript"></script>

<script>
    $(document).ready(function () {
        $('.code-digit:first').focus();
    })

    $('.code-digit').on('keyup', function(e) {
        if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
            e.keyCode = e.keyCode >= 96 ? e.keyCode - 48 : e.keyCode;
            $(this).val(String.fromCharCode(e.keyCode));
            $('.code-digit:eq('+this.id+')').focus();
        }
    })

    $('.code-digit').bind('paste', function(e) {
        e.preventDefault();
        const pastedData = parseInt(e.originalEvent.clipboardData.getData('text'));
        if(pastedData >= 100000 && pastedData < 1000000) {
            for (let i = 0, len = pastedData.toString().length; i < len; i += 1) {
                $('.code-digit').eq(i).val(+pastedData.toString().charAt(i));
            }
        }
    })

    $('')
</script>

</body>
</html>
