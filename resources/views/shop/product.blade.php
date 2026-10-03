@extends('layouts.shop')

@section('title', $product->name.' — Лавка')

@section('content')
    <article class="product-hero">
        <div class="product-photo">
            @if ($product->imageUrl())
                <img src="{{ $product->imageUrl() }}" alt="">
            @else
                {{ mb_substr($product->name, 0, 1) }}
            @endif
        </div>
        <div class="product-copy">
            <p class="meta">{{ $product->category->name }}</p>
            <h1>{{ $product->name }}</h1>
            <p>{{ $product->description }}</p>
            <p class="price">{{ number_format($product->price, 0, ',', ' ') }} ₽</p>
            <p class="meta">В наличии: {{ $product->stock }}</p>
            @if ($product->stock > 0)
                <form method="POST" action="{{ route('cart.store', $product) }}" class="actions" style="justify-content: flex-start;">
                    @csrf
                    <input class="qty" type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1">
                    <button type="submit">В корзину</button>
                </form>
            @endif
        </div>
    </article>
@endsection
