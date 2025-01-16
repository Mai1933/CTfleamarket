@extends('layouts/header_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<main>
    <div class="detail_content">
        <div class="content_image">
            <img src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="item_image" class="item_image">
        </div>
        <div class="content_others">
            <div class="content_others_inner">
                <div class="item_names">
                    <h3 class="item_name">{{ $item->item_name }}</h3>
                    <span class="item_brand">{{ $item->brand }}</span>
                    <div class="item_prices">
                        <span class="price_detail">￥</span>
                        <span class="price">{{ $item->price }}</span>
                        <span class="price_detail">（税込）</span>
                    </div>
                </div>
                <div class="item_evaluations">
                    <div class="favorite">
                        @if ($item->is_liked_by_auth_user())
                            <a href="/item/unlike/{{$item->id}}" class="favorite_button">
                                <img src="{{ asset('storage/comrade.png') }}" alt="favorite" class="evaluation_icon">
                                <span class="evaluation_count">{{ $item->favorites->count() }}</span>
                            </a>
                        @else
                            <a href="/item/like/{{$item->id}}" class="favorite_button">
                                <img src="{{ asset('storage/star.png') }}" alt="favorite" class="evaluation_icon">
                                <span class="evaluation_count">{{ $item->favorites->count() }}</span>
                            </a>
                        @endif
                    </div>
                    <div class="comments">
                        <img src="{{ asset('storage/comment.png') }}" alt="comment" class="evaluation_icon">
                        <span class="evaluation_count">{{ $commentNumber }}</span>
                    </div>
                </div>
                <div class="item_link">
                    <a href="/purchase/{{ $item->id}}" class="item_purchase">購入手続きへ</a>
                </div>
                <div class="item_infomation">
                    <span class="infommation_ttl">商品説明</span>
                    <p class="infomation_description">
                        カラー：{{ $item->color }}
                    </p>
                    <p class="infomation_description">
                        {{ $item->description }}
                    </p>
                    <span class="infommation_ttl">商品の情報</span>
                    <div class="categories">
                        <span class="ttl">カテゴリー</span>
                        <div class="categories_inner">
                            @foreach ($categories as $category)
                                <span class="categories_category">{{ $category->category_content }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="conditions">
                        <span class="ttl">商品の状態</span>
                        <span class="conditions_condition">{{ $item->condition }}</span>
                    </div>
                    <div class="infomation_comments">
                        <span class="comments_count">コメント({{ $commentNumber }})</span>
                        <div class="comments_content">
                            @if ($comments->isEmpty())
                                <p>コメントはまだありません</p>
                            @else
                                @foreach ($comments as $comment)
                                    <div class="user">
                                        <img src="{{ asset('storage/item_image/' . $comment['user_image']) }}" alt=""
                                            class="user_image">
                                        <span class="user_name">{{ $comment['user_name'] }}</span>
                                    </div>
                                    <p class="user_comment">{{ $comment['content'] }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <form action="/comment" class="comment_form" method="post">
                        @csrf
                        <span class="form_ttl">商品へのコメント</span>
                        @if ($errors->any())
                            <div class="error">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <textarea name="comment" id="" class="form_input"></textarea>
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <button type="submit" class="form_button">コメントを送信する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>