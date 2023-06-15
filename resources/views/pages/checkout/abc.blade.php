

<div class="container">


    <h1 class="text-center">{{ __('Checkout') }}</h1>


    <div class="col-md-12 col-sm-12">

        <!-- BEGIN CHECKOUT PAGE -->

        <div class="panel-group checkout-page accordion scrollable" id="checkout-page">

            <!-- BEGIN CHECKOUT -->

            <div id="checkout" class="panel panel-default">


                <div class="panel-heading">


                    <h2 class="panel-title">
                        <a data-toggle="collapse" data-parent="#checkout-page" href="#checkout-content"
                           class="accordion-toggle collapsed" aria-expanded="false">
                            {{ __('Cart') }}
                        </a>

                    </h2>


                </div>


                <div id="checkout-content" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">


                    <div class="panel-body row">


                        <form action="{{ url('/update-cart') }}" method="POST" style="padding: 0 20px">

                            @csrf <br>

                            <table>


                                <tbody>


                                <tr>


                                    <th class="checkout-image p-10">{{ __('Image') }}</th>


                                    <th class="checkout-description p-10" style="width:40%;">{{ __('Name') }}</th>


                                    <th class="checkout-quantity p-10">{{ __('Quantity') }}</th>


                                    <th class="checkout-price p-10">{{ __('Price') }}</th>


                                    <th class="checkout-total p-10">{{ __('Total') }}</th>


                                    <th></th>


                                </tr>

                                @if (Session::get('cart') == true)
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach (Session::get('cart') as $key => $cart)
                                        @php
                                            $subtotal = $cart['product_price'] * $cart['product_qty'];
                                            $total += $subtotal;
                                        @endphp

                                        <tr>


                                            <td class="checkout-image p-20">
                                                <a href="javascript:;">
                                                    <img src="{{ asset('/uploads/product/' . $cart['product_image']) }}"
                                                         alt="{{ $cart['product_name'] }}" width="50px">
                                                </a>

                                            </td>


                                            <td class="checkout-description p-20">


                                                <h3><a href="javascript:;" style="font-size:14px">{{ $cart['product_name'] }}</a></h3>


                                            </td>

                                            <input type="hidden" value="{{ $cart['product_quantity'] }}">

                                            <td class="checkout-quantity p-20">
                                                <input type="number" class="cart_quantity" min="1"
                                                       oninput="validity.valid||(value='');" type="text"
                                                       name="cart_qty[{{ $cart['session_id'] }}]"
                                                       value="{{ $cart['product_qty'] }}" autocomplete="off" style="border:none"
                                                       max="20">
                                                <input type="hidden" value="" name="rowId_cart"
                                                       class="form-control">

                                            </td>


                                            <td class="checkout-price p-20">
                                                <strong>{{ number_format($cart['product_price']) }} VND</strong>

                                            </td>


                                            <td class="checkout-total p-20"><strong>{{ number_format($subtotal) }}
                                                    VND</strong>
                                            </td>


                                            <td>
                                                <a class="cart_quantity_delete p-20"
                                                   href="{{ url('/delete-sp/' . $cart['session_id']) }}"><i
                                                        class="fa fa-times"></i></a>

                                            </td>


                                        </tr>

                                    @endforeach

                                    <tr>


                                        <td class="p-20"><input style="width:100%" type="submit" name="update-cart" class="btn btn-default btn-sm"
                                                                value="{{ __('Update') }}">
                                        </td>


                                        <td style="list-style: none;padding-left:15px">


                                            <li>{{ __('Price') }} :<span>{{ number_format($total) }} VND</span></li>

                                            @if (Session::get('coupon'))

                                                <li>

                                                    @foreach (Session::get('coupon') as $key => $cou)
                                                        @if ($cou['coupon_condition'] == 1)
                                                            {{ __('Coupon') }} : {{ $cou['coupon_number'] }} %

                                                            <p>
                                                                @php
                                                                    $total_coupon = ($total * $cou['coupon_number']) / 100;
                                                                @endphp

                                                            </p>


                                                            <p>
                                                                @php
                                                                    $total_after_coupon = $total - $total_coupon;
                                                                @endphp

                                                            </p>

                                                            <a href="{{url('del-cou')}}" class="btn btn-susscess">Xóa mã</a>
                                                        @elseif ($cou['coupon_condition'] == 2)
                                                            {{ __('Coupon') }} : {{ number_format($cou['coupon_number']) }}
                                                            VND

                                                            <p>
                                                                @php
                                                                    $total_coupon = $total - $cou['coupon_number'];
                                                                @endphp

                                                            </p>


                                                            <p>
                                                                @php
                                                                    $total_after_coupon = $total_coupon;
                                                                @endphp

                                                            </p>

                                                            <a href="{{ url('del-cou') }}"
                                                               class="btn btn-susscess">{{ __('DelCoupon') }}</a>
                                            @endif
                                            @endforeach
                                            @endif

                                            <li>Thuế <span></span></li>

                                            @if (Session::get('fee'))

                                                <li>
                                                    <a class="cart_quantity_delete"
                                                       href="{{ url('/del-fee') }}"><i
                                                            class="fa fa-times"></i></a>
                                                    {{ __('ShippingCharges') }} <span>{{ number_format(Session::get('fee')) }}
                                                                    VND</span>

                                                </li>

                                                @php
                                                    $total_after_fee = $total + Session::get('fee');
                                                @endphp
                                            @endif

                                            <li>{{ __('Total') }} :
                                                @php
                                                    if (Session::get('fee') && !Session::get('coupon')) {
                                                        $total_after = $total_after_fee;
                                                        echo number_format($total_after);
                                                    } elseif (!Session::get('fee') && Session::get('coupon')) {
                                                        $total_after = $total_after_coupon;
                                                        echo number_format($total_after);
                                                    } elseif (Session::get('fee') && Session::get('coupon')) {
                                                        $total_after = $total_after_coupon;
                                                        $total_after = $total_after + Session::get('fee');
                                                        echo number_format($total_after);
                                                    } elseif (!Session::get('fee') && !Session::get('coupon')) {
                                                        $total_after = $total;
                                                        echo number_format($total_after);
                                                    }
                                                @endphp
                                                VND

                                            </li>


                                        </td>


                                        <td colspan="5"><a href="{{ url('/delete-all-cart/') }}"
                                                           class="btn btn-default check_out pull-right" style="margin-right: 15px">{{ __('Cancel') }}</a>
                                        </td>


                                    </tr>

                                @else

                                    <tr>


                                        <td colspan="6">


                                            <center style="margin-top: 15px">
                                                @php
                                                    echo 'Chưa có sản phẩm trong giỏ';
                                                @endphp

                                            </center>


                                        </td>


                                    </tr>


                                </tbody>

                                @endif
                                </tbody>

                            </table>


                        </form>


                    </div>


                    <div class="clearfix"></div>


                </div>


            </div>


        </div>


    </div>

    <!-- END CHECKOUT -->
    @if (Session::get('cart'))
        @if (!Session::get('fee'))
            <!-- BEGIN PAYMENT ADDRESS -->

            <div class="col-md-12 col-sm-12">


                <div id="payment-address" class="panel panel-default panel-group checkout-page accordion scrollable">


                    <div class="panel-heading">


                        <h2 class="panel-title">
                            <a data-toggle="collapse" data-parent="#checkout-page" href="#payment-address-content"
                               class="accordion-toggle collapsed" aria-expanded="false">
                                {{ __('ShippingCharges') }}
                            </a>

                        </h2>


                    </div>


                    <div id="payment-address-content" class="panel-collapse collapse" aria-expanded="false">


                        <div class="panel-body row">


                            <div class="col-md-12 col-sm-12">


                                <form role="form">

                                    @csrf

                                    <div class="form-group">

                                        <label for="exampleInputPassword1">{{ __('City') }}</label>
                                        <select name="city" id="city"
                                                class="form-control input-lg m-bot15 city choose ">

                                            <option value="">--- {{ __('City') }} ---</option>

                                            @foreach ($city as $key => $c_t)

                                                <option value="{{ $c_t->matp }}">{{ $c_t->name_city }}</option>

                                            @endforeach
                                        </select>

                                    </div>


                                    <div class="form-group">

                                        <label for="exampleInputPassword1"> {{ __('District') }} </label>
                                        <select name="province" id="province"
                                                class="form-control input-lg m-bot15 province choose ">

                                            <option value="">--- {{ __('District') }} ---</option>

                                        </select>

                                    </div>


                                    <div class="form-group">

                                        <label for="exampleInputPassword1"> {{ __('Ward') }} </label>

                                        <select name="wards" id="wards" class="form-control input-lg m-bot15 wards">


                                            <option value="">--- {{ __('Ward') }} ---</option>


                                        </select>


                                    </div>

                                    <input type="button" value="{{ __('ShippingCharges') }}" name="caculate_order"
                                           class="btn btn-primary  pull-right collapsed caculate_delivery">

                                </form>


                            </div>


                        </div>


                    </div>


                </div>


            </div>

        @endif
        <!-- END PAYMENT ADDRESS -->
        <!-- BEGIN SHIPPING ADDRESS -->

        <div class="col-md-12 col-sm-12">


            <div id="shipping-address" class="panel panel-default panel-group checkout-page accordion scrollable">


                <div class="panel-heading">


                    <h2 class="panel-title">
                        <a data-toggle="collapse" data-parent="#checkout-page" href="#shipping-address-content"
                           class="accordion-toggle collapsed" aria-expanded="false">
                            {{ __('Personal') }}
                        </a>

                    </h2>


                </div>


                <div id="shipping-address-content" class="panel-collapse collapse" aria-expanded="false">


                    <div class="panel-body row">


                        <form method="POST">

                            @csrf

                            <div class="col-md-12 col-sm-12">


                                <div class="form-group">
                                    <label for="firstname-dd">Email<span class="require">*</span></label>
                                    <input type="email" value="{{ Session::get('customer_email') }}" name="shipping_email"
                                           class="shipping_email form-control">

                                </div>


                                <div class="form-group">
                                    <label for="lastname-dd">{{ __('Name') }}<span
                                            class="require">*</span></label>
                                    <input type="text" value="{{ Session::get('customer_name') }}" name="shipping_name"
                                           class="form-control shipping_name">

                                </div>


                                <div class="form-group">
                                    <label for="email-dd">{{ __('Address') }}<span class="require">*</span></label>
                                    <input type="text" placeholder="{{ __('Address') }}" name="shipping_address"
                                           class="shipping_address form-control">

                                </div>


                                <div class="form-group">
                                    <label for="telephone-dd">{{ __('Phone') }} <span class="require">*</span></label>
                                    <input type="text" value="{{ Session::get('customer_phone') }}" name="shipping_phone"
                                           class="shipping_phone form-control">

                                </div>


                                <div class="form-group">
                                    <label for="fax-dd">{{ __('Notes') }}</label>
                                    <input type="text" placeholder="{{ __('Notes') }}" name="shipping_notes"
                                           class="form-control shipping_notes">

                                </div>

                                @if (Session::get('fee'))
                                    <input type="hidden" name="order_fee" class="order_fee"
                                           value="{{ Session::get('fee') }}">
                                @else
                                    <input type="hidden" name="order_fee" class="order_fee" value="30000">
                                @endif
                                @if (Session::get('coupon'))
                                    @foreach (Session::get('coupon') as $key => $cou)
                                        <input type="hidden" name="order_coupon" class="order_coupon"
                                               value="{{ $cou['coupon_code'] }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="order_coupon" class="order_coupon" value="no">
                                @endif

                                <div class="">


                                    <div class="form-group">

                                        <label for="exampleInputPassword1">{{ __('PaymentMethod') }}</label>
                                        <select name="payment_select"
                                                class="form-control input-lg m-bot15 payment_select">

                                            <option value="0">php</option>


                                            <option value="1">{{ __('Direct') }}</option>


                                            <option value="2">{{ __('PayPal') }}</option>

                                        </select>

                                    </div>


                                </div>


                            </div>


                            <div class="col-md-12">
                                <button class="btn btn-primary pull-right collapsed send_order" type="button"
                                        name="send_order">{{ __('Pay') }}</button>

                            </div>


                        </form>


                    </div>


                </div>


            </div>


        </div>

        <!-- END SHIPPING ADDRESS -->
    @endif

</div>
