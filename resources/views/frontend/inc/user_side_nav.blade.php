<div class="aiz-user-sidenav-wrap position-relative z-1 shadow-sm">
    <div class="aiz-user-sidenav rounded overflow-auto c-scrollbar-light pb-3">
        <div class="p-4 text-xl-center mb-4 border-bottom bg-primary text-white position-relative text-center">
            <span class="avatar avatar-md mb-3">
                @if (Auth::user()->avatar_original != null)
                    <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                @else
                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image rounded-circle"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                @endif
            </span>
            <h4 class="h5 fs-16 mb-1 fw-600 text-white ">{{ Auth::user()->name }}</h4>
            @if (Auth::user()->phone != null)
                <div class="text-truncate opacity-60">{{ Auth::user()->phone }}</div>
            @else
                <div class="text-truncate opacity-60">{{ Auth::user()->email }}</div>
            @endif
        </div>

        <div class="sidemnenu mb-3">
            <ul class="aiz-side-nav-list px-2" data-toggle="aiz-side-menu">

                <li class="aiz-side-nav-item">
                    <a href="{{ route('dashboard') }}" class="aiz-side-nav-link {{ areActiveRoutes(['dashboard']) }}">
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>
                
                @php
                    $delivery_viewed = App\Models\Order::where('user_id', Auth::user()->id)
                        ->where('delivery_viewed', 0)
                        ->get()
                        ->count();
                    $payment_status_viewed = App\Models\Order::where('user_id', Auth::user()->id)
                        ->where('payment_status_viewed', 0)
                        ->get()
                        ->count();
                @endphp
                @if (Auth::user()->user_type == 'customer')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('purchase_history.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['purchase_history.index', 'purchase_history.details']) }}">
                            <span class="aiz-side-nav-text">{{ translate('Purchase History') }}</span>
                            @if ($delivery_viewed > 0 || $payment_status_viewed > 0)
                                <span class="badge badge-inline badge-success">{{ translate('New') }}</span>
                            @endif
                        </a>
                    </li>
                @endif

                @if (addon_is_activated('refund_request'))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('customer_refund_request') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['customer_refund_request']) }}">
                            <span class="aiz-side-nav-text">{{ translate('Sent Refund Request') }}</span>
                        </a>
                    </li>
                @endif
                
                <li class="aiz-side-nav-item">
                    <a href="{{ route('profile') }}" class="aiz-side-nav-link {{ areActiveRoutes(['profile']) }}">
                        {{ translate(' Manage Profile') }}
                    </a>
                </li>
                
                <li class="aiz-side-nav-item">
                    <a href="{{ route('logout') }}" class="aiz-side-nav-link">
                        <span class="aiz-side-nav-text">{{ translate('Logout') }}</span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="#" onclick="account_delete_confirm_modal('{{ route('account_delete') }}')" class="aiz-side-nav-link">
                        <span class="aiz-side-nav-text">{{ translate('Delete My Account') }}</span>
                    </a>
                </li>

            </ul>
        </div>

    </div>

    <div class="fixed-bottom d-xl-none bg-white border-top d-flex justify-content-between px-2"
        style="box-shadow: 0 -5px 10px rgb(0 0 0 / 10%);">
        <a class="btn btn-sm p-2 d-flex align-items-center" href="{{ route('logout') }}">
            <i class="las la-sign-out-alt fs-18 mr-2"></i>
            <span>{{ translate('Logout') }}</span>
        </a>
        <button class="btn btn-sm p-2 " data-toggle="class-toggle" data-backdrop="static"
            data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb">
            <i class="las la-times la-2x"></i>
        </button>
    </div>
</div>
