@extends('admin_layout')
@section('admin_content')
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left">
                    Danh mục
                    <a class="btn btn-xs btn-primary" href="{{ URL::to('add-danhmuc') }}">Thêm danh mục</a>
                </div>
            </div>
        </div>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<span class="text-alert">' . $message . '</span>';
            Session::put('message', null);
        }
        ?>
        <div class="table-responsive">
            <table class="table table-striped b-t b-light">
                <thead>
                    <tr>
                        <th>Danh mục</th>
                        <th>Mô tả</th>
                        <th>Hiển thị</th>
                        <th style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_danhmuc as $key => $cate_pro)
                        <tr>
                            <td>{{ $cate_pro->danhmuc_name }}</td>
                            <td>{!! $cate_pro->danhmuc_desc !!}</td>
                            <td><span class="text-ellipsis">
                                    <?php
                    if($cate_pro->danhmuc_status==0){
                ?>
                                    <a href="{{ URL::to('/unactive-danhmuc/' . $cate_pro->danhmuc_id) }}"><span
                                            class="fa-thumbs-styling fa fa-thumbs-down"></span></a>
                                    <?php
                    }else{
                    ?>
                                    <a href="{{ URL::to('/active-danhmuc/' . $cate_pro->danhmuc_id) }}"><span
                                            class="fa-thumbs-styling fa fa-thumbs-up"></span></a>
                                    <?php
                    }
                 ?>
                                </span></td>
                            <td>
                                <a href="{{ URL::to('/edit-danhmuc/' . $cate_pro->danhmuc_id) }}" class="active"
                                    ui-toggle-class=""><i
                                        class="fa fa-pencil-square-o text-success text-active"></i></a>
                                <a onclick="return confirm('Bạn có muốn xóa?')"
                                    href="{{ URL::to('/delete-danhmuc/' . $cate_pro->danhmuc_id) }}"
                                    class="active" ui-toggle-class=""><i
                                        class="fa fa-times text-danger text"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
