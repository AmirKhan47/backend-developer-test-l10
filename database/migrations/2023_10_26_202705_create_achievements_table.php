<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('type');
            $table->integer('required_count')->default(0);
            $table->timestamps();
        });

        DB::table('achievements')->insert([
            [
                'name' => 'First Lesson Watched',
                'description' => 'You watched your first lesson!',
                'type' => 'lessons_watched',
                'required_count' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '5 Lessons Watched',
                'description' => 'You watched 5 lessons!',
                'type' => 'lessons_watched',
                'required_count' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '10 Lessons Watched',
                'description' => 'You watched 10 lessons!',
                'type' => 'lessons_watched',
                'required_count' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '25 Lessons Watched',
                'description' => 'You watched 25 lessons!',
                'type' => 'lessons_watched',
                'required_count' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '50 Lessons Watched',
                'description' => 'You watched 50 lessons!',
                'type' => 'lessons_watched',
                'required_count' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'First Comment Written',
                'description' => 'You wrote your first comment!',
                'type' => 'comments_written',
                'required_count' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '3 Comments Written',
                'description' => 'You wrote 3 comments!',
                'type' => 'comments_written',
                'required_count' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '5 Comments Written',
                'description' => 'You wrote 5 comments!',
                'type' => 'comments_written',
                'required_count' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '10 Comments Written',
                'description' => 'You wrote 10 comments!',
                'type' => 'comments_written',
                'required_count' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '20 Comments Written',
                'description' => 'You wrote 20 comments!',
                'type' => 'comments_written',
                'required_count' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
