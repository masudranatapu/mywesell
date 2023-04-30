
<div class="checkout-details border border-bottom-0 border-dark">
    <table class="table align-middle border-dark">
        <tr>
            <td class="py-3">
                <h6 class="mb-0 fs-14 text-uppercase fw-700">{{ translate('Summary') }}</h6>
            </td>
            <td class="py-3">
                <h6 class="mb-0 fs-14 fw-700 text-end">
                    {{ count($carts) }} {{ translate('Items') }}
                    @php
                        $coupon_discount = 0;
                    @endphp
                    @if (Auth::check() && get_setting('coupon_system') == 1)
                        @php
                            $coupon_code = null;
                        @endphp
        
                        @foreach ($carts as $key => $cartItem)
                            @php
                                $product = \App\Models\Product::find($cartItem['product_id']);
                            @endphp
                            @if ($cartItem->coupon_applied == 1)
                                @php
                                    $coupon_code = $cartItem->coupon_code;
                                    break;
                                @endphp
                            @endif
                        @endforeach
        
                        @php
                            $coupon_discount = carts_coupon_discount($coupon_code);
                        @endphp
                    @endif
        
                    @php $subtotal_for_min_order_amount = 0; @endphp
                    @foreach ($carts as $key => $cartItem)
                        @php $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']; @endphp
                    @endforeach
        
                    @if (get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
                        <span class="badge badge-inline badge-primary">
                            {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
                        </span>
                    @endif
                </h6>
            </td>
        </tr>
        @if (addon_is_activated('club_point'))
            @php
                $total_point = 0;
            @endphp
            @foreach ($carts as $key => $cartItem)
                @php
                    $product = \App\Models\Product::find($cartItem['product_id']);
                    $total_point += $product->earn_point * $cartItem['quantity'];
                @endphp
            @endforeach

            <tr>
                <td class="py-3">
                    <h6 class="mb-0 fs-14 text-uppercase">{{ translate('Total Club point') }}</h6>
                </td>
                <td class="py-3">
                    <h6 class="mb-0 fs-14 fw-700 text-end">{{ $total_point }}</h6>
                </td>
            </tr>
        @endif
        @php
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $product_shipping_cost = 0;
            $shipping_region = $shipping_info['city'];
        @endphp
        @foreach ($carts as $key => $cartItem)
            @php
                $product = \App\Models\Product::find($cartItem['product_id']);
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $product_shipping_cost = $cartItem['shipping_cost'];
                
                $shipping += $product_shipping_cost;
                
                $product_name_with_choice = $product->getTranslation('name');
                if ($cartItem['variant'] != null) {
                    $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variant'];
                }
            @endphp
            <tr>
                <td class="py-3">
                    <h6 class="mb-0 fs-14 text-uppercase">
                        {{ $product_name_with_choice }}
                        <strong class="product-quantity">
                            × {{ $cartItem['quantity'] }}
                        </strong>
                    </h6>
                </td>
                <td class="py-3">
                    <h6 class="mb-0 fs-14 fw-700 text-end">{{ single_price(cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']) }}</h6>
                </td>
            </tr>
        @endforeach
        <input type="hidden" id="sub_total" value="{{ $subtotal }}">
        
        <tr>
            <td class="py-3">
                <h6 class="mb-0 fs-14 text-uppercase">{{ translate('Subtotal') }}</h6>
            </td>
            <td class="py-3">
                <h6 class="mb-0 fs-14 fw-700 text-end">{{ single_price($subtotal) }}</h6>
            </td>
        </tr>
        
        <tr>
            <td class="py-3">
                <h6 class="mb-0 fs-14 text-uppercase">{{ translate('Tax') }}</h6>
            </td>
            <td class="py-3">
                <h6 class="mb-0 fs-14 fw-700 text-end">{{ single_price($tax) }}</h6>
            </td>
        </tr>
        
        <tr>
            <td class="py-3">
                <h6 class="mb-0 fs-14 text-uppercase">{{ translate('Shipping') }}</h6>
            </td>
            <td class="py-3">
                <h6 class="mb-0 fs-14 fw-700 text-end">{{ single_price($shipping) }}</h6>
            </td>
        </tr>

        @if (Session::has('club_point'))
            <tr>
                <td class="py-3">
                    <h6 class="mb-0 fs-14 text-uppercase">{{ translate('Redeem point') }}</h6>
                </td>
                <td class="py-3">
                    <h6 class="mb-0 fs-14 fw-700 text-end">{{ single_price(Session::get('club_point')) }}</h6>
                </td>
            </tr>
        @endif

        @if ($coupon_discount > 0)
            <tr>
                <td class="py-3">
                    <h6 class="mb-0 fs-14 text-uppercase">{{ translate('Coupon Discount') }}</h6>
                </td>
                <td class="py-3">
                    <h6 class="mb-0 fs-14 fw-700 text-end">{{ single_price($coupon_discount) }}</h6>
                </td>
            </tr>
        @endif

        @php
            $total = $subtotal + $tax + $shipping;
            if (Session::has('club_point')) {
                $total -= Session::get('club_point');
            }
            if ($coupon_discount > 0) {
                $total -= $coupon_discount;
            }
        @endphp

        <tr>
            <td class="py-3">
                <h6 class="mb-0 fs-14 text-uppercase">{{ translate('Total') }}</h6>
            </td>
            <td class="py-3">
                <h6 class="mb-0 fs-14 fw-700 text-end">{{ single_price($total) }}</h6>
            </td>
        </tr>
        
        
        @if (Auth::check() && get_setting('coupon_system') == 1)
            @if ($coupon_discount > 0 && $coupon_code)
                <div class="mt-3">
                    <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <div class="form-control">{{ $coupon_code }}</div>
                            <div class="input-group-append">
                                <button type="button" id="coupon-remove"
                                    class="btn btn-primary">{{ translate('Change Coupon') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-3">
                    <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                        <div class="input-group">
                            <input type="text" class="form-control" name="code"
                                onkeydown="return event.key != 'Enter';"
                                placeholder="{{ translate('Have coupon code? Enter here') }}" required>
                            <div class="input-group-append">
                                <button type="button" id="coupon-apply"
                                    class="btn btn-primary">{{ translate('Apply') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        @endif
        
        <tr>
            <td class="py-3">
                <a href="{{ route('home') }}" class="add-cart-btn"> <i class="las la-arrow-left"></i> {{ translate('Return to shop') }} </a>
            </td>
            <td class="py-3">
                <button type="button" class="add-cart-btn" onclick="submitOrder(this)">Proceed To Checkout</a>
            </td>
        </tr>
        
    </table>
</div>