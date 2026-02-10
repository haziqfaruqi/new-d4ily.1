<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ThriftShopSeeder extends Seeder
{
    public function run()
    {
        if (Category::count() == 0) {
            $categories = [
                ['name' => 'Outerwear', 'slug' => 'outerwear', 'description' => 'Vintage jackets, coats, and blazers'],
                ['name' => 'Tops', 'slug' => 'tops', 'description' => 'Vintage shirts, sweaters, and blouses'],
                ['name' => 'Bottoms', 'slug' => 'bottoms', 'description' => 'Vintage jeans, pants, and skirts'],
                ['name' => 'Set', 'slug' => 'set', 'description' => 'Coordinated vintage sets'],
                ['name' => 'Dress', 'slug' => 'dress', 'description' => 'Vintage dresses'],
                ['name' => 'Skirt', 'slug' => 'skirt', 'description' => 'Vintage skirts'],
                ['name' => 'Footwear', 'slug' => 'footwear', 'description' => 'Vintage shoes and boots'],
                ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Vintage bags, belts, and jewelry'],
            ];
            foreach ($categories as $categoryData) {
                Category::create($categoryData);
            }
        }

        $this->command->info('Thrift shop data seeded successfully! Added ' . Category::count() . ' categories.');
    }
}
