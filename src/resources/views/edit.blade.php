@extends('layouts/header_search')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="edit_content">
    <form class="edit_form" enctype="multipart/form-data" action="/mypage/profile" method="POST">
        @csrf
        @method('PUT')
        <h2 class="form_ttl">プロフィール設定</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="form_images">
            <img src="{{ asset('storage/user_image/' . $user->user_image) }}" alt="icon" class="image_icon">
            <div class="image_uploads">
                <input type="file" class="image_upload" id="image_upload" name="user_image">
                <label class="custom_button" for="image_upload">画像を選択する</label>
            </div>
        </div>
        <div class="form_inputs">
            <div class="input_bunch">
                <span class="bunch_ttl">ユーザー名</span>
                <input type="text" class="bunch_input" name="name" value="{{ $user->name }}">
            </div>
            <div class="input_bunch">
                <span class="bunch_ttl">郵便番号</span>
                <input type="text" class="bunch_input" name="postcode" value="{{ $user->postcode }}">
            </div>
            <div class="input_bunch">
                <span class="bunch_ttl">住所</span>
                <input type="text" class="bunch_input" name="address" value="{{ $user->address }}">
            </div>
            <div class="input_bunch">
                <span class="bunch_ttl">建物名</span>
                <input type="text" class="bunch_input" name="building" value="{{ $user->building }}">
            </div>
        </div>
        <button type="submit" class="form_submit">更新する</button>
    </form>
</div>
@endsection