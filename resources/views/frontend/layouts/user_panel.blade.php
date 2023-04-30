@extends('frontend.layouts.app')
@section('content')
<div class="bg-dark py-2 text-center">
    <h6 class="mb-0 text-white">Customer Panel</h6>
</div>
<section class="py-lg-5 py-4">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-2 sidebar-area p-0">
			    @include('frontend.inc.user_side_nav')
            </div>
            <div class="col-lg-9 main-area">
    			<div class="aiz-user-panel">
    				@yield('panel_content')
                </div>
            </div>
        </div>
    </div>
</section>
@endsection