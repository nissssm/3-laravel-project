<nav class="admin-nav">
    <a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])>Заказы</a>
    <a href="{{ route('admin.products.index') }}" @class(['active' => request()->routeIs('admin.products.*')])>Товары</a>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="secondary" type="submit">Выйти</button>
    </form>
</nav>
