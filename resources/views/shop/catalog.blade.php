@extends('layouts.shop')

@section('title', ($currentCategory->name ?? 'Каталог').' — Лавка')

@section('content')
    <h1>{{ $currentCategory->name ?? 'Каталог' }}</h1>
    <p class="lede">Посуда, текстиль и свет для дома. Цена в рублях, остаток на складе виден в карточке.</p>

    @if ($products->isEmpty())
        <div class="panel empty">В этой категории пока нет товаров.</div>
    @else
        <div class="grid">
            @foreach ($products as $product)
                <article class="card">
                    <a class="thumb" href="{{ route('products.show', $product) }}">
                        @if ($product->imageUrl())
                            <img src="{{ $product->imageUrl() }}" alt="">
                        @else
                            {{ mb_substr($product->name, 0, 1) }}
                        @endif
                    </a>
                    <div class="card-body">
                        <p class="meta">{{ $product->category->name }}</p>
                        <h2><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h2>
                        <p class="price">{{ number_format($product->price, 0, ',', ' ') }} ₽</p>
                        @if ($product->stock > 0)
                            <form method="POST" action="{{ route('cart.store', $product) }}">
                                @csrf
                                <button type="submit">В корзину</button>
                            </form>
                        @else
                            <p class="meta">Нет в наличии</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
