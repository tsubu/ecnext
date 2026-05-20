<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Categories
        $electronics = Category::create(['name' => 'High-End Tech', 'slug' => 'tech', 'is_active' => true]);
        $interior = Category::create(['name' => 'Modern Interior', 'slug' => 'interior', 'is_active' => true]);

        // 2. Create Product 1: Headphones
        $headphone = Product::create([
            'name' => 'Aura Pro Wireless Headphones',
            'slug' => 'aura-pro-wireless',
            'description' => 'Experience cinematic sound with our flagship wireless headphones.',
            'short_description' => 'Premium Noise Cancelling Headphones',
            'is_active' => true,
        ]);
        $headphone->categories()->attach($electronics);

        ProductVariant::create([
            'product_id' => $headphone->id,
            'sku' => 'APW-BLK',
            'price' => 45000.00,
            'stock_quantity' => 10,
            'option1_name' => 'Color',
            'option1_value' => 'Midnight Black',
            'is_default' => true,
        ]);
        
        ProductVariant::create([
            'product_id' => $headphone->id,
            'sku' => 'APW-SLV',
            'price' => 45000.00,
            'stock_quantity' => 5,
            'option1_name' => 'Color',
            'option1_value' => 'Arctic Silver',
        ]);

        // 3. Create Product 2: Designer Chair
        $chair = Product::create([
            'name' => 'Zenith Lounge Chair',
            'slug' => 'zenith-lounge-chair',
            'description' => 'A masterpiece of comfort and minimalist design.',
            'short_description' => 'Ergonomic Designer Chair',
            'is_active' => true,
        ]);
        $chair->categories()->attach($interior);

        ProductVariant::create([
            'product_id' => $chair->id,
            'sku' => 'ZLC-OAK',
            'price' => 128000.00,
            'stock_quantity' => 3,
            'option1_name' => 'Material',
            'option1_value' => 'Oak Wood',
            'is_default' => true,
        ]);

        // 4. Create Product 3: Smart Speaker
        $speaker = Product::create([
            'name' => 'Orion Smart Hub',
            'slug' => 'orion-smart-hub',
            'description' => 'The heart of your smart home. Crystal clear audio and AI integration.',
            'short_description' => 'Premium Multi-room Speaker',
            'is_active' => true,
        ]);
        $speaker->categories()->attach($electronics);

        ProductVariant::create([
            'product_id' => $speaker->id,
            'sku' => 'OSH-GRY',
            'price' => 29800.00,
            'stock_quantity' => 20,
            'option1_name' => 'Color',
            'option1_value' => 'Space Gray',
            'is_default' => true,
        ]);
    }
}
