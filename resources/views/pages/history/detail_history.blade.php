@extends('layout')
@section('content')
  <div class="container">
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                Thông tin người mua
            </div>

            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>

                            <th>Tên người đặt</th>
                            <th>Email người đặt</th>
                            <th>Số điện thoại</th>

                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->customer_email }}</td>
                            <td>{{ $customer->customer_phone }}</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br><br>
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                Thông tin vận chuyển
            </div>
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>

                            <th>Tên người vận chuyển</th>
                            <th>Địa chỉ</th>
                            <th>Số điện thoại</th>
                            <th>Ghi chú</th>
                            <th>Hình thức thanh toán</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $shipping->shipping_name }}</td>
                            <td>{{ $shipping->shipping_address }}</td>
                            <td>{{ $shipping->shipping_phone }}</td>
                            <td>
                                @if ($shipping->shipping_notes != null)
                                    {{ $shipping->shipping_notes }}
                                @else
                                    Đơn hàng không có ghi chú
                                @endif
                            </td>
                            <td>
                                @if ($shipping->shipping_method == 0)
                                    Chuyển khoản
                                @elseif ($shipping->shipping_method == 1)
                                    Tiền mặt
                                @else
                                    Thanh toán qua paypal
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br><br>
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                Chi tiết đơn hàng
            </div>

            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Số thứ tự</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                            $total = 0;
                        @endphp
                        @foreach ($order_details as $key => $ord_d)
                            @php
                                $i++;
                                $subtotal = $ord_d->product_price * $ord_d->product_sales_quantity;
                                $total += $subtotal;
                            @endphp
                            <tr class="color_qty_{{ $ord_d->product_id }}">
                                <td>{{ $i }}</td>
                                <td>{{ $ord_d->product_name }}</td>
                                <td>
                                    <input type="number" min="1" readonly="readonly"
                                        class="order_qty_{{ $ord_d->product_id }}"
                                        value="{{ $ord_d->product_sales_quantity }}" name="product_sale_quantity"
                                        oninput="this.value = Math.abs(this.value)">
                                    <input type="hidden" name="order_qty_storage" id=""
                                        class="order_qty_storage_{{ $ord_d->product_id }}"
                                        value="{{ $ord_d->product->product_quantity }}">
                                    <input type="hidden" name="order_product_id" id="" class="order_product_id"
                                        value="{{ $ord_d->product_id }}">
                                </td>
                                <td>{{ number_format($ord_d->product_price) }} VND</td>
                                <td>{{ number_format($subtotal) }} VND</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th>Mã giảm giá</th>
                            <th colspan="2">Phương thức giảm</th>
                            <th colspan="3">Thanh toán</th>
                        </tr>
                        <tr>
                            <td>
                                @if ($ord_d->product_coupon != 'no')
                                    {{ $ord_d->product_coupon }}
                                @else
                                    Đơn hàng không áp dụng mã giảm giá
                                @endif
                            </td>
                            <td colspan="2">
                                @php
                                    if ($coupon_condition == 1) {
                                        echo 'Giảm theo %' . '<br>' . $coupon_number . ' %';
                                    } elseif ($coupon_condition == 2) {
                                        echo 'Giảm theo tiền' . '<br>' . number_format($coupon_number) . ' VND';
                                    } elseif ($coupon_condition == null) {
                                        echo 'Không có';
                                    }
                                @endphp
                            </td>
                            <td colspan="2">
                                Phí ship : {{ $ord_d->product_feeship }} VND<br>
                                @php
                                    $total_coupon = 0;
                                @endphp
                                @if ($coupon_condition == 1)
                                    @php
                                        $total_after_coupon = ($total * $coupon_number) / 100;
                                        echo 'Tổng giảm : ' . number_format($total_after_coupon) . ' VND' . '<br>';
                                        $total_coupon = $total - $total_after_coupon + $ord_d->product_feeship;
                                    @endphp
                                @else
                                    @php
                                        echo 'Tổng giảm : ' . number_format($coupon_number) . ' VND' . '<br>';
                                        $total_coupon = $total - $coupon_number + $ord_d->product_feeship;
                                    @endphp
                                @endif
                                Tổng : {{ number_format($total_coupon) }} VND
                            </td>
                        </tr>

                    </tbody>
                </table>
                @if($order_status == 1 )
                    <a href="{{url('cancel_order/'.$order_code)}}"><button type="button" class="btn btn-danger col-md-12">Hủy đơn hàng</button></a>
                @endif
            </div>
        </div>
    </div>
    <br><br>
  </div>
@endsection
