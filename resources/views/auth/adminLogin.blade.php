@extends("layouts.adminAuth")

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>{{__('messages.auth.login')}}</b>({{__('messages.role.admin')}})</a>
        </div>
        <!-- /.login-logo -->
        <div class="card p-2 border-radius-lg">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{__('messages.auth.type_credential')}}</p>

                <form method="POST" action="{{ route('adminLogin') }}">
                    @csrf

                    @error('email')
                    <small class="text-danger text-xs">{{ $message }}</small>
                    @enderror
                    <div class="input-group mb-4">
                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{old('email')}}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    @error('password')
                    <small class="text-danger text-xs">{{ $message }}</small>
                    @enderror
                    <div class="input-group mb-4">
                        <input type="password" name="password" class="form-control" placeholder={{__('messages.profile.password')}}>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="social-auth-links text-center mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            {{__('messages.auth.login')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script src="{{ asset('admin_assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function () {
        if({{count($errors)}}) toastr.error(`{{__('messages.auth.email_or_password_error')}}`)
    })
</script>
