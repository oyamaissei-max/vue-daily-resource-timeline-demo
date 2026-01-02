<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. テストユーザーを作成（ログイン用）
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // パスワードは 'password'
        ]);

        // 他にランダムなユーザーを9人作成
        User::factory(9)->create();

        // 2. リソースを作成（ResourceSeederを呼び出す）
        $this->call(ResourceSeeder::class);

        // 3. 予約データを作成（各リソースに対して適当に予約を入れる）
        // Factory側で user_id, resource_id をランダムに拾うロジックにしているので、
        // ここでは単純に件数分作成します。
        Reservation::factory(50)->create();
    }
}