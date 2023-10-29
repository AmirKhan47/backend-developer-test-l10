<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $unlockedAchievements = $this->getUnlockedAchievements($user);
        $nextAvailableAchievements = $this->getNextAvailableAchievements($user);
        $lastBadge = $this->getLastBadge($user);
        $nextBadge = $this->getNextBadge($lastBadge);
        $remainingToUnlockNextBadge = $this->calculateRemainingToUnlockNextBadge($nextBadge, $unlockedAchievements);

        return response()->json([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $lastBadge ? $lastBadge->name : '',
            'next_badge' => $nextBadge ? $nextBadge->name : '',
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge,
        ]);
    }

    private function getUnlockedAchievements(User $user)
    {
        return $user->achievements()->pluck('name')->toArray();
    }

    private function getNextAvailableAchievements(User $user)
    {
        return Achievement::whereNotIn('id', $user->achievements->pluck('id'))->pluck('name')->toArray();
    }

    private function getLastBadge(User $user)
    {
        return $user->badges()->orderBy('badge_user.id', 'desc')->first() ?? Badge::orderBy('id', 'asc')->first();
    }

    private function getNextBadge($lastBadge)
    {
        return $lastBadge ? Badge::where('id', '>', $lastBadge->id)->orderBy('id', 'asc')->first() : null;
    }

    private function calculateRemainingToUnlockNextBadge($nextBadge, $unlockedAchievements)
    {
        return $nextBadge ? max(0, $nextBadge->required_achievements - count($unlockedAchievements)) : 0;
    }

}
