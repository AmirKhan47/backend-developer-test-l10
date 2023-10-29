<?php

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Tests\TestCase;

class UserActionsTest extends TestCase
{

    /**
     * Test that the application returns a successful response.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
    }

    /**
     * Test that the user watches the first lesson and unlocks the first achievement.
     */
    public function testUserWatchesFirstLessonAndUnlocksFirstAchievement(): void
    {
        // Arrange
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        // Act
        $user->watch($lesson);
        $response = $this->get("/users/{$user->id}/achievements");

        // Assert
        $response->assertStatus(200);

        $firstLessonWatched = Achievement::where('type', Achievement::TYPE_LESSONS_WATCHED)
            ->where('required_count', 1)
            ->first()->name;

        $response->assertJsonFragment([
            'unlocked_achievements' => [
                $firstLessonWatched,
            ],
        ]);
    }

    /**
     * Test that the user watches 5 lessons and unlocks the "5 Lessons Watched" achievement.
     */
    public function test_user_watches_five_lessons_and_unlocks_five_lessons_watched_achievement(): void
    {
        // Arrange
        $user = User::factory()->create();
        $lessons = Lesson::factory(5)->create();

        // Act: Mark all 5 lessons as watched
        foreach ($lessons as $lesson) {
            $user->watch($lesson);
        }

        $response = $this->get("/users/{$user->id}/achievements");

        // Assert
        $response->assertStatus(200);

        $firstLessonWatched = Achievement::where('type', Achievement::TYPE_LESSONS_WATCHED)
            ->where('required_count', 1)
            ->first()->name;

        $fiveLessonsWatched = Achievement::where('type', Achievement::TYPE_LESSONS_WATCHED)
            ->where('required_count', 5)
            ->first()->name;

        $response->assertJsonFragment([
            'unlocked_achievements' => [
                $firstLessonWatched,
                $fiveLessonsWatched,
            ],
        ]);
    }

    /**
     * Test that the user watches 25 lessons and unlocks the "25 Lessons Watched" achievement and "Intermediate" badge.
     */
    public function test_user_watches_twenty_five_lessons_unlocks_achievement_and_intermediate_badge(): void
    {
        // Arrange
        $user = User::factory()->create();
        $lessons = Lesson::factory(25)->create();

        // Act: Mark all 25 lessons as watched
        foreach ($lessons as $lesson) {
            $user->watch($lesson);
        }

        $response = $this->get("/users/{$user->id}/achievements");

        // Assert
        $response->assertStatus(200);

        // Check achievements and badges by name
        $achievementsToCheck = [
            'First Lesson Watched',
            '5 Lessons Watched',
            '10 Lessons Watched',
            '25 Lessons Watched',
        ];

        $badgesToCheck = [
            'Intermediate',
            'Advanced',
        ];

        $response->assertJsonFragment([
            'unlocked_achievements' => $achievementsToCheck,
            'current_badge' => $badgesToCheck[0],
            'next_badge' => $badgesToCheck[1],
            'remaining_to_unlock_next_badge' => 4,
        ]);
    }

    /**
     * Test that the user writes the first comment and unlocks the "First Comment Written" achievement.
     */
    public function test_user_writes_first_comment_unlocks_first_comment_achievement(): void
    {
        // Arrange
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        // Act: The user writes a comment
        $user->writeCommentOnLesson($lesson, 'This is a comment');

        $response = $this->get("/users/{$user->id}/achievements");

        // Assert
        $response->assertStatus(200);

        // Check achievements by name
        $achievementsToCheck = ['First Comment Written'];

        $response->assertJsonFragment([
            'unlocked_achievements' => $achievementsToCheck,
        ]);
    }

    /**
     * Test that the user unlocks the "Master" badge after achieving 10 achievements.
     */
    public function test_user_unlocks_master_badge_after_achieving_10_achievements(): void
    {
        // Arrange
        $user = User::factory()->create();
        $lessons = Lesson::factory(50)->create();

        // Act
        // Mark all 50 lessons as watched
        foreach ($lessons as $lesson) {
            $user->watch($lesson);
        }

        // Write 20 comments (Achievements in this scenario are awarded for both lesson watching and commenting)
        foreach (range(1, 20) as $i) {
            $user->writeCommentOnLesson($lessons->random(), 'This is a comment');
        }

        $response = $this->get("/users/{$user->id}/achievements");

        // Assert
        $response->assertStatus(200);

        // Check the "Master" badge
        $response->assertJsonFragment([
            'current_badge' => 'Master',
            'next_badge' => '',
            'remaining_to_unlock_next_badge' => 0,
        ]);
    }

}
