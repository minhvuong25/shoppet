@extends('layout')
@section('content')
<div class="container">
    <h2 class="text-center mb-2">Có tất cả {{ count($allPet) }} sản phẩm</h2>
    <div class="block-element product-item-1 product-grid-view  default js-content-wrap">
        <div class="products row list-product-wrap js-content-main">
            @foreach ($allPet as $product)
                <div
                    class="list-col-item list-4-item post-48252 product type-product status-publish has-post-thumbnail product_cat-samoyed product_cat-danh-muc-cun first instock shipping-taxable purchasable product-type-simple">
                    <div class="item-product item-product-grid">
                        <div class="product-thumb">
                            <!-- s7upf_woocommerce_thumbnail_loop have $size and $animation -->
                            <a href="{{ URL::to('/chi-tiet-san-pham/' . $product->product_slug) }}"
                                class="product-thumb-link zoom-thumb">
                                <img width="270" height="270"
                                    src="{{ URL::to('/uploads/product/' . $product->product_image) }}"
                                    class="attachment-270x270 size-270x270 wp-post-image"
                                    sizes="(max-width: 270px) 100vw, 270px"
                                    style="width:270px;height:270px">
                            </a>
                            {{-- <div class="product-label"><span
                                    class="new">new</span></div> --}}
                            <div class="product-extra-link text-center">
                                <ul class="list-product-extra-link list-inline-block">
                                    <li><a href="{{ URL::to('add-wishlist/' . $product->product_id) }}" style="display: flex;justify-content: center;align-items: center;"
                                            class="add_to_wishlist wishlist-link"
                                            data-product-title="{{ $product->product_content }}"><i
                                                class="pegk pe-7s-like"></i><span>Yêu
                                                thích</span></a></li>
                                    <li><a title="Xem nhanh"
                                            href="{{ URL::to('/chi-tiet-san-pham/' . $product->product_slug) }}" style="display: flex;justify-content: center;align-items: center;"
                                            class="product-quick-view quickview-link "><i
                                                class="pegk pe-7s-search"></i><span>Xem
                                                nhanh</span></a></li>
                                    <li></li>
                                </ul>
                                <input type="hidden" name="productid_hidden"
                                    value="{{ $product->product_id }}">
                                <form action="">
                                    @csrf
                                    <input type="hidden"
                                        value="{{ $product->product_id }}"
                                        class="cart_product_id_{{ $product->product_id }}">
                                    <input type="hidden"
                                        id="wistlist_productname{{ $product->product_id }}"
                                        value="{{ $product->product_name }}"
                                        class="cart_product_name_{{ $product->product_id }}">
                                    <input type="hidden"
                                        value="{{ $product->product_image }}"
                                        class="cart_product_image_{{ $product->product_id }}">
                                    <input type="hidden"
                                        value="{{ $product->product_quantity }}"
                                        class="cart_product_quantity_{{ $product->product_id }}">
                                    @php
                                        $product->product_sale_after = $product->product_price - ($product->product_price * $product->product_sale) / 100;
                                    @endphp
                                    <input type="hidden"
                                        id="wistlist_productprice{{ $product->product_id }}"
                                        value="{{ $product->product_sale_after }}"
                                        class="cart_product_sale_after_{{ $product->product_id }}">
                                    <input type="hidden"
                                        class="cart_product_qty_{{ $product->product_id }}"
                                        name="cart_product_quantity" min="1"
                                        oninput="validity.valid||(value='');" value="1">
                                    <input type="hidden" name="productid_hidden"
                                        value="{{ $product->product_id }}">
                                </form>
                                <?php if($product->product_quantity > 0){ ?>
                                    <a type="button" data-id_product="{{ $product->product_id }}" name="add-to-cart" class="add-to-cart button addcart-link shop-button bg-color" style="cursor: pointer"><span style="color: #fff">{{ __('AddToCart') }}</span></a>
                                <?php } else { ?>
                                    <a type="button" href="javascript:;" class="button addcart-link shop-button bg-color add_to_cart_button s7upf_ajax_add_to_cart" style="text-decoration: none"><span>{{ __('SoldOff') }}</span></a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="product-info">
                            <span class="title12 text-uppercase color font-bold">ID:
                                {{ strtoupper($product->product_code) }}</span>
                            <h3
                                class="title18 text-uppercase product-title dosis-font font-bold">
                                <a title="{{ $product->product_content }}"
                                    href="{{ URL::to('/chi-tiet-san-pham/' . $product->product_slug) }}"
                                    class="black">{{ $product->product_name }}</a>
                            </h3>
                            <div class="product-price simple">
                                @if ($product->product_sale)
                                    <span
                                        class="woocommerce-Price-amount amount">{{ number_format($product->product_sale_after) }}<span
                                            class="woocommerce-Price-currencySymbol">&#8363;</span></span>
                                    <strike class="woocommerce-Price-amount amount"
                                        style="color: #de8ebe;
                                        font-weight: 700;
                                        font-size: 18px;">
                                        {{ number_format($product->product_price) }}
                                        <span
                                            class="woocommerce-Price-currencySymbol">&#8363;</span>
                                    </strike>
                                @else
                                    <span
                                        class="woocommerce-Price-amount amount">{{ number_format($product->product_price) }}<span
                                            class="woocommerce-Price-currencySymbol">&#8363;</span></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
