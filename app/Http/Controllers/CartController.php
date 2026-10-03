<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Cart $cart): View
    {
        $adjusted = $cart->reconcile();

        return view('shop.cart', [
            'lines' => $cart->lines(),
            'total' => $cart->total(),
            'adjusted' => $adjusted,
        ]);
    }

    public function store(Request $request, Product $product, Cart $cart): RedirectResponse
    {
        abort_unless($product->is_active && $product->stock > 0, 404);

        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:'.$product->stock],
        ]);

        $cart->add($product, (int) ($data['quantity'] ?? 1));

        return back()->with('status', 'Товар добавлен в корзину.');
    }

    public function update(Request $request, Product $product, Cart $cart): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:'.$product->stock],
        ]);

        $cart->set($product, (int) $data['quantity']);

        return redirect()->route('cart.index');
    }

    public function destroy(Product $product, Cart $cart): RedirectResponse
    {
        $cart->remove($product);

        return redirect()->route('cart.index');
    }
}
