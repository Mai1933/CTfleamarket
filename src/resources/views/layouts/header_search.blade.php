<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common_search.css') }}">
    @yield('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <title>fleamarket</title>
</head>

<body>
    <header class="header">
        <div class="logo">
            <img src="{{ asset('storage/logo.svg')}}" alt="icon" class="logo_icon">
        </div>
        <form action="" class="search">
            <input type="text" class="search_input" placeholder="なにをお探しですか？">
        </form>
        <div class="responsive_btn">
            <div class="menu_line"></div>
            <div class="menu_line"></div>
            <div class="menu_line"></div>
        </div>
        <nav class="header_nav">
            <ul class="nav_links">
                <li><a href="/logout" class="links_content">ログアウト</a></li>
                <li><a href="/mypage" class="links_content">マイページ</a></li>
                <li><a href="/sell" class="links_sell">出品</a></li>
            </ul>
        </nav>
    </header>
    @yield('content')
    <script src="{{ asset('js/master.js') }}"></script>
</body>

</html>