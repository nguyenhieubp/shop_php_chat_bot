<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            [
                'key' => 'bot_consultation_intro',
                'value' => 'Chào bạn! Để tôi tư vấn size chuẩn nhất, bạn vui lòng cho tôi biết giới tính của mình nhé:',
                'label' => 'Lời mời tư vấn chọn size',
                'group' => 'consultation'
            ],
            [
                'key' => 'bot_ask_height',
                'value' => 'Chiều cao của bạn là bao nhiêu cm? (VD: 170)',
                'label' => 'Câu hỏi Chiều cao',
                'group' => 'consultation'
            ],
            [
                'key' => 'bot_ask_weight',
                'value' => 'Cân nặng của bạn là bao nhiêu kg? (VD: 65)',
                'label' => 'Câu hỏi Cân nặng',
                'group' => 'consultation'
            ],
            [
                'key' => 'bot_analyzing',
                'value' => 'Đang phân tích thông số của bạn qua hệ thống... ⏳',
                'label' => 'Thông báo đang phân tích',
                'group' => 'consultation'
            ],
            [
                'key' => 'bot_no_fit_found',
                'value' => 'Rất tiếc, tôi chưa tìm thấy sản phẩm nào có size chuẩn xác tuyệt đối cho bạn. Tuy nhiên, shop còn nhiều mẫu Oversize, bạn có thể tham khảo nhé!',
                'label' => 'Thông báo không tìm thấy size chuẩn',
                'group' => 'consultation'
            ],
            [
                'key' => 'bot_fit_results_intro',
                'value' => 'Dựa trên các chỉ số của bạn, đây là những mẫu áo cực kỳ vừa vặn dành cho bạn:',
                'label' => 'Tiêu đề kết quả tư vấn',
                'group' => 'consultation'
            ],
            [
                'key' => 'bot_ask_track_phone',
                'value' => 'Vui lòng nhập số điện thoại bạn đã dùng để đặt hàng:',
                'label' => 'Câu hỏi SĐT tra cứu đơn',
                'group' => 'tracking'
            ],
            [
                'key' => 'bot_order_list_intro',
                'value' => 'Đây là các đơn hàng gần nhất của bạn:',
                'label' => 'Tiêu đề danh sách đơn hàng',
                'group' => 'tracking'
            ],
            [
                'key' => 'bot_order_not_found',
                'value' => 'Rất tiếc, tôi không tìm thấy đơn hàng nào với số điện thoại này. 😢',
                'label' => 'Lỗi không tìm thấy đơn hàng',
                'group' => 'tracking'
            ],
            [
                'key' => 'bot_feedback_intro',
                'value' => 'Chào bạn, chúng tôi luôn lắng nghe ý kiến từ khách hàng. Vui lòng nhập nội dung góp ý của bạn:',
                'label' => 'Lời mời gửi góp ý',
                'group' => 'feedback'
            ],
            [
                'key' => 'bot_feedback_thanks',
                'value' => 'Cảm ơn bạn đã đóng góp ý kiến! Fashion Hub sẽ ghi nhận và cải thiện dịch vụ tốt hơn. ❤️',
                'label' => 'Cảm ơn sau khi góp ý',
                'group' => 'feedback'
            ],
            [
                'key' => 'bot_back_menu_msg',
                'value' => 'Gõ "menu" để quay lại.',
                'label' => 'Gợi ý quay lại Menu',
                'group' => 'general'
            ],
        ];

        foreach ($settings as $setting) {
            \Illuminate\Support\Facades\DB::table('bot_settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            'bot_consultation_intro', 'bot_ask_height', 'bot_ask_weight', 'bot_analyzing',
            'bot_no_fit_found', 'bot_fit_results_intro', 'bot_ask_track_phone',
            'bot_order_list_intro', 'bot_order_not_found', 'bot_feedback_intro',
            'bot_feedback_thanks', 'bot_back_menu_msg'
        ];
        \Illuminate\Support\Facades\DB::table('bot_settings')->whereIn('key', $keys)->delete();
    }
};
