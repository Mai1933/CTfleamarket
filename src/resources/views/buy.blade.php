@extends('layouts/header_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/buy.css') }}">
@endsection

@section('content')
<main>
    <div class="buy_content">
        <div class="buy_content_innner">
            <div class="infomation">
                <div class="infomation_item">
                    <img src="{{ asset('storage/miu.png') }}" alt="" class="item_image">
                    <div class="item_names">
                        <p class="item_name">商品名</p>
                        <p class="item_price">￥47,000</p>
                    </div>
                </div>
                <div class="infomation_payment">
                    <span class="ttl">支払い方法</span>
                    <select name="payment" id="" class="payment">
                        <option value="選択してください" hidden>選択してください</option>
                        <option value="コンビニ支払い" class="payment_option">コンビニ支払い</option>
                        <option value="カード支払い" class="payment_option">カード支払い</option>
                    </select>
                </div>
                <div class="infomation_address">
                    <div class="address_ttls">
                        <span class="ttl">配送先</span>
                        <a href="" class="edit">変更する</a>
                    </div>
                    <div class="address_content">
                        <p class="address">〒999-9999</p>
                        <p class="address">ここには住所と建物が入ります</p>
                    </div>
                </div>
            </div>
            <div class="confirmation">
                <table class="confirmation_table">
                    <tr>
                        <th>商品代金</th>
                        <td>￥47,000</td>
                    </tr>
                    <tr>
                        <th>支払い方法</th>
                        <td>コンビニ支払い</td>
                    </tr>
                </table>
                <button class="submit_button" type="submit">購入する</button>
            </div>
        </div>
    </div>
</main>