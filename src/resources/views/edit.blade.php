@extends('layouts/header_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="edit_content">
    <form class="edit_form" enctype="multipart/form-data">
        <h2 class="form_ttl">プロフィール設定</h2>
        <div class="form_images">
            <img src="{{ asset('storage/miu.png')}}" alt="icon" class="image_icon">
            <div class="image_uploads">
                <input type="file" class="image_upload">
                <div class="custom_button">画像を選択する</div>
            </div>
        </div>
        <div class="form_inputs">
            <div class="input_bunch">
                <span class="bunch_ttl">ユーザー名</span>
                <input type="text" class="bunch_input" name="user_name">
            </div>
            <div class="input_bunch">
                <span class="bunch_ttl">郵便番号</span>
                <input type="text" class="bunch_input" name="postcode">
            </div>
            <div class="input_bunch">
                <span class="bunch_ttl">住所</span>
                <input type="text" class="bunch_input" name="address">
            </div>
            <div class="input_bunch">
                <span class="bunch_ttl">建物名</span>
                <input type="text" class="bunch_input" name="building">
            </div>
        </div>
        <button type="submit" class="form_submit">更新する</button>
    </form>
</div>