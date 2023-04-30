@extends('frontend.layouts.app')

@section('content')
    @if (get_setting('home_slider_images') != null)
        <section class="slider-section pb-4">
            <div class="slider-wrapper">
                <div class="carousel main-slider" data-items="1" data-dots="true" data-loop="true" data-infinite="true" data-autoplay="true" data-timeout="5000">
                    @php $slider_images = App\Models\Slider::latest()->get();  @endphp
                    @foreach ($slider_images as $key => $slider_image)
                        <div class="single-slide">
                            <div class="slide-image-wrapper">
                                <img class="lazyload slide-image" data-src="{{ uploaded_asset($slider_image->image) }}" alt="Slider">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    
    <section class="category-section">
        <div class="row g-1">
            @if (count($featured_categories) > 0)
                @foreach ($featured_categories as $key => $category)
                    <div class="col-sm-6">
                        <div class="single-category style-one">
                            <div class="category-image-wrapper">
                                <a href="{{ route('products.category', $category->slug) }}">
                                    <img class="category-image lazyload" data-src="{{ uploaded_asset($category->banner) }}" alt="Category Image">
                                </a>
                            </div>
                            <div class="category-link-wrapper">
                                <a class="category-link" href="{{ route('products.category', $category->slug) }}"><span>{{ $category->getTranslation('name') }}</span><span class="icon"><i class="fas fa-chevron-right"></i><i class="fas fa-chevron-right"></i></span></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="carousel category-carousel mb-4" data-items="4" data-xl-items="4" data-lg-items="4" data-md-items="2" data-xs-items="1" data-autoplay="true" data-dots="true" data-arrows="true"
            data-infinite="true" data-timeout="3500">
            
            @if (count($featured_categories) > 0)
                @foreach ($featured_categories as $key => $category)
                    @php
                        $cat_products = App\Models\Product::where('category_id', $category->id)->get();
                    @endphp
                    @if($cat_products->count() > 0)
                        @foreach($cat_products as $key => $product)
                            @php
                                $product_url = route('product', $product->slug);
                                if($product->auction_product == 1) {
                                    $product_url = route('auction-product', $product->slug);
                                }
                            @endphp
                            <div class="single-category-slide">
                                <div class="single-category style-two">
                                    <div class="category-image-wrapper">
                                        <a href="{{$product_url}}">
                                            <img class="category-image lazyload" data-src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{  $product->getTranslation('name')  }}">
                                        </a>
                                    </div>
                                    <div class="category-link-wrapper">
                                        <a class="category-link" href="{{$product_url}}"><span>{{  $product->getTranslation('name')  }}</span><span class="icon"><i class="fas fa-chevron-right"></i><i class="fas fa-chevron-right"></i></span></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </div>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $.post('{{ route('home.section.featured') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_featured').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.best_selling') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_best_selling').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.auction_products') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#auction_products').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.home_categories') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.best_sellers') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_best_sellers').html(data);
                AIZ.plugins.slickCarousel();
            });
        });
    </script>
@endsection
