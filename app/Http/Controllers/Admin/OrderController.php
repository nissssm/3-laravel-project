<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::query()->latest()->get(),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load('items');

        return view('admin.orders.show', [
            'order' => $order,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
        ]);

        try {
            DB::transaction(function () use ($order, $data) {
                $order = Order::query()->whereKey($order->id)->lockForUpdate()->first();
                $from = $order->status;
                $to = $data['status'];

                if ($from === $to) {
                    return;
                }

                $order->load('items');

                if ($to === 'cancelled') {
                    foreach ($order->items as $item) {
                        if ($item->product_id) {
                            Product::query()->whereKey($item->product_id)->increment('stock', $item->quantity);
                        }
                    }
                }

                if ($from === 'cancelled') {
                    $products = Product::query()
                        ->whereIn('id', $order->items->pluck('product_id')->filter())
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    foreach ($order->items as $item) {
                        $product = $products->get($item->product_id);

                        if (! $product || $product->stock < $item->quantity) {
                            throw new RuntimeException('Не хватает остатка, чтобы вернуть заказ «'.$item->product_name.'».');
                        }

                        $product->decrement('stock', $item->quantity);
                    }
                }

                $order->update(['status' => $to]);
            });
        } catch (RuntimeException $exception) {
            return back()->withErrors(['status' => $exception->getMessage()]);
        }

        return back()->with('status', 'Статус заказа обновлён.');
    }
}
