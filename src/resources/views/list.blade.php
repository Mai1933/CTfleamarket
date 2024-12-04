@extends('layouts/header_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="list_content">
    <div class="list_content-choice">
        <button class="choice-reccomend">おすすめ</button>
        <button class="choice-mylist">マイリスト</button>
    </div>
    <div class="list_content-products">
        <div class="product">
            <img src="{{ asset('storage/miu.png')}}" alt="item" class="product_img">
            <p class="product_name">商品名</p>
        </div>
    </div>
</div>