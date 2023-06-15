<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\MXH;

class CategoryMxhController extends Controller
{
    public function check(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function addCategoryMxh()
    {
        $this->check();
        return view('admin.category_mxh.add_categorymxh');
    }
    public function allCategoryMxh()
    {
        $this->check();
        // $all_category = DB::table('tbl_category')->get();
        $all_category = MXH::all();
        $all_category = MXH::orderBy('category_mxh_id','desc')->get();
        $manager_category = view('admin.category_mxh.all_categorymxh')->with(compact('all_category'));
        return view('admin_layout')->with('admin.category_mxh.all_categorymxh',$manager_category);
    }
    public function saveCategoryMxh(Request $request)
    {
        $this->check();
        $data = $request->all();
        $category = new MXH();
        $category->category_mxh_name = $data['category_mxh_name'];
        $category->category_mxh_desc = $data['category_mxh_desc'];
        $category->category_mxh_status = $data['category_mxh_status'];
        $category->save();

        // $data = array();
        // $data['category_name'] = $request->category_name;
        // $data['category_desc'] = $request->category_desc;
        // $data['category_status'] = $request->category_status;
        // DB::table('tbl_category')->insert($data);
        Session::put('message','Thêm thành công');
        return Redirect::to('all-category-mxh');
    }
    public function unactiveCategoryMxh($category_mxh_id){
        $this->check();
        MXH::where('category_mxh_id',$category_mxh_id)->update(['category_mxh_status'=>1]);
        return Redirect::to('all-category-mxh');
    }
    public function activeCategoryMxh($category_mxh_id){
        $this->check();
        MXH::where('category_mxh_id',$category_mxh_id)->update(['category_mxh_status'=>0]);
        return Redirect::to('all-category-mxh');
    }
    public function editCategoryMxh($category_id){
        $this->check();
        // $edit_category = DB::table('tbl_category')->where('category_id',$category_id)->get();
        $edit_category = MXH::find($category_id);
//        $edit_category = MXH::where('category_mxh_id',$category_id)->get();

        // $edit_category = Category::where('category_id',$category_id)->get();
        $image = $edit_category->post->first() ;
        $edit_category->post_image = $image->post_image ;
        $manager_category = view('admin.category_mxh.edit_categorymxh')->with('edit_category',$edit_category);

        return view('admin_layout')->with('admin.category_mxh.edit_categorymxh',$manager_category);
    }
    public function updateCategoryMxh(Request $request,$category_mxh_id){
        $this->check();
        // $data = array();
        // $data['category_name'] = $request->category_name;
        // $data['category_desc'] = $request->category_desc;
        $data = $request->all();
        if ($request->hasFile('category_mxh_image')) {
            $image = $request->file('category_mxh_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/post/'), $imageName);
            Post::where('category_mxh_id',$category_mxh_id)->first()->update(['post_image'=> $imageName]);
        }

        $category = MXH::find($category_mxh_id);
        $category->category_mxh_name = $data['category_mxh_name'];
        $category->category_mxh_desc = $data['category_mxh_desc'];
        $category->category_mxh_status = 1;
        $category->save();
        // DB::table('tbl_category')->where('category_id',$category_id)->update($data);
        return Redirect::to('all-category-mxh');
    }
    public function deleteCategoryMxh($category_mxh_id){
        $this->check();
        MXH::where('category_mxh_id',$category_mxh_id)->delete();
        return Redirect::to('all-category-mxh');
    }
}
