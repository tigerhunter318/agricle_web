<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{env('APP_NAME')}}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('assets/img/favicon.png')}}">

    <link href="{{ asset('assets/css/webkit.scrollbar.css') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/plugins/landing/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/landing/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/landing/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/landing/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/landing/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/landing/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/landing/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/plugins/landing/css/style.css') }}" rel="stylesheet">
    <style>
        .register-item {
            transition: 0.5s;
        }
        .register-item:hover {
            transform: translateY(-10px);
            transition: 0.5s;
        }
        .modal-content {
            background-color: transparent;
            border: none;
            outline: 0;
        }
    </style>
</head>

<body>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top ">
    <div class="container d-flex align-items-center">
        <h1 class="logo me-auto"><a href="index.html">{{env('APP_NAME')}}</a></h1>
    </div>
</header><!-- End Header -->

<!-- ======= Hero Section ======= -->
<section id="hero" class="">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 hero-img" data-aos="zoom-in" data-aos-delay="200">
                <img src="{{ asset('assets/plugins/landing/img/phone.png') }}" class="img-fluid animated" alt="">
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <h1 style="word-spacing: 999px; line-height: 70px;font-size: 3em">{{__('messages.landing.welcome')}}</h1>
{{--                <h2>{{__('messages.landing.welcome_detail')}}</h2>--}}
                <div class="d-flex justify-content-center justify-content-lg-start">
                    <a class="btn-get-started" href="{{ route('login') }}">{{__('messages.auth.login')}}</a>
                    <a class="btn-get-started" data-bs-toggle="modal" data-bs-target="#registerModal">{{__('messages.auth.register')}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <a class="d-block blur-shadow-image register-item position-relative" href="{{  url("/signup/producer") }}">
                            <img src="{{ asset('assets/img/auth/farm.png') }}" alt="img-colored-shadow" class="img-fluid" style="width: 100%; border-radius: 10px">
                            <h3 style="position: absolute; bottom: 20px; color: #fff; backdrop-filter: blur(50px); border-radius: 5px; font-size: 25px; font-weight: bold; left: 20px; letter-spacing: 5px; padding: 5px;"> {{__('messages.role.producer')}} </h3>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a class="d-block blur-shadow-image register-item position-relative" href="{{  url("/signup/worker") }}">
                            <img src="{{ asset('assets/img/auth/worker.png') }}" alt="img-colored-shadow" class="img-fluid" style="width: 100%; border-radius: 10px">
                            <h3 style="position: absolute; bottom: 20px; color: #fff; font-size: 25px; backdrop-filter: blur(50px);  font-weight: bold; right: 20px; letter-spacing: 5px; padding: 5px; border-radius: 5px"> {{__('messages.role.worker')}} </h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<main id="main">
    <!-- ======= Works Section ======= -->
    <section id="works" class="works">
        <div class="container" data-aos="fade-up">
            <h2 class="text-center">あなたの時間、農村で過ごしませんか？</h2>
            <h3 class="text-center">自分に合う農業アルバイトを探そう！</h3>
            <div class="row" id="producer-row">
                <div class="square"></div>
                <div class="col-lg-6 d-flex justify-content-end flex-column align-items-start p-5 title">
                    <h2>生産者ガイド</h2>
                    <h3 class="m-0">私たちは常に素晴らしいサービスを提供します</h3>
                </div>
                <div class="col-lg-6 img-column" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('assets/plugins/landing/img/producer-works.png') }}" class="animated" alt="">
                </div>
            </div>
            <div class="row" id="worker-row">
                <div class="square"></div>
                <div class="col-lg-6 img-column" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('assets/plugins/landing/img/worker-works.png') }}" class="animated" alt="">
                </div>
                <div class="col-lg-6 d-flex justify-content-end flex-column align-items-end p-5 title">
                    <h2>労働者ガイド</h2>
                    <h3 class="m-0">私たちは常に素晴らしいサービスを提供します</h3>
                </div>
            </div>
        </div>
    </section><!-- End Skills Section -->

    <!-- ======= Farms Section ======= -->
    <section id="farms" class="farms">
        <div class="container" data-aos="zoom-out">
            <h1>農場の紹介</h1>
            <div class="row">
                @foreach($farms as $farm)
                    <div class="col-lg-6 position-relative" data-aos="fade-up" data-aos-delay="200">
                        <img src="{{ $farm['avatar'] }}" class="animated" alt="">
                        <h3>{{ $farm['name'] }}</h3>
                        <h4>{{ $farm['appeal_point'] }}</h4>
                    </div>
                @endforeach
            </div>
        </div>
    </section><!-- End Skills Section -->

    <!-- ======= Recruitments Section ======= -->
    <section id="recruitments" class="recruitments">
        <div class="container" data-aos="zoom-in">
            <h1>仕事情報の一例です</h1>
            <h2>ジョブリスト</h2>
            <div class="row">
                <div class="col-md-12">
                    <img src="{{ asset('assets/plugins/landing/img/recruitments-list.png') }}" class="animated" alt="">
                </div>
            </div>
        </div>
    </section><!-- End Skills Section -->

    <!-- ======= workflow Section ======= -->
    <section id="workflow" class="workflow">
        <div class="container" data-aos="fade-out">
            <h1>一日の流れ</h1>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 p-0 position-relative">
                    <img src="{{ asset('assets/plugins/landing/img/workflow1.png') }}" class="animated" alt="">
                    <div class="workflow-title">マニュアル確認</div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 p-0 position-relative">
                    <img src="{{ asset('assets/plugins/landing/img/workflow2.png') }}" class="animated" alt="">
                    <div class="workflow-title">移動</div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 p-0 position-relative">
                    <img src="{{ asset('assets/plugins/landing/img/workflow3.png') }}" class="animated" alt="">
                    <div class="workflow-title">農作業</div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 p-0 position-relative">
                    <img src="{{ asset('assets/plugins/landing/img/workflow4.png') }}" class="animated" alt="">
                    <div class="workflow-title">評価</div>
                </div>
            </div>
        </div>
    </section><!-- End Skills Section -->

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="container footer-bottom clearfix">
        <div class="copyright">
            Copyright © <script>
                document.write(new Date().getFullYear())
            </script>
        </div>
        <div class="credits">
            {{__('messages.footer.secure')}}
        </div>
    </div>
</footer><!-- End Footer -->

<div id="preloader"></div>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


<script src="{{asset('assets/js/core/popper.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/core/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/core/jquery-3.6.0.min.js')}}" type="text/javascript"></script>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/plugins/landing/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('assets/plugins/landing/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('assets/plugins/landing/vendor/swiper/swiper-bundle.min.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('assets/plugins/landing/js/main.js') }}"></script>

<script>
</script>

</body>

</html>
