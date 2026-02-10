<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class AllProductsSeeder extends Seeder
{
    public function run()
    {
        // Get current categories
        $categories = Category::all()->keyBy('id');

        $this->command->info('Seeding all products from database...');

        $products = [
            [
                'category_id' => 5, // Accessories
                'name' => 'Vintage Backpack',
                'description' => 'Durable canvas backpack with leather trim.',
                'price' => 68.00,
                'condition' => 'good',
                'size' => 'One Size',
                'brand' => 'Herschel',
                'color' => 'Navy',
                'stock' => 2,
                'images' => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800'],
            ],
            [
                'category_id' => 6, // Set
                'name' => 'Gueliz Kurung',
                'description' => 'A chic modern kurung every women needs in her closet.',
                'price' => 289.00,
                'condition' => 'like new',
                'size' => 'M',
                'brand' => 'Petit Moi',
                'color' => 'Multi-Colour Abstract',
                'stock' => 1,
                'images' => ['/uploads/products/1767436229_6958efc511ce0.jpg'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Beaded Open Cardigan',
                'description' => 'Worn few times. Comes with box',
                'price' => 200.00,
                'condition' => 'good',
                'size' => 'S',
                'brand' => 'Our Second Nature',
                'color' => 'Pink',
                'stock' => 1,
                'images' => ['/uploads/products/1767437121_6958f3416bb6b.jpg', '/uploads/products/1767437135_6958f34f4533a.jpg'],
            ],
            [
                'category_id' => 6, // Set
                'name' => 'Penelope Kurung In Bloom',
                'description' => 'Bringing to mind spring & all its glory, Penelope will have you bloom with grace & beauty.

A comfortable set in fetching polka with beautiful custom florals in a delicate spring hue.

Be ready to add that spring to your step as you look your best while feeling your best!',
                'price' => 358.00,
                'condition' => 'like new',
                'size' => 'XS',
                'brand' => 'Petit Moi',
                'color' => 'Peter Pan Color',
                'stock' => 1,
                'images' => ['/uploads/products/1769574532_69799084715dd.jpg'],
            ],
            [
                'category_id' => 6, // Set
                'name' => 'Akiko Kurung in Bronze',
                'description' => 'Elegant printed florals in a fine cotton paired with intricate embroidery. An altogether comfy yet sophisticated package.

Akiko is elegance personified - a classic every lady needs in her closet! Our new Akiko in lush bronze hue inspired by gilded sunset and lush autumn foliage. It is a timeless piece that works as a wardrobe favourite, but also something to give a boost of confidence!

A chic modern kurung every women needs in her closet.',
                'price' => 300.00,
                'condition' => 'like new',
                'size' => 'XS',
                'brand' => 'Petit Moi',
                'color' => 'Chocolate Yellow',
                'stock' => 1,
                'images' => ['/uploads/products/1769574686_6979911ee5648.jpg'],
            ],
            [
                'category_id' => 6, // Set
                'name' => 'Hana Kurung in Autumn Floral',
                'description' => 'Elegant printed florals paired with intricate embroidery in a timeless design.

Hana is elegance personified – a classic every lady needs in her closet! The mandarin collar gives an oriental touch while the pearl buttons and embroidery elevates the classic design.

An elegant kurung every woman needs in her closet.',
                'price' => 250.00,
                'condition' => 'like new',
                'size' => 'XS',
                'brand' => 'Petit Moi',
                'color' => 'Deep Brown',
                'stock' => 1,
                'images' => ['/uploads/products/1769574841_697991b9416c3.jpg'],
            ],
            [
                'category_id' => 6, // Set
                'name' => 'Celeste Kurung in Pearl',
                'description' => 'An ode to timeless femininity, our Celeste Kurung is crafted from delicate lace that drapes in a dreamy silhouette—equal parts romantic and refined. The blouse features softly gathered sleeves and a gentle peplum waist, lending a touch of playful volume that flatters and floats with every step. Intricate floral motifs evoke the charm of treasured heirloom pieces, while a subtle stand collar frames the neckline in graceful simplicity.',
                'price' => 300.00,
                'condition' => 'good',
                'size' => 'M',
                'brand' => 'Zora Designer',
                'color' => 'Pearl White',
                'stock' => 1,
                'images' => ['/uploads/products/1769575020_6979926cc29c8.jpg'],
            ],
            [
                'category_id' => 8, // Skirt
                'name' => 'Aubrey Floral Skirt',
                'description' => '• Elasticised Waist at the Back
• Side Zipper
• Unlined
• 2-tiered Skirt
• Maxi / Ankle Length - Varies with Height',
                'price' => 100.00,
                'condition' => 'like new',
                'size' => 'M',
                'brand' => 'Zora Designer',
                'color' => 'Sage',
                'stock' => 1,
                'images' => ['/uploads/products/1769575165_697992fdc8861.jpg'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'The Emma Floral Cardigan',
                'description' => '• Soft-touch knit with an allover vintage-inspired floral motif
•Classic round neckline with ribbed trims
•Full button-down front with pearl-toned buttons
•Gentle puff at the shoulder for a subtly romantic silhouette
•Long sleeves with a slim cuff finish
•Lightweight, breathable knit perfect for layering year-round
•Finished hem for a relaxed yet refined fit',
                'price' => 150.00,
                'condition' => 'like new',
                'size' => 'L',
                'brand' => 'Zora Designer',
                'color' => 'Cream',
                'stock' => 1,
                'images' => ['/uploads/products/1769575307_6979938bb70f5.jpg'],
            ],
            [
                'category_id' => 6, // Set
                'name' => 'Alur Kurung',
                'description' => 'The kurung top is made from a ramie and cotton (30% Ramie, 70% Cotton). Ramie is a natural fibre known for its breathability and moisture-wicking properties, making it ideal for hot and humid climates, while cotton adds softness and everyday comfort.



Paired with a pario skirt made from 100% cotton, the set remains airy and lightweight, allowing heat to escape naturally for all-day wear in Malaysian weather.',
                'price' => 250.00,
                'condition' => 'like new',
                'size' => 'XS',
                'brand' => 'Kalmhs',
                'color' => 'Blue',
                'stock' => 1,
                'images' => ['/uploads/products/1769579036_6979a21c88b28.jpg', '/uploads/products/1769579036_6979a21c88ea4.jpg', '/uploads/products/1769579036_6979a21c891bd.jpg'],
            ],
            [
                'category_id' => 6, // Set
                'name' => 'Laras Set',
                'description' => 'Crafted from 100% cotton, the fabric is breathable, lightweight, and comfortable for warm weather, making it suitable for all-day wear in humid climates. A subtle grid pattern runs through the design, adding gentle structure and visual depth without overwhelming the form.

Thoughtfully made for comfort and alignment, Laras Set offers a calm, understated presence — refined, breathable, and effortless.',
                'price' => 200.00,
                'condition' => 'good',
                'size' => 'L',
                'brand' => 'Kalmhs',
                'color' => 'Beige',
                'stock' => 1,
                'images' => ['/uploads/products/1769579007_6979a1ff21fc2.jpg', '/uploads/products/1769579007_6979a1ff22299.jpg'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Poise Top',
                'description' => 'crafted from breathable ramie and cotton blend for everyday ease. Designed with a structured silhouette and dolman sleeves style for timeless refinement.',
                'price' => 150.00,
                'condition' => 'like new',
                'size' => 'XL',
                'brand' => 'Kalmhs',
                'color' => 'White',
                'stock' => 1,
                'images' => ['/uploads/products/1769577185_69799ae10c8d5.jpg', '/uploads/products/1769578978_6979a1e2427c6.jpg', '/uploads/products/1769578978_6979a1e242a09.jpg', '/uploads/products/1769578978_6979a1e242b8a.jpg'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Muse Top',
                'description' => 'A silhouette reminiscent of Embun & Lumi. Comes with a detachable rope. Elevated with hemp and cotton fabric for a draped, soft structure that flatters the form. The fabric\'s subtle grid weave adds dimension and structure. Designed for ease, made for',
                'price' => 150.00,
                'condition' => 'good',
                'size' => 'L',
                'brand' => 'Kalmhs',
                'color' => 'Pink',
                'stock' => 1,
                'images' => ['/uploads/products/1769577333_69799b75729d9.jpg', '/uploads/products/1769578947_6979a1c3e5ad2.jpg', '/uploads/products/1769578947_6979a1c3e5ce9.jpg'],
            ],
            [
                'category_id' => 8, // Skirt
                'name' => 'Nia Skirt',
                'description' => 'Design Details:
- Pleated waist
- High-waisted
- Flare bottom hem
- Back zip closure',
                'price' => 190.00,
                'condition' => 'like new',
                'size' => 'M',
                'brand' => 'Shop Hanya',
                'color' => 'Soft Nude',
                'stock' => 1,
                'images' => ['/uploads/products/1769578092_69799e6c8d89d.jpg', '/uploads/products/1769578908_6979a19c80fc4.jpg'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Nyssa Top',
                'description' => 'Top:
Round neckline
Signature pleated waist
Ruched sleeves details
Back zip closure',
                'price' => 200.00,
                'condition' => 'good',
                'size' => 'L',
                'brand' => 'Shop Hanya',
                'color' => 'Fresh Lime',
                'stock' => 1,
                'images' => ['/uploads/products/1769578251_69799f0b69606.jpg', '/uploads/products/1769578923_6979a1ab74033.jpg', '/uploads/products/1769578923_6979a1ab7429d.jpg'],
            ],
            [
                'category_id' => 7, // Dress
                'name' => 'Yara Dress',
                'description' => 'Design Details:
- Pleated waist
- Side pockets
- Oversized-collared shirt silhouette
- Oversized dropped shoulder silhouette
- Cuff sleeves with marble textured button
- Flare bottom hem
- Back zip closure',
                'price' => 150.00,
                'condition' => 'like new',
                'size' => 'XS',
                'brand' => 'Shop Hanya',
                'color' => 'Classic Black',
                'stock' => 1,
                'images' => ['/uploads/products/1769578894_6979a18e97d8c.jpg', '/uploads/products/1769578894_6979a18e97f7c.jpg', '/uploads/products/1769578894_6979a18e980d2.jpg'],
            ],
            [
                'category_id' => 6, // Set
                'name' => 'Aisya Drape Cape Abaya',
                'description' => 'Made from high-quality, lightweight material that drapes beautifully and feels comfortable against the skin. A sleek, all-black design with subtle texture adds a touch of sophistication, making it perfect for any occasion. Designed to offer a flattering fit while ensuring ease of movement, suitable for all body types. By incorporating a soft cape drape on the right side and signature shine fabric, the Abaya transforms into a unique and fashionable garment that maintains its traditional essence while offering a modern and elegant flair.',
                'price' => 200.00,
                'condition' => 'like new',
                'size' => 'M',
                'brand' => 'Shop Hanya',
                'color' => 'Black',
                'stock' => 1,
                'images' => ['/uploads/products/1769578830_6979a14ede9fd.jpg', '/uploads/products/1769578850_6979a162b3d4c.jpg'],
            ],
            [
                'category_id' => 6, // Set
                'name' => 'Aisya Drape Cape',
                'description' => 'Soft Cape Drape on the right side
Signature Shine Fabrics',
                'price' => 210.00,
                'condition' => 'good',
                'size' => 'M',
                'brand' => 'Shop Hanya',
                'color' => 'Rose Gold',
                'stock' => 1,
                'images' => ['/uploads/products/1769579246_6979a2ee4ba48.jpg', '/uploads/products/1769579246_6979a2ee4bc95.jpg', '/uploads/products/1769579246_6979a2ee4bdf1.jpg'],
            ],
            [
                'category_id' => 6, // Set
                'name' => 'Amaya',
                'description' => 'TOP:  batwing sleeves, bias camisole with adjustable straps

SKIRT:  fit and flare, fully lined, elastic back

FABRIC:  chiffon, embellishments',
                'price' => 500.00,
                'condition' => 'like new',
                'size' => 'M',
                'brand' => 'Alia B',
                'color' => 'Beige',
                'stock' => 1,
                'images' => ['/uploads/products/1769620180_697a42d43f223.jpg', '/uploads/products/1769620180_697a42d43f917.jpg'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Pointelle Cardigan',
                'description' => '- Lightweight fabric.
- Flattering fabric with the perfect weight and feel.
- The detailed stitching offers breathability and a vintage look.
- Softly cut to flatter. Perfect for layering over a dress or pairing with denim.
- Cherry print for playfully nostalgic style.
Function details
- Sheer: Not Sheer(Only 01 Off White is slightly sheer)
- Fit: Fitted
- Pockets: No Pockets',
                'price' => 50.00,
                'condition' => 'like new',
                'size' => 'L',
                'brand' => 'Uniqlo',
                'color' => 'Navy',
                'stock' => 1,
                'images' => ['/uploads/products/1770659624_698a1f28add55.jpg', '/uploads/products/1770659624_698a1f28adfd2.jpg'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'UV Protection Crew Neck Cardigan',
                'description' => '- Versatile cardigan goes with any outfit.
- UPF25 / JIS L 1925 : 2019',
                'price' => 70.00,
                'condition' => 'like new',
                'size' => 'L',
                'brand' => 'Uniqlo',
                'color' => 'Light Blue',
                'stock' => 1,
                'images' => ['/uploads/products/1770659823_698a1fef3165e.jpg', '/uploads/products/1770659823_698a1fef318d0.jpg', '/uploads/products/1770659823_698a1fef31a3c.jpg'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Premium Linen Shirt Long Sleeve Stripe',
                'description' => 'The premium texture of 100% using European-grown flax. Cool linen with a supple texture and subtle glossy sheen.',
                'price' => 99.00,
                'condition' => 'like new',
                'size' => 'M',
                'brand' => 'Uniqlo',
                'color' => 'Green',
                'stock' => 1,
                'images' => ['/uploads/products/1770659957_698a20751f608.jpg', '/uploads/products/1770659957_698a20751f93d.jpg'],
            ],
            [
                'category_id' => 3, // Trousers
                'name' => 'Stretch Jersey Easy Pants',
                'description' => '- Sheer: Not Sheer
- Fit: Regular
- Silhouette: Straight
- Pockets: With Pockets',
                'price' => 60.00,
                'condition' => 'new',
                'size' => 'L',
                'brand' => 'Uniqlo',
                'color' => 'Green',
                'stock' => 1,
                'images' => ['/uploads/products/1770661758_698a277e99b14.avif', '/uploads/products/1770661758_698a277e99dca.avif', '/uploads/products/1770661758_698a277e99f18.avif'],
            ],
            [
                'category_id' => 3, // Trousers
                'name' => 'Linen Blend Easy Pants',
                'description' => '- Sheer: Not Sheer(Only 30 Natural is slightly sheer)
- Fit: Regular
- Silhouette: Straight
- Pockets: With Pockets',
                'price' => 69.00,
                'condition' => 'new',
                'size' => 'M',
                'brand' => 'Uniqlo',
                'color' => 'Brown',
                'stock' => 1,
                'images' => ['/uploads/products/1770661836_698a27ccb0533.avif', '/uploads/products/1770661836_698a27ccb0730.avif', '/uploads/products/1770661836_698a27ccb087e.avif', '/uploads/products/1770661836_698a27ccb09ce.avif'],
            ],
            [
                'category_id' => 3, // Trousers
                'name' => 'Linen Blend Easy Pants',
                'description' => '- Loose-fitting wide straight cut keeps you cool.
- Available in a choice of versatile colors to showcase the cool feel of linen.',
                'price' => 79.00,
                'condition' => 'new',
                'size' => 'L',
                'brand' => 'Uniqlo',
                'color' => 'Beige',
                'stock' => 1,
                'images' => ['/uploads/products/1770662145_698a2901466cd.jpg', '/uploads/products/1770662145_698a290146a61.png'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Premium Linen Shirt Long Sleeve Stripe',
                'description' => '- Sheer: Not Sheer
- Fit: Relaxed',
                'price' => 99.00,
                'condition' => 'new',
                'size' => 'L',
                'brand' => 'Uniqlo',
                'color' => 'Grey',
                'stock' => 1,
                'images' => ['/uploads/products/1770662323_698a29b3a0d9f.jpg', '/uploads/products/1770662323_698a29b3a109b.png'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Peplum blouse',
                'description' => 'Blouse in a viscose-blend weave featuring a round neckline and a V-shaped opening with bead-ended spaghetti ties at the front. Long balloon sleeves with narrow elastication at the cuffs, and a flared peplum.',
                'price' => 68.00,
                'condition' => 'new',
                'size' => 'XS',
                'brand' => 'H&M',
                'color' => 'White Blue',
                'stock' => 1,
                'images' => ['/uploads/products/1770662473_698a2a49939b9.avif', '/uploads/products/1770662473_698a2a4993c1f.avif'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Peplum blouse',
                'description' => 'Blouse in a viscose-blend weave featuring a round neckline and a V-shaped opening with bead-ended spaghetti ties at the front. Long balloon sleeves with narrow elastication at the cuffs, and a flared peplum.',
                'price' => 65.00,
                'condition' => 'like new',
                'size' => 'L',
                'brand' => 'H&M',
                'color' => 'Dusty orange/Dark red/Beige/Pink, Patterned',
                'stock' => 1,
                'images' => ['/uploads/products/1770662611_698a2ad3d77de.jpg'],
            ],
            [
                'category_id' => 3, // Trousers
                'name' => 'Wide High Waist Jeans',
                'description' => '5-pocket jeans in rigid denim made from a cotton and lyocell blend that feels firm at first but then softens and loosens with wear. Wide leg with a regular fit from the waist to the hip and a wide cut from the thigh to the hem. High waist with a zip fly and button. Regular length, designed to hit the top of the foot with little or no stacking.',
                'price' => 89.00,
                'condition' => 'good',
                'size' => 'M',
                'brand' => 'H&M',
                'color' => 'Denim blue',
                'stock' => 1,
                'images' => ['/uploads/products/1770662728_698a2b48519f3.avif', '/uploads/products/1770662728_698a2b4851bf6.avif'],
            ],
            [
                'category_id' => 3, // Trousers
                'name' => 'Wide High Waist Jeans',
                'description' => '5-pocket jeans in rigid denim made from a cotton and lyocell blend that feels firm at first but then softens and loosens with wear. Wide leg with a regular fit from the waist to the hip and a wide cut from the thigh to the hem. High waist with a zip fly and button. Regular length, designed to hit the top of the foot with little or no stacking.',
                'price' => 75.00,
                'condition' => 'fair',
                'size' => 'L',
                'brand' => 'H&M',
                'color' => 'Dark denim blue',
                'stock' => 1,
                'images' => ['/uploads/products/1770662806_698a2b96d6c31.avif', '/uploads/products/1770662806_698a2b96d6ebc.avif'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Fine-knit cardigan',
                'description' => 'Cardigan in a soft, fine-knit viscose blend with buttons down the front and ribbing around the neckline, cuffs and hem.',
                'price' => 30.00,
                'condition' => 'new',
                'size' => 'L',
                'brand' => 'H&M',
                'color' => 'Mole',
                'stock' => 1,
                'images' => ['/uploads/products/1770697713_698ab3f14ba7d.avif', '/uploads/products/1770697713_698ab3f14c173.avif'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Fine-knit cardigan',
                'description' => 'Cardigan in a soft, fine-knit viscose blend with buttons down the front and ribbing around the neckline, cuffs and hem.',
                'price' => 35.00,
                'condition' => 'good',
                'size' => 'L',
                'brand' => 'H&M',
                'color' => 'Lilac',
                'stock' => 1,
                'images' => ['/uploads/products/1770697786_698ab43a8e245.avif'],
            ],
            [
                'category_id' => 2, // Tops
                'name' => 'Fine-knit cardigan',
                'description' => 'Cardigan in a fine knit with a round, ribbed neckline, buttons down the front and long sleeves. Ribbing at the cuffs and hem.',
                'price' => 59.00,
                'condition' => 'fair',
                'size' => 'XL',
                'brand' => 'H&M',
                'color' => 'Black',
                'stock' => 1,
                'images' => ['/uploads/products/1770697931_698ab4cb4f0b1.avif', '/uploads/products/1770697931_698ab4cb4f438.avif', '/uploads/products/1770697931_698ab4cb4f596.jpg'],
            ],
        ];

        foreach ($products as $productData) {
            // Check if category exists
            if (!isset($categories[$productData['category_id']])) {
                $this->command->warn("Skipping product '{$productData['name']}' - category ID {$productData['category_id']} not found");
                continue;
            }
            Product::create($productData);
        }

        $this->command->info('All products seeded successfully! Added ' . count($products) . ' products.');
    }
}
