<header class="header shop" style="background: #ffffff; border-bottom: 2px solid #ff6b00;">
    <!-- Top Bar -->
    <div class="topbar" style="background: #000000; padding: 8px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="top-left">
                        <ul class="list-main" style="margin-bottom: 0;">
                            @php $settings = DB::table('settings')->get(); @endphp
                            <li style="color: #ffffff;"><i class="ti-headphone-alt" style="color: #ff6b00;"></i>
                                @foreach($settings as $data) {{$data->phone}} @endforeach</li>
                            <li style="color: #ffffff;"><i class="ti-email" style="color: #ff6b00;"></i>
                                @foreach($settings as $data) {{$data->email}} @endforeach</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="right-content">
                        <ul class="list-main" style="margin-bottom: 0;">
                            <li><a href="{{route('order.track')}}" style="color: #ffffff;"><i class="ti-location-pin"
                                        style="color: #ff6b00;"></i> Track Order</a></li>
                            @auth
                                @if(Auth::user()->role == 'admin')
                                    <li><a href="{{route('admin')}}" target="_blank" style="color: #ffffff;"><i class="ti-user"
                                                style="color: #ff6b00;"></i> Dashboard</a></li>
                                @else
                                    <li><a href="{{route('user')}}" target="_blank" style="color: #ffffff;"><i class="ti-user"
                                                style="color: #ff6b00;"></i> Dashboard</a></li>
                                @endif
                                <li><a href="{{route('user.logout')}}" style="color: #ffffff;"><i class="ti-power-off"
                                            style="color: #ff6b00;"></i> Logout</a></li>
                            @else
                                <li style="color: #ffffff;">
                                    <a href="{{route('login.form')}}" style="color: #ffffff;">Login</a> /
                                    <a href="{{route('register.form')}}" style="color: #ffffff;">Register</a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Section -->
    <div class="middle-inner" style="padding: 20px 0;">
        <div class="container">
            <div class="row d-flex align-items-center">
                <!-- Logo -->
                <div class="col-lg-2 col-md-2 col-12 d-flex justify-content-center align-items-center">
                    <div class="logo">
                        <a href="{{route('home')}}">
                            @foreach($settings as $data)
                                <img src="{{$data->logo}}" alt="logo"
                                    style="width: 130px; height: 130px; border: 2px solid #ff6b00; padding: 5px;">
                            @endforeach
                        </a>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="search-bar-top">
                        <div class="search-bar"
                            style="background: #ffffff; border: 2px solid #000000; border-radius: 25px;">
                            <select
                                style="border: none; border-right: 2px solid #000000; border-radius: 25px 0 0 25px; padding: 0 15px;">
                                <option>All Category</option>
                                @foreach(Helper::getAllCategory() as $cat)
                                    <option>{{$cat->title}}</option>
                                @endforeach
                            </select>
                            <form method="POST" action="{{route('product.search')}}">
                                @csrf
                                <input name="search" placeholder="Search Products Here....." type="search"
                                    style="border: none; background: transparent; box-shadow: none;">
                                <button class="btnn" type="submit"
                                    style="background: #ff6b00; border-radius: 0 25px 25px 0; padding: 0 20px;">
                                    <i class="ti-search" style="color: #ffffff;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cart/Wishlist -->
                <div class="col-lg-2 col-md-3 col-12">
                    <div class="right-bar">
                        <div class="sinlge-bar shopping" style="margin-left: 15px;">
                            <a href="{{route('wishlist')}}" class="single-icon" style="color: #000000;">
                                <i class="fa fa-heart-o"></i>
                                <span class="total-count"
                                    style="background: #ff6b00; color: #ffffff;">{{Helper::wishlistCount()}}</span>
                            </a>
                        </div>
                        <div class="sinlge-bar shopping" style="margin-left: 15px;">
                            <a href="{{route('cart')}}" class="single-icon" style="color: #000000;">
                                <i class="ti-bag"></i>
                                <span class="total-count"
                                    style="background: #ff6b00; color: #ffffff;">{{Helper::cartCount()}}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="header-inner" style="background: #000000;">
        <div class="container">
            <div class="cat-nav-head">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div class="menu-area">
                            <nav class="navbar navbar-expand-lg">
                                <div class="navbar-collapse">
                                    <div class="nav-inner">
                                        <ul class="nav main-menu menu navbar-nav" style="gap: 2rem; font-weight: 600;">
                                            <li class="{{Request::path() == 'home' ? 'active' : ''}}">
                                                <a href="{{route('home')}}" style="color: #ffffff;">Home</a>
                                            </li>
                                            <li class="{{Request::path() == 'about-us' ? 'active' : ''}}">
                                                <a href="{{route('about-us')}}" style="color: #ffffff;">About Us</a>
                                            </li>
                                            <li
                                                class="@if(Request::path() == 'product-grids' || Request::path() == 'product-lists') active @endif">
                                                <a href="{{route('product-grids')}}" style="color: #ffffff;">
                                                    Products <span class="new" style="background: #ff6b00;">New</span>
                                                </a>
                                            </li>
                                            {{Helper::getHeaderCategory()}}
                                            <li class="{{Request::path() == 'blog' ? 'active' : ''}}">
                                                <a href="{{route('blog')}}" style="color: #ffffff;">Blog</a>
                                            </li>
                                            <li class="{{Request::path() == 'contact' ? 'active' : ''}}">
                                                <a href="{{route('contact')}}" style="color: #ffffff;">Contact Us</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    /* Modified hover effects */
    .list-main li a:hover,
    .main-menu li a:hover {
        color: #ffffff !important;
        opacity: 0.9;
        transition: all 0.3s ease;
    }

    .active a {
        border-bottom: 2px solid #ff6b00;
    }

    .search-bar input:focus {
        outline: none;
        box-shadow: 0 0 8px rgba(255, 107, 0, 0.3);
    }

    .shopping:hover .shopping-item {
        display: block;
        border: 2px solid #ff6b00;
    }
</style>