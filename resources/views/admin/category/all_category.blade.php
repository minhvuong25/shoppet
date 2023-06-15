@extends('admin_layout')
@section('admin_content')
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left">
                    {{ __('List brand') }}
                    <a class="btn btn-xs btn-primary" href="{{ URL::to('add-category') }}">{{ __('Add brand') }}</a>
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
                        <th>{{ __('tendanhmuc') }}</th>
                        <th>{{ __('mota') }}</th>
                        <th>{{ __('hienthi') }}</th>
                        <th style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_category as $key => $cate_pro)
                        <tr>
                            <td>{{ $cate_pro->category_name }}</td>
                            <td>{!! $cate_pro->category_desc !!}</td>
                            <td><span class="text-ellipsis">
                                    <?php
                    if($cate_pro->category_status==0){
                ?>
                                    <a href="{{ URL::to('/unactive-category/' . $cate_pro->category_id) }}"><span
                                            class="fa-thumbs-styling fa fa-thumbs-down"></span></a>
                                    <?php
                    }else{

                    ?>
                                    <a href="{{ URL::to('/active-category/' . $cate_pro->category_id) }}"><span
                                            class="fa-thumbs-styling fa fa-thumbs-up"></span></a>
                                    <?php
                    }
                 ?>
                                </span></td>
                            <td>
                                <a href="{{ URL::to('/edit-category/' . $cate_pro->category_id) }}" class="active"
                                    ui-toggle-class=""><i
                                        class="fa fa-pencil-square-o text-success text-active"></i></a>
                                <a onclick="return confirm('Bạn có muốn xóa?')"
                                    href="{{ URL::to('/delete-category/' . $cate_pro->category_id) }}"
                                    class="active" ui-toggle-class=""><i
                                        class="fa fa-times text-danger text"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <form action="{{ url('import-csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".xlsx"><br>
                <input type="submit" value="{{ __('import') }}" name="import_csv" class="btn btn-warning">
            </form>
            <form action="{{ url('export-csv') }}" method="POST">
                @csrf
                <input type="submit" value="{{ __('export') }}" name="export_csv" class="btn btn-success">
            </form>
        </div>
    </div>
@endsection
