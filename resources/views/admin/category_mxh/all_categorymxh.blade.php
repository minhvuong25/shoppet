@extends('admin_layout')
@section('admin_content')
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left">
                    Danh mục Blog
                    <a class="btn btn-xs btn-primary" href="{{ URL::to('add-category-mxh') }}">Thêm mới</a>
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
                        <th>Tên danh mục blog</th>
                        <th>Mô tả</th>
                        <th>Hiển thị</th>
                        <th style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_category as $key => $cate_pro)
                        <tr>
                            <td>{{ $cate_pro->category_mxh_name }}</td>
                            <td>{{ $cate_pro->category_mxh_desc }}</td>
                            <td><span class="text-ellipsis">
                                    <?php
                    if($cate_pro->category_mxh_status==0){
                ?>
                                    <a href="{{ URL::to('/unactive-category-mxh/' . $cate_pro->category_mxh_id) }}"><span
                                            class="fa-thumbs-styling fa fa-thumbs-down"></span></a>
                                    <?php
                    }else{

                    ?>
                                    <a href="{{ URL::to('/active-category-mxh/' . $cate_pro->category_mxh_id) }}"><span
                                            class="fa-thumbs-styling fa fa-thumbs-up"></span></a>
                                    <?php
                    }
                 ?>
                                </span></td>
                            <td>
                                <a href="{{ URL::to('/edit-category-mxh/' . $cate_pro->category_mxh_id) }}" class="active"
                                    ui-toggle-class=""><i
                                        class="fa fa-pencil-square-o text-success text-active"></i></a>
                                <a onclick="return confirm('Bạn có muốn xóa?')"
                                    href="{{ URL::to('/delete-category-mxh/' . $cate_pro->category_mxh_id) }}"
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
