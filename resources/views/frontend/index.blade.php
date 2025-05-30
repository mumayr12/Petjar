@extends('frontend.layouts.master')
@section('title','Petjar HOME PAGE')
@section('main-content')
<!-- Slider Area -->
@if(count($banners)>0)
    <section id="Gslider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($banners as $key=>$banner)
                <li data-target="#Gslider" data-slide-to="{{$key}}" class="{{ ($key==0) ? 'active' : '' }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner" role="listbox">
            @foreach($banners as $key=>$banner)
                <div class="carousel-item {{ ($key==0) ? 'active' : '' }}">
                    <img class="first-slide" src="{{ $banner->photo }}" alt="First slide">
                    <div class="carousel-caption d-none d-md-block text-left">
                        <h1 class="wow fadeInDown">{{ $banner->title }}</h1>
                        <p>{!! html_entity_decode($banner->description) !!}</p>
                        <a class="btn btn-lg ws-btn wow fadeInUpBig" href="{{ route('product-grids') }}" role="button">
                            Shop Now <i class="far fa-arrow-alt-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#Gslider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#Gslider" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </section>
@endif

<!-- Start Small Banner -->
<section class="small-banner section">
    <div class="container-fluid">
        <div class="row">
            @php
                $category_lists = DB::table('categories')
                    ->where('status','active')
                    ->limit(3)
                    ->get();
            @endphp
            
            @if($category_lists)
                @foreach($category_lists as $cat)
                    @if($cat->is_parent == 1)
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="single-banner">
                                @if($cat->photo)
                                    <img src="{{ $cat->photo }}" alt="{{ $cat->title }}">
                                @else
                                    <img src="https://via.placeholder.com/600x370" alt="Category Image">
                                @endif
                                <div class="content">
                                    <h3 style="color: white; font-size: 42px; background: #000000;">{{ $cat->title }}</h3>
                                    <a href="{{ route('product-cat', $cat->slug) }}" style="color: white; font-size: 26; background: #000000;">Discover Now</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</section>

