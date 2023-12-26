@extends("layouts.auth")

@section('content')
    <div class="container my-auto">
        <div class="row">
            <div class="col-lg-4 col-md-8 col-12 mx-auto">
                <div class="card z-index-0 fadeIn3 fadeInBottom">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
                            <a href="{{ route('welcome') }}"><h4 class="text-white font-weight-bolder text-center mt-2 mb-0">
                                {{__('messages.auth.login')}}</h4></a>
                            <div class="row mt-3">
                                <p class="text-center text-white">  </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <span class="alert-text text-white"> {{__('messages.auth.fail_to_login')}} </span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        {{ isset($error) ? $error : '' }}
                        <form method="POST" action="{{ route('login') }}" role="form" class="text-start">
                            @csrf
                            <div class="input-group input-group-static mt-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="email">
                            </div>
                            @error('email')
                            <small class="text-danger text-xs">{{ $message }}</small>
                            @enderror
                            <div class="input-group input-group-static mt-3" id="show_hide_password">
                                <label class="form-label">{{__('messages.profile.password')}}</label>
                                <input type="password" class="form-control" name="password">
                                <span class="input-group-text mx-2 cursor-pointer" id="show"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                            </div>
                            @error('password')
                            <small class="text-danger text-xs">{{ $message }}</small>
                            @enderror
                            <p class="text-xs text-end m-1">
                                <a href="#">{{__('messages.auth.forget_password')}}</a>
                            </p>
                            <div class="text-center">
                                <button type="submit" class="btn bg-gradient-success w-100 my-4 mb-2">{{__('messages.auth.login')}}</button>
                            </div>
                            <p class="mt-4 text-sm text-center">
                                <a href="{{  url("/signup/worker") }}">{{__('messages.auth.have_no_account')}}</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="{{asset('assets/js/core/jquery-3.6.0.min.js')}}" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        $("#show").on('click', function(event) {
            event.preventDefault();
            if($('#show_hide_password input').attr("type") == "text"){
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass( "fa-eye-slash" );
                $('#show_hide_password i').removeClass( "fa-eye" );
            }else if($('#show_hide_password input').attr("type") == "password"){
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass( "fa-eye-slash" );
                $('#show_hide_password i').addClass( "fa-eye" );
            }
        });
    });
</script>
