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
                'key' => 'bot_order_success',
                'value' => '🎉 Cảm ơn bạn! Đơn hàng đã được ghi nhận mang mã số: #',
                'label' => 'Thông báo đặt hàng thành công',
                'group' => 'order'
            ],
            [
                'key' => 'bot_order_cancel',
                'value' => 'Đã hủy. Hãy nhấn nút để bắt đầu lại nhé!',
                'label' => 'Thông báo hủy đặt hàng',
                'group' => 'order'
            ],
            [
                'key' => 'bot_confirm_order_intro',
                'value' => 'Xác nhận đặt hàng:',
                'label' => 'Tiêu đề xác nhận đơn hàng',
                'group' => 'order'
            ],
            [
                'key' => 'bot_confirm_order_question',
                'value' => 'Bạn xác nhận đặt hàng chứ?',
                'label' => 'Câu hỏi xác nhận đặt hàng',
                'group' => 'order'
            ],
            [
                'key' => 'bot_ask_name',
                'value' => 'Vui lòng cho biết tên của bạn:',
                'label' => 'Câu hỏi Họ tên khách hàng',
                'group' => 'order'
            ],
            [
                'key' => 'bot_ask_phone',
                'value' => 'Vui lòng cho biết SĐT của bạn:',
                'label' => 'Câu hỏi Số điện thoại khách hàng',
                'group' => 'order'
            ],
            [
                'key' => 'bot_ask_address',
                'value' => 'Địa chỉ nhận hàng của bạn ở đâu?',
                'label' => 'Câu hỏi Địa chỉ nhận hàng',
                'group' => 'order'
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
            'bot_order_success', 'bot_order_cancel', 'bot_confirm_order_intro',
            'bot_confirm_order_question', 'bot_ask_name', 'bot_ask_phone', 'bot_ask_address'
        ];
        \Illuminate\Support\Facades\DB::table('bot_settings')->whereIn('key', $keys)->delete();
    }
};
