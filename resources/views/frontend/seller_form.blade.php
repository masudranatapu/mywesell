@extends('frontend.layouts.app')

@section('content')

<div class="bg-dark py-10px">
    <div class="text-center text-white">Register Your Shop</div>
</div>
<!-- End Promo info -->
<section class="py-4">
    <div class="container">
        <div class="login-register-wrapper justify-content-center">
            <div class="single-area">
                <form id="shop" class="pad-hor" action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (!Auth::check())
                        <div class="bg-white rounded shadow-sm mb-3">
                            <div class="fs-15 fw-600 p-3 border-bottom">
                                {{ translate('Personal Info')}}
                            </div>
                            <div class="p-3">
                                <div class="form-group">
                                    <label>{{ translate('Your Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{  translate('Name') }}" name="name">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Your Email')}} <span class="text-danger">*</span></label>
                                    <input type="email" required class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Your Password')}} <span class="text-danger">*</span></label>
                                    <input type="password" required class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{  translate('Password') }}" name="password">
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Repeat Password')}} <span class="text-danger">*</span></label>
                                    <input type="password" required class="form-control" placeholder="{{  translate('Confirm Password') }}" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="bg-white rounded shadow-sm mb-4">
                        <div class="fs-15 fw-600 p-3 border-bottom">
                            {{ translate('Basic Info')}}
                        </div>
                        <div class="p-3">
                            <div class="form-group">
                                <label>{{ translate('Shop Name')}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="{{ translate('Shop Name')}}" name="shop_name" required>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Address')}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mb-3" placeholder="{{ translate('Address')}}" name="address" required>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Package')}} <span class="text-danger">*</span></label>
                                <select class="form-control mb-3" name="my_seller_package_id" required>
                                    <option value="">Select Package</option>
                                    @foreach($packages as $package)
                                        <option value="{{$package->id}}" @if(request()->get('packages') == $package->id) selected @endif>{{$package->name}} ( {{ $package->amount }} $)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @if(get_setting('google_recaptcha') == 1)
                        <div class="form-group mt-2 mx-auto row">
                            <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}"></div>
                        </div>
                    @endif

                    <div class="d-grid">
                        <button type="submit" class="button add-cart-btn mx-auto w-100">{{ translate('Register Your Shop')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript">
    // making the CAPTCHA  a required field for form submission
    $(document).ready(function(){
        // alert('helloman');
        $("#shop").on("submit", function(evt)
        {
            var response = grecaptcha.getResponse();
            if(response.length == 0)
            {
            //reCaptcha not verified
                alert("please verify you are humann!");
                evt.preventDefault();
                return false;
            }
            //captcha verified
            //do the rest of your validations here
            $("#reg-form").submit();
        });
    });
</script>
@endsection
