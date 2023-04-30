@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Brand Information')}}</h5>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body p-0">
            <form class="p-4" action="{{ route('my-seller-package.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6 mb-3">
                        <label class="col-from-label" for="name">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                        <input type="text" placeholder="{{translate('Name')}}" require id="name" name="name" value="{{ $brand->name }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="col-from-label">{{translate('Amount')}}</label>
                        <input type="number" class="form-control" name="amount" require value="{{ $brand->amount }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="col-from-label">Subscription (Monthly) <span class="text-danger">*</span></label>
                        <input type="number" min="1" max="12" placeholder="Subscription  (Monthly)" require name="subscription_days" value="{{ $brand->subscription_days }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="col-from-label">Available Days <span class="text-danger">*</span></label>
                        <input type="number" min="1" placeholder="Available Days" name="available_days" value="{{ $brand->available_days }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="col-from-label">Withdraw Days <span class="text-danger">*</span></label>
                        <input type="number" min="1" placeholder="Withdraw Days" require name="withdraw_days" value="{{ $brand->withdraw_days }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="col-from-label">Post Limit <span class="text-danger">*</span></label>
                        <input type="number" min="1" placeholder="Post Limit" require name="post_limit" value="{{ $brand->post_limit }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="col-from-label">Storage Limit <span class="text-danger">*</span></label>
                        <input type="number" min="1" placeholder="Storage Limit" require name="storage_limit" value="{{ $brand->storage_limit }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="col-from-label">Promoting <span class="text-danger">*</span></label>
                        <select name="promoting_status" class="form-control" require>
                            <option value="1" @if($brand->promoting_status == 1) selected @endif>Low</option>
                            <option value="2" @if($brand->promoting_status == 2) selected @endif>Medium</option>
                            <option value="3" @if($brand->promoting_status == 3) selected @endif>High</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
