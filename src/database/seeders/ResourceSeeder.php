<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        // 社有車
        $cars = [
            'Toyota Probox (札幌400あ1234)',
            'Toyota HiAce (札幌100い9999)',
            'Honda Fit (札幌500う5678)',
            'Nissan Note (札幌500え1122)',
        ];

        foreach ($cars as $car) {
            Resource::create(['name' => $car, 'kind' => 'vehicle']);
        }

        // 会議室
        $rooms = [
            'Conference Room A (Large)',
            'Conference Room B (Small)',
            'Meeting Booth 1',
            'Meeting Booth 2',
        ];

        foreach ($rooms as $room) {
            Resource::create(['name' => $room, 'kind' => 'room']);
        }
    }
}