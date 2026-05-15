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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('cod')->after('status');
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->string('vnpay_txn_ref')->nullable()->after('payment_status');
            $table->decimal('total_amount', 15, 2)->default(0)->after('vnpay_txn_ref');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'vnpay_txn_ref', 'total_amount']);
        });
    }
};
