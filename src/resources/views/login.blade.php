@extends('layouts/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login_content">
    <div class="login_content-inner">
        <form class="login_form">
            <h2 class="form_ttl">ログイン</h2>
            <div class="form_inputs">
                <div class="input_bunch">
                    <span class="bunch_ttl">ユーザー名/メールアドレス</span>
                    <input type="text" class="bunch_input" name="user_name" name="email">
                </div>
                <div class="input_bunch">
                    <span class="bunch_ttl">パスワード</span>
                    <input type="text" class="bunch_input" name="password">
                </div>
            </div>
            <button type="submit" class="form_submit">ログインする</button>
            <a href="/login" class="form_login">会員登録はこちら</a>
        </form>
    </div>
</div>