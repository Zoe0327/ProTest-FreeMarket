<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FreeMarket</title>
    <link rel="stylesheet" href="{{ asset('css/common.css')}}" />
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">

                <a href="{{ route('items.index') }}" class="header__logo">
                    <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="header__logo-img">
                </a>

                <form action="{{ route('items.index') }}" method="get" class="header__search">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
                </form>

                <button class="hamburger" id="js-hamburger" type="button" aria-label="メニューを開く">
                    <span class="hamburger__bar"></span>
                    <span class="hamburger__bar"></span>
                    <span class="hamburger__bar"></span>
                </button>

                <div class="header__nav-wrap" id="js-nav-wrap">
                    <nav aria-label="グローバルナビゲーション">
                        <ul class="header-nav">
                            {{-- ログイン後 --}}
                            @auth
                                <li class="header-nav__item">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="header-nav__button">ログアウト</button>
                                    </form>
                                </li>
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="{{ route('mypage.index') }}">マイページ</a>
                                </li>
                                <li class="header-nav__item">
                                    <a class="header-nav__link header-nav__sell-btn" href="{{ route('sell.create') }}">出品</a>
                                </li>
                            {{-- 未ログイン時 --}}
                            @else
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="{{ route('login') }}">ログイン</a>
                                </li>
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="{{ route('login') }}">マイページ</a>
                                </li>
                                <li class="header-nav__item">
                                    <a class="header-nav__link header-nav__sell-btn" href="{{ route('sell.create') }}">出品</a>
                                </li>
                            @endauth
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    {{-- ハンバーガーメニューの開閉処理 --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hamburger = document.getElementById('js-hamburger');
            const navWrap = document.getElementById('js-nav-wrap');
            const body = document.body;

            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('is-open');
                navWrap.classList.toggle('is-open');
                body.classList.toggle('is-fixed');
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
