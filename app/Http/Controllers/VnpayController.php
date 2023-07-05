<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shipping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class VnpayController extends Controller
{
    public function store(Request $request){

        $total_price = $request->total_price;
        $data=['shipping_email' => $request->email,
        'shipping_name' => $request->name,
        'shipping_address' => $request->address,
        'shipping_phone' => $request->phone,
        'shipping_notes' => $request->name,
        'shipping_method' => $request->payment_select,
        'order_fee' => $request->order_fee,
        'order_payment' => 'vnpay',
        'order_coupon' => $request->order_coupon,
        ];


        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl = route('checkout_online');
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');//Mã website tại VNPAY
        $vnp_HashSecret = config('vnpay.vnp_HashSecret'); //Chuỗi bí mật

        $vnp_TxnRef = rand(1,500000000); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'VNP';
        $vnp_OrderType = 'Banking';
        $vnp_Amount = $total_price * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
//Add Params of 2.0.1 Version
//        $vnp_ExpireDate = $_POST['txtexpire'];
////Billing
//        $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
//        $vnp_Bill_Email = $_POST['txt_billing_email'];
//        $fullName = trim($_POST['txt_billing_fullname']);
//        if (isset($fullName) && trim($fullName) != '') {
//            $name = explode(' ', $fullName);
//            $vnp_Bill_FirstName = array_shift($name);
//            $vnp_Bill_LastName = array_pop($name);
//        }
//        $vnp_Bill_Address=$_POST['txt_inv_addr1'];
//        $vnp_Bill_City=$_POST['txt_bill_city'];
//        $vnp_Bill_Country=$_POST['txt_bill_country'];
//        $vnp_Bill_State=$_POST['txt_bill_state'];
//// Invoice
//        $vnp_Inv_Phone=$_POST['txt_inv_mobile'];
//        $vnp_Inv_Email=$_POST['txt_inv_email'];
//        $vnp_Inv_Customer=$_POST['txt_inv_customer'];
//        $vnp_Inv_Address=$_POST['txt_inv_addr1'];
//        $vnp_Inv_Company=$_POST['txt_inv_company'];
//        $vnp_Inv_Taxcode=$_POST['txt_inv_taxcode'];
//        $vnp_Inv_Type=$_POST['cbo_inv_type'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
//            "vnp_ExpireDate"=>$vnp_ExpireDate,
//            "vnp_Bill_Mobile"=>$vnp_Bill_Mobile,
//            "vnp_Bill_Email"=>$vnp_Bill_Email,
//            "vnp_Bill_FirstName"=>$vnp_Bill_FirstName,
//            "vnp_Bill_LastName"=>$vnp_Bill_LastName,
//            "vnp_Bill_Address"=>$vnp_Bill_Address,
//            "vnp_Bill_City"=>$vnp_Bill_City,
//            "vnp_Bill_Country"=>$vnp_Bill_Country,
//            "vnp_Inv_Phone"=>$vnp_Inv_Phone,
//            "vnp_Inv_Email"=>$vnp_Inv_Email,
//            "vnp_Inv_Customer"=>$vnp_Inv_Customer,
//            "vnp_Inv_Address"=>$vnp_Inv_Address,
//            "vnp_Inv_Company"=>$vnp_Inv_Company,
//            "vnp_Inv_Taxcode"=>$vnp_Inv_Taxcode,
//            "vnp_Inv_Type"=>$vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

//var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        $returnData = array('code' => '00'
        , 'message' => 'success'
        , 'data' => $vnp_Url);
        $this->confirm_order($data);
        return redirect($vnp_Url);
    }
    public function confirm_order($data)
    {
        if (Session::get('cart')) {
            foreach (Session::get('cart') as $key => $cart) {
                $id = $cart['product_id'];
                // $coupon = $data['order_coupon'];
                // $fee = $data['order_fee'];
            }
            $checkPet = Product::where('product_id', $id)->where('product_status', 0)->first();
            //kieemr tra pet ton tai hay khong
            if (!$checkPet) {
                Session::forget('coupon');
                Session::forget('fee');
                Session::forget('cart');
                return response()->json([
                    'data' => 'Kotontai'
                ]);
                // kiem tra so luong pet
            } elseif ($cart['product_qty'] > $checkPet->product_quantity) {
                Session::forget('coupon');
                Session::forget('fee');
                Session::forget('cart');
                return response()->json([
                    'data' => false
                ]); //Đúng
            } elseif ($cart['product_qty'] > 0) {
                // get coupon
                if ($data['order_coupon'] != 'no') {
                    $coupon = Coupon::where('coupon_code', $data['order_coupon'])->first();
                    $coupon->coupon_used = $coupon->coupon_used . ',' . Session::get('customer_id');
                    $coupon->coupon_times = $coupon->coupon_times - 1;
                    $coupon_mail = $coupon->coupon_code;
                    $coupon_condition = $coupon->coupon_condition;
                    $coupon_number = $coupon->coupon_number;
                    $coupon->save();
                } else {
                    $coupon_mail = 'Không có';
                }
                //get vận chuyển
                $shipping = new Shipping();
                $shipping->shipping_name = $data['shipping_name'];
                $shipping->shipping_address = $data['shipping_address'];
                $shipping->shipping_phone = $data['shipping_phone'];
                $shipping->shipping_email = $data['shipping_email'];
                $shipping->shipping_notes = $data['shipping_notes'];
                $shipping->shipping_method = $data['shipping_method'];
                $shipping->save();
                $shipping_id = $shipping->shipping_id;
                $checkout_code = substr(md5(microtime()), rand(0, 25), 9);
                //get order
                $order = new Order();
                $order->customer_id = Session::get('customer_id');
                $order->shipping_id = $shipping_id;
                $order->order_status = 1;
                $order->order_code  = $checkout_code;
                $today = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
                $date_order = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
                $order->created_at = $today;
                $order->order_date = $date_order; //payment_method
                $order->order_payment =  $data['order_payment'];
                $order->save();
                if (Session::get('cart') == true) {
                    foreach (Session::get('cart') as $key => $cart) {
                        $order_details = new OrderDetail();
                        $order_details->order_code = $checkout_code;
                        $order_details->product_id = $cart['product_id'];
                        $order_details->product_name = $cart['product_name'];
                        $order_details->product_price = $cart['product_price'];
                        $order_details->product_sales_quantity = $cart['product_qty'];
                        $order_details->product_coupon = $data['order_coupon'];
                        $order_details->product_feeship = $data['order_fee'];
                        $order_details->save();
                    }
                }
                $now = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                $title_mail = "Đơn hàng xác nhận" . ' ' . $now;
                $customer = Customer::find(Session::get('customer_id'));
                $data['email'][] = $customer->customer_email;
                if (Session::get('cart') == true) {
                    foreach (Session::get('cart') as $key => $cart_mail) {
                        $cart_array[] = array(
                            'product_name' => $cart_mail['product_name'],
                            'product_price' => $cart_mail['product_price'],
                            'product_qty' => $cart_mail['product_qty'],
                        );
                    }
                }
                if (Session::get('fee') == true) {
                    $fee = Session::get('fee');
                } else {
                    $fee = 20000;
                }
                $shipping_array = array(
                    'fee' => $fee,
                    'customer_name' => $customer->name,
                    'shipping_name' => $data['shipping_name'],
                    'shipping_email' => $data['shipping_email'],
                    'shipping_phone' => $data['shipping_phone'],
                    'shipping_address' => $data['shipping_address'],
                    'shipping_notes' => $data['shipping_notes'],
                    'shipping_method' => $data['shipping_method'],
                );
                $ordercode_mail = array(
                    'coupon_code' => $coupon_mail,
                    'order_payment' => $data['order_payment'],
                    'order_code' => $checkout_code,
                    // 'coupon_number'=> $coupon_number,
                    // 'coupon_condition'=> $coupon_condition,
                );
                Mail::send('pages.mail.mail_order', ['cart_array' => $cart_array, 'shipping_array' => $shipping_array, 'code' => $ordercode_mail], function ($message) use ($title_mail, $data) {
                    $message->to($data['email'])->subject($title_mail);
                });
                Session::forget('coupon');
                Session::forget('fee');
                Session::forget('cart');
                return response()->json([
                    'data' => true
                ]);
            } else {
                Session::forget('coupon');
                Session::forget('fee');
                Session::forget('cart');
                return response()->json([
                    'data' => 'CheckAm'
                ]);
            }
        }
    }
}
