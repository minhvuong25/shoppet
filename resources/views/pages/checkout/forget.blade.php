@extends('layout')
@section('content')
<?php
        $message = Session::get('message');
        if ($message) {
            echo "<script>alert('$message')</script>";
            Session::put('message',null);
        }
        $error = Session::get('error');
        if ($error) {
            echo "<script>alert('$error')</script>";
            Session::put('error',null);
        }
?>
<section id="form"><!--form-->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="login-form"><!--login form-->
                    <h2>Lấy lại mật khẩu</h2>
                    <form action="{{URL::to('/send-mail-to-customer')}}" method="POST">
                        {{ csrf_field() }}
                        <div class="input-box">
                            <i></i>
                        <input type="email" placeholder="Tài khoản" name="email_account"/>
                        </div>
                        <button type="submit" class="btn btn-default">Gửi</button>
                    </form>
                </div><!--/login form-->
            </div>
        </div>
    </div>
    <style>
        .login-form {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 15px;
            border: 2px dotted #cccccc;
            border-radius: 10px;
        }

        h1 {
            color: #009999;
            font-size: 20px;
            margin-bottom: 30px;
        }

        .input-box {
            margin-bottom: 10px;
        }

        .input-box input {
            padding: 7.5px 15px;
            width: 100%;
            border: 1px solid #cccccc;
            outline: none;
        }

        .btn-box {
            text-align: right;
            margin-top: 30px;
        }

        .btn-box button {
            padding: 7.5px 15px;
            border-radius: 2px;
            background-color: #009999;
            color: #ffffff;
            border: none;
            outline: none;
        }

    </style>
</section><!--/form-->
@endsection
