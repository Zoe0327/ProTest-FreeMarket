<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FreeMarket</title>
    <link rel="stylesheet" href="{{ asset('css/common.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('css')
    @yield('scripts')
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                {{-- ロゴ --}}
                <a href="{{ route('items.index') }}" class="header__logo">
                    <img src="{{ asset('storage/item_images/logo.svg') }}" alt="COACHTECH" class="header__logo-img">
                </a>

                {{-- 検索フォーム（全ページ共通） --}}
                <form action="{{ route('items.index') }}" method="get" class="header__search">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
                </form>
                {{-- ハンバーガーメニューボタン --}}
                <button class="hamburger" id="js-hamburger">
                    <span class="hamburger__bar"></span>
                    <span class="hamburger__bar"></span>
                    <span class="hamburger__bar"></span>
                </button>

                <div class="header__nav-wrap" id="js-nav-wrap">
                    <nav>
                        <ul class="header-nav">
                            {{-- ログイン後 --}}
                            @auth
                                {{-- ログイン中：マイページと出品、ログアウト --}}
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
                // ハンバーガーアイコンとナビゲーションの表示/非表示を切り替える
                hamburger.classList.toggle('is-open');
                navWrap.classList.toggle('is-open');
                body.classList.toggle('is-fixed');
            });
        });
    </script>
</body>
</html>
