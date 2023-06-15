(function($){
    "use strict";
    function s7upf_all_slider(seff,number){
        if(!seff) seff = $('.smart-slider');
        if(!number) number = '';
        //Carousel Slider
        if(seff.length>0){
            seff.each(function(){
                var seff = $(this);
                var item = seff.attr('data-item'+number);
                var speed = seff.attr('data-speed');
                var itemres = seff.attr('data-itemres'+number);
                var text_prev = seff.attr('data-prev');
                var text_next = seff.attr('data-next');
                var nav = seff.attr('data-navigation');
                var pag = seff.attr('data-pagination');
                var animation = seff.attr('data-animation');
                var autoplay;
                var pagination = false, navigation= false, singleItem = false;
                if(animation != '' && animation !== undefined){
                    singleItem = true;
                    item = '1';
                }
                if(speed === undefined) speed = '';
                if(speed != '') autoplay = speed;
                else autoplay = false;
                if(item == '' || item === undefined) item = 1;
                if(itemres === undefined) itemres = '';
                var prev_text = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
                var next_text = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
                if(text_prev) prev_text = text_prev;
                if(text_next) next_text = text_next;
                if(nav) navigation = true;
                if(pag) pagination = true;
                // Item responsive
                if(itemres == '' || itemres === undefined){
                    if(item == '1') itemres = '0:1,480:1,768:1,1200:1';
                    if(item == '2') itemres = '0:1,480:1,768:2,1200:2';
                    if(item == '3') itemres = '0:1,480:2,768:2,992:3';
                    if(item == '4') itemres = '0:1,480:2,840:3,1200:4';
                    if(item >= '5') itemres = '0:1,480:2,768:3,1200:'+item;
                }               
                itemres = itemres.split(',');
                var i;
                for (i = 0; i < itemres.length; i++) { 
                    itemres[i] =  $.map(itemres[i].split(':'), function(value){
                        return parseInt(value, 10);
                    });
                }
                seff.owlCarousel({
                    items: item,
                    itemsCustom: itemres,
                    autoPlay:autoplay,
                    pagination: pagination,
                    navigation: navigation,
                    navigationText:[prev_text,next_text],
                    addClassActive : true,
                    touchDrag: true,
                    singleItem : singleItem,
                    transitionStyle : animation,
                });
            });         
        }
    }
    function live_search(){
        var key = $(this).parents('.live-search-on').find('input[name="s"]').val();
            var trim_key = key.trim();
            var cat = $(this).parents('.live-search-on').find('.cat-value').val();
            var taxonomy = $(this).parents('.live-search-on').find('.cat-value').attr("name");
            var post_type = $(this).parents('.live-search-on').find('input[name="post_type"]').val();
            var seff = $(this);
            var content = seff.parents('.live-search-on').find('.list-product-search');
            content.html('<i class="fa fa-spinner fa-spin"></i>');
            content.addClass('ajax-loading');
            $.ajax({
                type : "post",
                url : ajax_process.ajaxurl,
                crossDomain: true,
                data: {
                    action: "live_search",
                    key: key,
                    cat: cat,
                    post_type: post_type,
                    taxonomy: taxonomy,
                },
                success: function(data){
                    content.removeClass('ajax-loading');
                    if(data[data.length-1] == '0' ){
                        data = data.split('');
                        data[data.length-1] = '';
                        data = data.join('');
                    }
                    content.html(data);
                },
                error: function(MLHttpRequest, textStatus, errorThrown){                    
                    console.log(errorThrown);  
                }
            });
    }
    function detail_gallery(){
        if($('.detail-gallery').length>0){
            $('.detail-gallery').each(function(){
                var data=$(this).find(".carousel").data();
                var seff = $(this);
                $(this).find(".carousel").jCarouselLite({
                    btnNext: $(this).find(".gallery-control .next"),
                    btnPrev: $(this).find(".gallery-control .prev"),
                    speed: 800,
                    visible:data.visible,
                    vertical:data.vertical,
                });
                //Elevate Zoom
                $(this).find('.zoom-style1 .mid img').elevateZoom();
                $(this).find('.zoom-style2 .mid img').elevateZoom({
                    scrollZoom : true
                });
                $(this).find('.zoom-style3 .mid img').elevateZoom({
                    zoomType: "lens",
                    lensShape: "square",
                    lensSize: 150,
                    borderSize:1,
                    containLensZoom:true,
                    responsive:true
                });
                $(this).find('.zoom-style4 .mid img').elevateZoom({
                    zoomType: "inner",
                    cursor: "crosshair",
                    zoomWindowFadeIn: 500,
                    zoomWindowFadeOut: 750
                });

                $(this).find(".carousel a").on('click',function(event) {
                    event.preventDefault();
                    $(this).parents('.detail-gallery').find(".carousel a").removeClass('active');
                    $(this).addClass('active');
                    var z_url =  $(this).find('img').attr("data-src");
                    var srcset =  $(this).find('img').attr("data-srcset");
                    $(this).parents('.detail-gallery').find(".mid img").attr("src", z_url);
                    $(this).parents('.detail-gallery').find(".mid img").attr("srcset", srcset);
                    $('.zoomWindow,.zoomLens').css('background-image','url("'+z_url+'")');
                    $.removeData($('.detail-gallery .mid img'), 'elevateZoom');//remove zoom instance from image
                    $('.zoomContainer').remove();
                    $(this).parents('.detail-gallery').find('.zoom-style1 .mid img').elevateZoom();
                    $(this).parents('.detail-gallery').find('.zoom-style2 .mid img').elevateZoom({
                        scrollZoom : true
                    });
                    $(this).parents('.detail-gallery').find('.zoom-style3 .mid img').elevateZoom({
                        zoomType: "lens",
                        lensShape: "square",
                        lensSize: 150,
                        borderSize:1,
                        containLensZoom:true,
                        responsive:true
                    });
                    $(this).parents('.detail-gallery').find('.zoom-style4 .mid img').elevateZoom({
                        zoomType: "inner",
                        cursor: "crosshair",
                        zoomWindowFadeIn: 500,
                        zoomWindowFadeOut: 750
                    });
                });
                $('input[name="variation_id"]').on('change',function(){
                    var z_url =  seff.find('.mid img').attr("src");
                    $('.zoomWindow,.zoomLens').css('background-image','url("'+z_url+'")');
                })
            });
        }
    }
    //Shop Filter
    function get_shop_filter(seff){
        // Set default value
        var filter = {};
        filter['price'] = {};
        filter['cats'] = [];
        filter['attributes'] = {};
        filter['hover_animation'] = '';
        var terms = [];

        // 7up get filter price
        var min_price = $('#min_price').attr('data-min');
        var max_price = $('#max_price').attr('data-max');
        if($('.slider-range').length > 0){
            min_price = $('.slider-range').attr('data-min');
            max_price = $('.slider-range').attr('data-max');
        }
        filter['min_price'] = min_price;
        filter['max_price'] = max_price;

        // set active class
        $('.show-by .load-shop-ajax,.dropdown-box .load-shop-ajax').removeClass('active');
        seff.toggleClass('active');

        //Get page pagination
        if(seff.parents('.pagi-nav').hasClass('pagi-nav')){
            seff.parents('.pagi-nav').find('.page-numbers').not(seff).removeClass('current');
            seff.parents('.pagi-nav').find('.page-numbers').not(seff).removeClass('active');
            seff.addClass('current');
            seff.addClass('active');
        }
        else{
            $('.page-numbers').removeClass('current');
            $('.page-numbers').removeClass('active');
            $('.pagi-nav').find('.page-numbers').first().addClass('current active');
        }
        //Get style grid/list
        if(seff.attr('data-type')) seff.parents('.view-type').find('a.load-shop-ajax').not(seff).removeClass('active');        
        
        //Woocommerce price filter
        if($('.price_label .from')) filter['price']['min'] = $('#min_price').val();
        if($('.price_label .to')) filter['price']['max'] = $('#max_price').val();
        if($('.range-filter').length > 0){
            filter['price']['min'] = $('.range-filter .price-min-filter').val();
            filter['price']['max'] = $('.range-filter .price-max-filter').val();
        }

        //Woocommerce ordering filter
        if($('.woocommerce-ordering')) filter['orderby'] = $('select[name="orderby"]').val();
        
        //Get page pagination click
        if(seff.hasClass('page-numbers')){
            if(seff.parent().find('.page-numbers.current')) filter['page'] = seff.parent().find('.page-numbers.current').html();
        }
        else{
            if($('.page-numbers.current')) filter['page'] = $('.page-numbers.current').html();
        }

        //Get more data
        var data_element = $('.products-wrap.js-content-wrap').attr('data-load');
        data_element = $.parseJSON( data_element );
        filter = $.extend( filter, data_element['attr'] );
        if(!filter['cats']) filter['cats'] = [];

        //Get current filter active
        var i = 1;
        $('.load-shop-ajax.active').each(function(){
            var seff2 = $(this);
            if(seff2.attr('data-type')){
                if(i == 1) filter['style'] = seff2.attr('data-type');
                i++;
            }
            if(seff2.attr('data-number')){
                seff2.parents('.dropdown-box').find('.number').html(seff2.attr('data-number'));
                filter['number'] = seff2.attr('data-number');
            }
            if(seff2.attr('data-attribute') && seff2.attr('data-term')){
                if(!filter['attributes'][seff2.attr('data-attribute')]) filter['attributes'][seff2.attr('data-attribute')] = [];
                if($.inArray(seff2.attr('data-term'),filter['attributes'][seff2.attr('data-attribute')])) filter['attributes'][seff2.attr('data-attribute')].push(seff2.attr('data-term'));
            }
            if(seff2.attr('data-cat') && $.inArray(seff2.attr('data-cat'),filter['cats'])) filter['cats'].push(seff2.attr('data-cat'));
        })

        //Get variable current url
        var $_GET = {};
        if(document.location.toString().indexOf('?') !== -1) {
            var query = document.location
                           .toString()
                           // get the query string
                           .replace(/^.*?\?/, '')
                           // and remove any existing hash string (thanks, @vrijdenker)
                           .replace(/#.*$/, '')
                           .split('&');

            for(var i=0, l=query.length; i<l; i++) {
               var aux = decodeURIComponent(query[i]).split('=');
               $_GET[aux[0]] = aux[1];
            }
        }
        if($_GET['s']) filter['s'] = $_GET['s'];
        if($_GET['product_cat']) filter['cats'] = $_GET['product_cat'].split(',');
        return filter;
    }
    function load_ajax_shop(e){
        e.preventDefault();
        var filter = get_shop_filter($(this));
        var content = $('.js-content-wrap');
        content.addClass('loadding');
        content.append('<div class="shop-loading"><i class="fa fa-spinner fa-spin"></i></div>');
        $.ajax({
            type : "post",
            url : ajax_process.ajaxurl,
            crossDomain: true,
            data: {
                action: "load_shop",
                filter_data: filter,
            },
            success: function(data){
                if(data[data.length-1] == '0' ){
                    data = data.split('');
                    data[data.length-1] = '';
                    data = data.join('');
                }
                content.find(".shop-loading").remove();
                content.removeClass('loadding');
                content.html(data);
            },
            error: function(MLHttpRequest, textStatus, errorThrown){                    
                console.log(errorThrown);  
            }
        });
        setTimeout(function() {
            if($('.product-grid-view.list-masonry .list-product-wrap').length>0){
                $('.product-grid-view.list-masonry .list-product-wrap').masonry();
            }
        }, 3000);        
        return false;
    }

    $(document).ready(function() {
        // Wishlist ajax
        $('.wishlist-close').on('click',function(){
            $('.wishlist-mask').fadeOut();
        })
        $('.add_to_wishlist').live('click',function(){
            $('.wishlist-countdown').html('3');
            $(this).addClass('added');
            var product_id = $(this).attr("data-product-id");
            var product_title = $(this).attr("data-product-title");
            $('.wishlist-title').html(product_title);
            $('.wishlist-mask').fadeIn();
            var counter = 3;
            var popup;
            popup = setInterval(function() {
                counter--;
                if(counter < 0) {
                    clearInterval(popup);
                    $('.wishlist-mask').hide();
                } else {
                    $(".wishlist-countdown").text(counter.toString());
                }
            }, 1000);
        })
        
        // Shop ajax
        $('.shop-ajax-enable').on('click','.load-shop-ajax,.page-numbers,.price_slider_amount .button,.price-amount .button,.btn-filter',load_ajax_shop);
        $('.shop-ajax-enable').on('change','select[name="orderby"]',load_ajax_shop);
        $( '.shop-ajax-enable .woocommerce-ordering' ).on( 'submit', function(e) {
            e.preventDefault();
        });
        $('.shop-ajax-enable .range-filter form').on( 'submit', function(e) {
            e.preventDefault();
        });        

        //Live search
        $('.live-search-on input[name="s"]').on('click',function(event){
            event.preventDefault();
            event.stopPropagation();
            $(this).parents('.live-search-on').addClass('active');
        })
        $('body').on('click',function(event){
            $('.live-search-on.active').removeClass('active');
        })
        $('.current-search-cat').on('hover click',function(event){
            $('.live-search-on.active').removeClass('active');
        })
        $('.live-search-on .select-cat-search').on('click',live_search);
        $('.live-search-on input[name="s"]').on('keyup',live_search);

        /// Woocommerce Ajax
        $("body").on("click",".add_to_cart_button:not(.product_type_variable)",function(e){
            e.preventDefault();
            var product_id = $(this).attr("data-product_id");
            var seff = $(this);
            seff.find('i').remove();
            seff.append('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                type : "post",
                url : ajax_process.ajaxurl,
                crossDomain: true,
                data: {
                    action: "add_to_cart",
                    product_id: product_id
                },
                success: function(data){
                    seff.find('.fa-spinner').remove();
					seff.addClass('added');  
                    var cart_content = data.fragments['div.widget_shopping_cart_content'];
                    $('.mini-cart-main-content').html(cart_content);
                    $('.widget_shopping_cart_content').html(cart_content);
                    var count_item = cart_content.split("item-info-cart").length;
                    $('.set-cart-number').html(count_item-1);
                    var price = $('.mini-cart-main-content').find('.get-cart-price').html();
                    if(price) $('.set-cart-price').html(price);
                    else $('.set-cart-price').html($('.total-default').html());
                },
                error: function(MLHttpRequest, textStatus, errorThrown){                    
                    console.log(errorThrown);  
                }
            });
        });
		//Update cart
        $('.button[name="update_cart"]').live('click', function(e){   
            $( document ).ajaxComplete(function( event, xhr, settings ) {
                if(settings.url.indexOf('?wc-ajax=get_refreshed_fragments') != -1){
                    $.ajax({
                        type: 'POST',
                        url: ajax_process.ajaxurl,                
                        crossDomain: true,
                        data: { 
                            action: 'update_mini_cart',
                        },
                        success: function(data){
                            // Load html
                            var cart_content = data.fragments['div.widget_shopping_cart_content'];
                            $('.mini-cart-main-content').html(cart_content);
                            $('.widget_shopping_cart_content').html(cart_content);
                            // set count
                            var count_item = cart_content.split("item-info-cart").length;
                            $('.set-cart-number').html(count_item-1);
                            // set Price
                            var price = $('.mini-cart-main-content').find('.get-cart-price').html();
                            console.log(price);
                            if(price) $('.set-cart-price').html(price);
                            else $('.set-cart-price').html($('.total-default').html());
                        },
                        error: function(MLHttpRequest, textStatus, errorThrown){  
                            console.log(errorThrown);  
                        }
                    });
                }
            });        
            
        });
        $('body').on('click', '.remove-product', function(e){
            e.preventDefault();
            var cart_item_key = $(this).parents('.item-info-cart').attr("data-key");
            $(this).parents('.item-info-cart').addClass('hidden');
            $.ajax({
                type: 'POST',
                url: ajax_process.ajaxurl,                
                crossDomain: true,
                data: { 
                    action: 'product_remove',
                    cart_item_key: cart_item_key
                },
                success: function(data){
                    // Load html
                    var cart_content = data.fragments['div.widget_shopping_cart_content'];
                    $('.mini-cart-main-content').html(cart_content);
                    $('.widget_shopping_cart_content').html(cart_content);
                    // set count
                    var count_item = cart_content.split("item-info-cart").length;
                    $('.set-cart-number').html(count_item-1);
                    // set Price
                    var price = $('.mini-cart-main-content').find('.get-cart-price').html();
                    if(price) $('.set-cart-price').html(price);
                    else $('.set-cart-price').html($('.total-default').html());
                },
                error: function(MLHttpRequest, textStatus, errorThrown){  
                    console.log(errorThrown);  
                }
            });
            return false;
        });
        $('body').on('click','.product-quick-view', function(e){
            e.preventDefault;
            $.fancybox.showLoading();
            var product_id = $(this).attr('data-product-id');
            $.ajax({
                type: 'POST',
                url: ajax_process.ajaxurl,                
                crossDomain: true,
                data: { 
                    action: 'product_popup_content',
                    product_id: product_id
                },
                success: function(res){
                    if(res[res.length-1] == '0' ){
                        res = res.split('');
                        res[res.length-1] = '';
                        res = res.join('');
                    }
                    $.fancybox.hideLoading();
                    $.fancybox(res, {
                        width: 1000,
                        height: 500,
                        autoSize: false,
                        onStart: function(opener) {                            
                            if ($(opener).attr('id') == 'login') {
                                $.get('/hicommon/authenticated', function(res) { 
                                    if ('yes' == res) {
                                      console.log('this user must have already authenticated in another browser tab, SO I want to avoid opening the fancybox.');
                                      return false;
                                    } else {
                                      console.log('the user is not authenticated');
                                      return true;
                                    }
                                }); 
                            }
                        },
                    });
                    /*!
 * Variations Plugin
 */
!function(t,a,i,r){var e=function(t){this.$form=t,this.$attributeFields=t.find(".variations select"),this.$singleVariation=t.find(".single_variation"),this.$singleVariationWrap=t.find(".single_variation_wrap"),this.$resetVariations=t.find(".reset_variations"),this.$product=t.closest(".product"),this.variationData=t.data("product_variations"),this.useAjax=!1===this.variationData,this.xhr=!1,this.$singleVariationWrap.show(),this.$form.off(".wc-variation-form"),this.getChosenAttributes=this.getChosenAttributes.bind(this),this.findMatchingVariations=this.findMatchingVariations.bind(this),this.isMatch=this.isMatch.bind(this),this.toggleResetLink=this.toggleResetLink.bind(this),t.on("click.wc-variation-form",".reset_variations",{variationForm:this},this.onReset),t.on("reload_product_variations",{variationForm:this},this.onReload),t.on("hide_variation",{variationForm:this},this.onHide),t.on("show_variation",{variationForm:this},this.onShow),t.on("click",".single_add_to_cart_button",{variationForm:this},this.onAddToCart),t.on("reset_data",{variationForm:this},this.onResetDisplayedVariation),t.on("reset_image",{variationForm:this},this.onResetImage),t.on("change.wc-variation-form",".variations select",{variationForm:this},this.onChange),t.on("found_variation.wc-variation-form",{variationForm:this},this.onFoundVariation),t.on("check_variations.wc-variation-form",{variationForm:this},this.onFindVariation),t.on("update_variation_values.wc-variation-form",{variationForm:this},this.onUpdateAttributes),setTimeout(function(){t.trigger("check_variations"),t.trigger("wc_variation_form")},100)};e.prototype.onReset=function(t){t.preventDefault(),t.data.variationForm.$attributeFields.val("").change(),t.data.variationForm.$form.trigger("reset_data")},e.prototype.onReload=function(t){var a=t.data.variationForm;a.variationData=a.$form.data("product_variations"),a.useAjax=!1===a.variationData,a.$form.trigger("check_variations")},e.prototype.onHide=function(t){t.preventDefault(),t.data.variationForm.$form.find(".single_add_to_cart_button").removeClass("wc-variation-is-unavailable").addClass("disabled wc-variation-selection-needed"),t.data.variationForm.$form.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-enabled").addClass("woocommerce-variation-add-to-cart-disabled")},e.prototype.onShow=function(t,a,i){t.preventDefault(),i?(t.data.variationForm.$form.find(".single_add_to_cart_button").removeClass("disabled wc-variation-selection-needed wc-variation-is-unavailable"),t.data.variationForm.$form.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-disabled").addClass("woocommerce-variation-add-to-cart-enabled")):(t.data.variationForm.$form.find(".single_add_to_cart_button").removeClass("wc-variation-selection-needed").addClass("disabled wc-variation-is-unavailable"),t.data.variationForm.$form.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-enabled").addClass("woocommerce-variation-add-to-cart-disabled"))},e.prototype.onAddToCart=function(i){t(this).is(".disabled")&&(i.preventDefault(),t(this).is(".wc-variation-is-unavailable")?a.alert(wc_add_to_cart_variation_params.i18n_unavailable_text):t(this).is(".wc-variation-selection-needed")&&a.alert(wc_add_to_cart_variation_params.i18n_make_a_selection_text))},e.prototype.onResetDisplayedVariation=function(t){var a=t.data.variationForm;a.$product.find(".product_meta").find(".sku").wc_reset_content(),a.$product.find(".product_weight").wc_reset_content(),a.$product.find(".product_dimensions").wc_reset_content(),a.$form.trigger("reset_image"),a.$singleVariation.slideUp(200).trigger("hide_variation")},e.prototype.onResetImage=function(t){t.data.variationForm.$form.wc_variations_image_update(!1)},e.prototype.onFindVariation=function(a){var i=a.data.variationForm,r=i.getChosenAttributes(),e=r.data;if(r.count===r.chosenCount)if(i.useAjax)i.xhr&&i.xhr.abort(),i.$form.block({message:null,overlayCSS:{background:"#fff",opacity:.6}}),e.product_id=parseInt(i.$form.data("product_id"),10),e.custom_data=i.$form.data("custom_data"),i.xhr=t.ajax({url:wc_add_to_cart_variation_params.wc_ajax_url.toString().replace("%%endpoint%%","get_variation"),type:"POST",data:e,success:function(t){t?i.$form.trigger("found_variation",[t]):(i.$form.trigger("reset_data"),i.$form.find(".single_variation").after('<p class="wc-no-matching-variations woocommerce-info">'+wc_add_to_cart_variation_params.i18n_no_matching_variations_text+"</p>"),i.$form.find(".wc-no-matching-variations").slideDown(200))},complete:function(){i.$form.unblock()}});else{i.$form.trigger("update_variation_values");var o=i.findMatchingVariations(i.variationData,e).shift();o?i.$form.trigger("found_variation",[o]):(i.$form.trigger("reset_data"),i.$form.find(".single_variation").after('<p class="wc-no-matching-variations woocommerce-info">'+wc_add_to_cart_variation_params.i18n_no_matching_variations_text+"</p>"),i.$form.find(".wc-no-matching-variations").slideDown(200))}else i.$form.trigger("update_variation_values"),i.$form.trigger("reset_data");i.toggleResetLink(r.chosenCount>0)},e.prototype.onFoundVariation=function(a,i){var r=a.data.variationForm,e=r.$product.find(".product_meta").find(".sku"),o=r.$product.find(".product_weight"),n=r.$product.find(".product_dimensions"),s=r.$singleVariationWrap.find(".quantity"),_=!0,c=!1,d="";i.sku?e.wc_set_content(i.sku):e.wc_reset_content(),i.weight?o.wc_set_content(i.weight_html):o.wc_reset_content(),i.dimensions?n.wc_set_content(i.dimensions_html):n.wc_reset_content(),r.$form.wc_variations_image_update(i),i.variation_is_visible?(c=wp.template("variation-template"),i.variation_id):c=wp.template("unavailable-variation-template"),d=(d=(d=c({variation:i})).replace("/*<![CDATA[*/","")).replace("/*]]>*/",""),r.$singleVariation.html(d),r.$form.find('input[name="variation_id"], input.variation_id').val(i.variation_id).change(),"yes"===i.is_sold_individually?(s.find("input.qty").val("1").attr("min","1").attr("max",""),s.hide()):(s.find("input.qty").attr("min",i.min_qty).attr("max",i.max_qty),s.show()),i.is_purchasable&&i.is_in_stock&&i.variation_is_visible||(_=!1),t.trim(r.$singleVariation.text())?r.$singleVariation.slideDown(200).trigger("show_variation",[i,_]):r.$singleVariation.show().trigger("show_variation",[i,_])},e.prototype.onChange=function(a){var i=a.data.variationForm;i.$form.find('input[name="variation_id"], input.variation_id').val("").change(),i.$form.find(".wc-no-matching-variations").remove(),i.useAjax?i.$form.trigger("check_variations"):(i.$form.trigger("woocommerce_variation_select_change"),i.$form.trigger("check_variations"),t(this).blur()),i.$form.trigger("woocommerce_variation_has_changed")},e.prototype.addSlashes=function(t){return t=t.replace(/'/g,"\\'"),t=t.replace(/"/g,'\\"')},e.prototype.onUpdateAttributes=function(a){var i=a.data.variationForm,r=i.getChosenAttributes().data;i.useAjax||(i.$attributeFields.each(function(a,e){var o=t(e),n=o.data("attribute_name")||o.attr("name"),s=t(e).data("show_option_none"),_=":gt(0)",c=0,d=t("<select/>"),m=o.val()||"",v=!0;if(!o.data("attribute_html")){var l=o.clone();l.find("option").removeAttr("disabled attached").removeAttr("selected"),o.data("attribute_options",l.find("option"+_).get()),o.data("attribute_html",l.html())}d.html(o.data("attribute_html"));var h=t.extend(!0,{},r);h[n]="";var g=i.findMatchingVariations(i.variationData,h);for(var f in g)if("undefined"!=typeof g[f]){var u=g[f].attributes;for(var p in u)if(u.hasOwnProperty(p)){var w=u[p],b="";p===n&&(g[f].variation_is_active&&(b="enabled"),w?(w=t("<div/>").html(w).text(),d.find('option[value="'+i.addSlashes(w)+'"]').addClass("attached "+b)):d.find("option:gt(0)").addClass("attached "+b))}}c=d.find("option.attached").length,!m||0!==c&&0!==d.find('option.attached.enabled[value="'+i.addSlashes(m)+'"]').length||(v=!1),c>0&&m&&v&&"no"===s&&(d.find("option:first").remove(),_=""),d.find("option"+_+":not(.attached)").remove(),o.html(d.html()),o.find("option"+_+":not(.enabled)").prop("disabled",!0),m?v?o.val(m):o.val("").change():o.val("")}),i.$form.trigger("woocommerce_update_variation_values"))},e.prototype.getChosenAttributes=function(){var a={},i=0,r=0;return this.$attributeFields.each(function(){var e=t(this).data("attribute_name")||t(this).attr("name"),o=t(this).val()||"";o.length>0&&r++,i++,a[e]=o}),{count:i,chosenCount:r,data:a}},e.prototype.findMatchingVariations=function(t,a){for(var i=[],r=0;r<t.length;r++){var e=t[r];this.isMatch(e.attributes,a)&&i.push(e)}return i},e.prototype.isMatch=function(t,a){var i=!0;for(var r in t)if(t.hasOwnProperty(r)){var e=t[r],o=a[r];void 0!==e&&void 0!==o&&0!==e.length&&0!==o.length&&e!==o&&(i=!1)}return i},e.prototype.toggleResetLink=function(t){t?"hidden"===this.$resetVariations.css("visibility")&&this.$resetVariations.css("visibility","visible").hide().fadeIn():this.$resetVariations.css("visibility","hidden")},t.fn.wc_variation_form=function(){return new e(this),this},t.fn.wc_set_content=function(t){void 0===this.attr("data-o_content")&&this.attr("data-o_content",this.text()),this.text(t)},t.fn.wc_reset_content=function(){void 0!==this.attr("data-o_content")&&this.text(this.attr("data-o_content"))},t.fn.wc_set_variation_attr=function(t,a){void 0===this.attr("data-o_"+t)&&this.attr("data-o_"+t,this.attr(t)?this.attr(t):""),!1===a?this.removeAttr(t):this.attr(t,a)},t.fn.wc_reset_variation_attr=function(t){void 0!==this.attr("data-o_"+t)&&this.attr(t,this.attr("data-o_"+t))},t.fn.wc_maybe_trigger_slide_position_reset=function(a){var i=t(this),r=i.closest(".product").find(".images"),e=!1,o=a&&a.image_id?a.image_id:"";i.attr("current-image")!==o&&(e=!0),i.attr("current-image",o),e&&r.trigger("woocommerce_gallery_reset_slide_position")},t.fn.wc_variations_image_update=function(i){var r=this,e=r.closest(".product"),o=e.find(".images"),n=e.find(".flex-control-nav li:eq(0) img"),s=o.find(".woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder").eq(0),_=s.find(".wp-post-image"),c=s.find("a").eq(0);if(i&&i.image&&i.image.src&&i.image.src.length>1){if(t('.flex-control-nav li img[src="'+i.image.thumb_src+'"]').length>0)return t('.flex-control-nav li img[src="'+i.image.thumb_src+'"]').trigger("click"),void r.attr("current-image",i.image_id);_.wc_set_variation_attr("src",i.image.src),_.wc_set_variation_attr("height",i.image.src_h),_.wc_set_variation_attr("width",i.image.src_w),_.wc_set_variation_attr("srcset",i.image.srcset),_.wc_set_variation_attr("sizes",i.image.sizes),_.wc_set_variation_attr("title",i.image.title),_.wc_set_variation_attr("alt",i.image.alt),_.wc_set_variation_attr("data-src",i.image.full_src),_.wc_set_variation_attr("data-large_image",i.image.full_src),_.wc_set_variation_attr("data-large_image_width",i.image.full_src_w),_.wc_set_variation_attr("data-large_image_height",i.image.full_src_h),s.wc_set_variation_attr("data-thumb",i.image.src),n.wc_set_variation_attr("src",i.image.thumb_src),c.wc_set_variation_attr("href",i.image.full_src)}else _.wc_reset_variation_attr("src"),_.wc_reset_variation_attr("width"),_.wc_reset_variation_attr("height"),_.wc_reset_variation_attr("srcset"),_.wc_reset_variation_attr("sizes"),_.wc_reset_variation_attr("title"),_.wc_reset_variation_attr("alt"),_.wc_reset_variation_attr("data-src"),_.wc_reset_variation_attr("data-large_image"),_.wc_reset_variation_attr("data-large_image_width"),_.wc_reset_variation_attr("data-large_image_height"),s.wc_reset_variation_attr("data-thumb"),n.wc_reset_variation_attr("src"),c.wc_reset_variation_attr("href");a.setTimeout(function(){t(a).trigger("resize"),r.wc_maybe_trigger_slide_position_reset(i),o.trigger("woocommerce_gallery_init_zoom")},20)},t(function(){"undefined"!=typeof wc_add_to_cart_variation_params&&t(".variations_form").each(function(){t(this).wc_variation_form()})})}(jQuery,window,document);
                    
                    // variable
                    $.fn.tawcvs_variation_swatches_form = function () {
                        return this.each( function() {
                            var $form = $( this ),
                                clicked = null,
                                selected = [];

                            $form
                                .addClass( 'swatches-support' )
                                .on( 'click', '.swatch', function ( e ) {
                                    e.preventDefault();
                                    var $el = $( this ),
                                        $select = $el.closest( '.value' ).find( 'select' ),
                                        attribute_name = $select.data( 'attribute_name' ) || $select.attr( 'name' ),
                                        value = $el.data( 'value' );

                                    $select.trigger( 'focusin' );

                                    // Check if this combination is available
                                    if ( ! $select.find( 'option[value="' + value + '"]' ).length ) {
                                        $el.siblings( '.swatch' ).removeClass( 'selected' );
                                        $select.val( '' ).change();
                                        $form.trigger( 'tawcvs_no_matching_variations', [$el] );
                                        return;
                                    }

                                    clicked = attribute_name;

                                    if ( selected.indexOf( attribute_name ) === -1 ) {
                                        selected.push(attribute_name);
                                    }

                                    if ( $el.hasClass( 'selected' ) ) {
                                        $select.val( '' );
                                        $el.removeClass( 'selected' );

                                        delete selected[selected.indexOf(attribute_name)];
                                    } else {
                                        $el.addClass( 'selected' ).siblings( '.selected' ).removeClass( 'selected' );
                                        $select.val( value );
                                    }

                                    $select.change();
                                } )
                                .on( 'click', '.reset_variations', function () {
                                    $( this ).closest( '.variations_form' ).find( '.swatch.selected' ).removeClass( 'selected' );
                                    selected = [];
                                } )
                                .on( 'tawcvs_no_matching_variations', function() {
                                    window.alert( wc_add_to_cart_variation_params.i18n_no_matching_variations_text );
                                } );
                        } );
                    };

                    $( function () {
                        $( '.variations_form' ).tawcvs_variation_swatches_form();
                        $( document.body ).trigger( 'tawcvs_initialized' );
                    } );
                    detail_gallery();  
                },
                error: function(MLHttpRequest, textStatus, errorThrown){  
                    console.log(errorThrown);  
                }
            });        
            return false;
        })

        // end        

        // Load more blog
        $('body').on('click', '.blog-loadmore', function(e){
            e.preventDefault();
            $(this).append('<i class="fa fa-spinner fa-spin"></i>');
            var current     = $(this).parents('.js-content-wrap');
            var seff        = $(this);
            var content     = current.find('.js-content-main');
            var load_data   = seff.attr('data-load');
            var maxpage     = seff.attr('data-maxpage');
            var paged       = seff.attr('data-paged');
            $.ajax({
                type: 'POST',
                url: ajax_process.ajaxurl,
                crossDomain: true,
                data: { 
                    action: 'load_more_post',
                    load_data: load_data,
                    paged: paged,
                },
                success: function(data){
                    if($('.blog-grid-view.list-masonry .list-post-wrap').length>0){
                        var $newItem = $(data);
                        content.append($newItem).masonry( 'appended', $newItem, true );
                        content.imagesLoaded( function() {
                            content.masonry('layout');
                        });
                    }
                    else content.append(data);
                    paged = Number(paged) + 1;
                    seff.attr('data-paged',paged);
                    seff.find('.fa-spinner').remove();
                    if(Number(paged)>=Number(maxpage)){
                        seff.parent().fadeOut();
                    }
                    
                },
                error: function(MLHttpRequest, textStatus, errorThrown){  
                    console.log(errorThrown);  
                }
            });
            return false;
        });
        //end

        // update share
        $('.single-list-social li a').on('click',function(e){
            var social = $(this).attr('data-social');
            var seff = $(this).find('.number');
            var id = $(this).parents('.single-list-social').attr('data-id');
            var seff_tt = $(this).parents('.single-list-social').find('.total-share .number');
            var number = Number(seff.html());
            var total = Number(seff_tt.html());
            $.ajax({
                type: 'POST',
                url: ajax_process.ajaxurl,
                crossDomain: true,
                data: { 
                    action: 'update_share',
                    social: social,
                    id: id,
                },
                success: function(data){
                    console.log(data);
                    seff.html(number+1);
                    seff_tt.html(total+1);
                },
                error: function(MLHttpRequest, textStatus, errorThrown){  
                    console.log(errorThrown);  
                }
            });
        });
        //end

        // Load more product
        $('body').on('click', '.product-loadmore', function(e){
            e.preventDefault();
            $(this).append('<i class="fa fa-spinner fa-spin"></i>');
            var current     = $(this).parents('.js-content-wrap');
            var seff        = $(this);
            var content     = current.find('.js-content-main');
            var load_data   = seff.attr('data-load');
            var maxpage     = seff.attr('data-maxpage');
            var paged       = seff.attr('data-paged');
            console.log(ajax_process.ajaxurl);
            $.ajax({
                type: 'POST',
                url: ajax_process.ajaxurl,
                crossDomain: true,
                data: { 
                    action: 'load_more_product',
                    load_data: load_data,
                    paged: paged,
                },
                success: function(data){
                    if($('.product-grid-view.list-masonry .list-product-wrap').length>0){
                        var $newItem = $(data);
                        content.append($newItem).masonry( 'appended', $newItem, true );
                        content.imagesLoaded( function() {
                            content.masonry('layout');
                        });
                    }
                    else content.append(data);
                    paged = Number(paged) + 1;
                    seff.attr('data-paged',paged);
                    seff.find('.fa-spinner').remove();
                    if(Number(paged)>=Number(maxpage)){
                        seff.parent().fadeOut();
                    }
                    
                },
                error: function(MLHttpRequest, textStatus, errorThrown){  
                    console.log(errorThrown);  
                }
            });
            return false;
        });
        //end
        // Load tab content
        $('.tab-ajax-on .title-tab a').on('click', function(e){
            e.preventDefault();
            $(this).parents('ul').find('li').removeClass('active');
            $(this).parent().addClass('active');
            var current     = $(this).parents('.tabs-block');
            var seff        = $(this);
            var content     = current.find('.tab-content');
            var tab_content   = seff.find('.get-content-tab').html();
            content.addClass('loadding');
            content.append('<div class="ajax-loading"><i class="fa fa-spinner fa-spin"></i></div>');
            $.ajax({
                type: 'POST',
                url: ajax_process.ajaxurl,
                crossDomain: true,
                data: { 
                    action: 'load_tab_content',
                    tab_content: tab_content,
                },
                success: function(data){
                    content.removeClass('ajax-loading');
                    content.html(data);
                    s7upf_all_slider(content.find('.smart-slider'));
                    if(content.find('.list-masonry').length>0){
                        content.find('.list-masonry').find('.js-content-main').masonry();
                    }
                },
                error: function(MLHttpRequest, textStatus, errorThrown){  
                    console.log(errorThrown);  
                }
            });
            return false;
        });
        //end

        // tab fix load masonry
        $('a[data-toggle="tab"]').live('click',function(){
            var content = $(this).parents('.tabs-block').find('.list-masonry .list-product-wrap,.list-masonry .list-post-wrap');
            content.imagesLoaded( function() {
                content.masonry('layout');
            });
        })

        //Set section
        $('body').on("click","#close-newsletter",function(){
            var checked = $(this).is(':checked');
            $.ajax({
                type : "post",
                url : ajax_process.ajaxurl,
                crossDomain: true,
                data: {
                    action: "set_dont_show",
                    checked: checked
                },
                success: function(data){
                    console.log(data);
                },
                error: function(MLHttpRequest, textStatus, errorThrown){                    
                    console.log(errorThrown);  
                }
            });
        })
        //end
		// register ajax
        $('.popup-form form').on('submit', function(e){
            e.preventDefault();
            var seff = $(this);
            var url = seff.attr('action');
            var type = seff.attr('method');
            $('.ms-default').fadeOut();
            seff.parents('.popup-form').find('.form-header .message:not(.ms-default),#login_error:not(.ms-default)').remove();
            var submit = $(this).find('input[type="submit"]').parent();
            submit.find('i').remove();
            submit.append('<i class="fa fa-spinner fa-spin"></i>');
            var user_data = $( this ).serializeArray();
            var count = 0;
            var link_redirect = $(this).find('input[name="redirect_to1"]').val();
            $.each(user_data, function(i,n) {
                if(n.value == ''){
                    count++;
                    seff.parents('.popup-form').find('input[name="'+n.name+'"]').parent().addClass('invalid');
                }
                if(n.name.includes("email")){
                    if(!n.value.includes("@")){
                        count++;
                        seff.parents('.popup-form').find('input[name="'+n.name+'"]').parent().addClass('invalid');
                        seff.parents('.popup-form').find('.ms-default').fadeIn();
                    }
                }
            });
            if(count > 0){
                submit.find('i').remove();
                return;
            }
            $.ajax({
                type: type,
                url: url,                
                crossDomain: true,
                data: user_data,
                success: function(data){
                    console.log(data);
                    var error_html = $(data).find('#login_error').html();
                    if(error_html) seff.parents('.popup-form').find('.form-header').append('<div id="login_error">'+error_html+'</div>');
                    else{
                        if(seff.attr('name') == 'registerform'){
                            $('.login-link').trigger('click');
                            seff.parents('.popup-form').find('.ms-done').fadeIn();
                        }
                        if(seff.attr('name') == 'loginform') window.location = link_redirect;
                        if(seff.attr('name') == 'lostpasswordform'){
                            seff.parents('.popup-form').find('.ms-done').fadeIn();
                        }
                    }
                    $('#login_error a').on('click',function(e){
                        e.preventDefault();
                        $('.lostpass-link').trigger('click');

                    })
                    submit.find('i').remove();
                },
                error: function(MLHttpRequest, textStatus, errorThrown){ 
                    submit.find('i').remove();
                    seff.parents('.popup-form').find('.ms-error').fadeIn(); 
                    console.log(errorThrown);  
                }
            });
        });
        //end
    });

})(jQuery);