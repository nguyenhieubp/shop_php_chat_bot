<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa các bài viết cũ nếu cần, hoặc chỉ chèn thêm bài mới
        Post::where('slug', 'like', 'bai-viet-test-%')->delete();

        for ($i = 1; $i <= 10; $i++) {
            Post::create([
                'title' => "Bí quyết phối đồ cá tính số $i - Thời trang 2026",
                'slug' => "bai-viet-test-$i",
                'content' => "Đây là nội dung chi tiết bài viết số $i về phong cách thời trang, cách phối đồ thời thượng và xu hướng thời trang street-style dẫn đầu năm 2026. Hãy khám phá và tự tin thể hiện phong cách của riêng bạn.",
                'image' => null, // Sẽ tự động hiện placeholder icon
                'is_published' => true
            ]);
        }
    }
}
