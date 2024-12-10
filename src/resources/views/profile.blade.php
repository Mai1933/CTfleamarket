@extends('layouts/header_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="list_content">
    <div class="list_content-user">
        <div class="user_info">
            <img src="{{ asset('storage/miu.png')}}" alt="user_image" class="user_image">
            <span class="user_name">ユーザー名</span>
        </div>
        <div class="user_edit">
            <a href="/mypage/profile" class="edit_button">プロフィールを編集</a>
        </div>
    </div>
    <div class="list_content-choice">
        <button class="choice-sell">出品した商品</button>
        <button class="choice-buy">購入した商品</button>
    </div>
    <div class="list_content-products">
        <div class="product">
            <img src="{{ asset('storage/miu.png')}}" alt="item" class="product_img">
            <p class="product_name">商品名</p>
        </div>
        <div class="product">
            <img src="{{ asset('storage/miu.png')}}" alt="item" class="product_img">
            <p class="product_name">商品名</p>
        </div>
        <div class="product">
            <img src="{{ asset('storage/miu.png')}}" alt="item" class="product_img">
            <p class="product_name">商品名</p>
        </div>
        <div class="product">
            <img src="{{ asset('storage/miu.png')}}" alt="item" class="product_img">
            <p class="product_name">商品名</p>
        </div>
        <div class="product">
            <img src="{{ asset('storage/miu.png')}}" alt="item" class="product_img">
            <p class="product_name">商品名</p>
        </div>
        <div class="product">
            <img src="{{ asset('storage/miu.png')}}" alt="item" class="product_img">
            <p class="product_name">商品名</p>
        </div>
    </div>
</div>