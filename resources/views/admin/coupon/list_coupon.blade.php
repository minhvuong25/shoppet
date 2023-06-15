@extends('admin_layout')
@section('admin_content')
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left">
                    Mã khuyến mãi
                    <a class="btn btn-xs btn-primary" href="{{ URL::to('coupon') }}">Thêm mã khuyến mãi</a>
                </div>
            </div>
        </div>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<span class="text-alert">' . $message . '</span>';
            Session::put('message', null);
        }
        ?>
        <div class="table-responsive">
            <table class="table table-striped b-t b-light" id="myTable-2">
                <thead>
                    <tr>
                        <th>Tên mã giảm giá</th>
                        <th>Mã giảm giá</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Số lần dùng</th>
                        <th>Loại mã giảm</th>
                        <th>Quản lý</th>
                        <th>Tình trạng</th>
                        <th>Gửi</th>
                        <th style="width:30px;">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupon as $key => $coup)
                        <tr>
                            <td>{{ $coup->coupon_name }}</td>
                            <td>{{ $coup->coupon_code }}</td>
                            <td>{{ date('d-m-Y', $coup->coupon_date_start) }}</td>
                            <td>{{ date('d-m-Y', $coup->coupon_date_end) }}</td>
                            <td>{{ $coup->coupon_times }}</td>
                            <td>
                                @php
                                    if ($coup->coupon_condition == 2) {
                                        echo number_format($coup->coupon_number) . ' VND';
                                    } elseif ($coup->coupon_condition == 1) {
                                        echo $coup->coupon_number . ' %';
                                    }
                                @endphp
                            </td>
                            <td style="width:95px">
                                @if ($coup->coupon_date_end > $now)
                                    <span class="vip-vip" style="background: green;color:#fff">Còn hạn</span>
                                @else
                                    <span class="vip">Hết hạn</span>
                                @endif
                            </td>
                            <td>
                                <?php
                                    if($coup->coupon_status ==1){
                                    ?>
                                <a href="{{ URL::to('/active-coupon/' . $coup->coupon_id) }}"><span class="vip-vip"
                                        style="background: green;color:#fff">Mở</span></a>
                                <?php
                                    }else{
                                    ?>
                                <a href="{{ URL::to('/unactive-coupon/' . $coup->coupon_id) }}"><span
                                        class="vip">Đóng</span></a>
                                <?php
                                    }
                                    ?>
                            </td>
                            <td>
                                <?php
                                    if($coup->coupon_date_end > $now){
                                    ?>
                                <a href="{{ URL::to('/send-coupon-vip', [
                                    'coupon_times' => $coup->coupon_times,
                                    'coupon_condition' => $coup->coupon_condition,
                                    'coupon_number' => $coup->coupon_number,
                                    'coupon_code' => $coup->coupon_code,
                                ]) }}"
                                    class="btn btn-xs btn-default mb-2">Gửi mã
                                    giảm giá vip</a>
                                <a href="{{ URL::to('/send-coupon', [
                                    'coupon_times' => $coup->coupon_times,
                                    'coupon_condition' => $coup->coupon_condition,
                                    'coupon_number' => $coup->coupon_number,
                                    'coupon_code' => $coup->coupon_code,
                                ]) }}"
                                    class="btn btn-xs btn-default mb-2 mt-1">Gửi
                                    mã
                                    giảm giá thường</a>
                                <?php }else{
                                    ?>
                                <span>Hết hạn</span>
                                <?php
                                    }
                                    ?>
                            </td>
                            <td>
                                <a onclick="return confirm('Bạn có muốn xóa?')"
                                    href="{{ URL::to('/del-coupon/' . $coup->coupon_id) }}" class="active"
                                    ui-toggle-class=""><i class="fa fa-times text-danger text"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
