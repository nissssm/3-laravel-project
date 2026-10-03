@extends('layouts.shop')

@section('title', 'Корзина — Лавка')

@section('content')
    <div class="split">
        <div>
            <h1>Корзина</h1>
            <p class="lede">Количество можно поменять, пока товар есть на складе.</p>
        </div>
    </div>

    @if ($adjusted)
        <p class="notice">Корзина обновлена по текущим остаткам.</p>
    @endif

    @if ($lines->isEmpty())
        <div class="panel empty">
            <p>Корзина пуста.</p>
            <a class="button" href="{{ route('catalog') }}">Перейти в каталог</a>
        </div>
    @else
        <div class="panel">
            @foreach ($lines as $line)
                <div class="line">
                    <div>
                        <a class="quiet" href="{{ route('products.show', $line['product']) }}"><strong>{{ $line['product']->name }}</strong></a>
                        <p class="meta">{{ number_format($line['product']->price, 0, ',', ' ') }} ₽ за штуку</p>
                    </div>
                    <form method="POST" action="{{ route('cart.update', $line['product']) }}">
                        @csrf
                        @method('PATCH')
                        <input class="qty" type="number" name="quantity" min="0" max="{{ $line['product']->stock }}" value="{{ $line['quantity'] }}">
                        <button class="secondary" type="submit">Обновить</button>
                    </form>
                    <div>
                        <strong>{{ number_format($line['line_total'], 0, ',', ' ') }} ₽</strong>
                        <form method="POST" action="{{ route('cart.destroy', $line['product']) }}">
                            @csrf
                            @method('DELETE')
                            <button class="danger" type="submit">Убрать</button>
                        </form>
                    </div>
                </div>
            @endforeach
            <div class="actions">
                <strong class="total">Итого {{ number_format($total, 0, ',', ' ') }} ₽</strong>
                <a class="button" href="{{ route('checkout.create') }}">Оформить заказ</a>
            </div>
        </div>
    @endif
@endsection
