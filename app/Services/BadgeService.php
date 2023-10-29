<?php

namespace App\Services;

use App\Models\Badge;

class BadgeService
{
    public function handleBadges($user): void
    {
        $userAchievements = $user->achievements()->count();

        $matchingBadges = Badge::where('required_achievements', '<=', $userAchievements)->get();

        $user->badges()->syncWithoutDetaching($matchingBadges->pluck('id')->toArray(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}