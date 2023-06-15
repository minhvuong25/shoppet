<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Customer;
use App\Models\Coupon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MailAdminController extends Controller
{
    public function check()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }
    //Gửi mã giảm giá cho khách vip
    public function send_coupon_vip($coupon_times, $coupon_condition, $coupon_number, $coupon_code)
    {
        $customer_vip = Customer::where('customer_vip', 1)->get();
        $coupon = Coupon::where('coupon_code', $coupon_code)->first();
        $start_coupon = date('d-m-Y', $coupon->coupon_date_start);
        $end_coupon = date('d-m-Y', $coupon->coupon_date_end);
        $now = Carbon::now('Asia/Ho_Chi_minh')->format('d-m-Y H:i:s');
        $title_mail = "Mã khuyến mãi ngày" . ' ' . $now;
        $data = [];
        foreach ($customer_vip as $vip) {
            $data['email'][] = $vip->customer_email;
        }
        $coupon = array(
            'start_coupon' => $start_coupon,
            'end_coupon' => $end_coupon,
            'coupon_times' => $coupon_times,
            'coupon_condition' => $coupon_condition,
            'coupon_number' => $coupon_number,
            'coupon_code' => $coupon_code
        );
        Mail::send('admin.coupon.send_coupon.send_coupon_vip', ['coupon' => $coupon], function ($message) use ($title_mail, $data) {
            $message->to($data['email'])->subject($title_mail);
            $message->from($data['email'], $title_mail);
        });
        return redirect()->back()->with('message', 'Gửi thành công');
    }
    public function mail_example_vip()
    {
        return view('admin.coupon.send_coupon.send_coupon_vip');
    }
    //Gửi mã giảm giá cho khách bình thường
    public function send_coupon($coupon_times, $coupon_condition, $coupon_number, $coupon_code)
    {
        $customer = Customer::where('customer_vip', '!=', 0)->get();
        $coupon = Coupon::where('coupon_code', $coupon_code)->first();
        $start_coupon = date('d-m-Y', $coupon->coupon_date_start);
        $end_coupon = date('d-m-Y', $coupon->coupon_date_end);
        $now = Carbon::now('Asia/Ho_Chi_minh')->format('d-m-Y H:i:s');
        $title_mail = "Mã khuyến mãi ngày" . ' ' . $now;
        $data = [];
        foreach ($customer as $vip) {
            $data['email'][] = $vip->customer_email;
        }
        $coupon = array("start_coupon" => $start_coupon, "end_coupon" => $end_coupon, "coupon_times" => $coupon_times, "coupon_condition" => $coupon_condition, "coupon_number" => $coupon_number, "coupon_code" => $coupon_code);
//        dd($coupon);
         Mail::send('admin.coupon.send_coupon.send_coupon', ['coupon' => $coupon], function ($message) use ($title_mail, $data) {
             $message->to($data['email'])->subject($title_mail);
             $message->from($data['email'], $title_mail);
         });
        return redirect()->back()->with('message', 'Gửi thành công');
    }
    public function mail_example()
    {
        return view('admin.coupon.send_coupon.send_coupon');
    }
    //Trạng thái customer
    public function all_customer()
    {
        $customer = Customer::all();
        return view('admin.customer.customer_list', compact('customer'));
    }
    public function unactive_cus($customer_id)
    {
        $this->check();
        Customer::where('customer_id', $customer_id)->update(['customer_vip' => 0]);
        return Redirect::to('customer-list');
    }
    public function active_cus($customer_id)
    {
        $this->check();
        Customer::where('customer_id', $customer_id)->update(['customer_vip' => 1]);
        return Redirect::to('customer-list');
    }
    //Quen mat khau
    public function forginPassword(Request $request)
    {
        $meta_desc = "Chuyên bán thức ăn thú cưng";
        $meta_keywords = "";
        $meta_title = "MVPetShop.vn | Quên mật khẩu";
        $url_canonical = $request->url();
        $category = Category::where('category_status', '0')->orderBy('category_id', 'desc')->get();
        return view('pages.checkout.forget', compact('meta_desc', 'meta_keywords', 'meta_title', 'url_canonical', 'category'));
    }
    public function recoverPass(Request $request)
    {
        $data = $request->all();
        $customer = Customer::where('customer_email', '=', $data['email_account'])->get();
        foreach ($customer as $value) {
            $customer_id = $value->customer_id;
        }
        $now = Carbon::now('Asia/Ho_Chi_minh')->format('d-m-Y');
        $title_mail = "Lấy lại mật khẩu" . ' ' . $now;
        if ($customer) {
            $count_customer = $customer->count();
            if ($count_customer == 0) {
                return redirect()->back()->with('error', 'Email chưa được đăng ký để khôi phục mật khẩu');
            } else {
                $token_random = Str::random(20);
                $customer = Customer::find($customer_id);
                $customer->customer_token = $token_random;
                $customer->save();
                //send mail
                $to_email = $data['email_account'];
                $link_reset_pass = url('/update-new-pass?email=' . $to_email . '&token=' . $token_random);
                $data = array("name" => $title_mail, "body" => $link_reset_pass, "email" => $data['email_account']);
                Mail::send('pages.checkout.forget_notify', ['data' => $data], function ($message) use ($title_mail, $data) {
                    $message->to($data['email'])->subject($title_mail);
                    $message->from($data['email'], $title_mail);
                });
                return redirect()->back()->with('message', 'Gửi mail thành công, vui lòng vào email để lấy lại mật khẩu');
            }
        }
    }
    public function updateNewPass(Request $request)
    {
        $meta_desc = "Chuyên bán thức ăn thú cưng";
        $meta_keywords = "";
        $meta_title = "MVPetShop.vn | Lấy lại mật khẩu";
        $url_canonical = $request->url();
        $category = Category::where('category_status', '0')->orderBy('category_id', 'desc')->get();
        return view('pages.checkout.newpass', compact('meta_desc', 'meta_keywords', 'meta_title', 'url_canonical', 'category'));
    }
    public function resetNewPass(Request $request)
    {
        $data = $request->all();
        $token_random = Str::random(20);
        $customer = Customer::where('customer_email', '=', $data['email'])->where('customer_token', '=', $data['token'])->get();
        $count = $customer->count();
        if ($count > 0) {
            foreach ($customer as $cus) {
                $customer_id = $cus->customer_id;
            }
            $reset = Customer::find($customer_id);
            $reset->customer_password = md5($data['password_account']);
            $reset->customer_token = $token_random;
            $reset->save();
            return redirect('login-checkout')->with('message', 'Đặt lại mật khẩu thành công.');
        } else {
            return redirect('quen-mat-khau')->with('error', 'Đặt lại mật khẩu thất bại vì link đã quá hạn');
        }
    }
}
