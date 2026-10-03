@extends('layouts.shop')

@section('title', ($product->exists ? 'Редактирование' : 'Новый товар').' — Лавка')

@section('content')
    @include('admin.nav')

    <h1>{{ $product->exists ? 'Редактирование' : 'Новый товар' }}</h1>

    <form class="panel" method="POST" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        <div class="field">
            <label for="category_id">Категория</label>
            <select id="category_id" name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="name">Название</label>
            <input id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="field">
            <label for="description">Описание</label>
            <textarea id="description" name="description" required>{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="field">
            <label for="price">Цена, ₽</label>
            <input id="price" type="number" name="price" min="0" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="field">
            <label for="stock">Остаток</label>
            <input id="stock" type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" required>
        </div>
        <div class="field">
            <label for="image">Фото</label>
            <input id="image" type="file" name="image" accept="image/*">
        </div>
        <div class="field check">
            <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))>
            <label for="is_active">Показывать в каталоге</label>
        </div>
        <div class="actions">
            <a class="button secondary" href="{{ route('admin.products.index') }}">Назад</a>
            <button type="submit">Сохранить</button>
        </div>
    </form>

    @if ($product->exists)
        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="actions">
            @csrf
            @method('DELETE')
            <button class="danger" type="submit">Удалить товар</button>
        </form>
    @endif
@endsection
