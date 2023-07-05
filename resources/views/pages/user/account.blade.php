@extends('layout')
@section('content')
    <div class="container">
        <div id="shipping-address" class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">
                    <a data-toggle="collapse" data-parent="#checkout-page" href="#shipping-address-content"
                       class="accordion-toggle" aria-expanded="true">
                        Tài khoản
                    </a>
                </h2>
            </div>
            <div id="shipping-address-content" class="panel-collapse collapse in" aria-expanded="true" style="">
                <div class="panel-body row" style="width: auto">
                    <form action="{{ URL::to('edit-customer/' . Session::get('customer_id')) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6 col-sm-6">
{{--                                                    <img src="{{URL::to('/uploads/account/'.$getUserProfile->customer_picture)}}" alt="" width="100%">--}}
{{--                                                    <img src="{{$getUserProfile->customer_picture}}" alt="" width="100%">--}}
                            <img src="{{asset('/uploads/account/'.$getUserProfile->customer_picture)}}" alt=""
                                 width="100%">
                            <br>
                            <br>
                            <input type="file" name="customer_picture" class="col-md-12">
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="address2-dd">Email</label>
                                <input type="text" id="address2-dd" class="form-control"
                                       value="{{ $getUserProfile->customer_email }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="address1-dd">Họ tên</label>
                                <input type="text" id="address1-dd" class="form-control"
                                       value="{{ $getUserProfile->customer_name }}" name="customer_name">
                            </div>
                            <div class="form-group">
                                <label for="city-dd">Số điện thoại <span class="require">*</span></label>
                                <input required type="text" id="city-dd" class="form-control"
                                       value="{{ $getUserProfile->customer_phone }}" name="customer_phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary  pull-right collapsed" type="submit" name="EditCustomer">
                                Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
