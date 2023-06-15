@extends('admin_layout')
@section('admin_content')
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left">
                    {{ __('List banner') }}
                    <a class="btn btn-xs btn-primary" href="{{ URL::to('add-banner') }}">{{ __('Add banner') }}</a>
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
                            <th style="width:20px;">
                                <label class="i-checks m-b-none">
                                    <input type="checkbox"><i></i>
                                </label>
                            </th>
                            <th>{{ __('tenbanner') }}</th>
                            <th>{{ __('hinhanh') }}</th>
                            <th>{{ __('mota') }}</th>
                            <th>{{ __('noidung') }}</th>
                            <th>{{ __('hienthi') }}</th>
                            <th style="width:30px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_banner as $key => $pro)
                            <tr>
                                <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label>
                                </td>
                                <td>{{ $pro->banner_name }}</td>
                                <td><img src="{{ asset('/uploads/product/' . $pro->banner_image) }}" alt="" style="width:100%;height:50px"></td>
                                <td>{{ $pro->banner_desc }}</td>
                                <td>{{ $pro->banner_content }}</td>
                                <td><span class="text-ellipsis">
                                        <?php
                    if($pro->banner_status==0){
                ?>
                                        <a href="{{ URL::to('/unactive-banner/' . $pro->banner_id) }}"><span
                                                class="fa-thumbs-styling fa fa-thumbs-down"></span></a>
                                        <?php
                    }else{

                    ?>
                                        <a href="{{ URL::to('/active-banner/' . $pro->banner_id) }}"><span
                                                class="fa-thumbs-styling fa fa-thumbs-up"></span></a>
                                        <?php
                    }
                 ?>
                                    </span></td>
                                <td>
                                    <a href="{{ URL::to('/edit-banner/' . $pro->banner_id) }}" class="active"
                                        ui-toggle-class=""><i
                                            class="fa fa-pencil-square-o text-success text-active"></i></a>
                                    <a onclick="return confirm('Bạn có muốn xóa?')"
                                        href="{{ URL::to('/delete-banner/' . $pro->banner_id) }}" class="active"
                                        ui-toggle-class=""><i class="fa fa-times text-danger text"></i></a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
