<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExports;
use App\Exports\WordImport as ExportsWordImport;
use App\Imports\ProductImports;
use App\Imports\WordImport;
use App\Models\Category;
use App\Models\Product;
use App\Models\Gallery;
use Illuminate\Support\Facades\File;
use App\Models\Comment;
use App\Models\Danhmuc;
use App\Models\Favorite;
use App\Models\Rating;

// session_start();

class ProductController extends Controller
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
    public function add_product()
    {
        $this->check();
        $cate_product = Category::orderBy('category_id', 'desc')->whereNotIn('category_id',[21])->get();
        $danhmuc = Danhmuc::orderBy('danhmuc_id', 'desc')->get();
        return view('admin.product.add_product')->with('cate_product', $cate_product)->with('danhmuc',$danhmuc);
    }
    public function all_product()
    {
        $this->check();
        $all_product = Product::join('tbl_category', 'tbl_product.category_id', '=', 'tbl_category.category_id')->orderBy('tbl_product.product_id', 'desc')
            ->get();
        $manager_product = view('admin.product.all_product')->with('all_product', $all_product);
        return view('admin_layout')->with('admin.product.all_product', $manager_product);
    }
    public function save_product(Request $request)
    {
        $this->check();
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_slug'] = $request->product_slug;
        $data['product_desc'] = $request->product_desc;
        $data['product_tags'] = $request->product_tags;
        $data['product_quantity'] = $request->product_quantity;
        $data['product_content'] = $request->product_content;
        $data['product_price'] = $request->product_price;
        $data['product_cost'] = $request->product_cost;
        $data['product_sold'] = 0;
        $data['category_id'] = $request->product_cate;
        $data['danhmuc_id'] = $request->product_danhmuc;
        $data['product_status'] = $request->product_status;
        $data['product_sale'] = $request->product_sale;
        $data['product_code'] =  substr(md5(microtime()), rand(0, 25), 9);
        // if($data['product_sale'] = $request->product_sale){
        //     $request->product_price = $request->product_price -  ($request->product_price*$request->product_sale)/100;
        // }
        $get_image = $request->file('product_image');
        $path = 'uploads/product/';
        $path_gal = 'public/uploads/gallery/';
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
             $get_image->move($path, $new_image);
           File::copy($path . $new_image, $path_gal . $new_image);
            $data['product_image'] = $new_image;
        }
        // $data['product_image'] = '';
        // DB::table('tbl_product')->insert($data);
        // Session::put('message','Thêm thành công');
        // return Redirect::to('all-product');
        if($request->product_price > $request->product_cost){
            $pro_id = Product::insertGetId($data);
            $gallery = new Gallery();
            $gallery->gallery_image = $new_image;
            $gallery->gallery_name = $new_image;
            $gallery->product_id = $pro_id;
            $gallery->save();
            toastr()->success('Thêm thành công');
            Session::put('message', 'Thêm thành công');
            return Redirect::to('all-product');
        }else{
            Session::put('message', 'Giá bán phải >= giá gốc');
            return Redirect::to('all-product');
        }
    }
    public function unactive_product($product_id)
    {
        $this->check();
        Product::where('product_id', $product_id)->update(['product_status' => 1]);
        return Redirect::to('all-product');
    }
    public function active_product($product_id)
    {
        $this->check();
        Product::where('product_id', $product_id)->update(['product_status' => 0]);
        return Redirect::to('all-product');
    }

    //
    public function edit_product($product_id)
    {
        $this->check();
        $product = Product::where('product_id',$product_id)->first();
        $danhmuc_id= $product->danhmuc_id;
        $cate_id = $product->category_id;
        $cate_product = Category::orderBy('category_id', 'desc')->get();
        $danhmuc = Danhmuc::orderBy('danhmuc_id', 'desc')->get();
        $edit_product = Product::where('product_id', $product_id)->get();
        $manager_product = view('admin.product.edit_product')->with('edit_product', $edit_product)
            ->with('cate_product', $cate_product)->with('danhmuc',$danhmuc)->with('danhmuc_id',$danhmuc_id)->with('cate_id',$cate_id);

        return view('admin_layout')->with('admin.product.edit_product', $manager_product);
    }
    public function update_product(Request $request, $product_id)
    {
//        dd($request->product_danhmuc);
        $this->check();
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_desc'] = $request->product_desc;
        $data['product_tags'] = $request->product_tags;
        $data['product_quantity'] = $request->product_quantity;
        $data['product_content'] = $request->product_content;
        $data['product_price'] = $request->product_price;
        $data['product_cost'] = $request->product_cost;
        $data['danhmuc_id'] = $request->product_danhmuc;
        $data['category_id'] = $request->product_cate;
        $data['product_sale'] = $request->product_sale;
        $data['product_status'] = $request->product_status;
        $get_image = $request->file('product_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
//            $get_image->move('public/uploads/product', $new_image);
            $get_image->move('uploads/product', $new_image);
            $data['product_image'] = $new_image;
            Product::where('product_id', $product_id)->update($data);
            toastr()->success('Cập nhật thành công');
            Session::put('message', 'Cập nhật thành công');
            return Redirect::to('all-product');
        }
        Product::where('product_id', $product_id)->update($data);
        toastr()->success('Cập nhật thành công');
        Session::put('message', 'Cập nhật thành công');
        return Redirect::to('all-product');
    }
    public function delete_product($product_id)
    {
        $this->check();
        Product::where('product_id', $product_id)->delete();
        return Redirect::to('all-product');
    }
    //End Admin
    public function details_product($product_slug, Request $request)
    {
        $cate_product = Category::where('category_status', '0')->orderBy('category_id', 'desc')->whereNotIn('category_id',[21])->get();
        $details_product = Product::join('tbl_category', 'tbl_product.category_id', '!=', 'tbl_category.category_id')->where('tbl_product.product_slug', $product_slug)
            ->get();
        foreach ($details_product as $key) {

            $category_id = $key->category_id;
            $meta_title = "MVPetShop.vn" . ' | ' . $key->product_slug;

            $product_id = $key->product_id;
        }
        $commentProduct = Product::where('product_buy', 'LIKE', '%' . Session::get('customer_id') . '%')->first();
        //update views product
        $product = Product::where('product_slug', $product_slug)->first();
//        dd($product)
        $product->product_views = $product->product_views + 1;
        $product->save();
        // gallery
        $gallery = Gallery::where('product_id', $product_id)->get();
        $related_product = Product::join('tbl_category', 'tbl_product.category_id', '=', 'tbl_category.category_id')->where('tbl_product.category_id', $category_id)->whereNotIn('tbl_product.product_slug', [$product_slug])->limit(6)
            ->get();
        $rating = Rating::where('product_id', $product_id)->avg('rating');
        $rating = number_format($rating, 2, '.', '');
        $rating_all = Rating::where('product_id', $product_id)->get();
        $rating1 = Rating::where('product_id', $product_id)->where('rating',1)->get();
        $rating2 = Rating::where('product_id', $product_id)->where('rating',2)->get();
        $rating3 = Rating::where('product_id', $product_id)->where('rating',3)->get();
        $rating4 = Rating::where('product_id', $product_id)->where('rating',4)->get();
        $rating5 = Rating::where('product_id', $product_id)->where('rating',5)->get();
        $countrate = count($rating_all);
        $countrate1 = count($rating1);
        $countrate2 = count($rating2);
        $countrate3 = count($rating3);
        $countrate4 = count($rating4);
        $countrate5 = count($rating5);
        if ($category_id != '') {
            return view('pages.product.show_product')->with('category', $cate_product)->with('show_product', $details_product)->with('related_product', $related_product)->with(compact( 'gallery',  'meta_title', 'rating', 'product','rating1','countrate','countrate5','countrate4','countrate3','countrate2','countrate1','countrate','commentProduct'));
        } else {
            return view('pages.error');
        }
    }
    public function export_product()
    {
        return Excel::download(new ProductExports, 'product_product.xlsx');
    }
    public function import_product(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        Excel::import(new ProductImports, $path);
        return back();
    }
    public function import_word(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        Excel::import(new ExportsWordImport, $path);
        return back();
    }
    public function quickview(Request $request)
    {
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        $gallery = Gallery::where('product_id', $product_id)->get();
        $output['product_gallery'] = '';
        foreach ($gallery as $key => $gal) {
            $output['product_gallery'] .= '<p><img width="100%" src="public/uploads/gallery/' . $gal->gallery_image . '"></p>';
        }
        $output['product_name'] = $product->product_name;
        $output['product_id'] = $product->product_id;
        $output['product_desc'] = $product->product_desc;
        $output['product_tags'] = $product->product_tags;
        $output['product_content'] = $product->product_content;
        $output['product_price'] = number_format($product->product_price);
        $output['product_image'] = '<p><img style="margin-top:20px;margin-left:15px" width="100%" src="public/uploads/gallery/' . $gal->gallery_image . '"></p>';

        $output['product_button'] = '<input type="button" value="Mua ngay" class="btn btn-default add-to-cart-quickview" id="buy_quickview" data-id_product="' . $product->product_id . '" name="add-to-cart">
        ';
        $output['product_quickview_value'] = '
        <input type="hidden" value="' . $product->product_id . '" class="cart_product_id_' . $product->product_id . '">
        <input type="hidden" value="' . $product->product_name . '" class="cart_product_name_' . $product->product_id . '">
        <input type="hidden" value="' . $product->product_image . '" class="cart_product_image_' . $product->product_id . '">
        <input type="hidden" value="' . $product->product_quantity . '" class="cart_product_quantity_' . $product->product_id . '">
        <input type="hidden" value="' . $product->product_price . '" class="cart_product_price_' . $product->product_id . '">
        <input type="hidden" value="1" class="cart_product_qty_' . $product->product_id . '">';
        echo json_encode($output);
    }
    public function tag(Request $request, $product_tags)
    {
        $meta_desc = "Cung cấp thức ăn cho thú cưng";
        $meta_keywords = "thucanchocho, thucanchomeo, thức ăn thú cưng";
        $meta_title = "MVPetShop.vn | Tìm kiếm tags";
        $url_canonical = $request->url();
        $category = Category::where('category_status', '0')->orderBy('category_id', 'desc')->get();
        $banner = DB::table('tbl_banner')->get();
        $tag = str_replace(".", " ", $product_tags);
        $product_tag = Product::where('product_status', 0)->where('product_name', 'like', '%' . $tag . '%')
            ->orWhere('product_tags', 'like', '%' . $tag . '%')
            ->orWhere('product_slug', 'like', '%' . $tag . '%')
            ->get();
        return view('pages.product.tag')->with(compact('banner','category', 'meta_desc', 'meta_keywords', 'meta_title', 'url_canonical', 'product_tags', 'product_tag'));
    }
    public function load_comment(Request $request)
    {
        $product_id = $request->product_id;
        $comments = Comment::where('comment_product_id', $product_id)
            // ->where('comment_parent_comment',null)
            ->get();
        $output = '';

        foreach ($comments as $key => $comment) {

//            if ($comment->comment_parent_comment != null) {
//                $output .= '
//                    <div class="row comment_reply style_comment">
//                    <div class="col-md-2">
//                        <img src="public/fronent/images/" alt="">
//                    </div>
//                    <div class="col-md-10">
//                        <p style="color: blue">' . $comment->comment_name . '</p>
//                        <p>' . $comment->comment . '</p>
//                    </div>
//                </div>
//                <p></p>
//                    ';
//            }else {
//                $output .= '
//                <div class="row style_comment">
//                    <div class="col-md-2">
//                        <img src="public/fronent/images/" alt="">
//                    </div>
//                    <div class="col-md-10">
//                        <p style="color: blue">' . $comment->comment_name . '</p>
//                        <p>' . $comment->comment . '</p>
//                    </div>
//                </div>
//                <p></p>
//                ';
//            }
            if ($comment->comment_parent_comment != null) {
                $comment_chill_id = $comment->comment_parent_comment;
                $output .= '
        <div class="row style_comment">
            <div class="col-md-2">
                <img src="public/fronent/images/" alt="">
            </div>
            <div class="col-md-10">
                <p style="color: blue">' . $comment->comment_name . '</p>
                <p>' . $comment->comment . '</p>
            </div>
        </div>
        <p></p>
        <div class="row comment_reply style_comment">
            <div class="col-md-2">
                <img src="public/fronent/images/" alt="">
            </div>
            <div class="col-md-10">
                <p style="color: blue">Comment Reply</p>
                <p>Reply content goes here...</p>
            </div>
        </div>
        <p></p>';
            } else {
                $output .= '
        <div class="row style_comment">
            <div class="col-md-2">
                <img src="public/fronent/images/" alt="">
            </div>
            <div class="col-md-10">
                <p style="color: blue">' . $comment->comment_name . '</p>
                <p>' . $comment->comment . '</p>
            </div>
        </div>
        <p></p>';
            }

        }
        echo $output;
    }
    public function send_comment(Request $request)
    {
        $product_id = $request->product_id;
        $comment_name = $request->comment_name;
        $comment_content = $request->comment_content;
        $comment = new Comment();
        $comment->comment = $comment_content;
        $comment->comment_name = $comment_name;
        $comment->comment_product_id = $product_id;
        $comment->save();
    }
    //Admin
    public function list_comment()
    {
        $this->check();
        $comments = Comment::with('product')
            ->OrderBy('comment_date', 'desc')
            ->where('comment_parent_comment', null)
            ->get();
        return view('admin.comment.list_comment')->with(compact('comments'));
    }
     public function delete_comment($comment_id){
         $comment = Comment::findOrFail($comment_id);
         $commentreply = Comment::where('comment_parent_comment',$comment_id);
         $result_reply = $commentreply->delete();
         $result = $comment->delete();
         if($result){
             toastr()->success('Xóa bình luận thành công');
             Session::put('message','Xóa bình luận thành công');
             return Redirect::to('list-comment');
         }else{
             toastr()->error('Xóa bình luận thất bại');
             Session::put('message','Xóa bình luận thất bại');
             return Redirect::to('list-comment');
         }
     }
    public function reply_comment(Request $request)
    {
        $data = $request->all();
        $comment = new Comment();
        $comment->comment = $data['comment'];
        $comment->comment_product_id = $data['comment_product_id'];
        $comment->comment_parent_comment = $data['comment_id'];
        $comment->comment_name = "MVPetShop.vn";
        $comment->save();
    }
    // public function allow_comment(Request $request){
    //     $data = $request->all();
    //     $comment = Comment::find($data['comment_id']);
    //     $comment->comment_status = $data['comment_status'];
    //     $comment->save();
    // }
    public function insert_rating(Request $request)
    {
        $data = $request->all();
        $rating = new Rating();
        $rating->product_id = $data['product_id'];
        $rating->rating = $data['index'];
        $rating->save();
        echo 'done';
    }
    public function wishlist(Request $request)
    {
        $meta_title = "MVPetShop.vn | Danh sách yêu thích";
        $category = Category::whereNotIn('category_id',[21])->get();
        $user_id = Session::get('customer_id');
        $wishlist = Favorite::where('user_id', $user_id)->where('del_flg', 0)->get();
        if (!$user_id) {
            return view('pages.checkout.login_checkout', compact('category', 'meta_title'));
        } else {
            return view('pages.wishlist.wishlist', compact('category', 'wishlist', 'meta_title'));
        }
    }
    public function addWishlist(Request $request, $product_favorite_id)
    {
        $meta_desc = "Cung cấp thức ăn cho thú cưng";
        $meta_keywords = "thucanchocho, thucanchomeo, thức ăn thú cưng";
        $meta_title = "MVPetShop.vn | Đăng nhập";
        $url_canonical = $request->url();
        $category = Category::whereNotIn('category_id',[21,25])->get();
        $product_id_add = $request->product_id;
        $user_id_add = Session::get('customer_id');
        $product_id_add = $request->product_id;
        //
        $exitsFavorite = Favorite::where('product_id', $product_id_add)->where('user_id', $user_id_add)->first();
        // dd($exitsFavorite);
        if ($user_id_add) {
            if (!$exitsFavorite) {
                $favorite = new Favorite();
                $favorite->product_id = $product_id_add;
                $favorite->user_id = $user_id_add;
                $favorite->save();
                return redirect()->back()->with('message', 'Thêm vào danh sách yêu thích thành công');
            } elseif ($exitsFavorite->del_flg == 0) {
                return redirect()->back()->with('message', 'Sản phẩm đã có trong danh sách yêu thích');
                // print_r('đã tồn tại');
            } else {
                $exitsFavorite->update(['del_flg' => 0]);
                return redirect()->back()->with(compact('exitsFavorite', 'category'))->with('message', 'Thêm vào danh sách yêu thích thành công');
            }
        } else {
            return view('pages.checkout.login_checkout', compact('category', 'meta_desc', 'meta_keywords', 'meta_title', 'url_canonical'));
        }
    }
    public function delWishlist($product_favorite_id)
    {

//        Favorite::where('product_favorite_id', $product_favorite_id)->update(['del_flg' => 1]);
        $user_id = Session::get('customer_id');
        $favorite = Favorite::where('product_id', $product_favorite_id)->where('user_id',$user_id)->get();
        if ($favorite->count() > 0) {
            $favorite->first()->delete();
            toastr()->success('Xóa thành công !!');
        } else {
            toastr()->error('Xóa thất bại !!');
        }
        return redirect()->back();
    }
}
