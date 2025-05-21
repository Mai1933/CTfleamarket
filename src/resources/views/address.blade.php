@extends('layouts/header_search')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
    <main>
        <div class="address_content">
            <form action="{{ route('addressStore', ['item_id' => $item->id])}}" method="post" class="content_form">
                @csrf
                <h2 class="form_ttl">住所の変更</h2>
                <div class="form_inputs">
                    <div class="input_bunch">
                        <span class="input_ttl">郵便番号</span>
                        <input type="text" class="input" name="postcode" value="{{ $user->postcode }}">
                        <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                    </div>
                    <div class="input_bunch">
                        <span class="input_ttl">住所</span>
                        <input type="text" class="input" name="address" value="{{ $user->address }}">
                    </div>
                    <div class="input_bunch">
                        <span class="input_ttl">建物名</span>
                        <input type="text" class="input" name="building" value="{{ $user->building }}">
                    </div>
                </div>
                <button class="form_button" type="submit">更新する</button>
            </form>
        </div>
    </main>
@endsection