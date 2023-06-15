@extends('layout')
@section('content')
    <div class="container">
        <div id="shipping-address" class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">
                    <a data-toggle="collapse" data-parent="#checkout-page" href="#shipping-address-content"
                       class="accordion-toggle" aria-expanded="true">
                        Đổi mật khẩu
                    </a>
                </h2>
            </div>
            <div id="shipping-address-content" class="panel-collapse collapse in" aria-expanded="true" style="">
                <div class="panel-body row">
                    <form action="{{ URL::to('edit-password/' . Session::get('customer_id')) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="address1-dd">Mật khẩu mới</label>
                                <input type="text" required id="address1-dd" class="form-control col-md-12" name="password_new">
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <label for="address1-dd">Xác nhận mật khẩu</label>
                                <input type="text" required id="address1-dd" class="form-control col-md-12" name="password_confirm">
                            </div>
                        </div>
                        <div class="d-none">
                            <!-- Nội dung của div ẩn -->
                            <input type="hidden" name="changepass" value="changepass">
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary  pull-right collapsed" type="submit" name="EditCustomer">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
