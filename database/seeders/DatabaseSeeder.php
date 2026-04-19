<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clean up
        \App\Models\Product::truncate();
        \App\Models\Category::truncate();
        User::where('email', 'admin@example.com')->delete();

        User::factory()->create([
            'name' => 'Admin Fashion',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $aoThun = \App\Models\Category::create(['name' => 'Áo Thun', 'slug' => 'ao-thun']);
        $aoSoMi = \App\Models\Category::create(['name' => 'Áo Sơ Mi', 'slug' => 'ao-so-mi']);
        $hoodie = \App\Models\Category::create(['name' => 'Hoodie & Sweater', 'slug' => 'hoodie-sweater']);

        // Áo Thun
        \App\Models\Product::create([
            'category_id' => $aoThun->id,
            'name' => 'Áo Thun Cotton Basic - Black',
            'slug' => 'ao-thun-cotton-basic-black',
            'description' => 'Chất liệu cotton 100% co giãn 4 chiều, thiết kế tối giản dễ phối đồ.',
            'price' => 189000,
            'is_featured' => true,
            'stock' => 100,
            'min_height' => 160,
            'max_height' => 175,
            'min_weight' => 50,
            'max_weight' => 65,
            'gender' => 'Unisex',
            'material' => 'Cotton'
        ]);

        \App\Models\Product::create([
            'category_id' => $aoThun->id,
            'name' => 'Áo Thun In Graphic "Future"',
            'slug' => 'ao-thun-in-graphic-future',
            'description' => 'Áo thun phong cách streetwear với hình in sắc nét, bền màu.',
            'price' => 245000,
            'is_featured' => true,
            'stock' => 50,
            'min_height' => 165,
            'max_height' => 180,
            'min_weight' => 60,
            'max_weight' => 75,
            'gender' => 'Nam',
            'material' => 'Cotton'
        ]);

        // Áo Sơ Mi
        \App\Models\Product::create([
            'category_id' => $aoSoMi->id,
            'name' => 'Áo Sơ Mi Trắng Tay Dài Slimfit',
            'slug' => 'ao-so-mi-trang-tay-dai-slimfit',
            'description' => 'Sơ mi trắng thanh lịch, form dáng chuẩn, phù hợp đi làm và dự tiệc.',
            'price' => 350000,
            'is_featured' => true,
            'stock' => 30,
            'min_height' => 170,
            'max_height' => 185,
            'min_weight' => 65,
            'max_weight' => 80,
            'gender' => 'Nam',
            'material' => 'Kate'
        ]);

        // Hoodie
        \App\Models\Product::create([
            'category_id' => $hoodie->id,
            'name' => 'Hoodie Oversize "No Signal"',
            'slug' => 'hoodie-oversize-no-signal',
            'description' => 'Hoodie nỉ bông dày dặn, ấm áp, form oversize cá tính.',
            'price' => 450000,
            'is_featured' => true,
            'stock' => 20,
            'min_height' => 155,
            'max_height' => 170,
            'min_weight' => 45,
            'max_weight' => 60,
            'gender' => 'Nữ',
            'material' => 'Nỉ'
        ]);
    }
}
