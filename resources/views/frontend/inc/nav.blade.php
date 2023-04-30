@php
    if(Session::has('locale')){
        $locale = Session::get('locale', Config::get('app.locale'));
    }else{
        $locale = 'en';
    }
    
    if(Session::has('currency_code')){
        $currency_code = Session::get('currency_code');
    }else{
        $currency_code = \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code;
    }
    
    if (auth()->user() != null) {
        $user_id = Auth::user()->id;
        $cart = \App\Models\Cart::where('user_id', $user_id)->get();
    } else {
        $temp_user_id = Session()->get('temp_user_id');
        if ($temp_user_id) {
            $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
        }
    }
    
    $header_logo = get_setting('header_logo');
@endphp

<header class="header">
    <div class="topbar">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between">
                <div class="flex-shrink-0">
                    <ul class="d-flex align-items-center gap-3 d-lg-none">
                        <li>
                            <a class="topbar-link me-0" href="#" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                                <div class="flex-shrink-0 nav-toggler-wrapper">
                                    <div class="nav-toggler">
                                        <span class="toggle-line"></span>
                                        <span class="toggle-line"></span>
                                        <span class="toggle-line"></span>
                                    </div>
                                </div>
                                <span class="d-md-inline-block d-none">Menu</span>
                            </a>
                        </li>
                        <li><a class="topbar-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#search"><i class="fal fa-search"></i><span class="d-md-inline-block d-none">Search</span></a></li>
                    </ul>
                </div>
                <div class="flex-shrink-0">
                    <div class="logo-wrapper">
                        <a href="{{ route('home') }}"">
                            @if($header_logo != null)
                                <img class="logo" src="{{ uploaded_asset($header_logo) }}" height="80"  alt="{{ env('APP_NAME') }}">
                            @else
                                <img class="logo" src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" height="80">
                            @endif
                        </a>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <ul class="d-flex align-items-center gap-3 justify-content-end">
                        @if(get_setting('show_language_switcher') == 'on')
                            <li class="list-inline-item dropdown mr-3" id="lang-change">
                                @php
                                    if(Session::has('locale')){
                                        $locale = Session::get('locale', Config::get('app.locale'));
                                    }
                                    else{
                                        $locale = 'en';
                                    }
                                @endphp
                                <a href="javascript:void(0)" class="dropdown-toggle text-reset py-2" data-toggle="dropdown" data-display="static">
                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ static_asset('assets/img/flags/'.$locale.'.png') }}" class="mr-2 lazyload" alt="{{ \App\Models\Language::where('code', $locale)->first()->name }}" height="11">
                                    <span class="opacity-60">{{ \App\Models\Language::where('code', $locale)->first()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    @foreach (\App\Models\Language::where('status', 1)->get() as $key => $language)
                                        <li>
                                            <a href="javascript:void(0)" data-flag="{{ $language->code }}" class="dropdown-item @if($locale == $language) active @endif">
                                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" class="mr-1 lazyload" alt="{{ $language->name }}" height="11">
                                                <span class="language">{{ $language->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                        <li class="d-lg-inline-block d-none"><a class="topbar-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#search"><i class="fal fa-search"></i><span class="d-md-inline-block d-none">Search</span></a></li>
                        @auth
                            @if(isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="topbar-link"><i class="fal fa-user"></i></a>
                            @else
                                @if (Auth::user()->user_type == 'seller')
                                    <a href="{{ route('seller.dashboard') }}" class="topbar-link"><i class="fal fa-user"></i></a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="topbar-link"><i class="fal fa-user"></i></a>
                                @endif
                            @endif
                        @else
                            <li><a class="topbar-link" href="{{ route('shops.create') }}">Be A Saler</a></li>
                            <li><a class="topbar-link" href="{{route('login')}}"><i class="fal fa-user"></i><span class="d-md-inline-block d-none">SIGN IN</span></a></li>
                        @endauth
                        <li><a class="topbar-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#cart_menu"><i class="fal fa-shopping-bag"></i><span>@if (isset($cart) && count($cart) > 0) {{ count($cart) }} @else 0 @endif</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="menubar d-lg-block d-none">
        <div class="container-fluid">
            <nav class="main-menu">
                <ul class="main-menu-list">
                    @foreach (\App\Models\Category::where('level', 0)->orderBy('order_level', 'asc')->get()->take(11) as $key => $category)
                        <li>
                            <a href="{{ route('products.category', $category->slug) }}" class="nav-link">
                                <span class="site-nav__label">{{ $category->getTranslation('name') }}</span>
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('packages') }}" class="nav-link">
                            <span class="site-nav__label">{{ __('Packages') }}</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>


@section('script')
    <script type="text/javascript">
        
        function show_order_details(order_id)
        {
            $('#order-details-modal-body').html(null);

            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', { _token : AIZ.data.csrf, order_id : order_id}, function(data){
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }
    </script>
@endsection
