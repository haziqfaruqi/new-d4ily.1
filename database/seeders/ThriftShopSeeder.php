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
                ['name' => 'Footwear', 'slug' => 'footwear', 'description' => 'Vintage shoes and boots'],
                ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Vintage bags, belts, and jewelry'],
            ];
            foreach ($categories as $categoryData) {
                Category::create($categoryData);
            }
        }

        $products = [
            ['category_id' => 1, 'name' => 'Vintage Carhartt Detroit Jacket', 'description' => 'Classic brown Carhartt Detroit jacket in excellent condition.', 'price' => 145.00, 'stock' => 1, 'condition' => 'like new', 'size' => 'L', 'brand' => 'Carhartt', 'color' => 'Brown', 'images' => ['https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800'], 'featured' => true],
            ['category_id' => 1, 'name' => 'Levi\'s Denim Trucker Jacket', 'description' => 'Iconic Levi\'s trucker jacket in vintage wash.', 'price' => 89.00, 'stock' => 2, 'condition' => 'good', 'size' => 'M', 'brand' => 'Levi\'s', 'color' => 'Blue', 'images' => ['https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=800'], 'featured' => false],
            ['category_id' => 2, 'name' => 'Vintage Band T-Shirt', 'description' => 'Authentic vintage band tee from the 90s.', 'price' => 45.00, 'stock' => 1, 'condition' => 'good', 'size' => 'L', 'brand' => 'Hanes', 'color' => 'Black', 'images' => ['https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800'], 'featured' => false],
            ['category_id' => 2, 'name' => 'Wool Knit Sweater', 'description' => 'Cozy wool knit sweater with geometric pattern.', 'price' => 65.00, 'stock' => 1, 'condition' => 'like new', 'size' => 'M', 'brand' => 'Pendleton', 'color' => 'Multi', 'images' => ['https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800'], 'featured' => true],
            ['category_id' => 3, 'name' => 'Levi\'s 501 Vintage Jeans', 'description' => 'Classic Levi\'s 501 jeans with perfect fade.', 'price' => 75.00, 'stock' => 3, 'condition' => 'good', 'size' => '32x32', 'brand' => 'Levi\'s', 'color' => 'Blue', 'images' => ['https://images.unsplash.com/photo-1542272604-787c3835535d?w=800'], 'featured' => false],
            ['category_id' => 3, 'name' => 'Corduroy Pants', 'description' => 'Vintage corduroy pants in excellent condition.', 'price' => 55.00, 'stock' => 2, 'condition' => 'like new', 'size' => '30x30', 'brand' => 'Wrangler', 'color' => 'Tan', 'images' => ['https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=800'], 'featured' => false],
            ['category_id' => 4, 'name' => 'Converse Chuck Taylor High Tops', 'description' => 'Classic black Converse high tops.', 'price' => 48.00, 'stock' => 3, 'condition' => 'good', 'size' => 'US 10', 'brand' => 'Converse', 'color' => 'Black', 'images' => ['https://images.unsplash.com/photo-1607522370275-f14206abe5d3?w=800'], 'featured' => false],
            ['category_id' => 4, 'name' => 'Vintage Nike Air Max', 'description' => 'Retro Nike Air Max sneakers.', 'price' => 95.00, 'stock' => 1, 'condition' => 'like new', 'size' => 'US 9', 'brand' => 'Nike', 'color' => 'White/Red', 'images' => ['https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800'], 'featured' => true],
            ['category_id' => 5, 'name' => 'Leather Crossbody Bag', 'description' => 'Vintage leather crossbody bag with brass hardware.', 'price' => 95.00, 'stock' => 1, 'condition' => 'good', 'size' => 'One Size', 'brand' => 'Coach', 'color' => 'Brown', 'images' => ['https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=800'], 'featured' => false],
            ['category_id' => 5, 'name' => 'Vintage Sunglasses', 'description' => 'Classic vintage sunglasses with tortoise shell frames.', 'price' => 35.00, 'stock' => 1, 'condition' => 'like new', 'size' => 'One Size', 'brand' => 'Ray-Ban', 'color' => 'Tortoise', 'images' => ['https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=800'], 'featured' => false],
            ['category_id' => 1, 'name' => 'Vintage Bomber Jacket', 'description' => 'Classic MA-1 bomber jacket in sage green.', 'price' => 125.00, 'stock' => 1, 'condition' => 'good', 'size' => 'L', 'brand' => 'Alpha Industries', 'color' => 'Green', 'images' => ['https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800'], 'featured' => true],
            ['category_id' => 2, 'name' => 'Flannel Shirt', 'description' => 'Soft vintage flannel in red and black plaid.', 'price' => 42.00, 'stock' => 3, 'condition' => 'good', 'size' => 'L', 'brand' => 'Pendleton', 'color' => 'Red/Black', 'images' => ['https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=800'], 'featured' => false],
            // 30 Additional Products
            ['category_id' => 1, 'name' => 'Vintage Nike Windbreaker', 'description' => 'Classic 90s Nike windbreaker with full zip.', 'price' => 65.00, 'stock' => 2, 'condition' => 'good', 'size' => 'M', 'brand' => 'Nike', 'color' => 'Navy/White', 'images' => ['https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800'], 'featured' => false],
            ['category_id' => 1, 'name' => 'Adidas Track Jacket', 'description' => 'Retro Adidas track jacket with three stripes.', 'price' => 58.00, 'stock' => 3, 'condition' => 'good', 'size' => 'L', 'brand' => 'Adidas', 'color' => 'Black/White', 'images' => ['https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800'], 'featured' => false],
            ['category_id' => 3, 'name' => 'Dickies Work Pants', 'description' => 'Durable Dickies work pants in khaki.', 'price' => 45.00, 'stock' => 5, 'condition' => 'good', 'size' => '34x30', 'brand' => 'Dickies', 'color' => 'Khaki', 'images' => ['https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800'], 'featured' => false],
            ['category_id' => 3, 'name' => 'Vintage Corduroy Pants', 'description' => 'Soft corduroy pants in brown.', 'price' => 52.00, 'stock' => 2, 'condition' => 'good', 'size' => '33x32', 'brand' => 'Gap', 'color' => 'Brown', 'images' => ['https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800'], 'featured' => false],
            ['category_id' => 4, 'name' => 'Dr. Martens 1460 Boots', 'description' => 'Classic Dr. Martens 8-eye boots.', 'price' => 120.00, 'stock' => 2, 'condition' => 'like new', 'size' => 'UK 8', 'brand' => 'Dr. Martens', 'color' => 'Black', 'images' => ['https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=800'], 'featured' => false],
            ['category_id' => 5, 'name' => 'Vintage Leather Messenger Bag', 'description' => 'Genuine leather messenger bag with brass hardware.', 'price' => 85.00, 'stock' => 2, 'condition' => 'good', 'size' => 'One Size', 'brand' => 'Fossil', 'color' => 'Brown', 'images' => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800'], 'featured' => false],
            ['category_id' => 5, 'name' => 'Canvas Tote Bag', 'description' => 'Durable canvas tote with leather straps.', 'price' => 35.00, 'stock' => 4, 'condition' => 'good', 'size' => 'One Size', 'brand' => 'L.L.Bean', 'color' => 'Navy', 'images' => ['https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=800'], 'featured' => false],
            ['category_id' => 5, 'name' => 'Vintage Baseball Cap', 'description' => 'Faded vintage baseball cap with embroidered logo.', 'price' => 28.00, 'stock' => 6, 'condition' => 'fair', 'size' => 'Adjustable', 'brand' => 'New Era', 'color' => 'Black', 'images' => ['https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=800'], 'featured' => false],
            ['category_id' => 5, 'name' => 'Wool Beanie', 'description' => 'Warm wool beanie in classic style.', 'price' => 22.00, 'stock' => 8, 'condition' => 'like new', 'size' => 'One Size', 'brand' => 'Carhartt', 'color' => 'Gray', 'images' => ['https://images.unsplash.com/photo-1576871337622-98d48d1cf531?w=800'], 'featured' => false],
            ['category_id' => 1, 'name' => 'Patagonia Fleece Jacket', 'description' => 'Classic Patagonia fleece in excellent condition.', 'price' => 78.00, 'stock' => 2, 'condition' => 'like new', 'size' => 'M', 'brand' => 'Patagonia', 'color' => 'Navy', 'images' => ['https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800'], 'featured' => false],
            ['category_id' => 1, 'name' => 'The North Face Puffer Jacket', 'description' => 'Warm North Face puffer jacket.', 'price' => 110.00, 'stock' => 1, 'condition' => 'good', 'size' => 'L', 'brand' => 'The North Face', 'color' => 'Black', 'images' => ['https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800'], 'featured' => true],
            ['category_id' => 2, 'name' => 'Champion Reverse Weave Hoodie', 'description' => 'Classic Champion hoodie in reverse weave.', 'price' => 65.00, 'stock' => 4, 'condition' => 'like new', 'size' => 'XL', 'brand' => 'Champion', 'color' => 'Gray', 'images' => ['https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800'], 'featured' => false],
            ['category_id' => 2, 'name' => 'Vintage Polo Ralph Lauren Sweater', 'description' => 'Classic Ralph Lauren cable knit sweater.', 'price' => 72.00, 'stock' => 2, 'condition' => 'like new', 'size' => 'M', 'brand' => 'Ralph Lauren', 'color' => 'Cream', 'images' => ['https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=800'], 'featured' => false],
            ['category_id' => 3, 'name' => 'Vintage Wrangler Jeans', 'description' => 'Classic Wrangler denim in dark wash.', 'price' => 55.00, 'stock' => 3, 'condition' => 'good', 'size' => '32x34', 'brand' => 'Wrangler', 'color' => 'Dark Blue', 'images' => ['https://images.unsplash.com/photo-1542272604-787c3835535d?w=800'], 'featured' => false],
            ['category_id' => 3, 'name' => 'Vintage Cargo Pants', 'description' => 'Military-style cargo pants with multiple pockets.', 'price' => 48.00, 'stock' => 4, 'condition' => 'good', 'size' => '34x32', 'brand' => 'Rothco', 'color' => 'Olive', 'images' => ['https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800'], 'featured' => false],
            ['category_id' => 4, 'name' => 'Vans Old Skool', 'description' => 'Classic Vans Old Skool in black and white.', 'price' => 52.00, 'stock' => 3, 'condition' => 'good', 'size' => 'US 9.5', 'brand' => 'Vans', 'color' => 'Black/White', 'images' => ['https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=800'], 'featured' => false],
            ['category_id' => 4, 'name' => 'Vintage Reebok Classics', 'description' => 'Retro Reebok Classic sneakers.', 'price' => 58.00, 'stock' => 2, 'condition' => 'like new', 'size' => 'US 10.5', 'brand' => 'Reebok', 'color' => 'White', 'images' => ['https://images.unsplash.com/photo-1600185365926-3a2ce3cdb9eb?w=800'], 'featured' => false],
            ['category_id' => 5, 'name' => 'Vintage Leather Belt', 'description' => 'Full-grain leather belt with brass buckle.', 'price' => 32.00, 'stock' => 5, 'condition' => 'good', 'size' => '34', 'brand' => 'Levi\'s', 'color' => 'Brown', 'images' => ['https://images.unsplash.com/photo-1624222247344-550fb60583c2?w=800'], 'featured' => false],
            ['category_id' => 5, 'name' => 'Aviator Sunglasses', 'description' => 'Classic aviator-style sunglasses.', 'price' => 42.00, 'stock' => 3, 'condition' => 'like new', 'size' => 'One Size', 'brand' => 'Ray-Ban', 'color' => 'Gold', 'images' => ['https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=800'], 'featured' => false],
            ['category_id' => 1, 'name' => 'Alpha Industries Bomber', 'description' => 'Classic MA-1 bomber jacket.', 'price' => 125.00, 'stock' => 1, 'condition' => 'like new', 'size' => 'L', 'brand' => 'Alpha Industries', 'color' => 'Sage Green', 'images' => ['https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800'], 'featured' => true],
            ['category_id' => 1, 'name' => 'Vintage Harrington Jacket', 'description' => 'Classic Harrington jacket with tartan lining.', 'price' => 88.00, 'stock' => 2, 'condition' => 'good', 'size' => 'M', 'brand' => 'Baracuta', 'color' => 'Tan', 'images' => ['https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800'], 'featured' => false],
            ['category_id' => 2, 'name' => 'Vintage Graphic Sweatshirt', 'description' => 'Retro graphic sweatshirt with faded print.', 'price' => 45.00, 'stock' => 4, 'condition' => 'good', 'size' => 'L', 'brand' => 'Fruit of the Loom', 'color' => 'Gray', 'images' => ['https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800'], 'featured' => false],
            ['category_id' => 2, 'name' => 'Vintage Denim Shirt', 'description' => 'Classic western-style denim shirt.', 'price' => 52.00, 'stock' => 3, 'condition' => 'good', 'size' => 'L', 'brand' => 'Wrangler', 'color' => 'Light Blue', 'images' => ['https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=800'], 'featured' => false],
            ['category_id' => 3, 'name' => 'Vintage Chinos', 'description' => 'Classic chino pants in khaki.', 'price' => 42.00, 'stock' => 5, 'condition' => 'good', 'size' => '32x32', 'brand' => 'Dockers', 'color' => 'Khaki', 'images' => ['https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800'], 'featured' => false],
            ['category_id' => 5, 'name' => 'Vintage Backpack', 'description' => 'Durable canvas backpack with leather trim.', 'price' => 68.00, 'stock' => 2, 'condition' => 'good', 'size' => 'One Size', 'brand' => 'Herschel', 'color' => 'Navy', 'images' => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800'], 'featured' => false],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('Thrift shop data seeded successfully! Added ' . count($products) . ' products.');
    }
}
