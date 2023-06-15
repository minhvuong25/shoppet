@extends('layout')
@section('content')

<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading text-center" style="font-weight:900;font-size:20px;text-transform:uppercase">
        Liệt kê danh sách mua hàng
      </div>
      <?php
        $message = Session::get('message');
        if ($message) {
            echo '<span class="text-alert">'.$message.'</span>';
            Session::put('message',null);
        }
    ?>
      <div class="table-responsive container">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th>Stt</th>
              <th>Mã đơn hàng</th>
              <th>Ngày đặt hàng</th>
              <th>Tình trạng đơn hàng</th>
              <th style="width:30px;"></th>
            </tr>
          </thead>
          <tbody>
              @php
                  $i = 1;
              @endphp
              @foreach($historyOrder as $or)
            <tr>
              <td>{{$i++}}</td>
              <td>{{$or ->order_code}}</td>
              <td>{{$or ->created_at}}</td>
              <td>
                @if ($or->order_status==1)
                    Đơn hàng đang xử lý
                @elseif ($or->order_status==2)
                    Đã xong
                @else
                    Đơn hàng bị hủy
                @endif

              </td>
              <td>
                <a href="{{URL::to('/detail-history/'.$or->order_code)}}" class="active"><i class="fa fa-eye text-success text-active"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection
