<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class Cart
{
    public function items(): array
    {
        $items = [];

        foreach (session('cart', []) as $id => $quantity) {
            $items[(int) $id] = (int) $quantity;
        }

        return $items;
    }

    public function count(): int
    {
        return (int) array_sum($this->items());
    }

    /**
     * @return Collection<int, array{product: Product, quantity: int, line_total: int}>
     */
    public function lines(): Collection
    {
        $items = $this->items();

        if ($items === []) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', array_keys($items))
            ->get()
            ->keyBy('id');

        return collect($items)
            ->map(function (int $quantity, int $id) use ($products) {
                $product = $products->get($id);

                if (! $product || ! $product->is_active || $product->stock < 1) {
                    return null;
                }

                $quantity = min($quantity, $product->stock);

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => $product->price * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public function total(): int
    {
        return (int) $this->lines()->sum('line_total');
    }

    public function add(Product $product, int $quantity): void
    {
        $items = $this->items();
        $next = ($items[$product->id] ?? 0) + max(1, $quantity);
        $items[$product->id] = min($next, $product->stock);
        $this->put($items);
    }

    public function set(Product $product, int $quantity): void
    {
        $items = $this->items();

        if ($quantity < 1 || ! $product->is_active || $product->stock < 1) {
            unset($items[$product->id]);
        } else {
            $items[$product->id] = min($quantity, $product->stock);
        }

        $this->put($items);
    }

    public function remove(Product $product): void
    {
        $items = $this->items();
        unset($items[$product->id]);
        $this->put($items);
    }

    public function clear(): void
    {
        session()->forget('cart');
    }

    public function reconcile(): bool
    {
        $items = $this->items();
        $products = Product::query()->whereIn('id', array_keys($items))->get()->keyBy('id');
        $next = [];

        foreach ($items as $id => $quantity) {
            $product = $products->get($id);

            if (! $product || ! $product->is_active || $product->stock < 1) {
                continue;
            }

            $next[$id] = min($quantity, $product->stock);
        }

        $changed = $next !== $items;
        $this->put($next);

        return $changed;
    }

    private function put(array $items): void
    {
        session(['cart' => $items]);
    }
}
