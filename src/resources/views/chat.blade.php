@extends('layouts/header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
    <main>
        <div class="chat_content">
            <div class="chat_others">
                <div class="chat_others_inner">
                    <p class="chat_others-ttl">その他の取引</p>
                    @foreach ($otherTransactionItems as $otherTransactionItem)
                        <a href="/chat/{{ $otherTransactionItem->id}}"
                            class="chat_others-link">{{ $otherTransactionItem->item_name}}</a>
                    @endforeach
                </div>
            </div>
            <div class="chat_progressing">
                <div class="chat_progressing-ttl">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="partner_information">
                        @if ($partner !== null)
                            <img src="{{ asset('storage/user_image/' . $partner->user_image) }}" alt="" class="partner_icon">
                            <p class="partner_name">「{{ $partner->name }}」さんとの取引画面</p>
                        @else
                            <img src="{{ asset('storage/user_image/' . $seller->user_image) }}" alt="" class="partner_icon">
                            <p class="partner_name">「{{ $seller->name }}」さんとの取引画面</p>
                        @endif
                    </div>
                    <button class="partner_complete" data-dialog="#js-dialog-1"
                        data-is-stock="{{ $isStock ? 'true' : 'false' }}">取引を完了する</button>
                    <p id="evaluation-message" style="display:none; color: red;">購入完了後、評価を行ってください。</p>
                    <dialog class="dialog_open-inner" id="js-dialog-1">
                        <form action="/evaluate/{{ $item->id }}" class="dialog_form" method="post">
                            @csrf
                            <div class="dialog_form-ttl">
                                <p>取引が完了しました。</p>
                            </div>
                            <div class="dialog_form-evaluate">
                                <p class="form-evaluate-message">今回の取引相手はどうでしたか？</p>
                                <div class="form-evaluate-rating">
                                    <input class="rating_input" id="star5" name="rating" type="radio" value="5">
                                    <label class="rating_label" for="star5">★</label>

                                    <input class="rating_input" id="star4" name="rating" type="radio" value="4">
                                    <label class="rating_label" for="star4">★</label>

                                    <input class="rating_input" id="star3" name="rating" type="radio" value="3">
                                    <label class="rating_label" for="star3">★</label>

                                    <input class="rating_input" id="star2" name="rating" type="radio" value="2">
                                    <label class="rating_label" for="star2">★</label>

                                    <input class="rating_input" id="star1" name="rating" type="radio" value="1">
                                    <label class="rating_label" for="star1">★</label>
                                </div>
                            </div>
                            <div class="form-evaluate-button">
                                <button type="submit" class="button-submit">送信する</button>
                            </div>
                        </form>
                    </dialog>
                </div>
                <div class="chat_progressing-product">
                    <a href="/item/{{ $item->id }}" class="product_information">
                        <img src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="product" class="product-img">
                        <div class="product_scripts">
                            <p class="product-name">{{ $item->item_name }}</p>
                            <p class="product-price">￥{{ $item->price }}</p>
                        </div>
                    </a>
                </div>
                <div class="chat_progressing-conservation">
                    @foreach ($messages as $message)
                        @if ($message->user_id === $user->id)
                            <form action="/chat/delete/{{ $item->id }}" method="post" class="conservation-self">
                                @csrf
                                <span class="self_information">
                                    <p class="self-name">{{ $user->name }}</p>
                                    <img src="{{ asset('storage/user_image/' . $user->user_image) }}" alt="self-img"
                                        class="self-image">
                                </span>
                                <p class="self_message">{{ $message->message_content }}</p>
                                <input type="hidden" value="{{ $message->id }}" name="message_id">
                                <span class="self_buttons">
                                    <a href="/chat/edit/{{ $item->id }}" class="self_buttons-link">編集</a>
                                    <button type="submit" class="self_buttons-link">削除</button>
                                </span>
                            </form>
                        @else
                            @if ($partner !== null)
                                <div class="conservation-others">
                                    <div class="others_information">
                                        <img src="{{ asset('storage/user_image/' . $partner->user_image) }}" alt="other-img"
                                            class="others-image">
                                        <p class="others-name">{{ $partner->name }}</p>
                                    </div>
                                    <p class="others_message">{{ $message->message_content }}</p>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
                @if ($errors->any())
                    <div class="error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="/chat/{{ $item->id }}" class="chat_progressing-inputs" enctype="multipart/form-data"
                    method="post" id="chat-container" data-item-id="{{ $item->id }}">
                    @csrf
                    <textarea name="message" id="" class="input-message"
                        placeholder="取引メッセージを記入してください">{{ old('message') }}</textarea>
                    <input type="file" id="fileElem" name="image" style="display:none">
                    <button type="button" id="fileSelect" class="image-submit">画像を追加</button>
                    <button type="submit" class="message-submit"><img src="{{ asset('storage/inputbutton.png') }}"
                            alt="submit"></button>
                </form>
            </div>
        </div>
        <script>
            const fileSelect = document.getElementById("fileSelect");
            const fileElem = document.getElementById("fileElem");

            fileSelect.addEventListener("click", (e) => {
                if (fileElem) {
                    fileElem.click();
                }
            }, false);
        </script>
        <script src="{{ asset('js/modal.js') }}"></script>
        <script src="{{ asset('js/message.js') }}"></script>
        @php
            $isStock = ($item->status === 'stock');
        @endphp
        <script>
            const completeButton = document.querySelector('.partner_complete');
            const message = document.getElementById('evaluation-message');

            completeButton.addEventListener("click", () => {
                @if ($isStock)
                    message.style.display = 'block';
                @endif
                                                                    })
        </script>
    </main>
@endsection