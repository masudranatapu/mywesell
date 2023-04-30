@extends('frontend.layouts.app')

@section('content')
    <section class="category-section">
        <div class="container">
            <div class="row my-3">
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
                                <a href="{{ route('shops.create', ['packages' => $pack->id]) }}" class="btn btn-success">Select Package</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@section('script')

@endsection
