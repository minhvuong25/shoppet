@extends('layout')
@section('scripts')
    <script src="{{ asset('/frontend/js/delivery.js') }}"></script>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script>
        const urls = {
            fetchDelivery: '{{ route('fetchDelivery') }}',
            caculateFee: '{{route('CaculateFee')}}'
        }
    </script>
    <script>
        paypal.Button.render({
            // Configure environment
            env: 'sandbox',
            client: {
                sandbox: 'AedBF2FuNkcOSVb0JDuWT7I6Wtq70FQmQt2o89_pfV6uWq-QFxNXbfvvCLHTbEYyt_rsUeKiEaumrrZV',
                production: 'demo_production_client_id'
            },
            // Customize button (optional)
            locale: 'en_US',
            style: {
                size: 'small',
                color: 'gold',
                shape: 'pill',
            },

            // Enable Pay Now checkout flow (optional)
            commit: true,

            // Set up a payment
            payment: function(data, actions) {
                return actions.payment.create({
                    transactions: [{
                        amount: {
                            total: '100.00',
                            currency: 'USD'
                        }
                    }]
                });
            },
            // Execute the payment
            onAuthorize: function(data, actions) {
                return actions.payment.execute().then(function() {
                    // Show a confirmation message to the buyer
                    window.alert('Cảm ơn bạn đã mua hàng của chúng tôi !!!');
                });
            }
        }, '#paypal-button');


    </script>
@endsection
@section('content')
    <?php
    $error = Session::get('error');
    if ($error) {
        echo "<script>alert('$error')</script>";
        Session::put('error', null);
    }
    $message = Session::get('message');
    if ($message) {
        echo "<script>alert('$message')</script>";
        Session::put('message', null);
    }
    ?>
    <section class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 mb-4">
                    <!-- Checkout -->
                    <form>
                        <input type="hidden" name="order_fee" class="order_fee" value="{{ Session::get('fee') }}">
                        @if (Session::get('coupon'))
                            @foreach (Session::get('coupon') as $key => $cou)
                                <input type="hidden" name="order_coupon" class="order_coupon"
                                       value="{{ $cou['coupon_code'] }}">
                            @endforeach
                        @else
                            <input type="hidden" name="order_coupon" class="order_coupon" value="no">
                        @endif
                        <div class="card shadow-0 border">
                            <div class="p-4">
                                <h5 class="card-title mb-3">Guest checkout</h5>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <p class="mb-0">Full name</p>
                                        <div class="form-outline">
                                            <input name="name" type="text" id="typeText" placeholder="Type here"
                                                   class="form-control shipping_name" value="{{ Session::get('customer_name') ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <p class="mb-0">Phone</p>
                                        <div class="form-outline">
                                            <input name="phone" type="tel" id="typePhone"
                                                   class="form-control shipping_phone" value="{{ Session::get('customer_phone') ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <p class="mb-0">Email</p>
                                        <div class="form-outline">
                                            <input name="email" type="email" id="typeEmail" value="{{ Session::get('customer_email') ?? '' }}"
                                                   placeholder="Email"
                                                   class="form-control shipping_email">
                                        </div>
                                    </div>
                                        <div class="col-6 mb-3">
                                            <p class="mb-0">Address</p>
                                            <div class="form-outline">
                                                <input name="address" type="text" id="typeText" placeholder="Type here"
                                                       class="form-control shipping_address">
                                            </div>
                                        </div>
                                </div>
                                <hr class="my-4"/>
                                <h5 class="card-title mb-3">{{__("PaymentMethod")}}</h5>
                                <div class="row mb-3">
{{--                                    <div class="col-lg-4 mb-3">--}}
{{--                                        <!-- Default checked radio -->--}}
{{--                                        <div class="form-check h-100 border rounded-3">--}}
{{--                                            <div class="p-3">--}}
{{--                                                <input class="form-check-input payment_select" type="radio" name="payment_select"--}}
{{--                                                       id="payment_select" value="0" checked/>--}}

