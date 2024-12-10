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
        @foreach ($items as $item)
            <div class="product">
                <a href="/item/{{$item->id}}" class="product-link"></a>
                <img src="{{ asset('storage/' . $item->item_image) }}" alt="item" class="product_img">
                <p class="product_name">{{ $item->item_name }}</p>
            </div>
        @endforeach
    </div>
</div>