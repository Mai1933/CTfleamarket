@extends('layouts/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<main>
    <div class="register_content">
        <div class="register_content-inner">
            <form class="register_form" action="/register" method="post">
                @csrf
                <h2 class="form_ttl">会員登録</h2>
                <div class="form_inputs">
                    <div class="input_bunch">
                        <span class="bunch_ttl">ユーザー名</span>
                        <input type="text" class="bunch_input" name="name">
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">メールアドレス</span>
                        <input type="text" class="bunch_input" name="email">
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">パスワード</span>
                        <input type="text" class="bunch_input" name="password">
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">確認用パスワード</span>
                        <input type="text" class="bunch_input" name="password_confirmation">
                    </div>
                </div>
                <button type="submit" class="form_submit">登録する</button>
                <div class="form_login">
                <a href="/login" class="form_login-button">ログインはこちら</a>
                </div>
            </form>
        </div>
    </div>
</main>