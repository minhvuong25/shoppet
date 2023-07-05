<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Login;
use App\Models\Order;
use App\Models\Product;
use App\Models\Social;
use App\Models\Statistic;
use App\Models\Visitor;
use Carbon\Carbon;

// session_start();
class AdminController extends Controller
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
    public function index()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('thongke');
        } else {
            return view('admin_login');
        }
    }

    public function showdashboard(Request $request)
    {
        $this->check();
        $user_ip_address = $request->ip();
        $early_last_month = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth()->toDateString();
        $end_of_last_month = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth()->toDateString();
        $early_this_month = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth()->toDateString();
        $oneyears = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->toDateString();
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        //total last month
        $visitor_of_lastmonth = Visitor::whereBetween('date_visitors', [$early_last_month, $end_of_last_month])->get();
        $visitor_last_month_count = $visitor_of_lastmonth->count();
        //total this month
        $visitor_of_thismonth = Visitor::whereBetween('date_visitors', [$early_this_month, $now])->get();
        $visitor_this_month_count = $visitor_of_thismonth->count();
        //total in one year
        $visitor_of_year = Visitor::whereBetween('date_visitors', [$oneyears, $now])->get();
        $visitor_of_year_count = $visitor_of_year->count();
        //total online
        $visitor_of_current = Visitor::where('ip_address', $user_ip_address)->get();
        $visitor_count = $visitor_of_current->count();
        if ($visitor_count < 1) {
            $visitor = new Visitor();
            $visitor->ip_address = $user_ip_address;
            $visitor->date_visitors = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
            $visitor->save();
        }
        //total visitor
        $visitor = Visitor::all();
        $visitor_total = $visitor->count();
        //total
        $product = Product::all()->count();
        // $product_views = Product::orderBy('product_views','desc')->take(20)->get();
        $order = Order::all()->count();
        $customer = Customer::all()->count();
        return view('admin.thongke')->with(compact('visitor_last_month_count', 'visitor_this_month_count', 'visitor_of_year_count', 'visitor_of_year_count', 'visitor_total', 'product', 'order', 'customer', 'visitor_count'));
    }
    public function dashboard(Request $request)
    {
        $data = $request->all();
        $admin_email = $data['admin_email'];
        $admin_password = $data['admin_password'];
        $login = Login::where('admin_email', $admin_email)->where('admin_password', $admin_password)->first();
        if ($login) {
            $login_count = $login->count();
            if ($login_count > 0) {
                Session::put(['admin_name' => $login->admin_name]);
                Session::put(['admin_avatar' => $login->admin_avatar]);
                Session::put(['admin_id' => $login->admin_id]);
                return Redirect::to('/thongke');
            }
        } else {
            Session::put('message', "Làm ơn nhập lại");
            return Redirect::to('/admin');
        }
    }
    public function logout()
    {
        $this->check();
        Session::put('admin_name', null);
        Session::put('admin_id', null);
        return Redirect::to('/admin');
    }
    public function  changepass(
        Request $request,$customer_id
    ){
        $meta_desc = "Chuyên bán máy ảnh và phụ kiện máy ảnh";
        $meta_keywords = "may anh, máy ảnh, phụ kiện,phu kien, phụ kiện máy ảnh, phu kien may anh";
        $meta_title = "MVPetShop.vn | Trang chủ";
        $category = Category::where('category_status', '0')->orderBy('category_id', 'desc')->get();
        $url_canonical = $request->url();
        $customer = Customer::find($customer_id);
        return view('pages.user.changepass', compact('category','customer','meta_desc', 'meta_keywords', 'meta_title', 'url_canonical'));
        }

    //
    public function thongke(Request $request)
    {
        $this->check();
        $user_ip_address = $request->ip();
        $early_last_month = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth()->toDateString();
        $end_of_last_month = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth()->toDateString();
        $early_this_month = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth()->toDateString();
        $oneyears = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->toDateString();
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        //total last month
        $visitor_of_lastmonth = Visitor::whereBetween('date_visitors', [$early_last_month, $end_of_last_month])->get();
        $visitor_last_month_count = $visitor_of_lastmonth->count();
        //total this month
        $visitor_of_thismonth = Visitor::whereBetween('date_visitors', [$early_this_month, $now])->get();
        $visitor_this_month_count = $visitor_of_thismonth->count();
        //total in one year
        $visitor_of_year = Visitor::whereBetween('date_visitors', [$oneyears, $now])->get();
        $visitor_of_year_count = $visitor_of_year->count();
        //total online
        $visitor_of_current = Visitor::where('ip_address', $user_ip_address)->get();
        $visitor_count = $visitor_of_current->count();
        if ($visitor_count < 1) {
            $visitor = new Visitor();
            $visitor->ip_address = $user_ip_address;
            $visitor->date_visitors = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
            $visitor->save();
        }
        //total visitor
        $visitor = Visitor::all();
        $visitor_total = $visitor->count();
        //total
        $product = Product::all()->count();
        $app_order = Order::all()->count();
        $app_customer = Customer::all()->count();
        $product_views = Product::OrderBy('product_views')->get();
        //Lấy kho hàng
        $getAllProduct = Product::orderByRaw('CONVERT(product_sold, SIGNED) desc')->paginate(5);
        //Thống kê tổng doanh thu
        //Lấy dữ liệu ngày tháng
        $dauthangnay = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth()->toDateString();
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $dau_thang_truoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth()->toDateString();
        $cuoi_thang_truoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth()->toDateString();
        $sub7ngay = Carbon::now('Asia/Ho_Chi_Minh')->subDays(7)->toDateString();
        $sub365ngay = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->toDateString();
        //Tính doanh thu
        $get7ngay = Statistic::whereBetween('order_date', [$sub7ngay, $now])->orderBy('order_date', 'asc')->sum('profit');
        $getthangnay = Statistic::whereBetween('order_date', [$dauthangnay, $now])->orderBy('order_date', 'asc')->sum('profit');
        $getthangtruoc = Statistic::whereBetween('order_date', [$dau_thang_truoc, $cuoi_thang_truoc])->orderBy('order_date', 'asc')->sum('profit');
        $get1nam = Statistic::whereBetween('order_date', [$sub365ngay, $now])->orderBy('order_date', 'asc')->sum('profit');
        return view('admin.thongke.thongke')->with(compact('get1nam', 'get7ngay', 'getthangtruoc', 'getthangnay', 'getAllProduct', 'visitor_last_month_count', 'visitor_this_month_count', 'visitor_of_year_count', 'visitor_of_year_count', 'visitor_total', 'product', 'app_order', 'app_customer', 'visitor_count', 'product_views'));;
    }
    public function filter_by_date(Request $request)
    {
        $data = $request->all();
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        $get = Statistic::whereBetween('order_date', [$from_date, $to_date])->OrderBy('order_date', 'asc')->get();
        foreach ($get as $key => $val) {
            $chart_data[] = array(
                'period' => $val->order_date,
                'order' => $val->total_order,
                'sales' => $val->sales,
                'profit' => $val->profit,
                'quantity' => $val->quantity
            );
        }
        echo $data = json_encode($chart_data);
    }
    public function dashboard_filter(Request $request)
    {
        $data = $request->all();
        $dauthangnay = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth()->toDateString();
        $dau_thang_truoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth()->toDateString();
        $cuoi_thang_truoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth()->toDateString();
        $sub7ngay = Carbon::now('Asia/Ho_Chi_Minh')->subDays(7)->toDateString();
        $sub365ngay = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->toDateString();
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        if ($data['dashboard_value'] == '7ngay') {
            $get = Statistic::whereBetween('order_date', [$sub7ngay, $now])->orderBy('order_date', 'asc')->get();
        } elseif ($data['dashboard_value'] == 'thangtruoc') {
            $get = Statistic::whereBetween('order_date', [$dau_thang_truoc, $cuoi_thang_truoc])->orderBy('order_date', 'asc')->get();
        } elseif ($data['dashboard_value'] == 'thangnay') {
            $get = Statistic::whereBetween('order_date', [$dauthangnay, $now])->orderBy('order_date', 'asc')->get();
        } else {
            $get = Statistic::whereBetween('order_date', [$sub365ngay, $now])->orderBy('order_date', 'asc')->get();
        }
        foreach ($get as $key => $val) {
            $chart_data[] = array(
                'period' => $val->order_date,
                'order' => $val->total_order,
                'sales' => $val->sales,
                'profit' => $val->profit,
                'quantity' => $val->quantity
            );
        }
        echo $data = json_encode($chart_data);
    }
    public function day_orders(Request $request)
    {
        $data = $request->all();
        $sub60ngay = Carbon::now('Asia/Ho_Chi_Minh')->subDay(60)->toDateString();
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $get = Statistic::whereBetween('order_date', [$sub60ngay, $now])->orderBy('order_date', 'asc')->get();
        foreach ($get as $key => $val) {
            $chart_data[] = array(
                'period' => $val->order_date,
                'order' => $val->total_order,
                'sales' => $val->sales,
                'profit' => $val->profit,
                'quantity' => $val->quantity,
            );
        }
        echo $data = json_encode($chart_data);
    }
    ////////////////Login customer google
    public function loginCustomerGoogle()
    {
        config('services.facebook.redirect');
        return Socialite::driver('google')->redirect();
    }
    public function findOrCreateCustomer($users, $provider)
    {


        $authUser = Social::where('provider_user_id', $users->id)->where('provider_user_email', $users->email)->first();
        if ($authUser) {
            dd($authUser);
            return $authUser;
        } else {
            $customer_new = new Social([
                'provider_user_id' => $users->id,
                'provider_user_email' => $users->email,
                'provider' => strtoupper($provider),
            ]);
            $customer = Customer::where('customer_email', $users->email)->first();
            if (!$customer) {
                $customer = Customer::create([
                    'customer_name' => $users->name,
                    'customer_picture' => $users->avatar,
                    'customer_email' => $users->email,
                    'customer_password' => '',
                    'customer_phone' => ''
                ]);
            }

            $customer_new->customer()->associate($customer);
            // $customer_new->save();
            return $customer_new;
        }
    }
    public function CallbackCustomerGoogle()
    {
        config(['services.google.redirect' => env('GOOGLE_CLIENT_URL')]);

        $users = Socialite::driver('google')->stateless()->user();
        $authUser = $this->findOrCreateCustomer($users, 'google');
        if ($authUser) {

            $account_name = Customer::where('customer_id', $authUser->user)->first();
            Session::put('customer_id', $account_name->customer_id);
            Session::put('customer_picture', $account_name->customer_picture);
            Session::put('customer_name', $account_name->customer_name);
        } elseif ($customer_new) {
            $account_name = Customer::where('customer_id', $authUser->user)->first();
            Session::put('customer_id', $account_name->customer_id);
            Session::put('customer_picture', $account_name->customer_picture);
            Session::put('customer_name', $account_name->customer_name);
        }
        return redirect('/trang-chu')->with('message', 'Đăng nhập google thành công');
    }

    public function loginCustomerFacebook()
    {
        config('services.facebook.redirect');
        return Socialite::driver('facebook')->redirect();
    }
    public function CallbackCustomerFacebook()
    {
//        config(['services.facebook.redirect' => env('FACEBOOK_REDIRECT')]);
        $provider = Socialite::driver('facebook')->user();
        $account = Social::where('provider', 'facebook')->where('provider_user_id', $provider->getId())->first();
        if ($account != null) {
            $account_name = Customer::where('customer_id', $account->user)->first();
            Session::put('customer_id', $account_name->customer_id);
            Session::put('customer_name', $account_name->customer_name);
            return redirect('/')->with('message', 'Đăng nhập facebook thành công');
        } elseif ($account == null) {
            $facebook = 'facebook';
            $customer_login = new Social([
                'provider_user_id' => $provider->getId(),
                'provider_user_email' => $provider->getEmail(),
                'provider' => strtoupper($facebook)
            ]);
            $customer = Customer::where('customer_email', $provider->getEmail())->first();
            if (!$customer) {
                $customer = Customer::create([
                    'customer_name' => $provider->getName(),
                    'customer_picture' => $provider->getAvatar(),
                    'customer_email' => $provider->getEmail(),
                    'customer_password' => '',
                    'customer_phone' => ''
                ]);
            }
            $customer_login->customer()->associate($customer);
            $customer_login->save();
            $account_new = Customer::where('customer_id', $customer_login->user)->first();
            Session::put('customer_id', $account_new->customer_id);
            Session::put('customer_name', $account_new->customer_name);
            return redirect('/')->with('message', 'Đăng nhập facebook thành công');
        }
    }
    public function profileAdmin($admin_id)
    {
        $adminId = Session::get('admin_id');
        $profileAdmin = Admin::where('admin_id', $adminId)->first();
        return view('admin.admin.profile_admin', compact('profileAdmin'));
    }
    public function editAdmin(Request $request, $admin_id)
    {
//        if ($request->hasFile('admin_avatar') && $request->file('admin_avatar')->isValid()) {
//            $image = $request->file('admin_avatar');
//            $filename = time() . '.' . $image->getClientOriginalExtension();
//            $image->move(public_path('uploads/account/'), $filename);
//        }
        if ($request->hasFile('admin_avatar')) {
            $file = $request->file('admin_avatar');

            // Lấy tên tệp
            $filename = $file->getClientOriginalName();

            // Lưu tệp vào thư mục public/uploads
//           $file->storeAs('uploads/account/', $filename);
            $file->move(public_path('uploads/account/'), $filename);
        }
        $adminId = Session::get('admin_id');
        $profileAdmin = Admin::where('admin_id', $adminId)->first();
        $editAdmin = Admin::find($admin_id)->first();
        $editAdmin->admin_name = $request->admin_name;
        $editAdmin->admin_phone = $request->admin_phone;
        $editAdmin->admin_address = $request->admin_address;
        $editAdmin->admin_avatar = $filename;
        $result = $editAdmin->save();
        if ($result) {
            toastr()->success('Thay đổi thành công');
            return view('admin.dashboard')->with('message', 'Thay đổi thành công');
        } else {
            return view('admin.dashboard')->with('message', 'Thay đổi thất bại');
        }
    }
    //Account User
    public function Account(Request $request,$customer_id)
    {
        $meta_desc = "Chuyên bán máy ảnh và phụ kiện máy ảnh";
        $meta_keywords = "may anh, máy ảnh, phụ kiện,phu kien, phụ kiện máy ảnh, phu kien may anh";
        $meta_title = "MVPetShop.vn | Trang chủ";
        $url_canonical = $request->url();
        $category = Category::where('category_status', '0')->orderBy('category_id', 'desc')->get();
        $authUser = Session::get('customer_id');
        $getUserProfile = Customer::where('customer_id',$customer_id)->first();
//        dd($getUserProfile);
        // dd($authUser);
        if (!$authUser) {
            return redirect()->back()->with('message', 'Bạn phải có tài khoản mới vào được');
        } else {
//            dd($getUserProfile);
            return view('pages.user.account', compact('getUserProfile','meta_desc', 'meta_keywords', 'meta_title', 'url_canonical', 'category'));
        }
    }
    public function editCustomer(Request $request,$customer_id){

        $editCustomer = Customer::where('customer_id',$customer_id)->first();

        if ($request->hasFile('customer_picture') && $request->file('customer_picture')->isValid()) {
            $image = $request->file('customer_picture');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/account/'), $filename);
        }else {
            $filename = $editCustomer->customer_picture ;
        }
        $editCustomer->customer_name = $request->customer_name;
        $editCustomer->customer_phone = $request->customer_phone;
        $editCustomer->customer_picture = $filename;
            $result = $editCustomer->save();
            if($result){
                toastr()->success('Update Thành công');
            return redirect()->back()->with('message','Thành công');
            }else{
                dd('Loi');
            }
        // $editCustomer->customer_name = $request->customer_name;
    }
    public function editPassword(Request $request,$customer_id){

        if ($request->password_new == $request->password_confirm){
            $customer = Customer::find($customer_id);
            $newpassword = 'customer_password';
            $passwordmd5 = md5($newpassword);
            $customer->customer_password = $passwordmd5 ;
            if ($customer->save()){
                toastr()->success('Đổi mật khẩu thành công');
                return redirect()->route('Home');
            }
        }else{
            toastr()->warning('Vui lòng nhập mật khẩu giống với mật khẩu mới');
            return redirect()->back()->with('message','Vui lòng nhập mật khẩu giống với mật khẩu mới');
        }


    }
}
