@extends('layouts.shop')

@section('title', 'Заказ №'.$order->id.' — Лавка')

@section('content')
    @include('admin.nav')

    <h1>Заказ №{{ $order->id }}</h1>
    <p class="lede">{{ $order->customer_name }}, {{ $order->phone }}<br>{{ $order->address }}</p>

    <div class="two">
        <div class="panel">
            @foreach ($order->items as $item)
                <div class="line">
                    <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                    <strong>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽</strong>
                </div>
            @endforeach
            <p class="total">Итого {{ number_format($order->total, 0, ',', ' ') }} ₽</p>
        </div>

        <form class="panel" method="POST" action="{{ route('admin.orders.update', $order) }}">
            @csrf
            @method('PATCH')
            <div class="field">
                <label for="status">Статус</label>
                <select id="status" name="status">
                    @foreach (['new' => 'Новый', 'packed' => 'Собран', 'cancelled' => 'Отменён'] as $value => $label)
                        <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Сохранить</button>
        </form>
    </div>
@endsection
