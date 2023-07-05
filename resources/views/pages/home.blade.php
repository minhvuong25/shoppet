@extends('layout')
@section('content')
    <!-- Wrap -->
    <?php
    $message = Session::get('message');
    if ($message) {
        echo "<script>alert('$message')</script>";
        Session::put('message', null);
    }
    $error = Session::get('error');
    if ($error) {
        echo "<script>alert('$error')</script>";
        Session::put('error', null);
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="content-wrap col-md-12 col-sm-12 col-xs-12">
                <div class="entry-content clearfix">
                    <div class="clearfix">
                        <div class="vc_row wpb_row">
                            <div class="wpb_column column_container col-sm-12">
                                <div class="vc_column-inner vc_custom_1576307239909">
                                    <div class="wpb_wrapper">
                                        <div class="wpb_wrapper">
                                            <h2 class="font-coiny" style="text-align: center;"><a
                                                    href=""><strong>{{ __('Pet') }}</strong></a></h2>
                                        </div>
                                    </div>
                                    <div
                                        class="block-element product-item-1 product-grid-view  default js-content-wrap">
                                        <div class="products row list-product-wrap js-content-main">
                                            @foreach ($pet as $pet)
                                                <div
                                                    class="list-col-item list-4-item post-48252 product type-product status-publish has-post-thumbnail product_cat-samoyed product_cat-danh-muc-cun first instock shipping-taxable purchasable product-type-simple">
                                                    <div class="item-product item-product-grid">
                                                        <div class="product-thumb">
                                                            <!-- s7upf_woocommerce_thumbnail_loop have $size and $animation -->
                                                            <a href="{{ URL::to('/chi-tiet-san-pham/' . $pet->product_slug) }}"
                                                               class="product-thumb-link zoom-thumb">
                                                                <img width="270" height="270"
                                                                     src="{{ URL::to('/uploads/product/' . $pet->product_image) }}"
                                                                     class="attachment-270x270 size-270x270 wp-post-image"
                                                                     sizes="(max-width: 270px) 100vw, 270px"
                                                                     style="width:270px;height:270px">
                                                            </a>
                                                            @if ($pet->product_sale != 0)
                                                                <div class="product-label"><span
                                                                        class="new">sale</span></div>
                                                            @endif
                                                            <div class="product-label"><span
                                                                    class="new">new</span></div>
                                                            <div class="product-extra-link text-center">
                                                                <ul class="list-product-extra-link list-inline-block">
                                                                    <li>
                                                                        <a href="{{ URL::to('add-wishlist/' . $pet->product_id) }}"
                                                                           style="display: flex;justify-content: center;align-items: center;"
                                                                           class="add_to_wishlist wishlist-link"
                                                                           data-product-title="{{ $pet->product_content }}">
                                                                            <i class="pegk pe-7s-like"></i><span>Yêu thích</span>
                                                                        </a>
                                                                    </li>
                                                                    <li><a title="Xem nhanh"
                                                                           href="{{ URL::to('/chi-tiet-san-pham/' . $pet->product_slug) }}"
                                                                           style="display: flex;justify-content: center;align-items: center;"
                                                                           class="product-quick-view quickview-link "><i
                                                                                class="pegk pe-7s-search"></i><span>Xem nhanh</span></a>
                                                                    </li>
                                                                    <li></li>
                                                                </ul>
                                                                <input type="hidden" name="productid_hidden"
                                                                       value="{{ $pet->product_id }}">

                                                                <form action="">
                                                                    @csrf
                                                                    <input type="hidden"
                                                                           value="{{ $pet->product_id }}"
                                                                           class="cart_product_id_{{ $pet->product_id }}">
                                                                    <input type="hidden"
                                                                           id="wistlist_productname{{ $pet->product_id }}"
                                                                           value="{{ $pet->product_name }}"
                                                                           class="cart_product_name_{{ $pet->product_id }}">
                                                                    <input type="hidden"
                                                                           value="{{ $pet->product_image }}"
                                                                           class="cart_product_image_{{ $pet->product_id }}">
                                                                    <input type="hidden"
                                                                           value="{{ $pet->product_quantity }}"
                                                                           class="cart_product_quantity_{{ $pet->product_id }}">
                                                                    @php
                                                                        $pet->product_sale_after = $pet->product_price - ($pet->product_price * $pet->product_sale) / 100;
                                                                    @endphp
                                                                    <input type="hidden"
                                                                           id="wistlist_productprice{{ $pet->product_id }}"
                                                                           value="{{ $pet->product_sale_after }}"
                                                                           class="cart_product_sale_after_{{ $pet->product_id }}">
                                                                    <input type="hidden"
                                                                           class="cart_product_qty_{{ $pet->product_id }}"
                                                                           name="cart_product_quantity" min="1"
                                                                           oninput="validity.valid||(value='');"
                                                                           value="1">
                                                                    <input type="hidden" name="productid_hidden"
                                                                           value="{{ $pet->product_id }}">
                                                                </form>
                                                                    <?php if ($pet->product_quantity > 0){ ?>
                                                                <a type="button"
                                                                   data-id_product="{{ $pet->product_id }}"
                                                                   name="add-to-cart"
                                                                   class="add-to-cart button addcart-link shop-button bg-color"
                                                                   style="cursor: pointer"><span
                                                                        style="color: #fff">{{ __('AddToCart') }}</span></a>
                                                                <?php } else { ?>
                                                                <a href="javascript:;" rel="nofollow"
                                                                   class="button addcart-link shop-button bg-color product_type_simple add_to_cart_button s7upf_ajax_add_to_cart product_type_simple"
                                                                   style="text-decoration: none"><span>{{ __('SoldOff') }}</span></a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="product-info">
                                                                                                        <span
                                                                                                            class="title12 text-uppercase color font-bold">ID:
                                                                                                            {{ strtoupper($pet->product_code) }}</span>
                                                            <h3
                                                                class="title18 text-uppercase product-title dosis-font font-bold">
                                                                <a title="{{ $pet->product_content }}"
                                                                   href="{{ URL::to('/chi-tiet-san-pham/' . $pet->product_slug) }}"
                                                                   class="black">{{ $pet->product_name }}</a>
                                                            </h3>
                                                            <div class="product-price simple">
                                                                @if ($pet->product_sale)
                                                                    <span
                                                                        class="woocommerce-Price-amount amount">{{ number_format($pet->product_sale_after) }}<span
                                                                            class="woocommerce-Price-currencySymbol">&#8363;</span></span>
                                                                    <strike class="woocommerce-Price-amount amount"
                                                                            style="color: #de8ebe;
                                                                                                                            font-weight: 700;
                                                                                                                            font-size: 18px;">
                                                                        {{ number_format($pet->product_price) }}
                                                                        <span
                                                                            class="woocommerce-Price-currencySymbol">&#8363;</span>
                                                                    </strike>
                                                                @else
                                                                    <span
                                                                        class="woocommerce-Price-amount amount">{{ number_format($pet->product_price) }}<span
                                                                            class="woocommerce-Price-currencySymbol">&#8363;</span></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="wpb_raw_code wpb_content_element wpb_raw_html">
                                        <div class="wpb_wrapper">
                                            <a class="shop-button bg-color arrow-right block-right"
                                               href="{{ URL::to('/all-pet') }}">{{ __('Readmore') }}</a>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrap col-md-12 col-sm-12 col-xs-12">
            <div class="entry-content clearfix">
                <div class="clearfix">
                    <div class="vc_row wpb_row">
                        <div class="wpb_column column_container col-sm-12">
                            <div class="vc_column-inner vc_custom_1576307239909">
                                <div class="wpb_wrapper">
                                    <div
                                        class="wpb_text_column wpb_content_element  wpb_animate_when_almost_visible wpb_fadeIn fadeIn">
                                        <div class="wpb_wrapper">
                                            <h2 class="font-coiny" style="text-align: center;"><a
                                                    href=""><strong>{{ __('Accessory') }}</strong></a></h2>
                                        </div>
                                    </div>
                                    <div
                                        class="block-element product-item-1 product-grid-view  default js-content-wrap">
                                        <div class="products row list-product-wrap js-content-main">

                                            @foreach ($accessory as $accessory)
                                                <div
                                                    class="list-col-item list-4-item post-48252 product type-product status-publish has-post-thumbnail product_cat-samoyed product_cat-danh-muc-cun first instock shipping-taxable purchasable product-type-simple">
                                                    <div class="item-product item-product-grid">
                                                        <div class="product-thumb">
                                                            <!-- s7upf_woocommerce_thumbnail_loop have $size and $animation -->
                                                            <a href="{{ URL::to('/chi-tiet-san-pham/' . $accessory->product_slug) }}"
                                                               class="product-thumb-link zoom-thumb">
                                                                <img width="270" height="270"
                                                                     src="{{ URL::to('/uploads/product/' . $accessory->product_image) }}"
                                                                     class="attachment-270x270 size-270x270 wp-post-image"
                                                                     sizes="(max-width: 270px) 100vw, 270px"
                                                                     style="width:270px;height:270px">
                                                            </a>
                                                            @if($accessory->product_sale !=0)
                                                                <div class="product-label"><span
                                                                        class="new">sale</span></div>
                                                            @endif
                                                            {{-- <div class="product-label"><span
                                                                    class="new">new</span></div> --}}
                                                            <div class="product-extra-link text-center">
                                                                <ul class="list-product-extra-link list-inline-block">
                                                                    <li>
                                                                        <a href="{{ URL::to('add-wishlist/' . $accessory->product_id) }}"
                                                                           style="display: flex;justify-content: center;align-items: center;"
                                                                           class="add_to_wishlist wishlist-link"
                                                                           data-product-title="{{ $accessory->product_content }}"><i
                                                                                class="pegk pe-7s-like"></i><span>Yêu
                                                                                    thích</span></a></li>
                                                                    <li><a title="Xem nhanh"
                                                                           href="{{ URL::to('/chi-tiet-san-pham/' . $accessory->product_slug) }}"
                                                                           style="display: flex;justify-content: center;align-items: center;"
                                                                           class="product-quick-view quickview-link "><i
                                                                                class="pegk pe-7s-search"></i><span>Xem
                                                                                    nhanh</span></a></li>
                                                                    <li></li>
                                                                </ul>
                                                                <input type="hidden" name="productid_hidden"
                                                                       value="{{ $accessory->product_id }}">
                                                                <form action="">
                                                                    @csrf
                                                                    <input type="hidden"
                                                                           value="{{ $accessory->product_id }}"
                                                                           class="cart_product_id_{{ $accessory->product_id }}">
                                                                    <input type="hidden"
                                                                           id="wistlist_productname{{ $accessory->product_id }}"
                                                                           value="{{ $accessory->product_name }}"
                                                                           class="cart_product_name_{{ $accessory->product_id }}">
                                                                    <input type="hidden"
                                                                           value="{{ $accessory->product_image }}"
                                                                           class="cart_product_image_{{ $accessory->product_id }}">
                                                                    <input type="hidden"
                                                                           value="{{ $accessory->product_quantity }}"
                                                                           class="cart_product_quantity_{{ $accessory->product_id }}">
                                                                    @php
                                                                        $accessory->product_sale_after = $accessory->product_price - ($accessory->product_price * $accessory->product_sale) / 100;
                                                                    @endphp
                                                                    <input type="hidden"
                                                                           id="wistlist_productprice{{ $accessory->product_id }}"
                                                                           value="{{ $accessory->product_sale_after }}"
                                                                           class="cart_product_sale_after_{{ $accessory->product_id }}">
                                                                    <input type="hidden"
                                                                           class="cart_product_qty_{{ $accessory->product_id }}"
                                                                           name="cart_product_quantity" min="1"
                                                                           oninput="validity.valid||(value='');"
                                                                           value="1">
                                                                    <input type="hidden" name="productid_hidden"
                                                                           value="{{ $accessory->product_id }}">
                                                                </form>
                                                                    <?php if ($accessory->product_quantity > 0){ ?>
                                                                <a type="button"
                                                                   data-id_product="{{ $accessory->product_id }}"
                                                                   name="add-to-cart"
                                                                   class="add-to-cart button addcart-link shop-button bg-color "
                                                                   style="cursor: pointer"><span
                                                                        style="color: #fff">{{ __('AddToCart') }}</span></a>
                                                                <?php } else { ?>
                                                                <a disabled href="javascript:;" rel="nofollow"
                                                                   class="addcart-link shop-button bg-color product_type_simple add_to_cart_button s7upf_ajax_add_to_cart product_type_simple"
                                                                   style="text-decoration: none"><span>{{ __('SoldOff') }}</span></a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="product-info">
                                                                <span class="title12 text-uppercase color font-bold">ID:
                                                                    {{ strtoupper($accessory->product_code) }}</span>
                                                            <h3
                                                                class="title18 text-uppercase product-title dosis-font font-bold">
                                                                <a title="{{ $accessory->product_content }}"
                                                                   href="{{ URL::to('/chi-tiet-san-pham/' . $accessory->product_slug) }}"
                                                                   class="black">{{ $accessory->product_name }}</a>
                                                            </h3>
                                                            <div class="product-price simple">
                                                                @if ($accessory->product_sale)
                                                                    <span
                                                                        class="woocommerce-Price-amount amount">{{ number_format($accessory->product_sale_after) }}<span
                                                                            class="woocommerce-Price-currencySymbol">&#8363;</span></span>
                                                                    <strike class="woocommerce-Price-amount amount"
                                                                            style="color: #de8ebe;
                                                                                    font-weight: 700;
                                                                                    font-size: 18px;">
                                                                        {{ number_format($accessory->product_price) }}
                                                                        <span
                                                                            class="woocommerce-Price-currencySymbol">&#8363;</span>
                                                                    </strike>
                                                                @else
                                                                    <span
                                                                        class="woocommerce-Price-amount amount">{{ number_format($accessory->product_price) }}<span
                                                                            class="woocommerce-Price-currencySymbol">&#8363;</span></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="wpb_raw_code wpb_content_element wpb_raw_html">
                                        <div class="wpb_wrapper">
                                            <a class="shop-button bg-color arrow-right block-right"
                                               href="{{ URL::to('/all-accessory') }}">{{ __('Readmore') }}</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>


@endsection
