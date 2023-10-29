<?php

namespace Database\Seeders;

use App\Models\Lesson;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()
            ->count(100)
            ->create();

        $lessons = Lesson::factory()
            ->count(100)
            ->create();

        // user 1 watches till 24 lessons
//        $lessons->take(24)->each(function (Lesson $lesson) use ($users) {
//            $users->first()->watched()->attach($lesson->id, ['watched' => true, 'created_at' => now(), 'updated_at' => now()]);
//        });

    }
}
