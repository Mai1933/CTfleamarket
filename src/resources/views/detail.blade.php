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
                        <img src="{{ asset('storage/star.png') }}" alt="favorite" class="evaluation_icon">
                        <span class="evaluation_count">3</span>
                    </div>
                    <div class="comments">
                        <img src="{{ asset('storage/comment.png') }}" alt="comment" class="evaluation_icon">
                        <span class="evaluation_count">1</span>
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
                            <span class="categories_category">洋服</span>
                            <span class="categories_category">メンズ</span>
                        </div>
                    </div>
                    <div class="conditions">
                        <span class="ttl">商品の状態</span>
                        <span class="conditions_condition">{{ $item->condition }}</span>
                    </div>
                    <div class="infomation_comments">
                        <span class="comments_count">コメント(1)</span>
                        <div class="comments_content">
                            <div class="user">
                                <img src="{{ asset('storage/miu.png') }}" alt="" class="user_image">
                                <span class="user_name">admin</span>
                            </div>
                            <p class="user_comment">こちらにコメントが入ります。</p>
                        </div>
                    </div>
                    <form action="" class="comment_form">
                        <span class="form_ttl">商品へのコメント</span>
                        <textarea name="comment" id="" class="form_input"></textarea>
                        <button type="submit" class="form_button">コメントを送信する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>