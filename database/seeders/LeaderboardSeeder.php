<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\LeaderboardService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class LeaderboardSeeder extends Seeder
{
    public function run(LeaderboardService $leaderboard): void
    {
        Redis::del('user_leaderboard');

        $users = User::factory()->count(15)->create([
            'fcm_token' => fn() => 'fcm_' . Str::random(20),
        ]);

        foreach ($users as $user) {
            $randomScore = rand(50, 1000);
            $leaderboard->addScore($user->id, $randomScore);
        }
    }
}
