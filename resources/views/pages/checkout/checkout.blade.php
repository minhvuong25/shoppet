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
        $(document).ready(function () {
            if ($(".cart_quantity_delete").length > 0) {
                $(".shipping_fee").hide();
                $(".guest_checkout").show();
                $(".pay").show();
            } else {
                $(".guest_checkout").hide();
                $(".shipping_fee").show();
                $(".pay").hide();
            }
        });
    </script>
@endsection
@section('content')
    <!-- CSS -->
    <link rel="stylesheet" href="{{asset('/cdn/bootstrap_530.css')}}">
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
                        @csrf
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
                            <div class="p-4 guest_checkout">
                                <h5 class="card-title mb-3">{{__("Guest checkout")}}</h5>
                                <h5 class="card-title mb-3"
                                    style="color: #FFD700">{{__("Please fill in recipient information before payment")}}</h5>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <p class="mb-0">{{__("Full name")}}</p>
                                        <div class="form-outline">
                                            <input name="name" type="text" id="typeText"
                                                   class="form-control shipping_name"
                                                   value="{{ Session::get('customer_name') ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <p class="mb-0">{{__("Phone")}}</p>
                                        <div class="form-outline">
                                            <input name="phone" type="tel" id="typePhone"
                                                   class="form-control shipping_phone"
                                                   value="{{ Session::get('customer_phone') ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <p class="mb-0">{{__("Email")}}</p>
                                        <div class="form-outline">
                                            <input name="email" type="email" id="typeEmail"
                                                   value="{{ Session::get('customer_email') ?? '' }}"
                                                   class="form-control shipping_email">
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <p class="mb-0">{{__("Address")}}</p>
                                        <div class="form-outline">
                                            <input name="address" type="text" id="typeText"
                                                   class="form-control shipping_address">
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4"/>
                                <h5 class="card-title mb-3" hidden="true">{{__("PaymentMethod")}}</h5>
                                <div class="row mb-3" hidden="true">
                                    <div class="col-lg-4 mb-3">
                                        <!-- Default checked radio -->
                                        <div class="form-check h-100 border rounded-3">
                                            <div class="p-3">
                                                <input class="form-check-input payment_select" type="radio"
                                                       name="payment_select"
                                                       id="payment_select" value="1" checked/>
                                                <label class="form-check-label" style="margin-left: 30px"
                                                       for="payment_select">
                                                    {{ __('Online') }}<br/>
                                                    <small class="text-muted">3-4 days via Fedex </small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <p class="mb-0">{{ __('Message to seller') }}</p>
                                    <div class="form-outline">
                                        <textarea class="form-control shipping_notes" id="textAreaExample1"
                                                  rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Checkout -->
                </div>
                <div class="col-xl-4 col-lg-4 shipping-fee">
                    <div class="ms-lg-12 mt-4 mt-lg-0 p-4 border mb-3 shipping_fee">
                        <h6 class="mb-3" style="color: #ff0000">{{ __('Please select a delivery location') }}</h6>
                        <div class="">
                            <select id="city" class="form-select form-select-lg mb-3 w-100 city choose" name="city"
                                    aria-label=".form-select-lg example">
                                <option value="">--- {{ __('Choose the city') }} ---</option>
                                @foreach ($city as $key => $c_t)
                                    <option value="{{ $c_t->matp }}">{{ $c_t->name_city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="">
                            <select id="province" class="form-select form-select-lg mb-3 w-100 province choose "
                                    name="province" aria-label=".form-select-lg example">
                                <option>---{{ __('Choose a district') }} ---</option>
                            </select>
                        </div>
                        <div class="">
                            <select id="wards" class="form-select form-select-lg mb-3 w-100 wards" name="wards"
                                    aria-label=".form-select-lg example">
                                <option value="">--- {{ __('Choose a ward') }} ---</option>
                            </select>
                        </div>
                        <div class="input-group mt-3 mb-4">
                            <button class="btn btn-light text-primary border caculate_delivery"
                                    type="button">{{ __('Save') }}
                            </button>
                        </div>
                    </div>
                    <div class="ms-lg-12 mt-4 mt-lg-0 p-4 border">
                        <h6 class="text-dark my-4">{{ __('Items in cart') }}</h6>

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
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-secondary">1</span>
                                        <img src="{{asset('/uploads/product/'.$cart['product_image'])}}"
                                             style="height: 96px; width: 96px;" class="img-sm rounded border"/>
                                    </div>
                                    <div class="">
                                        <a href="#" class="text-dark">{{$cart['product_name']}}</a>
                                        <div class="price text-muted">
                                            {{ __('Total') }}
                                            : {{number_format($cart['product_qty'] * $cart['product_price'])}} VNĐ
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @endif
                        <hr>
                        <h6 class="mb-3">Summary</h6>
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">{{ __('Total product price') }}:</p>
                            <p class="mb-2">{{ number_format($total) }} VND</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">{{ __('Discount') }}:</p>
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

                                        @endif
                                    @endforeach
                                    @else
                                        <p class="mb-2 text-danger">0 VNĐ</p>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">{{ __('Shipping cost') }}:</p>
                            @if (Session::get('fee'))
                                <li>
                                    <span>{{ number_format(Session::get('fee')) }} VND</span>
                                    <a class="cart_quantity_delete" href="{{ url('/del-fee') }}"><i
                                            class="fa fa-times"></i></a>
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
                            <p class="mb-2">{{ __('Total price') }}:</p>
                            <p class="mb-2 fw-bold price_value" id="price_value">
                                @php
                                    if (Session::get('fee') && !Session::get('coupon')) {
                                    $total_after = $total_after_fee;
                                    echo number_format($total_after);
                                    }  elseif (Session::get('fee') && Session::get('coupon')) {
                                    $total_after = $total_after_coupon;
                                    $total_after = $total_after + Session::get('fee');
                                    echo number_format($total_after);
                                    }elseif (!Session::get('fee') && !Session::get('coupon'))
                                      {
                                           $total_after = $total;
                                    echo number_format($total_after);
                                      }elseif (!Session::get('fee') && Session::get('coupon'))
                                    {
                                           $total_after = $total_after_coupon;
                                    echo number_format($total_after);
                                    }
                                @endphp
                                VND
                            </p>
                        </div>
                        <div class="input-group mt-3 mb-4">
                            <button class="pay btn btn-light text-primary border send_order">{{ __('Pay') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
