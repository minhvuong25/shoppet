@extends('layout')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <section id="form">
        <!--form-->
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="login-form">
                        <form action="{{ URL::to('/login-customer') }}" method="POST">
                            {{ csrf_field() }}
                            <h1>Đăng nhập vào website</h1>
                            <div class="input-box">
                                <i></i>
                                <input type="email" placeholder="Nhập email" name="email_account">
                            </div>
                            <div class="input-box">
                                <i></i>
                                <input type="password" placeholder="Nhập mật khẩu" name="password_account">
                            </div>
                            <a href="{{ URL::to('forgin-password') }}">Quên mật khẩu</a><br>

                            <div class="btn-box" style="display: flex">
                                <button type="submit">
                                   Đăng nhập
                                </button>
                                <ul>
{{--                                    <li><a href="{{ URL::to('/login-customer-facebook') }}"> <i class="fa-brands fa-facebook"></i></a>--}}
                                    <li><a href="{{ URL::to('/login-customer-facebook') }}">Đăng nhập bằng Facebook</a>
                                    </li>
{{--                                    <li><a href="{{ URL::to('/login-customer-google') }}"><i class="fa-brands fa-google"></i></a></li>--}}
                                    <li><a href="{{ URL::to('/login-customer-google') }}">Đăng nhập bằng Google</i></a></li>
                                </ul>
                            </div>


                        </form>
                    </div>
                    <!--/login form-->

                </div>
                <div class="col-sm-1">
                    <h1 class="or">Hoặc</h1>
                    <?php
                    $message = Session::get('message');
                    if ($message) {
                        echo '<span class="text-alert">' . $message . '</span>';
                        Session::put('message', null);
                    }
                    ?>
                </div>
                <div class="col-sm-4">
                    <div class="login-form">
                        <!--sign up form-->
                        <h1>Đăng kí tài khoản</h1>
                        <form action="{{ URL::to('/add-customer') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="input-box">
                                <i></i>
                                <input type="text" placeholder="Họ tên" name="customer_name" />
                            </div>
                            <div class="input-box">
                                <i></i>
                                <input type="email" placeholder="Địa chỉ email" name="customer_email" />
                            </div>
                            <div class="input-box">
                                <i></i>
                                <input type="password" placeholder="Mật khẩu" name="customer_password" />
                            </div>
                            <div class="input-box">
                                <i></i>
                                <input type="text" placeholder="Điện thoại" name="customer_phone">
                            </div>
                            <div class="btn-box">
                                <i></i>
                                <button type="submit" class="btn btn-default">Đăng kí</button>
                            </div>
                        </form>
                    </div>
                    <!--/sign up form-->
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
    </section>
    <!--/form-->
@endsection
