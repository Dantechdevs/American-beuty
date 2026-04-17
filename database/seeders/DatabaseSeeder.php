<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\PaymentGateway;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@americanbeauty.com',
            'phone'    => '+254700000000',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'is_active'=> true,
        ]);

        // Site Settings
        $settings = [
            'site_name'         => 'American Beauty',
            'site_tagline'      => 'Glow Naturally, Live Beautifully',
            'site_email'        => 'info@americanbeauty.com',
            'site_phone'        => '+254 700 000 000',
            'site_address'      => 'Nairobi, Kenya',
            'currency_symbol'   => 'KSh',
            'currency_code'     => 'KES',
            'shipping_fee'      => '200',
            'free_shipping_min' => '3000',
            'tax_rate'          => '16',
        ];
        foreach ($settings as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }

        // Categories
        $categories = [
            ['name' => 'Skincare',    'slug' => 'skincare',    'description' => 'Nourish and protect your skin'],
            ['name' => 'Moisturizers','slug' => 'moisturizers','description' => 'Deep hydration for all skin types', 'parent' => 'skincare'],
            ['name' => 'Serums',      'slug' => 'serums',      'description' => 'Targeted skin treatments', 'parent' => 'skincare'],
            ['name' => 'Cleansers',   'slug' => 'cleansers',   'description' => 'Gentle face washes & cleansers', 'parent' => 'skincare'],
            ['name' => 'Sunscreen',   'slug' => 'sunscreen',   'description' => 'Sun protection essentials', 'parent' => 'skincare'],
            ['name' => 'Makeup',      'slug' => 'makeup',      'description' => 'Express your beauty'],
            ['name' => 'Foundation',  'slug' => 'foundation',  'description' => 'Flawless coverage', 'parent' => 'makeup'],
            ['name' => 'Lipstick',    'slug' => 'lipstick',    'description' => 'Bold & beautiful lips', 'parent' => 'makeup'],
            ['name' => 'Eyeshadow',   'slug' => 'eyeshadow',   'description' => 'Stunning eye looks', 'parent' => 'makeup'],
            ['name' => 'Haircare',    'slug' => 'haircare',    'description' => 'Luscious locks essentials'],
            ['name' => 'Fragrance',   'slug' => 'fragrance',   'description' => 'Captivating scents'],
            ['name' => 'Body Care',   'slug' => 'body-care',   'description' => 'Head to toe pampering'],
            ['name' => 'Tools',       'slug' => 'tools',       'description' => 'Beauty tools & accessories'],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $parent_id = isset($cat['parent']) ? ($categoryIds[$cat['parent']] ?? null) : null;
            $c = Category::create([
                'name'      => $cat['name'],
                'slug'      => $cat['slug'],
                'description'=> $cat['description'] ?? null,
                'parent_id' => $parent_id,
                'is_active' => true,
            ]);
            $categoryIds[$cat['slug']] = $c->id;
        }

        // Brands
        $brands = ['CeraVe', 'The Ordinary', 'Neutrogena', 'La Roche-Posay', 'Fenty Beauty', 'Charlotte Tilbury', 'MAC', 'NARS'];
        $brandIds = [];
        foreach ($brands as $brand) {
            $b = Brand::create(['name' => $brand, 'slug' => Str::slug($brand), 'is_active' => true]);
            $brandIds[$brand] = $b->id;
        }

        // Sample Products
        $products = [
            [
                'name'              => 'Hydrating Facial Moisturizer SPF 30',
                'category_slug'     => 'moisturizers',
                'brand'             => 'CeraVe',
                'price'             => 2500,
                'sale_price'        => 1999,
                'stock_quantity'    => 50,
                'short_description' => 'Lightweight daily moisturizer with SPF 30 and hyaluronic acid.',
                'skin_type'         => 'All Skin Types',
                'is_featured'       => true,
                'is_new_arrival'    => true,
            ],
            [
                'name'              => 'Niacinamide 10% + Zinc 1% Serum',
                'category_slug'     => 'serums',
                'brand'             => 'The Ordinary',
                'price'             => 1800,
                'stock_quantity'    => 80,
                'short_description' => 'Reduces blemishes and congestion. Controls sebum.',
                'skin_type'         => 'Oily / Combination',
                'is_featured'       => true,
                'is_best_seller'    => true,
            ],
            [
                'name'              => 'Gentle Foaming Cleanser',
                'category_slug'     => 'cleansers',
                'brand'             => 'CeraVe',
                'price'             => 1500,
                'stock_quantity'    => 60,
                'short_description' => 'Non-stripping cleanser for normal to oily skin.',
                'skin_type'         => 'Normal to Oily',
                'is_best_seller'    => true,
            ],
            [
                'name'              => 'Invisible Fluid Sunscreen SPF 50+',
                'category_slug'     => 'sunscreen',
                'brand'             => 'La Roche-Posay',
                'price'             => 3200,
                'stock_quantity'    => 40,
                'short_description' => 'Ultra-light, invisible mineral sunscreen.',
                'skin_type'         => 'All Skin Types',
                'is_featured'       => true,
            ],
            [
                'name'              => 'Pro Filt\'r Soft Matte Foundation',
                'category_slug'     => 'foundation',
                'brand'             => 'Fenty Beauty',
                'price'             => 4500,
                'stock_quantity'    => 35,
                'short_description' => '40 shades. Longwear matte finish.',
                'is_featured'       => true,
                'is_new_arrival'    => true,
            ],
            [
                'name'              => 'Matte Revolution Lipstick',
                'category_slug'     => 'lipstick',
                'brand'             => 'Charlotte Tilbury',
                'price'             => 3800,
                'stock_quantity'    => 45,
                'short_description' => 'Iconic matte lipstick for a Hollywood finish.',
                'is_best_seller'    => true,
            ],
            [
                'name'              => 'Retinol Eye Cream',
                'category_slug'     => 'skincare',
                'brand'             => 'Neutrogena',
                'price'             => 2200,
                'sale_price'        => 1750,
                'stock_quantity'    => 30,
                'short_description' => 'Reduces fine lines & dark circles around eyes.',
                'skin_type'         => 'All Skin Types',
                'is_new_arrival'    => true,
            ],
            [
                'name'              => 'Vitamin C Brightening Serum',
                'category_slug'     => 'serums',
                'brand'             => 'The Ordinary',
                'price'             => 2800,
                'stock_quantity'    => 55,
                'short_description' => '20% Vitamin C for radiant, even-toned skin.',
                'is_featured'       => true,
                'is_best_seller'    => true,
            ],
        ];

        foreach ($products as $p) {
            Product::create([
                'name'              => $p['name'],
                'slug'              => Str::slug($p['name']),
                'category_id'       => $categoryIds[$p['category_slug']],
                'brand_id'          => $brandIds[$p['brand']] ?? null,
                'price'             => $p['price'],
                'sale_price'        => $p['sale_price'] ?? null,
                'stock_quantity'    => $p['stock_quantity'],
                'short_description' => $p['short_description'],
                'skin_type'         => $p['skin_type'] ?? null,
                'is_active'         => true,
                'is_featured'       => $p['is_featured'] ?? false,
                'is_new_arrival'    => $p['is_new_arrival'] ?? false,
                'is_best_seller'    => $p['is_best_seller'] ?? false,
                'sku'               => 'AB-' . strtoupper(Str::random(6)),
            ]);
        }

        // Payment Gateways
        $gateways = [
            ['name' => 'M-PESA',           'slug' => 'mpesa',   'is_active' => true,  'mode' => 'sandbox'],
            ['name' => 'Cash on Delivery', 'slug' => 'cod',     'is_active' => true,  'mode' => 'live'],
            ['name' => 'Stripe',           'slug' => 'stripe',  'is_active' => false, 'mode' => 'sandbox'],
            ['name' => 'PayPal',           'slug' => 'paypal',  'is_active' => false, 'mode' => 'sandbox'],
        ];
        foreach ($gateways as $gateway) {
            PaymentGateway::create($gateway);
        }

        // Sliders
        $sliders = [
            [
                'title'       => 'Glow Naturally',
                'subtitle'    => 'Discover our premium skincare collection crafted for your skin.',
                'button_text' => 'Shop Skincare',
                'button_link' => '/products?category=skincare',
                'image'       => 'slider1.jpg',
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Makeup That Moves You',
                'subtitle'    => 'Express yourself with our curated makeup collection.',
                'button_text' => 'Shop Makeup',
                'button_link' => '/products?category=makeup',
                'image'       => 'slider2.jpg',
                'sort_order'  => 2,
            ],
            [
                'title'       => 'New Arrivals Are Here',
                'subtitle'    => 'Be the first to try our latest beauty innovations.',
                'button_text' => 'See New Arrivals',
                'button_link' => '/products?filter=new',
                'image'       => 'slider3.jpg',
                'sort_order'  => 3,
            ],
        ];
        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
