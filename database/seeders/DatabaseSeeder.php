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
        // Admin user
        User::create([
            'name'               => 'Admin',
            'email'              => 'libprince1999@email.com',
            'password'           => bcrypt('0795919537'),
            'is_admin'           => true,
            'email_verified_at'  => now(),
        ]);

        // Regular user
        User::create([
            'name'     => 'John Doe',
            'email'    => 'user@hisgrace.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        $categories = [
            ['name' => 'Dresses',    'slug' => 'dresses'],
            ['name' => 'Tops',       'slug' => 'tops'],
            ['name' => 'Bottoms',    'slug' => 'bottoms'],
            ['name' => 'Outerwear',  'slug' => 'outerwear'],
            ['name' => 'Accessories','slug' => 'accessories'],
            ['name' => 'Footwear',   'slug' => 'footwear'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $products = [
            ['name' => 'Floral Summer Dress',    'category' => 'Dresses',    'price' => 89.99,  'gender' => 'women', 'featured' => true,  'is_new' => true,  'stock' => 25],
            ['name' => 'Blue Abstract Dress',    'category' => 'Dresses',    'price' => 109.99, 'gender' => 'women', 'featured' => true,  'is_new' => false, 'stock' => 15],
            ['name' => 'Elegant Evening Gown',   'category' => 'Dresses',    'price' => 199.99, 'gender' => 'women', 'featured' => false, 'is_new' => true,  'stock' => 10],
            ['name' => 'Casual Linen Dress',     'category' => 'Dresses',    'price' => 69.99,  'gender' => 'women', 'featured' => false, 'is_new' => false, 'stock' => 30],
            ['name' => 'Classic White Shirt',    'category' => 'Tops',       'price' => 49.99,  'gender' => 'men',   'featured' => true,  'is_new' => false, 'stock' => 50],
            ['name' => 'Striped Polo Shirt',     'category' => 'Tops',       'price' => 59.99,  'gender' => 'men',   'featured' => false, 'is_new' => true,  'stock' => 40],
            ['name' => 'Silk Blouse',            'category' => 'Tops',       'price' => 79.99,  'gender' => 'women', 'featured' => true,  'is_new' => true,  'stock' => 20],
            ['name' => 'Crop Top',               'category' => 'Tops',       'price' => 34.99,  'gender' => 'women', 'featured' => false, 'is_new' => false, 'stock' => 35],
            ['name' => 'Slim Fit Chinos',        'category' => 'Bottoms',    'price' => 79.99,  'gender' => 'men',   'featured' => true,  'is_new' => false, 'stock' => 30],
            ['name' => 'High Waist Jeans',       'category' => 'Bottoms',    'price' => 89.99,  'gender' => 'women', 'featured' => false, 'is_new' => true,  'stock' => 25],
            ['name' => 'Pleated Midi Skirt',     'category' => 'Bottoms',    'price' => 64.99,  'gender' => 'women', 'featured' => true,  'is_new' => false, 'stock' => 20],
            ['name' => 'Tailored Blazer',        'category' => 'Outerwear',  'price' => 149.99, 'gender' => 'unisex','featured' => true,  'is_new' => true,  'stock' => 15],
            ['name' => 'Trench Coat',            'category' => 'Outerwear',  'price' => 229.99, 'gender' => 'women', 'featured' => false, 'is_new' => false, 'stock' => 10],
            ['name' => 'Leather Jacket',         'category' => 'Outerwear',  'price' => 299.99, 'gender' => 'men',   'featured' => true,  'is_new' => false, 'stock' => 8],
            ['name' => 'Gold Chain Necklace',    'category' => 'Accessories','price' => 39.99,  'gender' => 'unisex','featured' => false, 'is_new' => true,  'stock' => 50],
            ['name' => 'Leather Handbag',        'category' => 'Accessories','price' => 129.99, 'gender' => 'women', 'featured' => true,  'is_new' => true,  'stock' => 12],
            ['name' => 'Silk Scarf',             'category' => 'Accessories','price' => 44.99,  'gender' => 'women', 'featured' => false, 'is_new' => false, 'stock' => 30],
            ['name' => 'Strappy Heels',          'category' => 'Footwear',   'price' => 99.99,  'gender' => 'women', 'featured' => true,  'is_new' => true,  'stock' => 20],
            ['name' => 'Oxford Dress Shoes',     'category' => 'Footwear',   'price' => 139.99, 'gender' => 'men',   'featured' => false, 'is_new' => false, 'stock' => 15],
            ['name' => 'White Sneakers',         'category' => 'Footwear',   'price' => 79.99,  'gender' => 'unisex','featured' => true,  'is_new' => false, 'stock' => 40],
        ];

        foreach ($products as $p) {
            $cat = Category::where('name', $p['category'])->first();
            Product::create([
                'category_id' => $cat->id,
                'name'        => $p['name'],
                'slug'        => Str::slug($p['name']) . '-' . uniqid(),
                'description' => "Premium quality {$p['name']} crafted with the finest materials. Perfect for any occasion.",
                'price'       => $p['price'],
                'sale_price'  => rand(0, 1) ? round($p['price'] * 0.8, 2) : null,
                'stock'       => $p['stock'],
                'gender'      => $p['gender'],
                'featured'    => $p['featured'],
                'is_new'      => $p['is_new'],
                'image'       => null, // Upload via admin panel
            ]);
        }
    }
}
