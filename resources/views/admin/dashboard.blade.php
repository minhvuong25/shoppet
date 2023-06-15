
@extends('admin_layout')
@section('admin_content')
    <h1>{{__('Welcome')}}</h1>
    <?php
        $message = Session::get('message');
        if ($message) {
            echo '<span class="text-alert">'.$message.'</span>';
            Session::put('message',null);
        }
    ?>
@endsection
