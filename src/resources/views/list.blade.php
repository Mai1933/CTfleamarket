@extends('layouts/header_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="list_content">
    <input id="tab1" type="radio" name="tab_btn" checked>
    <input id="tab2" type="radio" name="tab_btn">

    <div class="list_content-choice">
        <label class="tab1_label" for="tab1">おすすめ</label>
        <label class="tab2_label" for="tab2">マイリスト</label>
    </div>
    <div class="list_content-tabs">
        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif
        <div id="panel1" class="list_content-products">
            @foreach ($items as $item)
                <div class="product">
                    <a href="/item/{{$item->id}}" class="product-link">
                        @if ($item->status == 'stock')
                            <img src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="item" class="product_img">
                        @else
                            <img src="{{ asset('storage/sold.png') }}" alt="item" class="product_img">
                        @endif
                        <p class="product_name">{{ $item->item_name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
        <div id="panel2" class="list_content-favorites">
            @if ($favorites->isEmpty())
                <p class="no_favorite">　
                </p>
            @else
                @foreach ($favorites as $favorite)
                    <div class="product">
                        <a href="/item/{{$favorite->id}}" class="product-link">
                            @if ($favorite->status == 'stock')
                                <img src="{{ asset('storage/item_image/' . $favorite->item_image) }}" alt="item"
                                    class="product_img">
                            @else
                                <img src="{{ asset('storage/sold.png') }}" alt="item" class="product_img">
                            @endif
                            <p class="product_name">{{ $favorite->item_name }}</p>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection