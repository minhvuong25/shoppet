@extends('layout_mxh')
@section('content_mxh')
    <main class="site-content">
        <div class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-8 ml-auto">
                        <article class="entry">
                            <p>{!! $inForPost->post_content !!}</p>
                            <div style="display: flex;justify-content: space-between;">Tác giả :
                                {{ $inForPost->author->customer_name }}
                                <div>Thời gian : {{ $inForPost->created_at }}</div>
                            </div>
                            <div class="post-reaction">
                                <div class="activity-icons" style="display: flex;margin-top:15px">
                                    <div><a href="{{URL::to('/like/'.$inForPost->post_id)}}"><i class="pegk pe-7s-like2" style="font-size: 16px"></i></a></div>
                                    <div style="margin-left: 15px"><a href="{{URL::to('/heart/'.$inForPost->post_id)}}"><i class="pegk pe-7s-like" style="font-size: 16px"></i></a></div>
                                </div>
                            </div>
                        </article>
                        <div>
                            <h4>Bình luận</h4>
                            @foreach ($commentPost as $c)
                            <div class="col-lg-12 mb-3" style="padding: 5px;background:#eeeeee;border:1px solid;border-radius:5px">
                                <div class="col-lg-12">
                                    <p style="font-weight: bold">{{$c->comment_name}}</p>
                                    <span>{{$c->comment}}</span>
                                </div>
                            </div>
                            @endforeach
                            <div style="margin-bottom:15px"></div>
                            <br>
                            @if (Session::get('customer_id'))
                                    <form action="{{ URL::to('add-comment-post') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="comment_post_id" value="{{ $inForPost->post_id }}">
                                        <label for="" style="margin-top: 15px">Tên</label>
                                        <input type="text" class="form-control" name="comment_name"
                                            value="{{ Session::get('customer_name') }}" readonly><br>
                                        <label for="">Bình luận</label>
                                        <textarea type="text" style="resize: none" class="form-control" name="comment" placeholder="Nhập bình luận ..."
                                            rows="4" autocomplete="off"></textarea><br>
                                        <button type="submit" style="background: gray">Gửi</button>
                                    </form>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 sidebar">
                        <aside class="small">
                            <h2 class="h5 mb-4">Tin liên quan</h2>
                            @foreach ($relatedNews as $t)
                                <div class="media pb-2 mb-2 border-bottom">
                                    <a style="display: flex" href="{{ URL::to('pet-mxh/infor/' . $t->post_id) }}">
                                        <img class="mr-3" data-src="holder.js/64x64" alt="64x64"
                                            style="width: 64px; height: 64px;"
                                            src="{{ URL::to('uploads/post/' . $t->post_image) }}"
                                            data-holder-rendered="true">
                                        <div class="media-body">
                                            <h5 class="h6">{{ $t->post_desc }}</h5>
                                            <p class="" style="max-height:3em;overflow:hidden;">
                                                {{ $t->created_at }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
