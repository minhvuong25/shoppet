@extends('layout_mxh')
@section('content_mxh')
    <div class="container">
        <div class="header">
            <h2>{{__("keyword")}}: {{$keyword}}</h2>
        </div>
        <div class="row">
            <div class="leftcolumn">
                @foreach($posts as $post)
                    <div class="card">
                        <h2><a href="{{route('PostDetail',['post_id'=>$post['post_id']])}}" class="text-dark">{{$post['post_desc']}}</a></h2>
                        <h5>{{ $post['created_at']->format('Y-m-d') }}</h5>
                        <img class="fakeimg" src="{{asset('uploads/post/'.$post['post_image'])}}">
                        <p>{!! \Illuminate\Support\Str::limit($post['post_content'],300) !!}</p>
                    </div>
                @endforeach
                <div class="mt-5 d-flex justify_content-center">
                    {{$posts->links()}}
                </div>
            </div>
            <div class="rightcolumn">
                @foreach($allPost as $post)
                    <a href="{{route('PostDetail',['post_id'=>$post['post_id']])}}">
                        <div class="card">
                            <p style="font-size: 16px">{{$post['post_desc']}}</p>
                            <img src="{{asset('uploads/post/'.$post['post_image'])}}">
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
