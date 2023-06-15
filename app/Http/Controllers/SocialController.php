<?php

namespace App\Http\Controllers;

use App\Models\CommentPost;
use App\Models\Customer;
use App\Models\MXH;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class SocialController extends Controller
{
    public function PetMXH(){
        $social = MXH::all();
        $customer = Customer::where('customer_id',Session::get('customer_id'))->first();
        $postColumn = Post::where('category_mxh_id',22)->get();
        $postColumns = Post::where('category_mxh_id',21)->get();
        $commentPost = CommentPost::all();

        return view('mxh.social',compact('social','customer','postColumn','commentPost','postColumns'));
    }

    public function ProfileCustomer($customer_id){
        $customer = Customer::where('customer_id',$customer_id)->first();
        $cateMxh = MXH::orderBy('category_mxh_id', 'asc')->get();
        $postUser = Post::where('customer_id',Session::get('customer_id'))->get();
        $isPro = true;
        if($customer){
            return view('mxh.profile',compact('customer','cateMxh','postUser','isPro'));
        }else{
            return view('mxh.login')->with('message','Bạn phải đăng nhập mới được xem thông tin');
        }
    }
    public function addPost(Request $request){
        $post = new Post();
        $post->customer_id = Session::get('customer_id');
        $post->category_mxh_id = $request->post_desc_mxh;
        $post->post_desc = $request->post_desc;
        $post->post_content = $request->post_content;
        $get_image = $request->file('post_image');
        if($get_image){
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.',$get_name_image));
            $new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/post',$new_image);
            $post->post_image = $new_image;
        }else{
            $post->post_image = 'default.jpg';
        }
        $post = $post->save();
        return redirect()->back()->with('message','Thêm thành công');
        // $cateMxh = MXH::orderBy('category_mxh_id', 'asc')->get();
        // return view('mxh.add_post',compact('cateMxh'));
    }
    public function delPost($post_id){
        $post = Post::where('post_id', $post_id)->first();
        if(Session::get('customer_id') == $post['customer_id']){
            Post::where('post_id', $post_id)->delete();
            return redirect()->back()->with('message','Xóa thành công');
        }
        return redirect()->back()->with('message','Bạn không có quyền xóa bài viết này !!!');
    }
    public function inFor($post_id){
        $social = MXH::all();
        $inForPost = Post::where('post_id',$post_id)->first();
        $relatedNews = Post::where('category_mxh_id', $inForPost->category_mxh_id)->whereNotIn('post_id', [$post_id])->limit(6)
        ->get();
        $commentPost = CommentPost::where('comment_post_id',$post_id)->get();
        return view('mxh.infor',compact('inForPost','social','relatedNews','commentPost'));
    }
    public function addComment(Request $request){
        $addComment = new CommentPost();
        $addComment->comment_name = $request->comment_name;
        $addComment->comment_post_id = $request->comment_post_id;
        $addComment->comment = $request->comment;
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $addComment->comment_date = $now;
        $addComment->save();
        return redirect()->back()->with('message','Thêm thành công');
    }
    public function likePost($post_id){
        $checkPost = Post::find($post_id);
        $checkPost->post_like_active = 1;
        $checkPost->post_like =  $checkPost->post_like +1;
        $checkPost->save();
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $keyword = $request['keyword'];
        $posts = Post::where('post_desc', 'LIKE', '%'.$keyword.'%')->orderBy('created_at')->paginate(10);
        $allPost = Post::orderBy('created_at')->get();
        return view('mxh.all_post',compact('posts','keyword','allPost'));
    }

    public function edit(){
        $isPro = false;
        $cateMxh = MXH::orderBy('category_mxh_id', 'asc')->get();
        $customer = Customer::where('customer_id', Session::get('customer_id'))->first();
        $postUser = Post::where('customer_id',Session::get('customer_id'))->get();
        return view('mxh.profile',compact('isPro','cateMxh', 'customer','postUser'));
    }

    public function savePost(Request $request)
    {
        $post = Post::where('post_id',$request['post_id'])->first();
        $post->customer_id = Session::get('customer_id');
        $post->category_mxh_id = $request->post_desc_mxh;
        $post->post_desc = $request->post_desc;
        $post->post_content = $request->post_content;
        $get_image = $request->file('post_image');
        if($get_image){
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.',$get_name_image));
            $new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/post',$new_image);
            $post->post_image = $new_image;
        }
        $post = $post->save();
        return redirect()->route('UserMXH',['customer_id'=> Session::get('customer_id')]);
    }
}
