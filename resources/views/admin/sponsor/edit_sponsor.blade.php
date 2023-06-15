@extends('admin_layout')
@section('admin_content')
<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                         Cập nhật tin tức
                        </header>
                        <div class="panel-body">
                            @foreach ($edit_sponsor as $key => $edit_pro)
                            <div class="position-center">
                                <form role="form" action="{{URL::to('update-sponsor/'.$edit_pro->sponsor_id)}}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tiêu đề</label>
                                    <input type="text" name="sponsor_name" class="form-control" id="exampleInputEmail1" value="{{$edit_pro->sponsor_name}}" >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Hình ảnh</label>
                                    <input type="file" name="sponsor_image" class="form-control" id="exampleInputEmail1">
                                    <img src="{{URL::to('/uploads/product/'.$edit_pro->sponsor_image)}}" alt="" height="100px" width="100px" style="margin-top: 10px">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả</label>
                                    <input style="resize: none" rows="5" class="form-control" id="exampleInputPassword1" name="sponsor_desc" value="{{$edit_pro->sponsor_desc}}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Hiển thị</label>
                                    <select name="sponsor_status" class="form-control input-lg m-bot15">
                                        <option value="0">Ẩn</option>
                                        <option value="1">Hiện</option>
                                    </select>
                                </div>
                                <button type="submit" name="update_sponsor" class="btn btn-info">Lưu</button>
                            </form>
                            </div>
                            @endforeach
                        </div>
                    </section>

            </div>
@endsection