{{--                                                <label class="form-check-label" style="margin-left: 30px"  for="payment_select">--}}
{{--                                                    {{ __('Online') }}<br/>--}}
{{--                                                    <small class="text-muted">3-4 days via Fedex </small>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-lg-4 mb-3">
                                        <!-- Default radio -->
                                        <div class="form-check h-100 border rounded-3">
                                            <div class="p-3">
                                                <input class="form-check-input payment_select" checked value="1" type="radio" name="payment_select"
                                                       id="payment_select"/>
                                                <label class="form-check-label" style="margin-left: 30px" for="payment_select">
                                                    {{ __('Direct') }}<br/>
                                                    <small class="text-muted">20-30 days via post </small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="mb-3">
                                    <p class="mb-0">Message to seller</p>
                                    <div class="form-outline">
                                        <textarea class="form-control shipping_notes" id="textAreaExample1" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Checkout -->
                </div>
                <div class="col-xl-4 col-lg-4">
                    <div class="ms-lg-12 mt-4 mt-lg-0 p-4 border mb-3">
                        <h6 class="mb-3">Shipping Fee</h6>
                        <div class="">
                            <select id="city" class="form-select form-select-lg mb-3 w-100 city choose" name="city" aria-label=".form-select-lg example">
                                <option value="">--- {{ __('Choose the city') }} ---</option>
                                @foreach ($city as $key => $c_t)
                                    <option value="{{ $c_t->matp }}">{{ $c_t->name_city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="">
                            <select id="province" class="form-select form-select-lg mb-3 w-100 province choose " name="province" aria-label=".form-select-lg example">
                                <option >---{{ __('Choose a district') }} ---</option>
                            </select>
                        </div>
                        <div class="">
                            <select id="wards" class="form-select form-select-lg mb-3 w-100 wards" name="wards" aria-label=".form-select-lg example">
                                <option value="">--- {{ __('Choose a district') }} ---</option>
                            </select>
                        </div>
                        <div class="input-group mt-3 mb-4">
                            <button class="btn btn-light text-primary border caculate_delivery" type="button">Save</button>
                        </div>
                    </div>
                    <div class="ms-lg-12 mt-4 mt-lg-0 p-4 border">
                        <h6 class="text-dark my-4">Items in cart</h6>
                        @if(Session::get('cart'))
                            @php
                                $total = 0;
                            @endphp
                            @foreach(Session::get('cart') as $cart)
                                @php
                                    $subtotal = $cart['product_price'] * $cart['product_qty'];
                                    $total += $subtotal;
                                @endphp
                                <div class="d-flex align-items-center mb-4">
                                    <div class="mr-3 position-relative">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-secondary">1</span>
                                        <img src="{{asset('/uploads/product/'.$cart['product_image'])}}"
                                             style="height: 96px; width: 96px;" class="img-sm rounded border"/>
                                    </div>
                                    <div class="">
                                        <a href="#" class="text-dark">{{$cart['product_name']}}</a>
                                        <div class="price text-muted">
                                            Total: {{number_format($cart['product_qty'] * $cart['product_price'])}} VNĐ
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <hr>
                        <h6 class="mb-3">Summary</h6>
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">Total price:</p>
                            <p class="mb-2">{{ number_format($total) }} VND</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">Discount:</p>
                            @if (Session::get('coupon'))
                                <li class="d-flex align_items-center">
                                    @foreach (Session::get('coupon') as $key => $cou)
                                        @if ($cou['coupon_condition'] == 1)
                                            {{ $cou['coupon_number'] }} %
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
                                            <a href="{{url('del-cou')}}" class="btn btn-susscess pl-3 pr-0">Xóa mã</a>
                                        @elseif ($cou['coupon_condition'] == 2)
                                           {{ number_format($cou['coupon_number']) }} VND
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
                            @else
                                <p class="mb-2 text-danger">0 VNĐ</p>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">Shipping cost:</p>
                            @if (Session::get('fee'))
                                <li>
                                    <span>{{ number_format(Session::get('fee')) }} VND</span>
                                    <a class="cart_quantity_delete" href="{{ url('/del-fee') }}"><i class="fa fa-times"></i></a>
                                </li>
                                @php
                                    $total_after_fee = $total + Session::get('fee');
                                @endphp
                            @else
                                <p class="mb-2 text-danger"> 0 VNĐ</p>
                            @endif
                        </div>
                        <hr/>
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">Total price:</p>
                            <p class="mb-2 fw-bold">
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
                            </p>
                        </div>
                        <div class="input-group mt-3 mb-4">
                            <button class="btn btn-light text-primary border send_order">{{ __('Pay') }}</button>
                        </div>
{{--                        <div class="input-group mt-3 mb-4 send_order">--}}
{{--                            <label>thanh toán bằng ví điện tử</label>--}}
{{--                          --}}
{{--                        </div>--}}


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
