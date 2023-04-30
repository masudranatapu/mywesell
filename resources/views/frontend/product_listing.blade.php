@extends('frontend.layouts.app')

@if (isset($category_id))
    @php
        $meta_title = \App\Models\Category::find($category_id)->meta_title;
        $meta_description = \App\Models\Category::find($category_id)->meta_description;
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = \App\Models\Brand::find($brand_id)->meta_title;
        $meta_description = \App\Models\Brand::find($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title         = get_setting('meta_title');
        $meta_description   = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')
    <div class="bg-dark py-10px">
        <div class="text-center text-white">Contactless Delivery:Save 5% On Entire Order With Digital Payment</div>
    </div>
    <!-- End Promo info -->

    <section class="pt-4 pb-5">
        <div class="container-fluid">
            <div class="row g-0 align-items-center mb-4">
                <div class="col">
                    <nav class="breadcrumb-nav">
                        <ul class="breadcrumb-menu">
                            <li><a href="{{route('home')}}">Home</a></button></li>
                            @if (\App\Models\Category::find($category_id)->parent_id != 0)
                                <li><a href="{{ route('products.category', \App\Models\Category::find(\App\Models\Category::find($category_id)->parent_id)->slug) }}">{{ \App\Models\Category::find(\App\Models\Category::find($category_id)->parent_id)->getTranslation('name') }}</a></li>
                            @endif
                            <li><a href="{{ route('products.category', \App\Models\Category::find($category_id)->slug) }}">{{ \App\Models\Category::find($category_id)->getTranslation('name') }}</a></li>
                            @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category_id) as $key => $id)
                                <li><a href="{{ route('products.category', \App\Models\Category::find($id)->slug) }}">{{ \App\Models\Category::find($id)->getTranslation('name') }}</a></li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="row g-lg-4 g-3">
                @foreach ($products as $key => $product)
                    @php
                        $product_url = route('product', $product->slug);
                        if($product->auction_product == 1) {
                            $product_url = route('auction-product', $product->slug);
                        }
                    @endphp
                    <div class="col-xl-custom col-lg-3 col-sm-4 col-6">
                        <div class="single-product">
                            <figure class="mb-0 product-image-wrapper">
                                <a href="{{ $product_url }}">
                                    <img class="product-image image-one lazyload" data-src="{{ uploaded_asset($product->thumbnail_img) }}" alt="Product-title">
                                    <img class="product-image image-two lazyload" data-src="{{ uploaded_asset($product->thumbnail_img) }}" alt="Product-title">
                                </a>
                            </figure>
                            <div class="product-content">
                                <h6 class="product-title"><a href="{{ $product_url }}">{{  $product->getTranslation('name')  }}</a></h6>
                                <span class="product-desc">Product Code: {{$product->code}} </span>
                                <span class="product-price"> {{ home_discounted_base_price($product) }} </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                <span class="text-center d-block fs-16">{{ $products->appends(request()->input())->links() }}</span>
            </div>
        </div>
    </section>
    <!-- End Category Product Area -->

@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }
        function rangefilter(arg){
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>
@endsection
