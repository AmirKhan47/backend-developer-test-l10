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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('required_achievements');
            $table->timestamps();
        });

        DB::table('badges')->insert([
            [
                'name' => 'Beginner',
                'description' => 'You have unlocked 0 achievements!',
                'required_achievements' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intermediate',
                'description' => 'You have unlocked 4 achievements!',
                'required_achievements' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Advanced',
                'description' => 'You have unlocked 8 achievements!',
                'required_achievements' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Master',
                'description' => 'You have unlocked 10 achievements!',
                'required_achievements' => 10,
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
        Schema::dropIfExists('badges');
    }
};
