<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use App\Models\Comment;
use App\Models\Danhmuc;
use App\Models\Gallery;

// session_start();

class HomeController extends Controller
{
    public function index(Request $request){
        $meta_title = "MVPetShop.vn | Trang chủ";
        $category = Category::whereNotIn('category_id',[21])->where('category_status',1
        )->get();
//        dd($category);
        $danhmuc = Danhmuc::all();
        $pet = Product::where('product_status','0')->where('danhmuc_id',21)->orderBy('product_id','desc')->limit(8)->get();
        $accessory = Product::where('product_status','0')->where('danhmuc_id',23)->orderBy('product_id','desc')->limit(4)->get();
//        $all_product = Product::where('product_status','0')->orderBy('product_id','desc')->get();
//        $food = Product::where('product_status','0')->where('danhmuc_id',22)->orderBy('product_price','desc')->limit(3)->get();
        return view('pages.home')->with(compact('pet','accessory','danhmuc','category','meta_title'));
    }



    public function timkiem(Request $request){
        $meta_title = "MVPetShop.vn | Tìm kiếm";
        $category = Category::where('category_status','0')->orderBy('category_id','asc')->get();
        $search_pet = Product::where('product_name','like','%'.$request->keyword.'%')->orWhere('product_price',$request->keyword)->get();
        return view('pages.product.search',compact('search_pet','category','meta_title'));
    }
    public function autocomplete_ajax(Request $request){
        $data = $request->all();
        if($data['query']){
            $product = Product::where('product_status',0)->where('product_name','like','%'.$data['query'].'%')->get();
            $output = '<ul class="dropdown-menu" style="display:block;width:100%;left:0">';
            foreach($product as $key => $word){
                $output .='
                    <li class="li_search_ajax"><a href="#.">'.$word->product_name.'</a></li>
                ';
            }
            $output .= '</ul> <button type="submit" style="background: #de8ebe;"><i
                                                                class="pegk pe-7s-search"></i></button>';
            echo $output;
        }
    }
    public function product_by_category($category_id,Request $request)
    {

        $meta_title = "MVPetShop.vn | Sản phẩm theo thương hiệu";
//        $show_product_by_cate = Product::where('category_id',$category_id)->whereNotIn('category_id',[21,25])->OrderBy('product_price','asc')->where('product_status',0)->get();
        $show_product_by_cate = Product::where('category_id',$category_id)->OrderBy('product_price','asc')->where('product_status',0)->get();
//        $category = Category::whereNotIn('category_id',[21,25])->get();

        $category = Category::all();
        $newProduct = Product::where('category_id',$category_id)->Orderby('product_id','desc')->take(1)->get();
        return view('pages.product.show_product_by_cate')
        ->with(compact('category','show_product_by_cate','meta_title','newProduct'));
    }
    public function allFood(Request $request){
        $meta_title = "MVPetShop.vn | Tất cả sản phẩm";
        $category = Category::all();
//        $allFood = Product::where('danhmuc_id',22)->where('product_status',0)->get();
        return view('pages.product.allFood',compact('meta_title','category'));
    }
    public function allAccessory(Request $request){
        $meta_desc = "";
        $meta_keywords = "";
        $meta_title = "MVPetShop.vn | Tất cả sản phẩm";
        $url_canonical = $request->url();
        $category = Category::all();
        $allAccessory = Product::where('danhmuc_id',23)->where('product_status',0)->get();
        return view('pages.product.allAccessory',compact('meta_desc','meta_keywords','meta_title','url_canonical','category','allAccessory'));
    }
    public function allPet(Request $request){
        $meta_title = "MVPetShop.vn | Tất cả sản phẩm";
        $category = Category::all();
        $allPet = Product::where('danhmuc_id',21)->where('product_status',0)->get();
        return view('pages.product.allPet',compact('meta_title','category','allPet'));
    }
    public function order_success(){
        return view('success_page');

    }
}
