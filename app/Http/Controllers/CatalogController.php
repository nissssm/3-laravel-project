<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $category = null;

        if ($request->filled('category')) {
            $category = Category::query()
                ->where('slug', $request->string('category'))
                ->firstOrFail();
        }

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($category, fn ($query) => $query->where('category_id', $category->id))
            ->orderBy('name')
            ->get();

        return view('shop.catalog', [
            'products' => $products,
            'currentCategory' => $category,
        ]);
    }
}
