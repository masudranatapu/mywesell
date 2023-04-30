@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:price:currency"
        content="{{ \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@section('content')
    <div class="bg-dark py-10px">
        <div class="text-center text-white">Contactless Delivery:Save 5% On Entire Order With Digital Payment</div>
    </div>
    <!-- End Promo info -->

    <section class="pt-3 pb-5">
        <div class="container">
            <nav class="breadcrumb-nav mb-3">
                <ul class="breadcrumb-menu">
                    <li><a href="{{route('home')}}">Home</a></button></li>
                    <li><a href="#">{{ $detailedProduct->getTranslation('name') }}</a></li>
                </ul>
            </nav>
            <div class="row g-4 pb-4">
                <div class="col-md-6">
                    @php
                        $photos = explode(',', $detailedProduct->photos);
                    @endphp
                    <div class="big-thumb">
                        @foreach ($photos as $key => $photo)
                            <div class="single-item">
                                <div class="zoom">
                                    <div class="big-image-wrapper">
                                        <a href="{{ uploaded_asset($photo) }}" data-fancybox="gallery" class="zoom-trigger"><i class="far fa-expand-alt"></i></a>
                                        <img class="lazyload big-image" data-src="{{ uploaded_asset($photo) }}" alt="Product Image">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                    </div>
                    <div class="nav-thumb pt-10px">
                        @foreach ($photos as $key => $photo)
                            <div class="single-item pe-1">
                                <div class="small-image-wrapper">
                                    <img class="lazyload small-image" data-src="{{ uploaded_asset($photo) }}" alt="Product Image">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6">
                    <form id="option-choice-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $detailedProduct->id }}">
                        <input type="hidden" name="quantity" class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}" max="10" lang="en">
                        @php
                            $qty = 0;
                            foreach ($detailedProduct->stocks as $key => $stock) {
                                $qty += $stock->qty;
                            }
                        @endphp
                        <div class="product-details">
                            <h2 class="single-product-title">{{ $detailedProduct->getTranslation('name') }}</h2>
                            <p class="fs-13 text-muted">Code: {{$detailedProduct->id}}</p>
                            <p class="price mb-4">{{ home_discounted_price($detailedProduct) }}</p>
                            <div class="d-flex flex-column gap-3 mb-4">
                                <button class="add-cart-btn" onclick="addToCart()"><span>Add to Bag</span></button>
                                <button class="buy-btn" onclick="buyNow()"><span>Buy Now</span></button>
                            </div>
                            <div class="row no-gutters mt-4">
                                <div class="col-sm-2">
                                    <div class="opacity-50 my-2">{{ translate('Share') }}:</div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="aiz-share"></div>
                                </div>
                            </div>
                            <!--<ul class="mb-3 d-flex gap-2 share-list">-->
                            <!--    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>-->
                            <!--    <li><a href="#"><i class="fab fa-facebook-messenger"></i></a></li>-->
                            <!--    <li><a href="#"><i class="fab fa-whatsapp"></i></a></li>-->
                            <!--    <li><a href="#"><i class="fab fa-twitter"></i></a></li>-->
                            <!--    <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>-->
                            <!--</ul>-->
                        </div>
                    </form>
                </div>
            </div>
            <div class="product-description">
                <div class="row g-4">
                    <div class="col-12">
                        <h5>Description</h5>
                        <p><?php echo $detailedProduct->getTranslation('description'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="text-center">
            <h6 class="h6 mb-4 pb-4 border-bottom d-inline-block px-4">YOU MAY ALSO LIKE</h6>
        </div>
        <div>
            <div class="carousel product-carousel mb-4" data-items="3" data-xl-items="3" data-lg-items="3" data-md-items="2" data-xs-items="1" data-autoplay="true" data-dots="true" data-center="true" data-arrows="true" data-infinite="true" data-timeout="3500">
                @foreach (filter_products(\App\Models\Product::where('category_id', $detailedProduct->category_id)->where('id', '!=', $detailedProduct->id))->limit(10)->get() as $key => $related_product)
                    <div class="px-md-3 px-sm-2 px-5px">
                    <div class="single-product">
                        <figure class="mb-0 product-image-wrapper">
                            <a href="{{ route('product', $related_product->slug) }}">
                                <img class="product-image image-one lazyload" data-src="{{ uploaded_asset($related_product->thumbnail_img) }}" alt="Product-title">
                                <img class="product-image image-two lazyload" data-src="{{ uploaded_asset($related_product->thumbnail_img) }}" alt="Product-title">
                            </a>
                        </figure>
                        <div class="product-content">
                            <h6 class="product-title"><a href="{{ route('product', $related_product->slug) }}">{{ $related_product->getTranslation('name') }}</a></h6>
                            <span class="product-desc">Product Code: {{$related_product->id}} </span>
                            <span class="product-price"> {{ home_discounted_base_price($related_product) }} </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@section('modal')
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3" name="title"
                                value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="8" name="message" required
                                placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600">{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login') }}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}"
                            method="POST">
                            @csrf
                            <div class="form-group">
                                @if (addon_is_activated('otp_system'))
                                    <input type="text"
                                        class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone') }}"
                                        name="email" id="email">
                                @else
                                    <input type="email"
                                        class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" placeholder="{{ translate('Email') }}"
                                        name="email">
                                @endif
                                @if (addon_is_activated('otp_system'))
                                    <span class="opacity-60">{{ translate('Use country code before number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg"
                                    placeholder="{{ translate('Password') }}">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class=opacity-60>{{ translate('Remember Me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}"
                                        class="text-reset opacity-60 fs-14">{{ translate('Forgot password?') }}</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit" class="btn btn-primary btn-block fw-600">{{ translate('Login') }}</button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">{{ translate('Dont have an account?') }}</p>
                            <a href="{{ route('user.registration') }}">{{ translate('Register Now') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        // Pagination using ajax
        $(window).on('hashchange', function() {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    getQuestions(page);
                }
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.pagination a', function(e) {
                getQuestions($(this).attr('href').split('page=')[1]);
                e.preventDefault();
            });
        });

        function getQuestions(page) {
            $.ajax({
                url: '?page=' + page,
                dataType: 'json',
            }).done(function(data) {
                $('.pagination-area').html(data);
                location.hash = page;
            }).fail(function() {
                alert('Something went worng! Questions could not be loaded.');
            });
        }
        // Pagination end
    </script>
@endsection
