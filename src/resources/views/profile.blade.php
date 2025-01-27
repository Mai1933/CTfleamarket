@extends('layouts/header_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="list_content">
    <div class="list_content-user">
        <div class="user_info">
            @if (empty(($user->user_image)))
                <img src="{{ asset('storage/no_image.png') }}" alt="user_image" class="user_image">
            @else
                <img src="{{ asset('storage/user_image/' . $user->user_image) }}" alt="user_image" class="user_image">
            @endif
            <span class="user_name">{{ $user->name }}</span>
        </div>
        <div class="user_edit">
            <a href="/mypage/profile" class="edit_button">プロフィールを編集</a>
        </div>
    </div>

    <input id="tab1" type="radio" name="tab_btn" checked>
    <input id="tab2" type="radio" name="tab_btn">

    <div class="list_content-choice">
        <label class="tab1_label" for="tab1">出品した商品</label>
        <label class="tab2_label" for="tab2">購入した商品</label>
    </div>
    <div class="list_content-tabs">
        <div id="panel1" class="list_content-sell">
            @foreach ($sellItems as $sellItem)
                <div class="product">
                    <a href="/item/{{$sellItem->id}}" class="product-link">
                        <img src="{{ asset('storage/item_image/' . $sellItem->item_image) }}" alt="item"
                            class="product_img">
                        <p class="product_name">{{ $sellItem->item_name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
        <div id="panel2" class="list_content-buy">
            @foreach ($buyItems as $buyItem)
                <div class="product">
                    <a href="/item/{{$buyItem->id}}" class="product-link">
                        <img src="{{ asset('storage/item_image/' . $buyItem->item_image) }}" alt="item" class="product_img">
                        <p class="product_name">{{ $buyItem->item_name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection