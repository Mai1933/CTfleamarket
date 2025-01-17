@extends('layouts/header_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/buy.css') }}">
@endsection

@section('content')
<main>
    <div class="buy_content">
        <form action="/purchase" method="post" class="buy_content_innner">
            @csrf
            <div class="infomation">
                <div class="infomation_item">
                    <img src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="" class="item_image">
                    <div class="item_names">
                        <p class="item_name">{{ $item->item_name }}</p>
                        <p class="item_price">￥{{ $item->price }}</p>
                    </div>
                </div>
                <div class="infomation_payment">
                    <span class="ttl">支払い方法</span>
                    <select name="payment" id="paymentSelect" class="payment" onchange="updatePaymentMethod()">
                        <option value="選択してください" hidden>選択してください</option>
                        <option value="コンビニ支払い" class="payment_option">コンビニ支払い</option>
                        <option value="カード支払い" class="payment_option">カード支払い</option>
                    </select>
                </div>
                <div class="infomation_address">
                    <div class="address_ttls">
                        <span class="ttl">配送先</span>
                        <a href="/purchase/address/{{ $item->id}}" class="edit">変更する</a>
                    </div>
                    <div class="address_content">
                        <p class="address">〒{{ $user->postcode }}</p>
                        <span class="address">{{ $user->address }}</span>
                        <span class="address">{{ $user->building }}</span>
                    </div>
                </div>
            </div>
            <div class="confirmation">
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="item_price" value="{{ $item->price }}">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <table class="confirmation_table">
                    <tr>
                        <th>商品代金</th>
                        <td>￥{{ $item->price }}</td>
                    </tr>
                    <tr>
                        <th>支払い方法</th>
                        <td id="paymentMethod">選択してください</td>
                    </tr>
                </table>
                <button class="submit_button" type="submit">購入する</button>
            </div>
        </form>
    </div>
    <script>
        function updatePaymentMethod() {
            const paymentSelect = document.getElementById('paymentSelect');
            const paymentMethod = document.getElementById('paymentMethod');
            paymentMethod.textContent = paymentSelect.value;
        }
    </script>
</main>