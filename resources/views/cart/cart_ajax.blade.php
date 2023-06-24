@extends('layout')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php
    $message = Session::get('message');
    if ($message) {
        echo "<script>alert('$message')</script>";
        Session::put('message', null);
    }
    $error = Session::get('error');
    if ($error) {
        echo "<script>alert('$error')</script>";
        Session::put('error', null);
    }
    ?>
<style>
    .p-10{
        padding: 10px;
    }
    .p-20{
        padding: 20px;
    }
</style>
    <div class="container">
        <form action="{{ url('/update-cart') }}" method="POST">
            @csrf
            <table style="width: 100%">
                <tr class="chiakhoangcach">
                    <th class="checkout-image p-10">{{ __('Image') }}</th>
                    <th class="checkout-description p-10">{{ __('Name') }}</th>
                    <th class="checkout-quantity p-10">{{ __('Quantity') }}</th>
                    <th class="checkout-quantity p-10">{{ __('Price') }}</th>
                    <th class="checkout-quantity p-10" >{{ __('Total') }}</th>
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
                                <a href=""><img src="{{ asset('/uploads/product/' . $cart['product_image']) }}"
                                        alt="{{ $cart['product_name'] }}" width="50px"></a>
                            </td>
                            <td class="checkout-description p-20">
                                <h3><a href="javascript:;" style="font-size: 20px">{{ $cart['product_name'] }}</a></h3>
                                {{-- <p>{{ $cart['product_desc'] }}</p> --}}
                            </td>
                            <td class="checkout-quantity p-20">
                                <input type="number" class="cart_quantity" min="1" oninput="validity.valid||(value='');"
                                    name="cart_qty[{{ $cart['session_id'] }}]" value="{{ $cart['product_qty'] }}"
                                    autocomplete="off" style="border:none">
                                <input type="hidden" value="" name="rowId_cart" class="form-control">
                            </td>
                            <td class="checkout-price p-20"><strong class="text-center">{{ number_format($cart['product_price']) }} VND</strong>
                            </td>
                            <td class="checkout-total p-20"><strong class="text-center">{{ number_format($subtotal) }} VND</strong></td>
                            <td>
                                <a class="cart_quantity_delete p-20" href="{{ url('/delete-sp/' . $cart['session_id']) }}"><i
                                        class="fa-solid fa-trash"></i></a>
{{--                                <i class="fa-solid fa-trash"></i>--}}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="padding-left:15px"><input type="submit" name="update-cart"
                                class="btn btn-default btn-sm" value="{{ __('Update') }}"></td>
                        <td style="list-style: none;padding-top:15px;padding-bottom:15px">
                            <li style="margin-left:15px">{{ __('Total') }} :<span>{{ number_format($total) }} VND</span></li>
                            @if (Session::get('coupon'))
                                <li>
                                    @foreach (Session::get('coupon') as $key => $cou)
                                        @if ($cou['coupon_condition'] == 1)
                                            {{ __('Coupon') }} : {{ $cou['coupon_number'] }} %
                                            <p>
                                                @php
                                                    $total_coupon = ($total * $cou['coupon_number']) / 100;
                                                    echo '<p>Tổng giảm :' . number_format($total_coupon) . ' VND</p>';
                                                @endphp
                                            </p>
                                            <p>{{ __('Total') }} : {{ number_format($total - $total_coupon) }} VND</p>
                                            <a href="{{ url('del-cou') }}"
                                                class="btn btn-primary">{{ __('DelCoupon') }}
                                            </a>
                                        @elseif ($cou['coupon_condition'] == 2)
                                            {{ __('Coupon') }} : {{ number_format($cou['coupon_number']) }} VND
                                            <p>
                                                @php
                                                    $total_coupon = $total - $cou['coupon_number'];
                                                @endphp
                                            </p>
                                            <p>{{ __('Total') }} : {{ number_format($total_coupon) }} VND</p>
                                            <a href="{{ url('del-cou') }}" class="btn btn-primary">
                                                {{ __('DelCoupon') }}
                                            </a>
                                        @endif
                                    @endforeach
                                </li>
                            @endif
                            {{-- <li>Thuế <span></span></li>
                <li>Phí vận chuyển <span>Free</span></li> --}}
                        </td>
                        <td style="padding-top: 15px;padding-bottom:15px" colspan="5">
                            @if (Session::get('customer_id'))
                                <a href="{{ url('checkout') }}" class="btn btn-primary pull-right" style="margin-right:15px;margin-left:15px">
                                    {{ __('Payment Direct') }}
                                </a>
                                <a href="{{ url('checkout_online') }}" class="btn btn-primary pull-right" style="margin-right:15px;margin-left:15px">
                                    {{ __('Payment Online') }}
                                </a>
                            @else
                                <a href="{{ url('login-checkout') }}" class="btn btn-primary pull-right" style="margin-right:15px;margin-left:15px">
                                    {{ __('Order') }}
                                </a>

                            @endif

                            <a href="{{ url('/delete-all-cart/') }}"
                                class="btn btn-default pull-right margin-right-20">{{ __('Cancel') }}</a>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5">
                            <center>
                                @php
                                    echo 'Chưa có sản phẩm trong giỏ';
                                @endphp
                            </center>
                        </td>
                    </tr>
                    </tbody>
                @endif
            </table>
        </form>
        @if (!Session::get('coupon'))
            @if (Session::get('cart'))
                <tr>
                    <td colspan="5">
                        <form action="{{ url('/check-coupon') }}" method="POST">
                            @csrf
                            <div style="display: flex;margin-bottom:30px;margin-top:15px">
                                <input type="text" class="form-control" name="coupon"
                                    placeholder="{{ __('EnterDiscountCode') }}">
                                <input type="submit" name="form-control check_coupon"
                                    class="btn btn-default btn-sm check_coupon" value="{{ __('Coupon') }}">
                            </div>
                        </form>
                    </td>
                </tr>
            @endif
        @endif
        <div class="clearfix"></div>

    </div>

@endsection
