@extends('admin_layout')
@section('admin_content')
<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                          Chỉnh sửa danh mục sản phẩm
                        </header>
                        <div class="panel-body">
                            <div class="position-center">
                                <form role="form" action="{{URL::to('update-danhmuc/'.$edit_danhmuc->danhmuc_id)}}" method="post">
                                    {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên danh mục</label>
                                    <input type="text" name="danhmuc_name" class="form-control" id="name" value="{{$edit_danhmuc->danhmuc_name}}">
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <label for="exampleInputEmail1">Slug</label>--}}
{{--                                    <input type="text" name="danhmuc_slug" class="form-control" id="slug"  value="{{$edit_danhmuc->danhmuc_slug}}">--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả danh mục</label>
                                    <textarea style="resize: none" rows="5" class="form-control" id="ckeditor_desc" name="danhmuc_desc">
                                        {{$edit_danhmuc->danhmuc_desc}}
                                    </textarea>
                                </div>
                                <button type="submit" name="update_danhmuc" class="btn btn-info">Lưu</button>
                            </form>
                            </div>
                        </div>
                    </section>

            </div>
    <script>
        CKEDITOR.replace('ckeditor_desc')
    </script>
@endsection
