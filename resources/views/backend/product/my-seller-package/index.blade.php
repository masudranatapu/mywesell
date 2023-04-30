@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<h1 class="h3">{{translate('All Packages')}}</h1>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="card">
		    <div class="card-header row">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">
						{{ translate('Packages') }}
						<!-- <span class="float-right">
							<a href="{{ route('my-seller-package.create') }}" class="btn btn-soft-primary btn-sm text-right">
								Add Package
							</a>
						</span> -->
					</h5>
				</div>
		    </div>
		    <div class="card-body">
		        <table class="table aiz-table mb-0">
		            <thead>
		                <tr>
		                    <th>#</th>
		                    <th>{{translate('Name')}}</th>
		                    <th>{{translate('Amount')}}</th>
		                    <th>Subscription</th>
		                    <th>Available Days</th>
		                    <th>Withdraw Days</th>
		                    <th>Post Limit</th>
		                    <th>Storage Limit</th>
		                    <th>Promoting</th>
		                    <th class="text-right">{{translate('Options')}}</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach($brands as $key => $brand)
		                    <tr>
		                        <td>{{ ($key+1) + ($brands->currentPage() - 1)*$brands->perPage() }}</td>
		                        <td>{{ $brand->name }}</td>
		                        <td>{{ $brand->amount }} $</td>
		                        <td>{{ $brand->subscription_days }} month</td>
		                        <td>{{ $brand->available_days }}</td>
		                        <td>{{ $brand->withdraw_days }}</td>
		                        <td>{{ $brand->post_limit }}</td>
		                        <td>{{ $brand->storage_limit }} GB</td>
		                        <td>
									@if($brand->promoting_status == 1) 
										<span class="badge text-danger">Low</span>
									@elseif($brand->promoting_status == 2)
										<span class="badge text-info">Medium</span>
									@elseif($brand->promoting_status == 3)
										<span class="badge text-success">High</span>
									@else
										<span class="badge text-danger">Not Set Yet</span>
									@endif
								</td>
		                        <td class="text-right">
									<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('my-seller-package.edit', ['id'=>$brand->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
										<i class="las la-edit"></i>
									</a>
									<!-- <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('my-seller-package.destroy', $brand->id)}}" title="{{ translate('Delete') }}">
										<i class="las la-trash"></i>
									</a> -->
		                        </td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
		        <div class="aiz-pagination">
                	{{ $brands->appends(request()->input())->links() }}
            	</div>
		    </div>
		</div>
	</div>
	<!-- @can('add_brand')
		<div class="col-md-5">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{ translate('Add New Package') }}</h5>
				</div>
				<div class="card-body">
					<form action="{{ route('my-seller-package.store') }}" method="POST">
						@csrf
						<div class="form-group mb-3">
							<label for="name">{{translate('Name')}}</label>
							<input type="text" placeholder="{{translate('Name')}}" name="name" class="form-control" required>
						</div>
						<div class="form-group mb-3">
							<label for="amount">{{translate('Amount')}}</label>
							<input type="number" class="form-control" name="amount" value="0">
						</div>
						<div class="form-group mb-3 text-right">
							<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endcan -->
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function sort_brands(el){
        $('#sort_brands').submit();
    }
</script>
@endsection
