@extends('frontend.layouts.app')

@section('content')
    <div class="bg-dark py-2 text-center">
        <h6 class="mb-0 text-white">SHOPPING BAG</h6>
    </div>
    <section class="py-4">
        <div class="container">
            @php
                $total = 0;
                $discount = 0;
            @endphp
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($carts && count($carts) > 0)
                        <table class="shop_table shop_table_responsive cart table align-middle">
                            <thead>
                                <tr class="text-uppercase">
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($carts as $key => $cartItem)
                                    @php
                                        $product = \App\Models\Product::find($cartItem['product_id']);
                                        $product_url = route('product', $product->slug);
                                        if($product->auction_product == 1) {
                                            $product_url = route('auction-product', $product->slug);
                                        }
                            
                                        $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                                        // $total = $total + ($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity'];
                                        $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
                                        $discount = $discount + ((cart_product_price($cartItem, $product, false) * $cartItem['quantity']) - (cart_product_price($cartItem, $product, false) * $cartItem['quantity']));
                                        $product_name_with_choice = $product->getTranslation('name');
                                        if ($cartItem['variation'] != null) {
                                            $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variation'];
                                        }
                                    @endphp
                                    <tr class="cart_item">
                                        <td class="product-remove" width="50"><a href="#" class="remove" aria-label="Remove this item" onclick="removeFromCartView(event, {{ $cartItem['id'] }})"><i class="fal fa-times"></i></a></td>
                                        <td class="product-thumbnail" width="150">
                                            <div class="cart-item-image-wrapper">
                                                <a href="{{$product_url}}"><img width="450" height="450" class="cart-item-image lazyload" data-src="{{ uploaded_asset($product->thumbnail_img) }}" alt="product image"></a>
                                            </div>
                                        </td>
                                        <td class="product-name">
                                            <a href="#">{{ $product->getTranslation('name') }}</a>
                                            <p class="mb-0 text-muted fs-13">Product Code: {{$product->code}}</p>
                                        </td>
                                        <td class="product-price" data-title="Price">{{ cart_product_price($cartItem, $product, true, false) }}</td>
                                        <td class="product-quantity" data-title="Quantity">
                                            <div class="qty-select quantity">
                                                <div class="d-flex align-items-center justify-content-md-start justify-content-end quantity-wrapper">
                                                    <button type="button" class="qty-minus btn" data-type="minus" data-field="quantity[{{ $cartItem['id'] }}]"><i class="fal fa-minus"></i></button>
                                                    <div class="input-wrapper">
                                                        <input class="mx-1 qty" type="number" name="quantity[{{ $cartItem['id'] }}]" value="{{ $cartItem['quantity'] }}" min="{{ $product->min_qty }}" max="{{ $product_stock->qty }}" onchange="updateQuantity({{ $cartItem['id'] }}, this)">
                                                    </div>
                                                    <button type="button" class="qty-plus btn" data-type="plus" data-field="quantity[{{ $cartItem['id'] }}]"><i class="fal fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="product-subtotal" data-title="Subtotal" width="150">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem['quantity']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="checkout-details border border-bottom-0 border-dark">
                        <table class="table align-middle border-dark">
                            <tr>
                                <td class="py-3">
                                    <h6 class="mb-0 fs-14 text-uppercase">Subtotal</h6>
                                </td>
                                <td class="py-3">
                                    <h6 class="mb-0 fs-14 fw-700 text-end">{{ single_price($total) }}</h6>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="py-4">
                                    <div class="text-center">
                                        @if (Auth::check())
                                            <a href="{{ route('checkout.shipping_info') }}" class="add-cart-btn">
                                                {{ translate('Proceed To Checkout') }}
                                            </a>
                                        @else
                                            <button type="button" class="add-cart-btn" onclick="showCheckoutModal()">Proceed To Checkout</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Cart Section -->
@endsection

@section('modal')
<div class="modal login-register-modal fade" id="checkout-login-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="login_registerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn modal-close" data-bs-dismiss="modal" style="position: absolute; right: 5px; top: 5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
                        <path
                            d="M13.414,12l6.293-6.293a1,1,0,0,0-1.414-1.414L12,10.586,5.707,4.293A1,1,0,0,0,4.293,5.707L10.586,12,4.293,18.293a1,1,0,1,0,1.414,1.414L12,13.414l6.293,6.293a1,1,0,0,0,1.414-1.414Z">
                        </path>
                    </svg>
                </button>
                <div class="login-register-wrapper">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                        <div class="flex-shrink-0">
                            <h5 class="h5 mb-0">log in</h5>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('user.registration') }}" class="btn btn-toggle btn-sm btn-outline-primary">to register</a>
                        </div>
                    </div>
                    <form action="{{ route('cart.login.submit') }}" method="POST">
                        @csrf
                        
                        <input type="hidden" name="country_code" value="">
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
@endsection

@section('script')
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key);
        }

        function updateQuantity(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart-summary').html(data.cart_view);
                location.reload();
            });
        }

        function showCheckoutModal() {
            $('#checkout-login-modal').modal('show');
        }

        // Country Code
        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phone-code");

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if (country.iso2 == 'bd') {
                country.dialCode = '88';
            }
        }

        var iti = intlTelInput(input, {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                if (selectedCountryData.iso2 == 'bd') {
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

        function toggleEmailPhone(el) {
            if (isPhoneShown) {
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('{{ translate('Use Phone Instead') }}');
            } else {
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('{{ translate('Use Email Instead') }}');
            }
        }
    </script>
@endsection
