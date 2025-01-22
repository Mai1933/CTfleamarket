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
                        @error('name')
                            <span class="input_error">
                                <p class="input_error_message">{{$errors->first('name')}}</p>
                            </span>
                        @enderror
                        <input type="text" class="bunch_input" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">メールアドレス</span>
                        @error('email')
                            <span class="input_error">
                                <p class="input_error_message">{{$errors->first('email')}}</p>
                            </span>
                        @enderror
                        <input type="text" class="bunch_input" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">パスワード</span>
                        @if($errors->has('password'))
                            <div class="input_error">
                                <ul>
                                    @foreach($errors->get('password') as $error)
                                        <li class="input_error_message">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="text" class="bunch_input" name="password" value="{{ old('password') }}">
                    </div>
                    <div class="input_bunch">
                        <span class="bunch_ttl">確認用パスワード</span>
                        @error('password_confirmation')
                            <span class="input_error">
                                <p class="input_error_message">{{$errors->first('password_confirmation')}}</p>
                            </span>
                        @enderror
                        <input type="text" class="bunch_input" name="password_confirmation"
                            value="{{ old('password_confirmation') }}">
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
@endsection