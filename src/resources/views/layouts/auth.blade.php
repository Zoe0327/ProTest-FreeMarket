<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FreeMarket')</title>
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
    @yield('css')
</head>
<body class="auth-body">
    <header class="auth-header">
        <div class="auth-header__inner">
            <a href="{{ route('items.index') }}" class="auth__logo">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHロゴ">
            </a>
        </div>
    </header>

    <main class="auth-main">
        @yield('content')
    </main>
</body>
</html>
