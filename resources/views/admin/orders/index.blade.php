@extends('layouts.shop')

@section('title', 'Заказы — Лавка')

@section('content')
    @include('admin.nav')

    <div class="split">
        <h1>Заказы</h1>
    </div>

    @if ($orders->isEmpty())
        <div class="panel empty">Заказов пока нет.</div>
    @else
        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Покупатель</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->id }}</a></td>
                            <td>
                                {{ $order->customer_name }}
                                <div class="meta">{{ $order->phone }}</div>
                            </td>
                            <td>{{ number_format($order->total, 0, ',', ' ') }} ₽</td>
                            <td><span class="badge {{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
