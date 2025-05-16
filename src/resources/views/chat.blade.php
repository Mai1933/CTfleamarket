@extends('layouts/header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
    <main>
        <div class="chat_content">
            <div class="chat_others">
                <div class="chat_others_inner">
                    <p class="chat_others-ttl">その他の取引</p>
                    <a href="" class="chat_others-link">商品名</a>
                </div>
            </div>
            <div class="chat_progressing">
                <div class="chat_progressing-ttl">
                    <img src="" alt="" class="partner_icon">
                    <p class="partner_name">「ユーザー名」さんとの取引画面</p>
                </div>
                <div class="chat_progressing-product">
                    <a href="" class="product_information">
                        <img src="" alt="product" class="product-img">
                        <div class="product_scripts">
                            <p class="product-name">商品名</p>
                            <p class="product-price">商品価格</p>
                        </div>
                    </a>
                </div>
                <div class="chat_progressing-conservation">
                    <div class="conservation-others">
                        <div class="others_information">
                            <img src="" alt="other-img" class="others-image">
                            <p class="others-name">お相手</p>
                        </div>
                        <p class="others_message">相手からのメッセージ</p>
                    </div>
                    <div class="conservation-self">
                        <div class="self_information">
                            <img src="" alt="self-img" class="self-image">
                            <p class="self-name">自分</p>
                        </div>
                        <p class="self_message">自分が送ったメッセージ</p>
                        <div class="self_buttons">
                            <a href="">編集</a>
                            <a href="">削除</a>
                        </div>
                    </div>
                </div>
                <form action="" class="chat_progressing-inputs" enctype="multipart/form-data">
                    <textarea name="message" id="" class="input-message" placeholder="取引メッセージを記入してください"></textarea>
                    <input type="file" placeholder="画像を追加" name="image">
                    <button type="submit" class="message-submit"><img src="" alt="submit"></button>
                </form>
            </div>
        </div>
    </main>
@endsection