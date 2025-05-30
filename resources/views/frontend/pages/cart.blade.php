@extends('frontend.layouts.master')
@section('title','Cart Page')
@section('main-content')
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="">Cart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="shopping-cart section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <table class="table shopping-summery">
                        <thead>
                            <tr class="main-hading">
                                <th>PRODUCT</th>
                                <th>NAME</th>
                                <th class="text-center">UNIT PRICE</th>
                                <th class="text-center">QUANTITY</th>
                                <th class="text-center">TOTAL</th>
                                <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody id="cart_item_list">
                            <form action="{{route('cart.update')}}" method="POST">
                                @csrf
                                @if(Helper::getAllProductFromCart())
                                    @foreach(Helper::getAllProductFromCart() as $key=>$cart)
                                        <tr>
                                            @php
                                            $photo=explode(',',$cart->product['photo']);
                                            // Get the actual stock for this product
                                            $product_stock = $cart->product->stock;
                                            @endphp
                                            <td class="image" data-title="No"><img src="{{$photo[0]}}" alt="{{$photo[0]}}"></td>
                                            <td class="product-des" data-title="Description">
                                                <p class="product-name"><a href="{{route('product-detail',$cart->product['slug'])}}" target="_blank">{{$cart->product['title']}}</a></p>
                                                <p class="product-des">{!!($cart['summary']) !!}</p>
                                            </td>
                                            <td class="price" data-title="Price"><span>£{{number_format($cart['price'],2)}}</span></td>
                                            <td class="qty" data-title="Qty"><div class="input-group">
                                                    <div class="button minus">
                                                        <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[{{$key}}]">
                                                            <i class="ti-minus"></i>
                                                        </button>
                                                    </div>
                                                    {{--
                                                        HERE IS THE KEY CHANGE:
                                                        Set data-max to the product's actual stock.
                                                    --}}
                                                    <input type="text" name="quant[{{$key}}]" class="input-number" data-min="1" data-max="{{ $product_stock }}" value="{{$cart->quantity}}">
                                                    <input type="hidden" name="qty_id[]" value="{{$cart->id}}">
                                                    <div class="button plus">
                                                        <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[{{$key}}]">
                                                            <i class="ti-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                </td>
                                            <td class="total-amount cart_single_price" data-title="Total"><span class="money">£{{$cart['amount']}}</span></td>

                                            <td class="action" data-title="Remove"><a href="{{route('cart-delete',$cart->id)}}"><i class="ti-trash remove-icon"></i></a></td>
                                        </tr>
                                    @endforeach
                                    <track>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="float-right">
                                            <button class="btn float-right" type="submit">Update</button>
                                        </td>
                                    </track>
                                @else
                                    <tr>
                                        <td class="text-center" colspan="6">
                                            There are no any carts available. <a href="{{route('product-grids')}}" style="color:blue;">Continue shopping</a>
                                        </td>
                                    </tr>
                                @endif

                            </form>
                        </tbody>
                    </table>
                    </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="total-amount">
                        <div class="row">
                            <div class="col-lg-8 col-md-5 col-12">
                                <div class="left">
                                    <div class="coupon">
                                        <form action="{{route('coupon-store')}}" method="POST">
                                            @csrf
                                            <input name="code" placeholder="Enter Your Coupon">
                                            <button class="btn">Apply</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7 col-12">
                                <div class="right">
                                    <ul>
                                        <li class="order_subtotal" data-price="{{Helper::totalCartPrice()}}">Cart Subtotal<span>£{{number_format(Helper::totalCartPrice(),2)}}</span></li>

                                        @if(session()->has('coupon'))
                                        <li class="coupon_price" data-price="{{Session::get('coupon')['value']}}">You Save<span>£{{number_format(Session::get('coupon')['value'],2)}}</span></li>
                                        @endif
                                        @php
                                            $total_amount=Helper::totalCartPrice();
                                            if(session()->has('coupon')){
                                                $total_amount=$total_amount-Session::get('coupon')['value'];
                                            }
                                        @endphp
                                        @if(session()->has('coupon'))
                                            <li class="last" id="order_total_price">You Pay<span>£{{number_format($total_amount,2)}}</span></li>
                                        @else
                                            <li class="last" id="order_total_price">You Pay<span>£{{number_format($total_amount,2)}}</span></li>
                                        @endif
                                    </ul>
                                    <div class="button5">
                                        <a href="{{route('checkout')}}" class="btn">Checkout</a>
                                        <a href="{{route('product-grids')}}" class="btn">Continue shopping</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
    <section class="shop-services section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service">
                        <i class="ti-rocket"></i>
                        <h4>Free shiping</h4>
                        <p>Orders over £100</p>
                    </div>
                    </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service">
                        <i class="ti-reload"></i>
                        <h4>Free Return</h4>
                        <p>Within 30 days returns</p>
                    </div>
                    </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service">
                        <i class="ti-lock"></i>
                        <h4>Sucure Payment</h4>
                        <p>100% secure payment</p>
                    </div>
                    </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service">
                        <i class="ti-tag"></i>
                        <h4>Best Peice</h4>
                        <p>Guaranteed price</p>
                    </div>
                    </div>
            </div>
        </div>
    </section>
    @include('frontend.layouts.newsletter')
    @endsection
