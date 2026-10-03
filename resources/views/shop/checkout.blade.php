@extends('layouts.shop')

@section('title', 'Оформление — Лавка')

@section('content')
    <h1>Оформление</h1>
    <p class="lede">Оплата при подтверждении заказа. Доставку согласуем по телефону.</p>

    <div class="two">
        <form class="panel" method="POST" action="{{ route('checkout.store') }}">
            @csrf
            <div class="field">
                <label for="customer_name">Имя</label>
                <input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
            </div>
            <div class="field">
                <label for="phone">Телефон</label>
                <input id="phone" name="phone" value="{{ old('phone') }}" required>
            </div>
            <div class="field">
                <label for="address">Адрес</label>
                <textarea id="address" name="address" required>{{ old('address') }}</textarea>
            </div>
            <button type="submit">Подтвердить заказ</button>
        </form>

        <aside class="panel">
            @foreach ($lines as $line)
                <div class="line">
                    <span>{{ $line['product']->name }} × {{ $line['quantity'] }}</span>
                    <strong>{{ number_format($line['line_total'], 0, ',', ' ') }} ₽</strong>
                </div>
            @endforeach
            <p class="total">Итого {{ number_format($total, 0, ',', ' ') }} ₽</p>
        </aside>
    </div>
@endsection
