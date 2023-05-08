@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 text-primary">Bank Payment</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6>
                        Account Number : {{ $packages->account_number }}
                    </h6>
                    <h6>
                        Account Name : {{ $packages->branch_name }}
                    </h6>
                    <h6>
                        Branch Name : {{ $packages->acccount_name }}
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('seller.bank.payment.post', $packages->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $packages->id }}">
                        <input type="hidden" name="amount" value="{{ $packages->amount }}">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Your Account Number</label>
                                <input type="number" class="form-control" name="account_number" required placeholder="Your Account Number">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Branch Name</label>
                                <input type="text" name="branch_name" class="form-control" required placeholder="Branch name">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Acccount Name</label>
                                <input type="text" name="acccount_name" class="form-control" required placeholder=" Account name">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Payment Proved Image</label>
                                <input type="file" name="proved_image" required class="form-control">
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    Pay Now
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
