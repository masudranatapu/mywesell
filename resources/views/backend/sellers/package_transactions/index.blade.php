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
		        <table class="table aiz-table mb-0">
		            <thead>
		                <tr>
		                    <th>#</th>
		                    <th>Order</th>
		                    <th>Transactions</th>
		                    <th>User</th>
		                    <th>Package</th>
		                    <th>Amount</th>
		                    <th>Account No</th>
		                    <th>Status</th>
		                    <th>Payment M.</th>
		                    <th>P. Status</th>
		                    <th class="text-right">Options</th>
		                </tr>
		            </thead>
		            <tbody>
						@foreach($package_transactions as $key => $transactions)
							@php
								$user = DB::table('users')->where('id', $transactions->user_id)->first();
								$package = DB::table('my_seller_packages')->where('id', $transactions->package_id)->first();
							@endphp
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $transactions->order_id }}</td>
								<td>{{ $transactions->transactions_id }}</td>
								<td>{{ $user->name ?? '' }}</td>
								<td>{{ $package->name ?? '' }}</td>
								<td>{{ $transactions->amount }}</td>
								<td>{{ $transactions->account_number }}</td>
								<td>
									@if($transactions->status == 1)
										<span class="text-info">Active</span>
									@else 
										<span class="text-info">Inactive</span>
									@endif
								</td>
								<td>
									@if($transactions->payment_mathod == 1)
										Paypal
									@else
										Bank
									@endif
								</td>
								<td>{{ $transactions->payment_status }}</td>
								<td>
									@if($transactions->payment_mathod == 0)
										<a href="{{route('admin.package.transactions.edit', $transactions->id )}}" class="btn btn-success btn-sm">
											Edit
										</a>
									@endif
								</td>
							</tr>
						@endforeach
		            </tbody>
		        </table>
		    </div>
		</div>
	</div>
</div>

@endsection

@section('script')

@endsection
