<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\Danhmuc;

use Illuminate\Http\Request;

class DanhmucController extends Controller
{
    public function check(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function addDanhmuc()
    {
        $this->check();
        return view('admin.danhmuc.add_danhmuc');
    }
    public function allDanhmuc()
    {
        $this->check();
        // $all_danhmuc = DB::table('tbl_danhmuc')->get();
        $all_danhmuc = Danhmuc::all();
        $all_danhmuc = Danhmuc::orderBy('danhmuc_id','desc')->get();
        $manager_danhmuc = view('admin.danhmuc.all_danhmuc')->with('all_danhmuc',$all_danhmuc);
        return view('admin_layout')->with('admin.danhmuc.all_danhmuc',$manager_danhmuc);
    }
    public function saveDanhmuc(Request $request)
    {
        $this->check();
        $data = $request->all();
        $danhmuc = new danhmuc();
        $danhmuc->danhmuc_name = $data['danhmuc_name'];
        $danhmuc->danhmuc_desc = $data['danhmuc_desc'];
        $danhmuc->danhmuc_status = $data['danhmuc_status'];
        $danhmuc->save();

        // $data = array();
        // $data['danhmuc_name'] = $request->danhmuc_name;
        // $data['danhmuc_desc'] = $request->danhmuc_desc;
        // $data['danhmuc_status'] = $request->danhmuc_status;
        // DB::table('tbl_danhmuc')->insert($data);
        Session::put('message','Thêm thành công');
        return Redirect::to('all-danhmuc');
    }
    public function unactiveDanhmuc($danhmuc_id){
        $this->check();
        danhmuc::where('danhmuc_id',$danhmuc_id)->update(['danhmuc_status'=>1]);
        return Redirect::to('all-danhmuc');
    }
    public function activeDanhmuc($danhmuc_id){
        $this->check();
        danhmuc::where('danhmuc_id',$danhmuc_id)->update(['danhmuc_status'=>0]);
        return Redirect::to('all-danhmuc');
    }
    public function editDanhmuc($danhmuc_id){
        $this->check();
        // $edit_danhmuc = DB::table('tbl_danhmuc')->where('danhmuc_id',$danhmuc_id)->get();
        $edit_danhmuc = danhmuc::find($danhmuc_id);
//        dd($edit_danhmuc);
        // $edit_danhmuc = danhmuc::where('danhmuc_id',$danhmuc_id)->get();

        $manager_danhmuc = view('admin.danhmuc.edit_danhmuc')->with('edit_danhmuc',$edit_danhmuc);
        return view('admin_layout')->with('admin.danhmuc.edit_danhmuc',$manager_danhmuc);
    }
    public function updateDanhmuc(Request $request,$danhmuc_id){
        $this->check();
        // $data = array();
        // $data['danhmuc_name'] = $request->danhmuc_name;
        // $data['danhmuc_desc'] = $request->danhmuc_desc;
        $data = $request->all();
        $danhmuc = danhmuc::find($danhmuc_id);
        $danhmuc->danhmuc_name = $data['danhmuc_name'];
        $danhmuc->danhmuc_desc = $data['danhmuc_desc'];
        $danhmuc->danhmuc_status = 1;
        $danhmuc->save();
        // DB::table('tbl_danhmuc')->where('danhmuc_id',$danhmuc_id)->update($data);
        return Redirect::to('all-danhmuc');
    }
    public function deleteDanhmuc($danhmuc_id){
        $this->check();
        danhmuc::where('danhmuc_id',$danhmuc_id)->delete();
        return Redirect::to('all-danhmuc');
    }
    //End Function
    // public function show_danhmuc_home($danhmuc_id,Request $request){
    //     $meta_desc = "Chuyên bán máy ảnh và phụ kiện máy ảnh";
    //     $meta_keywords = "may anh, máy ảnh, phụ kiện,phu kien, phụ kiện máy ảnh, phu kien may anh";
    //     $meta_title = "MVPetShop.vn";
    //     $url_canonical = $request->url();
    //     $cate_product = danhmuc::where('danhmuc_status','0')->orderBy('danhmuc_id','desc')->get();
    //     $danhmuc_by_id = Product::join('tbl_danhmuc','tbl_product.danhmuc_id','=','tbl_danhmuc.danhmuc_id')->where('tbl_danhmuc.danhmuc_id', $danhmuc_id)->get();
    //     $danhmuc_name = danhmuc::where('tbl_danhmuc.danhmuc_id',$danhmuc_id)->get();
    //     return view('pages.danhmuc.show_danhmuc_home')->with('danhmuc',$cate_product)->with('danhmuc_id',$danhmuc_by_id)->with('danhmuc_name',$danhmuc_name)->with(compact('meta_keywords','meta_desc','meta_title','url_canonical'));
    // }
}
