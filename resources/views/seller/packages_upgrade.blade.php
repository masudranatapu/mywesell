@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 text-primary">Packages Details</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        {{  $packages->name }}
                    </h5>
                    <p class="card-text">Amount {{  $packages->amount }} $</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">{{ $packages->subscription_days}} months website subscription </li>
                    <li class="list-group-item">{{ $packages->available_days}} days money will be available after sell</li>
                    <li class="list-group-item">{{ $packages->withdraw_days}} days will take to withdraw money </li>
                    <li class="list-group-item">{{ $packages->post_limit}} products can be post </li>
                    <li class="list-group-item">{{ $packages->storage_limit}} storage</li>
                    <li class="list-group-item">
                        @if( $packages->promoting_status == 1)
                            Low product promoting
                        @elseif( $packages->promoting_status == 2)
                            Medium  product promoting
                        @else
                            High Excellent product promoting
                        @endif
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="{{ asset('public/payments/paypal.jpg') }}" height="200" class="card-img" alt="...">
                    </div>
                     <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Paypal Payment</h5>
                            <form action="{{ route('seller.paypal.post') }}" method="post">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $packages->id }}">
                                <button type="submit" class="btn btn-primary">Pay Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="{{ asset('public/payments/bank.jpg') }}" height="200" class="card-img" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Bank Payment</h5>
                            <a href="{{ route('seller.bank.payment', $packages->id) }}" class="btn btn-primary mt-5">Pay Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
