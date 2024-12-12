@extends('layouts/header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login_content">
    <div class="login_content-inner">
        <form class="login_form" action="/login" method="post">
            @csrf
            <h2 class="form_ttl">ログイン</h2>
            <div class="form_inputs">
                <div class="input_bunch">
                    <span class="bunch_ttl">ユーザー名/メールアドレス</span>
                    @error('email')
                        <span class="input_error">
                            <p class="input_error_message">{{$errors->first('email')}}</p>
                        </span>
                    @enderror
                    <input type="text" class="bunch_input" name="name">
                </div>
                <div class="input_bunch">
                    <span class="bunch_ttl">パスワード</span>
                    @error('password')
                        <span class="input_error">
                            <p class="input_error_message">{{$errors->first('password')}}</p>
                        </span>
                    @enderror
                    <input type="text" class="bunch_input" name="password">
                </div>
            </div>
            <button type="submit" class="form_submit">ログインする</button>
            <a href="/register" class="form_login">会員登録はこちら</a>
        </form>
    </div>
</div>