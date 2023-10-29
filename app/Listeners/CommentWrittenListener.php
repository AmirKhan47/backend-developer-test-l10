<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Models\Achievement;
use App\Services\BadgeService;
use Illuminate\Support\Facades\Log;

class CommentWrittenListener
{
    /**
     * Create the CommentWrittenListener instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the CommentWritten event.
     *
     * @param CommentWritten $event
     * @return void
     */
    public function handle(CommentWritten $event): void
    {
        Log::info('CommentWrittenListener: Handling CommentWritten event');
        $comment = $event->comment;
        $user = $comment->user;

        $this->handleAchievements($user);
        (new BadgeService())->handleBadges($user);
    }

    /**
     * Handle user achievements based on written comments.
     *
     * @param $user
     * @return void
     */
    private function handleAchievements($user): void
    {
        $commentAchievements = Achievement::where('type', Achievement::TYPE_COMMENTS_WRITTEN)->get();

        $commentAchievements->each(function ($achievement) use ($user) {
            if (!$user->hasAchievement($achievement) && $user->commentCount() >= $achievement->required_count) {
                $user->unlockAchievement($achievement);
            }
        });
    }
}
