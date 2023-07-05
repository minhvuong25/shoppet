<!-- resources/views/emails/order_status_updated.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Cập nhật trạng thái đơn hàng</title>
</head>
<body>
<h1>Cập nhật trạng thái đơn hàng</h1>

<p>Xin chào {{ $order->customer_name }},</p>

<p>Đơn hàng của bạn với mã {{ $order->order_code }} đã được cập nhật với trạng thái mới:</p>

<p><strong>Trạng thái mới:</strong>
    @if ($order->order_status == 1)
        Đơn hàng đang được chờ xác nhận
    @elseif ($order->order_status == 2)
        Xác nhận đơn hàng và đang giao
    @elseif ($order->order_status == 3)
        Đơn hàng đã bị hủy do lỗi sản phẩm
    @elseif ($order->order_status == 4)
        Xác nhận đơn hàng đã đến nơi
    @endif
</p>

<p>Cảm ơn bạn đã tiếp tục hỗ trợ.</p>

<p>Trân trọng,</p>
</body>
</html>
