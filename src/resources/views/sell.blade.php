@extends('layouts/header_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<main>
    <div class="sell_content">
        <div class="sell_content-inner">
            <form class="sell_form">
                <h2 class="form_ttl">商品の出品</h2>
                <div class="form_inputs">
                    <div class="input_bunch">
                        <span class="bunch_ttl">商品画像</span>
                        <div class="item_image">
                            <input type="file" class="image_upload" id="image_upload">
                            <label class="custom_button" for="image_upload">画像を選択する</label>
                        </div>
                    </div>
                    <div class="input_index">
                        <span class="ttl_detail">商品の詳細</span>
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">カテゴリー</span>
                        <div class="categories">
                            <input type="checkbox" class="bunch_input-category" name="category" id="fasion" value="ファッション">
                            <label class="category_label" for="fasion">ファッション</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="appliance"value="家電">
                            <label class="category_label" for="appliance">家電</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="interior"value="インテリア">
                            <label class="category_label" for="interior">インテリア</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="ladies" value="レディース">
                            <label class="category_label" for="ladies">レディース</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="mens" value="メンズ">
                            <label class="category_label" for="mens">メンズ</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="cosmetics" value="コスメ">
                            <label class="category_label" for="cosmetics">コスメ</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="books" value="本">
                            <label class="category_label" for="books">本</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="games" value="ゲーム">
                            <label class="category_label" for="games">ゲーム</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="sport" value="スポーツ">
                            <label class="category_label" for="sport">スポーツ</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="kitchen" value="キッチン">
                            <label class="category_label" for="kitchen">キッチン</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="handmade" value="ハンドメイド">
                            <label class="category_label" for="handmade">ハンドメイド</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="accessory" value="アクセサリー">
                            <label class="category_label" for="accessory">アクセサリー</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="toys" value="おもちゃ">
                            <label class="category_label" for="toys">おもちゃ</label>
                            <input type="checkbox" class="bunch_input-category" name="category" id="kids" value="ベビー・キッズ">
                            <label class="category_label" for="kids">ベビー・キッズ</label>
                        </div>
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">商品の状態</span>
                        <select class="bunch_input-select" name="condition" id="select">
                            <option value="選択してください" hidden class="select_option">選択してください</option>
                            <option value="良好" class="select_option">良好</option>
                            <option value="目立った傷や汚れなし" class="select_option">目立った傷や汚れなし</option>
                            <option value="やや傷や汚れあり" class="select_option">やや傷や汚れあり</option>
                            <option value="状態が悪い" class="select_option">状態が悪い</option>
                        </select>
                    </div>
                    <div class="input_index">
                        <span class="ttl_detail">商品名と説明</span>
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">商品名</span>
                        <input type="text" class="bunch_input" name="item_name">
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">ブランド名</span>
                        <input type="text" class="bunch_input" name="brand">
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">商品の説明</span>
                        <textarea class="bunch_input-description" name="item_description"></textarea>
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">販売価格</span>
                        <input type="text" class="bunch_input" name="price" placeholder="￥">
                    </div>
                </div>
                <button type="submit" class="form_submit">出品する</button>
            </form>
        </div>
    </div>
</main>