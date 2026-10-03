<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@shop.local'],
            [
                'name' => 'Администратор',
                'password' => 'password',
                'is_admin' => true,
            ],
        );

        if (Category::query()->exists()) {
            return;
        }

        $catalog = [
            'Кухня' => [
                ['Керамическая кружка', 890, 12, 'Плотная кружка на 300 мл. Подходит для чая и кофе, можно мыть руками.'],
                ['Деревянная доска', 1500, 9, 'Доска из бука для нарезки хлеба и овощей. Масло уже втёрто.'],
                ['Стеклянный графин', 2100, 4, 'Графин на литр с широким горлом, его удобно мыть.'],
            ],
            'Дом' => [
                ['Льняное полотенце', 640, 20, 'Плотное полотенце 50×90 см. С каждой стиркой становится мягче.'],
                ['Шерстяной плед', 4200, 5, 'Тёплый плед на кресло. Размер 130×180 см.'],
            ],
            'Свет' => [
                ['Настольная лампа', 3100, 7, 'Лампа с тканевым абажуром и тёплым светом. Патрон E27.'],
            ],
        ];

        foreach ($catalog as $categoryName => $products) {
            $category = Category::query()->create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);

            foreach ($products as [$name, $price, $stock, $description]) {
                Product::query()->create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $description,
                    'price' => $price,
                    'stock' => $stock,
                    'is_active' => true,
                ]);
            }
        }
    }
}
