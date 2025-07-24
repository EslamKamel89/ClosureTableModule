<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // DB::table('products')->truncate();
        // DB::table('category_hierarchy')->truncate();
        // DB::table('categories')->truncate();
        $categories = [
            ['id' => 1, 'name' => 'Electronics', 'slug' => 'electronics'],
            ['id' => 2, 'name' => 'Phones', 'slug' => 'phones'],
            ['id' => 3, 'name' => 'Smartphones', 'slug' => 'smartphones'],
            ['id' => 4, 'name' => 'Android Phones', 'slug' => 'android-phones'],
            ['id' => 5, 'name' => 'iOS Phones', 'slug' => 'ios-phones'],
            ['id' => 6, 'name' => 'Laptops', 'slug' => 'laptops'],
            ['id' => 7, 'name' => 'Gaming Laptops', 'slug' => 'gaming-laptops'],
            ['id' => 8, 'name' => 'Accessories', 'slug' => 'accessories'],
            ['id' => 9, 'name' => 'Phone Cases', 'slug' => 'phone-cases'],
            ['id' => 10, 'name' => 'Chargers', 'slug' => 'chargers'],
        ];
        DB::table('categories')->insert(
            collect($categories)
                ->map(fn($c) => array_merge($c, ['updated_at' => now(), 'created_at' => now()]))
                ->toArray()
        );
        $hierarchy = collect($categories)->map(function ($c) {
            return [$c['id'], $c['id'], 0];
        })->merge([
            // Electronics → Phones → Smartphones → Android/iOS
            [1, 2, 1],
            [1, 3, 2],
            [1, 4, 3],
            [1, 5, 3],
            [2, 3, 1],
            [2, 4, 2],
            [2, 5, 2],
            [3, 4, 1],
            [3, 5, 1],

            // Electronics → Laptops → Gaming Laptops
            [1, 6, 1],
            [1, 7, 2],
            [6, 7, 1],

            // Electronics → Accessories → Phone Cases & Chargers
            [1, 8, 1],
            [1, 9, 2],
            [1, 10, 2],
            [8, 9, 1],
            [8, 10, 1],
        ]);
        DB::table('category_hierarchy')->insert(
            $hierarchy->map(fn($r) => [
                'ancestor_id' => $r[0],
                'descendant_id' => $r[1],
                'depth' => $r[2],
            ])->toArray()
        );
        $products = [
            ['name' => 'Google Pixel 8', 'price' => 799.99, 'category_id' => 4],
            ['name' => 'iPhone 15 Pro', 'price' => 1099.99, 'category_id' => 5],
            ['name' => 'Alienware m18', 'price' => 3499.99, 'category_id' => 7],
            ['name' => 'OtterBox Defender', 'price' => 49.99, 'category_id' => 9],
            ['name' => 'Anker 65W Charger', 'price' => 39.99, 'category_id' => 10],
        ];
        DB::table('products')->insert(
            collect($products)
                ->map(fn($p) => array_merge($p, ['updated_at' => now(), 'created_at' => now()]))
                ->toArray()
        );
    }
}