@push('styles')
    <style>
        li.shipping{
            display: inline-flex;
            width: 100%;
            font-size: 14px;
        }
        li.shipping .input-group-icon {
            width: 100%;
            margin-left: 10px;
        }
        .input-group-icon .icon {
            position: absolute;
            left: 20px;
            top: 0;
            line-height: 40px;
            z-index: 3;
        }
        .form-select {
            height: 30px;
            width: 100%;
        }
        .form-select .nice-select {
            border: none;
            border-radius: 0px;
            height: 40px;
            background: #f6f6f6 !important;
            padding-left: 45px;
            padding-right: 40px;
            width: 100%;
        }
        .list li{
            margin-bottom:0 !important;
        }
        .list li:hover{
            background:#F7941D !important;
            color:white !important;
        }
        .form-select .nice-select::after {
            top: 14px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() { $("select.select2").select2(); });
        $('select.nice-select').niceSelect();
    </script>
    <script>
        $(document).ready(function(){
            $('.shipping select[name=shipping]').change(function(){
                let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
                let subtotal = parseFloat( $('.order_subtotal').data('price') );
                let coupon = parseFloat( $('.coupon_price').data('price') ) || 0;
                $('#order_total_price span').text('$'+(subtotal + cost-coupon).toFixed(2));
            });

            // Adjust the quantity input's data-max and the +/- buttons behavior
            $('.btn-number').click(function(e){
                e.preventDefault();

                fieldName = $(this).attr('data-field');
                type      = $(this).attr('data-type');
                var input = $("input[name='"+fieldName+"']");
                var currentVal = parseInt(input.val());

                // Get max value from data-max attribute
                var maxVal = parseInt(input.attr('data-max'));

                if (!isNaN(currentVal)) {
                    if(type == 'minus') {
                        if(currentVal > input.attr('data-min')) {
                            input.val(currentVal - 1).change();
                        }
                        if(parseInt(input.val()) == input.attr('data-min')) {
                            $(this).attr('disabled', true);
                        }
                    } else if(type == 'plus') {
                        // Check against the dynamically set maxVal
                        if(currentVal < maxVal) { // Use maxVal from data-max
                            input.val(currentVal + 1).change();
                        }
                        if(parseInt(input.val()) == maxVal) { // Disable if at max
                            $(this).attr('disabled', true);
                        }
                    }
                } else {
                    input.val(input.attr('data-min'));
                }
            });

            // Re-enable buttons on input change
            $('.input-number').change(function() {
                var minValue =  parseInt($(this).attr('data-min'));
                var maxValue =  parseInt($(this).attr('data-max')); // Get max value dynamically
                var valueCurrent = parseInt($(this).val());
                
                var name = $(this).attr('name');
                if(valueCurrent >= maxValue) {
                    $(this).val(maxValue); // Cap the input if typed value exceeds max
                    $('.btn-number[data-type="plus"][data-field="'+name+'"]').attr('disabled', true);
                } else {
                    $('.btn-number[data-type="plus"][data-field="'+name+'"]').attr('disabled', false);
                }
                
                if(valueCurrent <= minValue) {
                    $(this).val(minValue); // Cap the input if typed value is less than min
                    $('.btn-number[data-type="minus"][data-field="'+name+'"]').attr('disabled', true);
                } else {
                    $('.btn-number[data-type="minus"][data-field="'+name+'"]').attr('disabled', false);
                }
            });

            // Initial button state check on page load
            $('.input-number').each(function() {
                var minValue = parseInt($(this).attr('data-min'));
                var maxValue = parseInt($(this).attr('data-max'));
                var valueCurrent = parseInt($(this).val());
                var name = $(this).attr('name');

                if (valueCurrent >= maxValue) {
                    $('.btn-number[data-type="plus"][data-field="'+name+'"]').attr('disabled', true);
                }
                if (valueCurrent <= minValue) {
                    $('.btn-number[data-type="minus"][data-field="'+name+'"]').attr('disabled', true);
                }
            });

        });

    </script>

@endpush