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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('min_height')->nullable();
            $table->integer('max_height')->nullable();
            $table->integer('min_weight')->nullable();
            $table->integer('max_weight')->nullable();
            $table->string('gender')->nullable(); // Nam, Nữ, Unisex
            $table->string('material')->nullable(); // Cotton, Kate, ...
            $table->string('size_guide')->nullable(); // URL or JSON
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['min_height', 'max_height', 'min_weight', 'max_weight', 'gender', 'material', 'size_guide']);
        });
    }
};
