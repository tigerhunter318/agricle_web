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
                            <a href="{{  url("/signup/producer") }}" type="button" class="btn {{$role=='producer'?'btn-success':'btn-outline-success'}}">{{__('messages.role.producer')}}</a>
                            <a href="{{  url("/signup/worker") }}" type="button" class="btn {{$role=='worker'?'btn-success':'btn-outline-success'}}">{{__('messages.role.worker')}}</a>
                        </div>
                        <div class="card-body">
                            <form role="form" class="text-start" method="POST" action="{{ route('register') }}" autocomplete="off">
                                @csrf
                                <input type="hidden" name="role" value="{{$role}}">

                                <div class="row max-height-vh-50" style="overflow: auto">
                                    <div class="input-group input-group-static mt-3">
                                        @if($role == 'producer')
                                            <label> {{__('messages.profile.producer_name')}} </label>
                                        @else
                                            <label> {{__('messages.profile.name')}} </label>
                                        @endif
                                        <input type="text" class="form-control" name="family_name" value="{{ old('family_name') }}" />
                                    </div>
                                    @error('family_name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <div class="input-group input-group-static my-3">
                                        @if($role == 'producer')
                                            <label> {{__('messages.profile.producer_name_read')}} </label>
                                        @else
                                            <label> {{__('messages.profile.name_read')}} </label>
                                        @endif
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}"/>
                                    </div>

                                    @if($role == 'worker')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.nickname')}} </label>
                                            <input type="text" class="form-control" name="nickname" value="{{ old('nickname') }}"/>
                                        </div>
                                    @endif

                                    @if($role == 'worker')
                                        <div class="input-group input-group-static mt-3">
                                            <label> {{__('messages.profile.gender.title')}} </label>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="gender" id="manRadio" value="man" checked>
                                                <label class="custom-control-label" for="manRadio">{{__('messages.profile.gender.man')}}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="womanRadio" value="woman">
                                                <label class="custom-control-label" for="womanRadio">{{__('messages.profile.gender.woman')}}</label>
                                            </div>
                                        </div>
                                    @endif

                                    @if($role == 'worker')
                                        <div class="input-group input-group-static mt-3">
                                            <label> {{__('messages.profile.birthday')}} </label>
                                            <input class="form-control" type="date" name="birthday" value="{{ old('birthday') }}" lang="fr-CA">
                                        </div>
                                    @endif

                                    @if($role == 'producer')
                                        <div class="input-group input-group-static mt-3">
                                            <label> {{__('messages.profile.management_mode.title')}} </label>
                                            <select name="management_mode" class="form-control">
                                                <option value="individual" {{ old('management_mode') == 'individual' ? "selected" : "" }}> {{__('messages.profile.management_mode.individual')}} </option>
                                                <option value="corporation" {{ old('management_mode') == 'corporation' ? "selected" : "" }}> {{__('messages.profile.management_mode.corporation')}} </option>
                                                <option value="other" {{ old('management_mode') == 'other' ? "selected" : "" }}> {{__('messages.profile.management_mode.other')}} </option>
                                            </select>
                                        </div>
                                    @endif

                                    <div class="input-group input-group-static mt-3">
                                        <label> {{__('messages.profile.post_number')}} </label>
                                        <input type="text" class="form-control" name="post_number" id="post_number" value="{{ old('post_number') }}">
                                        <span class="input-group-text p-1 z-index-3">
                                            <button class="btn btn-success btn-sm my-0 mx-3" id="get_address_btn" type="button">{{__('messages.action.get_address')}}</button>
                                        </span>
                                    </div>
                                    @error('post_number')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <div class="input-group input-group-static mt-3">
                                        <label> {{__('messages.profile.prefectures')}} </label>
                                        <select name="prefectures" id="prefectures" class="form-control">
                                            <option value="">{{__('messages.title.select')}}</option>
                                            @for($k = 0; $k < count(config('global.pref_city')); $k++)
                                                <option value="{{config('global.pref_city')[$k]['id']}}" {{ old('prefectures') == config('global.pref_city')[$k]['id'] ? "selected" : "" }}>{{config('global.pref_city')[$k]['pref']}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    @error('prefectures')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <div class="input-group input-group-static mt-3">
                                        <label> {{__('messages.profile.city')}} </label>
                                        <input type="text" class="form-control" name="city" id="city" value="{{ old('city') }}">
                                    </div>
                                    @error('city')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <div class="input-group input-group-static mt-3">
                                        <label> {{__('messages.profile.address')}} </label>
                                        <input type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="{{__('messages.profile.input-half-width')}}">
                                    </div>
                                    @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    @if($role == 'producer')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.contact_address')}} </label>
                                            <input type="text" class="form-control" name="contact_address" value="{{ old('contact_address') }}" />
                                        </div>
                                    @endif

                                    @if($role == 'producer')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.agency_name')}} </label>
                                            <input type="text" class="form-control" name="agency_name" value="{{ old('agency_name') }}">
                                        </div>
                                    @endif

                                    @if($role == 'producer')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.agency_phone')}} </label>
                                            <input type="text" class="form-control" name="agency_phone" value="{{ old('agency_phone') }}">
                                        </div>
                                    @endif

                                    @if($role == 'producer')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.insurance.title')}} </label>
                                            <select name="insurance" class="form-control">
                                                <option value="1" {{ old('insurance') === '1' ? "selected" : "" }}>{{__('messages.profile.insurance.yes')}}</option>
                                                <option value="0" {{ old('insurance') === '0' ? "selected" : "" }}>{{__('messages.profile.insurance.no')}}</option>
                                            </select>
                                        </div>
                                    @endif

                                    @if($role == 'producer')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.other_insurance')}} </label>
                                            <input type="text" class="form-control" name="other_insurance" value="{{ old('other_insurance') }}">
                                        </div>
                                    @endif

                                    @if($role == 'producer')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.product_name')}} </label>
                                            <input type="text" class="form-control" name="product_name" placeholder="{{__('messages.profile.product_name_example')}}" value="{{ old('product_name') }}">
                                        </div>
                                    @endif

                                    @if($role == 'worker')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.cell_phone')}} </label>
                                            <input type="text" class="form-control" name="cell_phone" value="{{ old('cell_phone') }}">
                                        </div>
                                    @endif

                                    @if($role == 'worker')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.emergency_phone')}} </label>
                                            <input type="text" class="form-control" name="emergency_phone" value="{{ old('emergency_phone') }}">
                                        </div>
                                    @endif

                                    @if($role == 'worker')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.emergency_relation')}}
                                            </label>
                                            <input type="text" class="form-control" name="emergency_relation" value="{{ old('emergency_relation') }}">
                                        </div>
                                    @endif

                                    @if($role == 'worker')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.job')}}  </label>
                                            <input type="text" class="form-control" name="job" value="{{ old('job') }}">
                                        </div>
                                    @endif

                                    @if($role == 'worker')
                                        <div class="input-group input-group-static my-3">
                                            <label> {{__('messages.profile.bio')}} </label>
                                            <textarea type="text" class="form-control" name="bio">{{ old('bio') }}</textarea>
                                        </div>
                                    @endif

                                    <div class="input-group input-group-static my-3">
                                        <label> {{__('messages.profile.appeal_point')}} </label>
                                        <textarea type="text" class="form-control" name="appeal_point" placeholder="{{__('messages.profile.'.$role.'_appeal_point_example')}}">{{ old('appeal_point') }}</textarea>
                                    </div>

                                    <div class="input-group input-group-static mt-3">
                                        <label> Email </label>
                                        <input type="email" class="form-control" name="email" />
                                    </div>
                                    @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <div class="input-group input-group-static mt-3" id="password_group">
                                        <label> {{__('messages.profile.password')}} </label>
                                        <input type="password" class="form-control" name="password" />
                                        <span class="input-group-text mx-4 cursor-pointer z-index-3" id="show_password"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                                    </div>
                                    @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <div class="input-group input-group-static mt-3" id="password_confirmation_group">
                                        <label> {{__('messages.profile.confirm_password')}} </label>
                                        <input type="password" class="form-control" name="password_confirmation" />
                                        <span class="input-group-text mx-4 cursor-pointer z-index-3" id="show_password_confirmation"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                                    </div>
                                    @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-success w-100 my-4 mb-2"> {{__('messages.action.register')}} </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center pt-0 px-lg-2 px-1">
                            <a class="mb-2 text-sm mx-auto" href="{{ route('login') }}">
                                {{__('messages.auth.already_have_account')}}
                            </a>
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
    $(document).ready(function() {
        $("#show_password").on('click', function(event) {
            event.preventDefault();
            if($('#password_group input').attr("type") == "text"){
                $('#password_group input').attr('type', 'password');
                $('#password_group i').addClass( "fa-eye-slash" );
                $('#password_group i').removeClass( "fa-eye" );
            }else if($('#password_group input').attr("type") == "password"){
                $('#password_group input').attr('type', 'text');
                $('#password_group i').removeClass( "fa-eye-slash" );
                $('#password_group i').addClass( "fa-eye" );
            }
        });

        $("#show_password_confirmation").on('click', function(event) {
            event.preventDefault();
            if($('#password_confirmation_group input').attr("type") == "text"){
                $('#password_confirmation_group input').attr('type', 'password');
                $('#password_confirmation_group i').addClass( "fa-eye-slash" );
                $('#password_confirmation_group i').removeClass( "fa-eye" );
            }else if($('#password_confirmation_group input').attr("type") == "password"){
                $('#password_confirmation_group input').attr('type', 'text');
                $('#password_confirmation_group i').removeClass( "fa-eye-slash" );
                $('#password_confirmation_group i').addClass( "fa-eye" );
            }
        });

        $("#get_address_btn").click(function(){
            $.ajax({
                url: "https://zipcloud.ibsnet.co.jp/api/search",
                type: "get",
                data: {
                    zipcode : $("#post_number").val(),
                },
                success: function (data) {
                    data = JSON.parse(data);
                    if(data["status"] === 200){
                        $("#prefectures").val(data["results"][0]["prefcode"]);
                        $("#city").val(data["results"][0]["address2"]+" "+data["results"][0]["address3"]);
                    }
                },
                error: function (data, textStatus, errorThrown) {
                    console.log(data);
                },
            });
        });
    });
</script>

</body>
</html>
