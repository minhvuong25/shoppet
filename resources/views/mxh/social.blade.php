@extends('layout_mxh')
@section('content_mxh')
    <main class="main columns">
        <!-- 2/3 Main Column -->
        <section class="column main-column">
            @foreach ($postColumns as $s)
                <a class="article first-article" href="{{ URL::to('pet-mxh/infor/' . $s->post_id) }}">
                    <figure class="article-image is-4by3">
                        <img src="{{ URL::to('/uploads/post/' . $s->post_image) }}">
                    </figure>
                    <div class="article-body">
                        <h2 class="article-title">{{ $s->post_desc }}</h2>
                        <p class="article-content" style="overflow: hidden;text-overflow: ellipsis;line-height: 25px;-webkit-line-clamp: 3;height: 75px;display: -webkit-box;-webkit-box-orient: vertical;">
{{--                            {!!  Str::limit( $s->post_content, $limit = 200, $end = '...') !!}--}}
                          ....  {!!  substr($s->post_content, -282) !!}
                        </p>
                        <footer class="article-info">

                            <span>By {{ $s->author->customer_name}}</span>
                            @php
                                $countComment = count($commentPost);
                            @endphp
                            <span>{{ $countComment }} comment</span>
                        </footer>
                    </div>
                </a>
            @endforeach


        </section>

        <!-- 1/3 Column -->
        <section class="column">
            @foreach ($postColumn as $p)
                <a class="article" href="{{ URL::to('pet-mxh/infor/' . $p->post_id) }}">
                    <figure class="article-image is-3by2">
                        <img src="{{ URL::to('/uploads/post/' . $p->post_image) }}">
                    </figure>
                    <div class="article-body">
                        <h2 class="article-title">{{ $p->post_desc }}</h2>
                        <p class="article-content"
                            style="display: -webkit-box;width: 300px;line-height: 25px;overflow: hidden;text-overflow: ellipsis;-webkit-line-clamp: 3;-webkit-box-orient: vertical;">

{{--                            {!!  Str::limit( $p->post_content, $limit = 200, $end = '...') !!}--}}
                    ....        {!!  substr($p->post_content, -282) !!}
                        </p>
                        <footer class="article-info">
                            <span>By {{ $p->author->customer_name }}</span>
                            @php
                                $countComment = count($commentPost);
                            @endphp
                            <span>{{ $countComment }} comment</span>
                        </footer>
                    </div>
                </a>
            @endforeach

        </section>

    </main>
    <style>
        a {
            text-decoration: none;
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
        }

        div,
        h2,
        p,
        figure {
            margin: 0;
            padding: 0;
        }

        .main {
            margin: 0 auto;
            max-width: 1040px;
            padding;
            20px;
        }



        .column {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .main-column {
            flex: 2;
        }

        .article {
            display: flex;
            flex-direction: column;
            flex: 1;
            flex-basis: auto;
            background: #fff;
            color: #666;
            margin: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        }

        .article:hover,
        .article:focus {
            box-shadow: 0 14px 28px rgba(0, 0, 0, .25), 0 10px 10px rgba(0, 0, 0, .22);
            color: #444;
        }

        .article-image {
            background: #eee;
            display: block;
            padding-top: 75%;
            position: relative;
            width: 100%;
        }

        .article-image img {
            display: block;
            height: 100%;
            width: 100%;
            left: 0;
            top: 0;
            position: absolute;
        }

        .article-image.is-3by2 {
            padding-top: 66.6666%;
        }

        .article-image.is-16by9 {
            padding-top: 56.25%;
        }

        .article-body {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 20px;
        }

        .article-title {
            color: #333;
            flex-shrink: 0;
            font-size: 1.4em;
            font-weight: bold;
            font-weight: 700;
            line-height: 1.2;
        }

        .article-content {
            flex: 1;
            margin-top: 5px;
        }

        .article-info {
            display: flex;
            font-size: 0.85em;
            justify-content: space-between;
            margin-top: 10px;
        }

        @media screen and (min-width: 750px) {

            .columns,
            .column {
                display: flex;
            }
        }

    </style>
@endsection
