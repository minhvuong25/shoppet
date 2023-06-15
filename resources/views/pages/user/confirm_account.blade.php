<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirm Account</title>
</head>
<body>
<h2>Xác nhận tài khoản</h2>
<p>Xin chào {{ $user['customer_name'] }},</p>
<p>Cám ơn bạn đã đăng ký tài khoản. Vui lòng nhấp vào liên kết bên dưới để xác nhận tài khoản của bạn:</p>
<a href="{{ $confirmationLink }}">Xác nhận tài khoản</a>
<p>Nếu bạn không thực hiện yêu cầu này, hãy bỏ qua email này.</p>
</body>
</html>
