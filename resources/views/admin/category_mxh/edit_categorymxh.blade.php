@extends('admin_layout')
@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Chỉnh sửa danh mục blog
                </header>
                <div class="panel-body">
                    <div class="position-center">
                        <form role="form" action="{{URL::to('update-category-mxh/'.$edit_category->category_mxh_id)}}"
                              method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tên danh mục</label>
                                <input type="text" name="category_mxh_name" class="form-control" id="name"
                                       value="{{$edit_category->category_mxh_name}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Mô tả danh mục</label>
                                <input style="resize: none"  class="form-control"
                                       value="{{$edit_category->category_mxh_desc}}" name="category_mxh_desc">
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label for="exampleInputPassword1">Ảnh danh mục</label>--}}
{{--                                <img style="resize: none ; object-fit: cover ;height: auto" rows="5"--}}
{{--                                     class="form-control"--}}
{{--                                     src="{{asset('/uploads/post/' . $edit_category->post_image )}}" alt="">--}}
{{--                                <br><br><br>--}}
{{--                                <input style="resize: none" rows="5" class="form-control" type="file"--}}
{{--                                       name="category_mxh_image">--}}
{{--                            </div>--}}
                            <button type="submit" name="update_category" class="btn btn-info">Lưu</button>
                        </form>
                    </div>
                </div>
            </section>

        </div>

@endsection
