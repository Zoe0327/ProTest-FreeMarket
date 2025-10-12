<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECH')</title>
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
