<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\VnpayController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryProduct;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryMxhController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DanhmucController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MailAdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SocialController;
use Illuminate\Routing\RouteUri;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Laravel\Socialite\Facades\Socialite ;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::resource('/', function () {
//     return view('layout');
// });
// Route::get('/trang-chu', function () {
//     return view('layout');
// });
//Frontend
Route::resource('trang-chu', HomeController::class);
Route::resource('/', HomeController::class);

Route::get('home', [HomeController::class,'index'])->name('Home');
//Search
Route::get('/timkiem', [HomeController::class, 'timkiem']);
Route::get('/tag/{product_tags}', [ProductController::class, 'tag']);
Route::post('/autocomplete-ajax', [HomeController::class, 'autocomplete_ajax']);

//Danh muc
//Route::get('/danh-muc-san-pham/{category_id}', [CategoryProduct::class, 'show_category_home']);
Route::get('/chi-tiet-san-pham/{product_slug}', [ProductController::class, 'details_product']);

//send mail
Route::get('/send-mail', [MailAdminController::class, 'send_mail']);
//
Route::post('/quickview', [ProductController::class, 'quickview']);
//Comment
Route::post('/load-comment', [ProductController::class, 'load_comment']);
Route::post('/send-comment', [ProductController::class, 'send_comment']);
Route::get('/list-comment', [ProductController::class, 'list_comment'])->name('list-comment');
Route::get('/delete-comment/{comment_id}', [ProductController::class, 'delete_comment']);
Route::post('/reply-comment', [ProductController::class, 'reply_comment']);
Route::post('/allow-comment', [ProductController::class, 'allow_comment']);
//Rating
Route::post('/insert-rating', [ProductController::class, 'insert_rating']);
// Route::get('/language/{language}', [LanguageController::class, 'language.dashboard']);
//Backend
Route::resource('/admin', AdminController::class);
Route::get('/dashboard', [AdminController::class, 'showdashboard']);
Route::get('/thongke', [AdminController::class, 'thongke'])->name('thongke');
Route::post('/admin-dashboard', [AdminController::class, 'dashboard']);
Route::get('/logout', [AdminController::class, 'logout']);
Route::get('/language/{language}', [LanguageController::class, 'language']);
Route::post('/postcontact', [RegisterController::class, 'postcontact']);
//Category Product
Route::get('/add-category', [CategoryProduct::class, 'add_category']);
Route::get('/edit-category/{category_id}', [CategoryProduct::class, 'edit_category']);
Route::get('/delete-category/{category_id}', [CategoryProduct::class, 'delete_category']);
Route::get('/all-category', [CategoryProduct::class, 'all_category'])->name('all-category');
Route::get('/unactive-category/{category_id}', [CategoryProduct::class, 'unactive_category']);
Route::get('/active-category/{category_id}', [CategoryProduct::class, 'active_category']);
Route::post('/save-category', [CategoryProduct::class, 'save_category']);
Route::post('/update-category/{category_id}', [CategoryProduct::class, 'update_category']);
Route::get('/product-by-category/{category_id}', [HomeController::class, 'product_by_category']);
//Export
Route::post('/export-csv', [CategoryProduct::class, 'export_csv']);
Route::post('/export-product', [ProductController::class, 'export_product']);
//Import
Route::post('/import-csv', [CategoryProduct::class, 'import_csv']);
Route::post('/import-product', [ProductController::class, 'import_product']);
Route::post('/import-word', [ProductController::class, 'import_word']);
//Product
Route::get('/add-product', [ProductController::class, 'add_product']);
Route::get('/edit-product/{product_id}', [ProductController::class, 'edit_product']);
Route::get('/delete-product/{product_id}', [ProductController::class, 'delete_product']);
Route::get('/all-product', [ProductController::class, 'all_product'])->name('all-product');
Route::get('/unactive-product/{product_id}', [ProductController::class, 'unactive_product']);
Route::get('/active-product/{product_id}', [ProductController::class, 'active_product']);
Route::post('/save-product', [ProductController::class, 'save_product']);
Route::post('/update-product/{product_id}', [ProductController::class, 'update_product']);
//Cart
Route::post('/save-cart', [CartController::class, 'save_cart']);
Route::get('/show-cart', [CartController::class, 'show_cart']);
// Route::get('/delete-to-cart/{rowId}', [CartController::class, 'delete_to_cart']);
// Route::post('/update-cart-quantity', [CartController::class, 'update_cart_quantity']);
Route::post('/update-cart', [CartController::class, 'update_cart']);
Route::post('/add-cart-ajax', [CartController::class, 'add_cart_ajax']);
Route::get('/gio-hang', [CartController::class, 'gio_hang'])->name('giohang');
Route::get('/delete-sp/{session_id}', [CartController::class, 'delete_sp']);
Route::get('/delete-all-cart', [CartController::class, 'delete_all_cart']);
//Coupon
Route::post('/check-coupon', [CartController::class, 'check_coupon']);
Route::get('/del-cou', [CartController::class, 'del_cou']);
Route::get('/unactive-coupon/{coupon_id}', [CouponController::class, 'unactive_coupon']);
Route::get('/active-coupon/{coupon_id}', [CouponController::class, 'active_coupon']);
//Delivery
Route::get('/delivery', [DeliveryController::class, 'delivery'])->name('delivery');
Route::post('/select-delivery', [DeliveryController::class, 'select_delivery']);
Route::post('/insert-delivery', [DeliveryController::class, 'insert_delivery']);
Route::post('/select-feeship', [DeliveryController::class, 'select_feeship']);
Route::post('/update-delivery', [DeliveryController::class, 'update_delivery']);
Route::post('/fetch-delivery', [DeliveryController::class, 'fetchDelivery'])->name('fetchDelivery');

//post
Route::post('/select-delivery-home', [CheckoutController::class, 'select_delivery_home']);
Route::post('/caculate-fee', [CheckoutController::class, 'caculateFee'])->name('CaculateFee');

Route::get('/del-fee', [CheckoutController::class, 'del_fee']);
//Coupon Admin
Route::get('/coupon', [CouponController::class, 'insert_coupon']);
Route::get('/list-coupon', [CouponController::class, 'list_coupon'])->name('list-coupon');
Route::post('/save-coupon', [CouponController::class, 'save_coupon']);
Route::get('/del-coupon/{coupon_id}', [CouponController::class, 'del_coupon']);
//Checkout
Route::get('/login-checkout', [CheckoutController::class, 'login_checkout'])->name('Login');
Route::get('/login-checkout-online', [CheckoutController::class, 'login_checkout_online'])->name('Login_online');
Route::post('/add-customer', [CheckoutController::class, 'add_customer']);
Route::get('/add-customer-admin', [CheckoutController::class, 'LayoutCustomer']);
Route::post('/save-customer', [CheckoutController::class, 'addCustomer']);
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::get('/checkout_online', [CheckoutController::class, 'checkout_online'])->name('checkout_online');
Route::post('/save-checkout', [CheckoutController::class, 'save_checkout']);
Route::get('/payment', [CheckoutController::class, 'payment']);
Route::get('/logout-checkout', [CheckoutController::class, 'logout_checkout']);
Route::post('/login-customer', [CheckoutController::class, 'login_customer']);
Route::post('/vnpay', [VnpayController::class, 'store'])->name('vnpay');
Route::get('/order_success', [HomeController::class, 'order_success'])->name('order-success');

Route::post('/confirm-order', [CheckoutController::class, 'confirm_order'])->name('checkout.confirm');

Route::get('/confirm-create-account/{confirmation_token}', [CheckoutController::class, 'create_customer']);
//Order
Route::post('/order-place', [CheckoutController::class, 'order_place']);
Route::post('/update-order-qty', [OrderController::class, 'update_order_qty']);
//Order Admin
Route::get('/manager-order', [OrderController::class, 'manager_order'])->name('manager-order');
Route::get('/view-order/{order_code}', [OrderController::class, 'view_order']);
Route::get('/delete-order/{order_code}', [OrderController::class, 'delete_order']);
Route::get('/profile-admin/{admin_id}', [AdminController::class, 'profileAdmin']);
Route::post('/edit-admin-profile/{admin_id}', [AdminController::class, 'editAdmin']);
//Print_Order
Route::get('/print-order/{checkout_code}', [OrderController::class, 'print_order']);

//Login facebook
Route::get('/login-facebook', [AdminController::class, 'login_facebook']);
Route::get('/admin/callback', [AdminController::class, 'callback_facebook']);
//add gallery
Route::get('/add-gallery/{product_id}', [GalleryController::class, 'add_gallery']);
Route::post('/select-gallery', [GalleryController::class, 'select_gallery']);
Route::post('/insert-gallery/{pro_id}', [GalleryController::class, 'insert_gallery']);
Route::post('/update-gallery', [GalleryController::class, 'update_gallery']);
Route::post('/delete-gallery', [GalleryController::class, 'delete_gallery']);
Route::post('/update-gallery-image', [GalleryController::class, 'update_gallery_image']);
//THong ke
Route::post('/filter-by-date', [AdminController::class, 'filter_by_date']);
Route::post('/day-orders', [AdminController::class, 'day_orders']);
Route::post('/dashboard-filter', [AdminController::class, 'dashboard_filter']);

//Send Mail
Route::get('/send-coupon-vip/{coupon_times}/{coupon_condition}/{coupon_number}/{coupon_code}', [MailAdminController::class, 'send_coupon_vip']);
Route::get('/send-coupon/{coupon_times}/{coupon_condition}/{coupon_number}/{coupon_code}', [MailAdminController::class, 'send_coupon']);
Route::get('/mail-example', [MailAdminController::class, 'mail_example']);
Route::get('/mail-example-vip', [MailAdminController::class, 'mail_example_vip']);
Route::get('/send-mail', [MailAdminController::class, 'send_mail']);
Route::get('/customer-list', [MailAdminController::class, 'all_customer'])->name('customer-list');
Route::get('/unactive-cus/{customer_id}', [MailAdminController::class, 'unactive_cus']);
Route::get('/active-cus/{customer_id}', [MailAdminController::class, 'active_cus']);
//wishlist
Route::get('/wishlist', [ProductController::class, 'wishlist']);
Route::get('/del-wishlist/{product_favorite_id}', [ProductController::class, 'delWishlist']);
Route::get('/add-wishlist/{product_id}', [ProductController::class, 'addWishlist']);
//Lấy lại mật khẩu
Route::post('/send-mail-to-customer', [MailAdminController::class, 'recoverPass']);
Route::get('/update-new-pass', [MailAdminController::class, 'updateNewPass']);
Route::post('/reset-new-pass', [MailAdminController::class, 'resetNewPass']);
Route::get('/forgin-password', [MailAdminController::class, 'forginPassword']);
//login-customer-google
Route::get('/login-customer-google', [AdminController::class, 'loginCustomerGoogle']);
Route::get('/customer/google/callback', [AdminController::class, 'CallbackCustomerGoogle']);
//login-customer-facebook
Route::get('/login-customer-facebook', [AdminController::class, 'loginCustomerFacebook']);
Route::get('/customer/facebook/callback', [AdminController::class, 'CallbackCustomerFacebook']);


Route::get('/policy',function (){return 'successs';});
Route::get('/terms-service',function (){return 'successs';});


//Mail accept
Route::get('/mail_order', [CheckoutController::class, 'mailOrder']);
//lịch sử mua hàng
Route::get('/history', [OrderController::class, 'history'])->name('history');
Route::get('/detail-history/{order_code}', [OrderController::class, 'detailsHistory']);
Route::get('/cancel_order/{order_code}', [OrderController::class, 'cancel_order']);
//Tai khoan
Route::get('/account/{customer_id}', [AdminController::class, 'Account']);
Route::post('/edit-customer/{customer_id}', [AdminController::class, 'editCustomer']);
Route::post('/edit-password/{customer_id}', [AdminController::class, 'editPassword']);
Route::get('/changepass/{customer_id}', [AdminController::class, 'changepass']);
//gio hang

//category-mxh
Route::get('/add-category-mxh', [CategoryMxhController::class, 'addCategoryMxh']);
Route::get('/edit-category-mxh/{category_mxh_id}', [CategoryMxhController::class, 'editCategoryMxh']);
Route::get('/delete-category-mxh/{category_mxh_id}', [CategoryMxhController::class, 'deleteCategoryMxh']);
Route::get('/all-category-mxh', [CategoryMxhController::class, 'allCategoryMxh'])->name('all-category-mxh');
Route::get('/unactive-category-mxh/{category_mxh_id}', [CategoryMxhController::class, 'unactiveCategoryMxh']);
Route::get('/active-category-mxh/{category_mxh_id}', [CategoryMxhController::class, 'activeCategoryMxh']);
Route::post('/save-category-mxh', [CategoryMxhController::class, 'saveCategoryMxh']);
Route::post('/update-category-mxh/{category_mxh_id}', [CategoryMxhController::class, 'updateCategoryMxh']);
// Route::get('/product-by-category-mxh/{category-mxh_id}', [::class, 'product_by_category-mxh']);
//MXH
Route::get('/pet-mxh', [SocialController::class, 'PetMXH']);
Route::get('/pet-mxh/profile/{customer_id}', [SocialController::class, 'ProfileCustomer'])->name('UserMXH');
Route::post('add-post', [SocialController::class, 'addPost']);
Route::get('del-post/{post_id}', [SocialController::class, 'delPost']);
Route::get('/pet-mxh/infor/{post_id}', [SocialController::class, 'inFor'])->name('PostDetail');
Route::post('/add-comment-post', [SocialController::class, 'addComment']);
Route::get('like/{post_id}', [SocialController::class, 'likePost']);
Route::get('/pet-mxh/search',[SocialController::class,'search'])->name('SearchMXH');
Route::get('edit-post/{post_id}',[SocialController::class,'edit'])->name('edit-post');
Route::post('save-post',[SocialController::class,'savePost'])->name('save-post');

//danhmuc
Route::get('/add-danhmuc', [DanhmucController::class, 'addDanhmuc']);
Route::get('/edit-danhmuc/{category_mxh_id}', [DanhmucController::class, 'editDanhmuc']);
Route::get('/delete-danhmuc/{category_mxh_id}', [DanhmucController::class, 'deleteDanhmuc']);
Route::get('/all-danhmuc', [DanhmucController::class, 'allDanhmuc'])->name('all-danhmuc');
Route::get('/unactive-danhmuc/{category_mxh_id}', [DanhmucController::class, 'unactiveDanhmuc']);
Route::get('/active-danhmuc/{category_mxh_id}', [DanhmucController::class, 'activeDanhmuc']);
Route::post('/save-danhmuc', [DanhmucController::class, 'saveDanhmuc']);
Route::post('/update-danhmuc/{category_mxh_id}', [DanhmucController::class, 'updateDanhmuc']);
//
Route::get('/all-food', [HomeController::class, 'allFood']);
Route::get('/all-accessory', [HomeController::class, 'allAccessory']);
Route::get('/all-pet', [HomeController::class, 'allPet']);
//paypal
Route::get('handle-payment', 'PayPalPaymentController@handlePayment')->name('make.payment');
Route::get('cancel-payment', 'PayPalPaymentController@paymentCancel')->name('cancel.payment');
Route::get('payment-success', 'PayPalPaymentController@paymentSuccess')->name('success.payment');
