+@extends('layout')
@section('content')
<section class="padding-top-50 padding-bottom-150">
    <div class="container">
        <!-- Main Heading -->
        <div class="heading text-center" id="khoahoc">
            <h4>Nhà tài trợ và hệ thống phòng tập TLU</h4>
            <div style="border-bottom: 1px solid white;margin-bottom: 10px;"></div>
        </div>
        <div class="arrival-block-2">
@foreach ($sponsor as $key=>$spon)
            <!-- Item -->
            <div class="item">
                <!-- Images -->
                <img class="img-1" src="{{asset('/uploads/product/'.$spon->sponsor_image)}}" alt="" > <img class="img-2" src="{{asset('/uploads/product/'.$spon->sponsor_image)}}" alt="" >
                <!-- Overlay  -->
                <div class="overlay">
                    <!-- Price -->
                    <div class="position-center-center"> <a href=""  target="_blank"><i class="fa fa-facebook"></i></a> </div>
                </div>
                <!-- Item Name -->
                <div class="item-name">
                    <a href="#">{{$spon->sponsor_name}}</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
