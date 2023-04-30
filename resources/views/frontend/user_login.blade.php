@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-5">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 mx-auto">
                        <div class="card">
                            <div class="text-center pt-4">
                                <h1 class="h4 fw-600">
                                    {{ translate('Login to your account.')}}
                                </h1>
                            </div>

                            <div class="px-4 py-3 py-lg-4">
                                <div class="">
                                    <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <label for="login_email" class="form-label"><b>E-mail address</b></label>
                                                <input type="email" id="login_email" name="email" value="{{ old('email') }}" required class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
                                            </div>
                                            <div class="col-12">
                                                <label for="login_password" class="form-label"><b>Password</b></label>
                                                <input type="password" id="login_password" name="password" required class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="flex-shrink-0 wt-checkbox">
                                                        <input type="checkbox" name="remember" id="remember" class="remeber-checkbox">
                                                        <label for="remember">stay logged in</label>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <a class="reset-link text-muted" href="#">Forgot Password?</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-grid">
                                                <button type="submit" class="btn btn-primary">log in</button>
                                            </div>
                                            <div class="col-12 text-center">
                                                <a href="{{ route('user.registration') }}" class="reset-link text-muted">Have no account?</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phone-code");

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if(country.iso2 == 'bd'){
                country.dialCode = '88';
            }
        }

        var iti = intlTelInput(input, {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                if(selectedCountryData.iso2 == 'bd'){
                    return "01xxxxxxxxx";
                }
                return selectedCountryPlaceholder;
            }
        });

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

        input.addEventListener("countrychange", function(e) {
            // var currentMask = e.currentTarget.placeholder;

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

        });

        function toggleEmailPhone(el){
            if(isPhoneShown){
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('{{ translate('Use Phone Instead') }}');
            }
            else{
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('{{ translate('Use Email Instead') }}');
            }
        }



        function autoFillSeller(){
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }
        function autoFillCustomer(){
            $('#email').val('customer@example.com');
            $('#password').val('123456');
        }
        function autoFillDeliveryBoy(){
            $('#email').val('deliveryboy@example.com');
            $('#password').val('123456');
        }
    </script>
@endsection