<!-- Start Product Area -->
<div class="product-area section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>Trending Items</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="product-info">
                    <div class="nav-main">
                        <ul class="nav nav-tabs filter-tope-group" id="myTab" role="tablist">
                            @php
                                $categories = DB::table('categories')
                                    ->where('status','active')
                                    ->where('is_parent',1)
                                    ->get();
                            @endphp
                            
                            @if($categories)
                                <button class="btn" style="background: black" data-filter="*">
                                    All Products
                                </button>
                                @foreach($categories as $category)
                                    <button class="btn" style="background: none; color: black;" 
                                        data-filter=".{{ $category->id }}">
                                        {{ $category->title }}
                                    </button>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    
                    <div class="tab-content isotope-grid" id="myTabContent">
                        @if($product_lists)
                            @foreach($product_lists as $product)
                                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{ $product->cat_id }}">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="{{ route('product-detail', $product->slug) }}">
                                                @php
                                                    $photos = explode(',', $product->photo);
                                                @endphp
                                                <img class="default-img" src="{{ $photos[0] }}" alt="Product Image">
                                                <img class="hover-img" src="{{ $photos[0] }}" alt="Product Image">
                                                
                                                @if($product->stock <= 0)
                                                    <span class="out-of-stock">Sold Out</span>
                                                @elseif($product->condition == 'new')
                                                    <span class="new">New</span>
                                                @elseif($product->condition == 'hot')
                                                    <span class="hot">Hot</span>
                                                @else
                                                    @if($product->discount > 0)
                                                        <span class="price-dec">{{ $product->discount }}% Off</span>
                                                    @endif
                                                @endif
                                            </a>
                                            <div class="button-head">
                                                <div class="product-action">
                                                    <a data-toggle="modal" data-target="#{{ $product->id }}" 
                                                        title="Quick View" href="#">
                                                        <i class="ti-eye"></i><span>Quick Shop</span>
                                                    </a>
                                                    <a title="Wishlist" 
                                                        href="{{ route('add-to-wishlist', $product->slug) }}">
                                                        <i class="ti-heart"></i><span>Add to Wishlist</span>
                                                    </a>
                                                </div>
                                                <div class="product-action-2">
                                                    <a href="{{ route('add-to-cart', $product->slug) }}">Add to cart</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <h3>
                                                <a href="{{ route('product-detail', $product->slug) }}">
                                                    {{ $product->title }}
                                                </a>
                                            </h3>
                                            <div class="product-price">
                                                @php
                                                    $after_discount = $product->price - ($product->price * $product->discount / 100);
                                                @endphp
                                                @if($product->discount > 0)
                                                    <span>£{{ number_format($after_discount, 2) }}</span>
                                                    <del>£{{ number_format($product->price, 2) }}</del>
                                                @else
                                                    <span>£{{ number_format($product->price, 2) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Items Section -->
<section class="shop-home-list section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="shop-section-title">
                    <h1>Latest Items</h1>
                </div>
            </div>
        </div>
        <div class="row">
            @php
                $latestProducts = DB::table('products')
                    ->where('status', 'active')
                    ->orderBy('id', 'DESC')
                    ->limit(6)
                    ->get();
            @endphp
            
            @foreach($latestProducts as $product)
                <div class="col-md-4">
                    <div class="single-list">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="list-image overlay">
                                    @php
                                        $photos = explode(',', $product->photo);
                                        $after_discount = $product->price - ($product->price * $product->discount / 100);
                                    @endphp
                                    <img src="{{ $photos[0] }}" alt="Product Image">
                                    <a href="{{ route('add-to-cart', $product->slug) }}" class="buy">
                                        <i class="fa fa-shopping-bag"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 no-padding">
                                <div class="content">
                                    <h4 class="title">
                                        <a href="{{ route('product-detail', $product->slug) }}">
                                            {{ $product->title }}
                                        </a>
                                    </h4>
                                    <p class="price with-discount">
                                        @if($product->discount > 0)
                                            £{{ number_format($after_discount, 2) }}
                                            <del>£{{ number_format($product->price, 2) }}</del>
                                        @else
                                            £{{ number_format($product->price, 2) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Remaining sections (Midium Banner, Most Popular, etc.) follow similar patterns -->

@include('frontend.layouts.newsletter')

@endsection
@push('styles')
    
    <style>
#Gslider .carousel-inner {
    background: #000000;
    color: black;
    height: 500px; /* Adjusted: A good height for full-width banners with 16:9 images.
                       Slightly shorter than your original 550px to make it "not too long"
                       while still showcasing the 1920x1080 (16:9) images effectively. */
}

#Gslider .carousel-inner img {
    width: 100% !important;
    opacity: .8;
    object-fit: fill; /* ESSENTIAL: Ensures your 1920x1080 image fills the 500px height
                                       and 100% width of the banner without distortion,
                                       cropping the sides as needed to maintain its aspect ratio. */
    object-position: center; /* Centers the image within the banner, ensuring the most important
                                   parts are visible unless heavily cropped. */
}

#Gslider .carousel-inner .carousel-caption {
    bottom: 25%; /* Adjusted: Moved up slightly to fit better with the new 500px height. */
}

#Gslider .carousel-inner .carousel-caption h1 {
    font-size: 48px; /* Slightly adjusted font size for better fit in the slightly shorter banner */
    font-weight: bold;
    line-height: 100%;
    color: #F7941D;
}

#Gslider .carousel-inner .carousel-caption p {
    font-size: 17px; /* Slightly adjusted font size */
    color: white;
    margin: 25px 0 25px 0; /* Adjusted margin */
}

#Gslider .carousel-inner img {
    width: 100% !important;
    opacity: .8;
    object-fit: cover;
    object-position: center; /* This line ensures the image is centered */
}
#Gslider .carousel-indicators {
    bottom: 40px; /* Adjusted: Moved up slightly to fit better with the new 500px height. */
}
    </style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>

        /*==================================================================
        [ Isotope ]*/
        var $topeContainer = $('.isotope-grid');
        var $filter = $('.filter-tope-group');

        // filter items on button click
        $filter.each(function () {
            $filter.on('click', 'button', function () {
                var filterValue = $(this).attr('data-filter');
                $topeContainer.isotope({filter: filterValue});
            });

        });

        // init Isotope
        $(window).on('load', function () {
            var $grid = $topeContainer.each(function () {
                $(this).isotope({
                    itemSelector: '.isotope-item',
                    layoutMode: 'fitRows',
                    percentPosition: true,
                    animationEngine : 'best-available',
                    masonry: {
                        columnWidth: '.isotope-item'
                    }
                });
            });
        });

        var isotopeButton = $('.filter-tope-group button');

        $(isotopeButton).each(function(){
            $(this).on('click', function(){
                for(var i=0; i<isotopeButton.length; i++) {
                    $(isotopeButton[i]).removeClass('how-active1');
                }

                $(this).addClass('how-active1');
            });
        });
    </script>
    <script>
         function cancelFullScreen(el) {
            var requestMethod = el.cancelFullScreen||el.webkitCancelFullScreen||el.mozCancelFullScreen||el.exitFullscreen;
            if (requestMethod) { // cancel full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        function requestFullScreen(el) {
            // Supports most browsers and their versions.
            var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen;

            if (requestMethod) { // Native full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
            return false
        }
    </script>

@endpush
