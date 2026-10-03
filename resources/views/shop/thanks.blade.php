@extends('layouts.shop')

@section('title', 'Заказ №'.$order->id.' — Лавка')

@section('content')
    <h1>Заказ №{{ $order->id }} принят</h1>
    <p class="lede">Статус: {{ $order->statusLabel() }}. Мы свяжемся по телефону {{ $order->phone }}.</p>

    <div class="panel">
        <p><strong>{{ $order->customer_name }}</strong></p>
        <p class="meta">{{ $order->address }}</p>
        @foreach ($order->items as $item)
            <div class="line">
                <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                <strong>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽</strong>
            </div>
        @endforeach
        <p class="total">Итого {{ number_format($order->total, 0, ',', ' ') }} ₽</p>
    </div>
@endsection
