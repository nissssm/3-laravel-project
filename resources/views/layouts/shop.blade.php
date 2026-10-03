<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Лавка')</title>
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="wrap">
            <div class="header-row">
                <a class="brand" href="{{ route('catalog') }}">Лавка</a>
                <a class="cart-link" href="{{ route('cart.index') }}">Корзина · {{ $cartCount }}</a>
            </div>
            <nav class="nav-row">
                <a href="{{ route('catalog') }}" @class(['active' => ! request()->filled('category') && request()->routeIs('catalog')])>Все товары</a>
                @foreach ($navCategories as $category)
                    <a href="{{ route('catalog', ['category' => $category->slug]) }}" @class(['active' => request('category') === $category->slug])>{{ $category->name }}</a>
                @endforeach
                @auth
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.orders.index') }}">Админка</a>
                    @endif
                @else
                    <a href="{{ route('admin.login') }}">Вход</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <div class="wrap">
            @if (session('status'))
                <p class="notice">{{ session('status') }}</p>
            @endif
            @if ($errors->any())
                <div class="errors">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer>
        <div class="wrap">Заказ без онлайн-оплаты: менеджер подтверждает его вручную.</div>
    </footer>
</body>
</html>
