<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;

class LeaderboardService
{
    private string $key = 'user_leaderboard';

    public function updateScore(int $userId, float $score): void
    {
        Redis::zadd($this->key, $score, $userId);
    }

    public function incrementScore(int $userId, float $amount): void
    {
        Redis::zincrby($this->key, $amount, $userId);
    }

    public function getTop(int $limit = 10): array
    {
        return Redis::zrevrange($this->key, 0, $limit - 1, 'WITHSCORES');
    }

    public function getUserRank(int $userId): ?int
    {
        $rank = Redis::zrevrank($this->key, $userId);
        return $rank !== null ? $rank + 1 : null;
    }

    public function addScore(Request $request)
    {
        $userId = $request->input('user_id');
        $points = $request->input('points');

        // Прибавляем $points к текущему счету пользователя в множестве 'leaderboard'
        Redis::zincrby('leaderboard', $points, $userId);

        return response()->json(['message' => 'Points added successfully']);
    }
}
