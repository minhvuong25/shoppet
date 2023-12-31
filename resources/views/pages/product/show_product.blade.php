@extends('layout')
@section('content')
    @foreach ($show_product as $key => $show)
        <div class="product-details" style="border-top:1px solid;">
            <!--product-details-->
            <div class="container" style="margin-top:20px">
                <div class="col-sm-5">
                    <div class="view-product">
                    </div>
                    <form action="">
                        @csrf
                        <input type="hidden" value="{{ $show->product_id }}"
                            class="cart_product_id_{{ $show->product_id }}">
                        <input type="hidden" value="{{ $show->product_name }}"
                            class="cart_product_name_{{ $show->product_id }}">
                        <input type="hidden" value="{{ $show->product_image }}"
                            class="cart_product_image_{{ $show->product_id }}">
                        <input type="hidden" value="{{ $show->product_quantity }}"
                            class="cart_product_quantity_{{ $show->product_id }}">
                        @php
                            $show->product_sale_after = $show->product_price - ($show->product_price * $show->product_sale) / 100;
                        @endphp
                        <input type="hidden" value="{{ $show->product_sale_after }}"
                            class="cart_product_sale_after_{{ $show->product_id }}">

                        <ul id="imageGallery" style="padding-left: 0 !important">
                            @foreach ($gallery as $key => $gal)
                                <li data-thumb="{{ asset('./public/uploads/gallery/' . $gal->gallery_image) }}"
                                    data-src="{{ asset('/public/uploads/gallery/' . $gal->gallery_image) }}"
                                    >
                                    <img class="img-1" alt="{{ $gal->gallery_name }}"
                                        src="{{ asset('/public/uploads/gallery/' . $gal->gallery_image) }}" alt=""
                                        width="100%" height="100%">
                                </li>
                            @endforeach

                        </ul>
                    </form>
                </div>
                <div class="col-sm-7">
                    <div class="product-information">
                        <!--/product-information-->
                        <h2 class="name_details">{{ $show->product_name }}</h2>
                        <label class="brand_name">Thương hiệu :</label>
                        <span class="brand_name"><a href="{{ URL::to('product-by-category', ['category_id' => $show->category_id]) }}" style="color: red">{{ $show->category_name }}</a></span>
                        <form action="{{ URL::to('/save-cart') }}" method="POST">

                            {{ csrf_field() }}
                            <br>
                            @php
                                $show->product_sale_after = $show->product_price - ($show->product_price * $show->product_sale) / 100;
                            @endphp
                            @if ($show->product_sale)

                                <span class="price d-flex brand_name">
                                    <div class="d-flex">
                                        <span
                                            class="price" style="display: flex;margin-right:5px">Giá:  {{ number_format($show->product_sale_after) }}</span>
                                        <div class="m-0-5" style="margin-left:5px"> VND</div>
                                    </div>
                                    <strike class="m-0-5 d-flex mausay" style="margin-left:5px">{{ number_format($show->product_price) }}<div>
                                            VND
                                        </div></strike>
                                </span>
                            @else
                                <span class="price" style="display: flex;margin-right:5px">{{ number_format($show->product_price) }} <div
                                        class="m-0-5">VND</div></span>
                            @endif
                            <label class="brand_name" class="brand_name">Số lượng :</label>
                            <input type="number" class="cart_product_qty_{{ $show->product_id }}"
                                name="cart_product_quantity" min="1" oninput="validity.valid||(value='');" value="1">
                            <input type="hidden" name="productid_hidden" value="{{ $show->product_id }}"><br>
                            <label class="brand_name" class="brand_name">Trạng thái :</label>
                            <?php
                    // if($show->product_quantity==0){

                    // }
                    if($show->product_quantity==0){
                ?>
                            <span class="brand_name">Hết hàng</span><br>

                            <fieldset>
                                <legend class="brand_name" style="font-weight: 500">Tags</legend>
                                <p><i class="fa fa-tag"></i>
                                    @php
                                        $tags = $show->product_tags;
                                        $tags = explode(',', $tags);
                                    @endphp
                                    @foreach ($tags as $tag)
                                        <a href="{{ url('/tag/' . str_slug($tag)) }}"
                                            class="tags_style">{{ $tag }}</a>
                                    @endforeach
                                </p>
                            </fieldset>

                            <img src="{{ URL::to('/frontend/images/sold-1.jpg') }}" alt="" width="200px"
                                height="50px">
                            <?php
                    }else{
                    ?>
                            <span class="brand_name">Còn hàng</span><br>

                            <fieldset>
                                <legend class="brand_name" style="font-weight: 500">Tags</legend>
                                <p><i class="fa fa-tag"></i>
                                    @php
                                        $tags = $show->product_tags;
                                        $tags = explode(',', $tags);
                                    @endphp
                                    @foreach ($tags as $tag)
                                        <a href="{{ url('/tag/' . str_slug($tag)) }}"
                                            class="tags_style">{{ $tag }}</a>
                                    @endforeach
                                </p>
                            </fieldset>

                            {{-- <button type="submit" class="btn btn-fefault cart">
                        <i class="fa fa-shopping-cart"></i>
                       Thêm vào giỏ hàng
                    </button> --}}
                            <button class="btn btn-violet add home add-to-cart" style="background: #de8ebe;" type="button" name="add-to-cart"
                                data-id_product="{{ $show->product_id }}">Đặt mua ngay</button>

                            <?php
                    }
                 ?>
                            <br>
                        </form>
    @endforeach
    </form>
    </div>
    <!--/product-information-->
    </div>
    </div>
    <div class="category-tab shop-details-tab col-md-7">
        <!--category-tab-->
        <div class="col-sm-12">
            <ul class="nav nav-tabs delta">
                <li class="active"><a href="#details" data-toggle="tab">
                        <h3>Chi tiết</h3>
                    </a></li>
                <li><a href="#companyprofile" data-toggle="tab">
                        <h3>Thương hiệu</h3>
                    </a></li>
                <li><a href="#reviews" data-toggle="tab">
                        <h3>Đánh giá</h3>
                    </a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="details" style="margin-left: 15px;">
                <p class="infot">{!! $show->product_desc !!}</p>
            </div>
            <div class="tab-pane fade" id="companyprofile" class="brand_name" style="margin-left: 15px;">
                {!! $show->category_desc !!}
            </div>
            <div class="tab-pane fade " id="reviews">
                <div class="col-sm-12">
                    <ul class="list-inline rating" title="Average Rating">
                        <h3>Đánh giá sản phẩm</h3>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="rating_item">
                                    <div class="" style="display: flex;font-size:60px;color:yellow"><span>&#9733;</span>
                                        <p style="color: black">{{ $rating }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="list_rating">
                                    <div class="item_rating">
                                                <div>
                                                   5 &#9733; {{$countrate5}} Đánh giá
                                                    <span style="width:200px;height:6px;display:block;border:1px solid"></span>
                                                </div><br>
                                                <div>
                                                    4 &#9733; {{$countrate4}} Đánh giá
                                                     <span style="width:200px;height:6px;display:block;border:1px solid"></span>
                                                 </div><br>
                                                 <div>
                                                    3 &#9733; {{$countrate3}} Đánh giá
                                                     <span style="width:200px;height:6px;display:block;border:1px solid"></span>
                                                 </div><br>
                                                 <div>
                                                    2 &#9733; {{$countrate2}} Đánh giá
                                                     <span style="width:200px;height:6px;display:block;border:1px solid"></span>
                                                 </div><br>
                                                 <div>
                                                    1 &#9733; {{$countrate1}} Đánh giá
                                                     <span style="width:200px;height:6px;display:block;border:1px solid"></span>
                                                 </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- @for ($count = 1; $count <= 5; $count++)
                            @php
                                if($count<=$rating){
                                    $color = 'color:#ffcc00;';
                                }
                                else {
                                    $color = 'color:#ccc;';
                                }
                            @endphp
                            <li title="star_rating"
                                style="cursor: pointer; {{$color}} font-size:30px;">
                                &#9733;</li>
                        @endfor --}}

                    </ul>
                    <style>
                        .style_comment {
                            border: 1px solid #ddd;
                            border-radius: 10px;
                            background: #f0f0e9;
                            width: 90%;
                            margin: 0 auto;
                            display: block;
                        }

                        .comment_reply {
                            border: 1px solid #ddd;
                            border-radius: 10px;
                            background: #f0f0e9;
                            width: 80%;
                            margin: 0 auto;
                            display: block;
                        }

                        .style_comment p {
                            color: black;
                        }

                    </style>
                    <form>
                        @csrf
                        <input type="hidden" name="comment_product_id" value="{{ $show->product_id }}"
                            class="comment_product_id">
                        <div id="show_comment"></div>
                    </form>
                    @php
                        $customer_id = Session::get('customer_id');
                        // dd($show->product_buy = isset($customer_id));
                    @endphp
                    @if (isset($customer_id) && $commentProduct)
                        <p style="color: #000"> <b id="rating">Viết đánh giá</b></p>
                        <ul class="list-inline rating" title="Average Rating">
                            @for ($count = 1; $count <= 5; $count++)
                                @php
                                    if ($count <= $rating) {
                                        $color = 'color:#ffcc00;';
                                    } else {
                                        $color = 'color:#ccc;';
                                    }
                                @endphp
                                <li title="title_rating" id="{{ $show->product_id }}-{{ $count }}"
                                    data-index="{{ $count }}" data-product_id="{{ $show->product_id }}"
                                    data-rating="{{ $rating }}" class="rating"
                                    style="cursor: pointer; {{ $color }} font-size:30px;">
                                    &#9733;</li>
                            @endfor

                        </ul>
                        <form action="#">
                            <label for="">Bình luận</label>
                            <span>
                                <input type="text" placeholder="Your Name" class="comment_name" name="comment_name"
                                    value="{{ Session::get('customer_name') }}" readonly>
                            </span>
                            <input name="comment" class="comment_content" type="text" placeholder="Your Comment">
                            <button type="button" class="btn btn-default pull-right send-comment">
                                Gửi
                            </button>
                            <div id="notify_comment"></div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5 bestview">
        <h3>Sản phẩm liên quan</h3>
        <div class="list">
            <ul class="ul">
                @foreach ($related_product as $key => $relat)
                    <li>
                        <div class="p_container" style="display: flex;margin-bottom:10px;border:1px solid">
                            <a href="{{ URL::to('chi-tiet-san-pham/' . $relat->product_slug) }}"
                                title="{{ $relat->product_name }}" class="p-img">
                                <img data-src="{{ URL::to('/uploads/product/' . $relat->product_image) }}"
                                    alt="{{ $relat->product_name }}" class="lazy loaded"
                                    src="{{ URL::to('/uploads/product/' . $relat->product_image) }}"
                                    data-was-processed="true" width="150px" height="150px">
                            </a>
                            <div style="align-self: center;margin-left: 10px;">
                                <a style="color: #000;font-size:14px"
                                    href="{{ URL::to('chi-tiet-san-pham/' . $relat->product_slug) }}"
                                    class="p-name" data-toggle="tooltip" data-placement="top"
                                    title="Chi tiết {{ $relat->product_name }}">{{ $relat->product_name }}</a>

                                <div class="p-price">
                                    @php
                                        $relat->product_sale_after = $relat->product_price - ($relat->product_price * $relat->product_sale) / 100;
                                    @endphp
                                    @if ($relat->product_sale)
                                        <span class="price d-flex brand_name">
                                            <div class="d-flex">
                                                <span
                                                    class="price">{{ number_format($relat->product_sale_after) }}</span>
                                                <div class="m-0-5"> VND</div>
                                            </div>
                                            <strike
                                                class="m-0-5 d-flex mausay">{{ number_format($relat->product_price) }}
                                                <div>
                                                    VND</div>
                                            </strike>
                                        </span>
                                    @else
                                        <span class="price brand_name" style="display: flex">{{ number_format($relat->product_price) }} <div
                                                class="m-0-5" style="margin-left:5px">VND</div></span>
                                    @endif
                                </div>
                                <a href="{{ URL::to('chi-tiet-san-pham/' . $relat->product_slug) }}"
                                    class="btn-violet add home mt-3">Xem</a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    </div>
    </div>
@endsection
