<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mã khuyến mãi</title>
    <link rel=" icon" type="image/x-icon" href="{{ URL::to('/frontend/images/download.png') }}">
</head>
<body>
{{--    <h2>{{$coupon['coupon_times']}}</h2>--}}
    <h1>Gửi mã khuyến mãi</h1> <a href="https://shoppet.com/" target="_blank"></a>
    <h2>Mã khuyến mãi : {{$coupon['coupon_code']}}</h2>
    <span style="color: #00c167">
        Sử dụng cho tất cả các đơn hàng
        </span> <br><br><span>
        Hạn sử dụng từ ngày : {{$coupon['start_coupon']}}   đến ngày : {{$coupon['end_coupon']}}
    </span>
</body>
</html>
