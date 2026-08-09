<?php

namespace App\Http\Controllers;

use App\Services\LeaderboardService;
use App\Jobs\SendPushNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private LeaderboardService $leaderboardService
    ) {}

    public function addScore(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer',
            'score' => 'required|numeric',
        ]);

        $userId = $request->input('user_id');
        $score = $request->input('score');

        $this->leaderboardService->incrementScore($userId, $score);

        SendPushNotificationJob::dispatch(
            $userId,
            "Ваш счет обновлен! Добавлено очков: {$score}"
        );

        return response()->json([
            'message' => 'Score updated successfully',
            'rank' => $this->leaderboardService->getUserRank($userId),
        ]);
    }

    public function getLeaderboard(): JsonResponse
    {
        $top = $this->leaderboardService->getTop(10);
        return response()->json(['leaderboard' => $top]);
    }
}
