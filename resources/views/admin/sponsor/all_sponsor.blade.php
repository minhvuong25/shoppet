@extends('admin_layout')
@section('admin_content')

<div class="table-agile-info">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-left">
                Tin tức
                <a class="btn btn-xs btn-primary" href="{{ URL::to('add-sponsor') }}">Thêm tin tức</a>
            </div>
        </div>
    </div>
      <?php
        $message = Session::get('message');
        if ($message) {
            echo '<span class="text-alert">'.$message.'</span>';
            Session::put('message',null);
        }
    ?>
      <div class="table-responsive">
        <table class="table table-striped b-t b-light" id="myTable">
          <thead>
            <tr>
              <th>Tiêu đề</th>
              <th>Hình ảnh</th>
              <th>Mô tả</th>
              <th>Hiển thị</th>
              <th style="width:30px;"></th>
            </tr>
          </thead>
          <tbody>
              @foreach($all_sponsor as $key => $pro)
            <tr>
              <td>{{$pro ->sponsor_name}}</td>
              <td><img src="{{asset('/uploads/product/'.$pro->sponsor_image)}}" alt="" width="100px" height="50px"></td>
              <td>{{$pro->sponsor_desc}}</td>
              <td><span class="text-ellipsis">
                <?php
                    if($pro->sponsor_status==0){
                ?>
                    <a href="{{URL::to('/unactive-sponsor/'.$pro->sponsor_id)}}"><span class="fa-thumbs-styling fa fa-thumbs-down"></span></a>
                    <?php
                    }else{

                    ?>
                        <a href="{{URL::to('/active-sponsor/'.$pro->sponsor_id)}}"><span class="fa-thumbs-styling fa fa-thumbs-up"></span></a>
                    <?php
                    }
                 ?>
                </span></td>
              <td>
                <a href="{{URL::to('/edit-sponsor/'.$pro->sponsor_id)}}" class="active" ui-toggle-class=""><i class="fa fa-pencil-square-o text-success text-active"></i></a>
                <a onclick="return confirm('Bạn có muốn xóa?')" href="{{URL::to('/delete-sponsor/'.$pro->sponsor_id)}}" class="active" ui-toggle-class=""><i class="fa fa-times text-danger text"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  </div>

@endsection
