<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Services\BadgeService;
use Illuminate\Support\Facades\Log;

class LessonWatchedListener
{
    /**
     * Handle the LessonWatched event.
     *
     * @param LessonWatched $event
     * @return void
     */
    public function handle(LessonWatched $event): void
    {
        Log::info('LessonWatchedListener: Handling LessonWatched event');
        $user = $event->user;

        $lessonsWatchedCount = $user->lessons()->wherePivot('watched', true)->count();

        $this->handleAchievements($user);
        (new BadgeService())->handleBadges($user);
    }

    /**
     * Handle user achievements based on watched lessons.
     *
     * @param $user
     * @return void
     */
    private function handleAchievements($user): void
    {
        $lessonsWatchedCount = $user->lessons()->wherePivot('watched', true)->count();

        $userAchievements = $user->achievements->pluck('id')->toArray();

        $achievements = Achievement::whereNotIn('id', $userAchievements)
            ->where('required_count', '<=', $lessonsWatchedCount)
            ->where('type', Achievement::TYPE_LESSONS_WATCHED)
            ->get();

        $user->achievements()->syncWithoutDetaching($achievements->pluck('id')->toArray(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}