<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class CheckoutController extends Controller
{
    public function create(Cart $cart): View|RedirectResponse
    {
        $cart->reconcile();
        $lines = $cart->lines();

        if ($lines->isEmpty()) {
            return redirect()->route('cart.index');
        }

        return view('shop.checkout', [
            'lines' => $lines,
            'total' => $cart->total(),
        ]);
    }

    public function store(Request $request, Cart $cart): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
        ], [
            'customer_name.required' => 'Укажите имя.',
            'phone.required' => 'Укажите телефон.',
            'address.required' => 'Укажите адрес.',
        ]);

        $cart->reconcile();
        $lines = $cart->lines();

        if ($lines->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->withErrors(['cart' => 'Корзина пуста.']);
        }

        try {
            $order = DB::transaction(function () use ($cart, $data, $lines) {
                $ids = $lines->pluck('product.id')->sort()->values();
                $products = Product::query()
                    ->whereIn('id', $ids)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $snapshots = [];
                $total = 0;

                foreach ($lines as $line) {
                    $product = $products->get($line['product']->id);

                    if (! $product || ! $product->is_active || $product->stock < $line['quantity']) {
                        throw new RuntimeException('«'.$line['product']->name.'» закончился или его меньше, чем в корзине.');
                    }

                    $product->decrement('stock', $line['quantity']);

                    $snapshots[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $line['quantity'],
                    ];
                    $total += $product->price * $line['quantity'];
                }

                $order = Order::query()->create([
                    'customer_name' => $data['customer_name'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'status' => 'new',
                    'total' => $total,
                ]);

                $order->items()->createMany($snapshots);
                $cart->clear();

                return $order;
            });
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('cart.index')
                ->withErrors(['cart' => $exception->getMessage()]);
        }

        return redirect()->route('orders.show', $order);
    }

    public function show(Order $order): View
    {
        $order->load('items');

        return view('shop.thanks', [
            'order' => $order,
        ]);
    }
}
