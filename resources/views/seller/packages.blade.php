@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 text-primary">Packages List</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5>Your Current Package is => {{ $current_packageName->name }}</h5>
                    @if(Auth::user()->package_validate < now())
                        <h6>Your Package Validation is over. Please Upgrade you package</h6>
                    @else
                        <h6> Package Validation is :  {{ date('Y-M-d', strtotime(Auth::user()->package_validate)) }} </h6>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach($packages as $key => $pack)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            {{ $pack->name }}
                        </h5>
                        <p class="card-text">Amount {{ $pack->amount }} $</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">{{$pack->subscription_days}} months website subscription </li>
                        <li class="list-group-item">{{$pack->available_days}} days money will be available after sell</li>
                        <li class="list-group-item">{{$pack->withdraw_days}} days will take to withdraw money </li>
                        <li class="list-group-item">{{$pack->post_limit}} products can be post </li>
                        <li class="list-group-item">{{$pack->storage_limit}} storage</li>
                        <li class="list-group-item">
                            @if($pack->promoting_status == 1)
                                Low product promoting
                            @elseif($pack->promoting_status == 2)
                                Medium  product promoting
                            @else
                                High Excellent product promoting
                            @endif
                        </li>
                    </ul>
                    <div class="card-body text-center">
                        <a href="{{ route('seller.pack.upgrade', ['packages' => $pack->id]) }}" class="btn btn-success">Upgrade Package</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('script')

@endsection
