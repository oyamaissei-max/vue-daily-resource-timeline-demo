<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        // 1. ランダムな日付（今日〜1ヶ月後）の朝9時を基準にする
        // Carbonを使うと日時操作が楽です
        $date = Carbon::today()->addDays(rand(0, 30))->setHour(9)->setMinute(0)->setSecond(0);

        // 2. 開始時間を決定（9:00 〜 17:00 の間でランダムな時間枠）
        // 30分単位でずらす例: rand(0, 16) * 30分
        $startAt = $date->copy()->addMinutes(rand(0, 16) * 30);

        // 3. 終了時間を決定（開始から 1時間〜3時間後）
        $endAt = $startAt->copy()->addMinutes(rand(2, 6) * 30);

        return [
            // 既存のユーザーとリソースからランダムにIDを取得（なければ生成）
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'resource_id' => Resource::inRandomOrder()->first()?->id ?? Resource::factory(),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'is_confirmed' => fake()->boolean(20), // 20%の確率で確定済み
        ];
    }
}