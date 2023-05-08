@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">Package Transactions</h1>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header row">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">
                            Packages Transactions
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 offset-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6>
                                        Account Number : {{ $transactions->account_number }}
                                    </h6>
                                    <h6>
                                        Account Name : {{ $transactions->acccount_name }}
                                    </h6>
                                    <h6>
                                        Branch Name : {{ $transactions->branch_name }}
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ asset($transactions->proved_image) }}" target="__blank">
                                                <img widht="200" height="200" src="{{ asset($transactions->proved_image) }}" alt="">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="{{ route('admin.package.transactions.acpect', $transactions->id) }}" method="POST">
                                                @csrf
                                                <select name="status" class="form-control" onchange="this.form.submit()">
                                                    <option value="" selected disabled>Select One</option>
                                                    <option value="1">Approved</option>
                                                    <option value="0">Aisapproved</option>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
