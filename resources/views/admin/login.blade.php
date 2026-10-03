@extends('layouts.shop')

@section('title', 'Вход — Лавка')

@section('content')
    <h1>Вход для магазина</h1>
    <p class="lede">Отсюда правятся товары, остатки и статусы заказов.</p>

    <form class="panel" method="POST" action="{{ route('admin.login.store') }}" style="max-width: 420px;">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="field">
            <label for="password">Пароль</label>
            <input id="password" type="password" name="password" required>
        </div>
        <button type="submit">Войти</button>
    </form>
@endsection
