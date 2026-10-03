@extends('layouts.shop')

@section('title', 'Товары — Лавка')

@section('content')
    @include('admin.nav')

    <div class="split">
        <h1>Товары</h1>
        <a class="button" href="{{ route('admin.products.create') }}">Новый товар</a>
    </div>

    <div class="two">
        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Остаток</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a>
                                <div class="meta">{{ $product->category->name }} @unless ($product->is_active) · скрыт @endunless</div>
                            </td>
                            <td>{{ number_format($product->price, 0, ',', ' ') }} ₽</td>
                            <td>{{ $product->stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="stack">
            <form class="panel" method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <h2>Категория</h2>
                <div class="field">
                    <label for="name">Название</label>
                    <input id="name" name="name" required>
                </div>
                <button type="submit">Добавить</button>
            </form>

            <div class="panel">
                @foreach ($categories as $category)
                    <div class="line">
                        <span>{{ $category->name }} <span class="meta">{{ $category->products_count }}</span></span>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                            @csrf
                            @method('DELETE')
                            <button class="danger" type="submit">Удалить</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
