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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            
            // 外部キー制約
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('予約者');
            $table->foreignId('resource_id')->constrained()->onDelete('cascade')->comment('対象リソース');
            
            // 日時
            $table->dateTime('start_at')->comment('開始日時');
            $table->dateTime('end_at')->comment('終了日時');
            
            // 制御フラグ
            $table->boolean('is_confirmed')->default(false)->comment('管理者確定フラグ');
            
            $table->timestamps();

            // インデックス（期間検索の高速化）
            $table->index(['start_at', 'end_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
