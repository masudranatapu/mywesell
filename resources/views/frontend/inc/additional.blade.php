<div class="offcanvas offcanvas-top search-popup" tabindex="-1" id="search">
    <div class="offcanvas-body">
        <form action="{{ route('search') }}" method="GET" class="search-form">
            <div class="search-wrapper text-center">
                <input type="text" id="search" name="keyword" @isset($query) value="{{ $query }}" @endisset placeholder="{{translate('I am shopping for...')}}" class="form-control rounded-0" placeholder="I'm Looking for....">
                <button type="submit" class="btn btn-primary rounded-0 px-5 mt-3">Search</button>
            </div>
        </form>
    </div>
</div>


<div class="offcanvas offcanvas-start mobile-menu-offcanvas" tabindex="-1" id="mobileMenu">
    <div class="parent-layer">
        <div class="offcanvas-header justify-content-end p-0 mb-4">
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body pt-0">
            <div class="d-flex justify-content-center pb-4">
                <a href="{{ route('home') }}">
                    @php
                        $header_logo = get_setting('header_logo');
                    @endphp
                    @if($header_logo != null)
                        <img class="lazyload" src="{{ uploaded_asset($header_logo) }}" height="48"  alt="{{ env('APP_NAME') }}">
                    @else
                        <img class="lazyload" src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" height="48">
                    @endif
                </a>
            </div>
            <ul class="mobile-menu-list">    
                @foreach (\App\Models\Category::where('level', 0)->orderBy('order_level', 'asc')->get()->take(11) as $key => $category)
                    <li>
                        <a href="{{ route('products.category', $category->slug) }}" class="nav-link">
                            <span class="site-nav__label">{{ $category->getTranslation('name') }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<!-- End Mobile Menu -->

@php
    if (auth()->user() != null) {
        $user_id = Auth::user()->id;
        $cart = \App\Models\Cart::where('user_id', $user_id)->get();
    } else {
        $temp_user_id = Session()->get('temp_user_id');
        if ($temp_user_id) {
            $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
        }
    }
@endphp
<div class="offcanvas offcanvas-end cart-offcanvas" tabindex="-1" id="cart_menu">
    <div class="offcanvas-body p-0">
        <div class="offcanvas-cart">
            <div class="cart-header">
                <button class="cart-close" aria-label="Close Cart" type="button" data-bs-dismiss="offcanvas">
                    <i class="fal fa-times fa-2x"></i>
                </button>
                <h4 class="cart-title">Shopping Bag (@if (isset($cart) && count($cart) > 0) {{ count($cart) }} @else 0 @endif)</h4>
            </div>
            <div class="cart-body">
                @php
                    $total = 0;
                @endphp
                @if (isset($cart) && count($cart) > 0)
                    <div class="cart-items">
                        @foreach ($cart as $key => $cartItem)
                            @php
                                $product = \App\Models\Product::find($cartItem['product_id']);
                                // $total = $total + ($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity'];
                                $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
                            @endphp
                            @if ($product != null)
                                <div class="single-cart-item">
                                    <div class="item-top">
                                        <div class="cart-image-wrapper">
                                            <img class="cart-image lazyload" data-src="{{ uploaded_asset($product->thumbnail_img) }}">
                                        </div>
                                        <div class="cart-item-info">
                                            <a href="{{ route('product', $product->slug) }}">{{ $product->getTranslation('name') }}</a>
                                            <div class="text-muted">Code: {{$product->id}}</div>
                                            <div class="text-muted">Price: {{ home_discounted_base_price($product) }}</div>
                                        </div>
                                    </div>
                                    <div class="item-bottom d-flex align-items-center">
                                        <div class="col">
                                            <div class="qty-select quantity">
                                                <div class="d-flex justify-content-center align-items-center quantity-wrapper">
                                                    <button type="button" class="qty-minus btn"><i class="fal fa-minus"></i></button>
                                                    <div class="input-wrapper">
                                                        <input class="mx-1 text-center border-0 fs-13" type="number" value="{{ $cartItem['quantity'] }}" min="1" max="50">
                                                    </div>
                                                    <button type="button" class="qty-plus btn"><i class="fal fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col text-center">
                                            <p class="item-price">{{ cart_product_price($cartItem, $product) }}</p>
                                        </div>
                                        <div class="col text-center">
                                            <button type="button" class="remove-btn-small" onclick="removeFromCart({{ $cartItem['id'] }})">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-3">
                        <i class="las la-frown la-3x opacity-60 mb-3"></i>
                        <h3 class="h6 fw-700">{{ translate('Your Cart is empty') }}</h3>
                    </div>
                @endif
            </div>
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Subtotal: {{ single_price($total) }}</span>
                </div>
                <div class="cart-btns d-flex">
                    <div class="col">
                        <a class="view-cart-btn text-uppercase" href="{{ route('cart') }}">View Bag</a>
                    </div>
                    <div class="col">
                        @if (Auth::check())
                            <a class="checkout-btn text-uppercase" href="{{ route('checkout.shipping_info') }}">Checkout</a>
                        @else
                            <a class="checkout-btn text-uppercase" href="{{ route('cart') }}">Checkout</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Cart -->

<button class="btn scrollTop" type="button"><i class="fad fa-chevron-up"></i></button>