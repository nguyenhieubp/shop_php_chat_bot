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
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $skinCare = \App\Models\Category::create(['name' => 'Chăm sóc da', 'slug' => 'cham-soc-da']);
        $makeUp = \App\Models\Category::create(['name' => 'Trang điểm', 'slug' => 'trang-diem']);
        $perfume = \App\Models\Category::create(['name' => 'Nước hoa', 'slug' => 'nuoc-hoa']);

        \App\Models\Product::create([
            'category_id' => $skinCare->id,
            'name' => 'Kem dưỡng ẩm chuyên sâu',
            'slug' => 'kem-duong-am-chuyen-sau',
            'description' => 'Kem dưỡng ẩm giúp làm mềm da và cung cấp độ ẩm suốt 24h.',
            'price' => 250000,
            'is_featured' => true,
            'stock' => 50
        ]);

        \App\Models\Product::create([
            'category_id' => $makeUp->id,
            'name' => 'Son môi Matte Red',
            'slug' => 'son-moi-matte-red',
            'description' => 'Son môi màu đỏ rực rỡ, lâu trôi và mềm mịn.',
            'price' => 180000,
            'is_featured' => true,
            'stock' => 100
        ]);

        \App\Models\Product::create([
            'category_id' => $perfume->id,
            'name' => 'Nước hoa Rose Dream',
            'slug' => 'nuoc-hoa-rose-dream',
            'description' => 'Hương hoa hồng dịu nhẹ, quyến rũ cho phái đẹp.',
            'price' => 550000,
            'is_featured' => true,
            'stock' => 20
        ]);
    }
}
