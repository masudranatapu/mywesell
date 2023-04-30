@extends('frontend.layouts.app')

@section('content')

<div class="bg-dark py-2 text-center">
    <h6 class="mb-0 text-white">MY ACCOUNT</h6>
</div>

<section class="py-4">
    <div class="container">
        <div class="login-register-wrapper justify-content-center">
            <div class="single-area">
                <form class="pad-hor" method="POST" role="form" action="{{ route('login') }}">
                    @csrf
                    <div class="form-login-wrap">
                        <h2 class="login-form-title">Login</h2>
                        <div class="mb-3">
                            <label class="form-label" for="username">Email address &nbsp;<span class="required">*</span></label>
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" class="form-control input-text" autocomplete="Email" value="">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password &nbsp;<span class="required">*</span></label>
                            <input class="form-control input-text" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" autocomplete="current-password">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="d-flex align-items-center gap-2 c-pointer">
                                <input class="form-check mb-0" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span>Remember me</span>
                            </label>
                             @if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null)
                                <div>
                                    <a href="{{ route('password.request') }}">Lost your password?</a>
                                </div>
                            @endif
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="button add-cart-btn mx-auto w-100" name="login">Log in</button>
                        </div>
                        <div class="text-center mt-2 mb-2">
                            <p class="text-muted mb-0">{{ translate('Dont have an account?') }}</p>
                            <a href="{{ route('user.registration') }}">{{ translate('Register Now') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- End Login Register Section -->

@endsection

@section('script')
    <script type="text/javascript">
        function autoFill(){
            $('#email').val('admin@example.com');
            $('#password').val('123456');
        }
    </script>
@endsection
