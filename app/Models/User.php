<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\BadgeAssigned;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted(): void
    {
        parent::boot();

        // Give the user the Beginner badge when they register
        static::created(function ($user) {
            $user->badges()->attach(Badge::where('name', 'Beginner')->first(), ['created_at' => now(), 'updated_at' => now()]);
        });
    }

    /**
     * The comments that belong to the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class)
            ->using(LessonUser::class)
            ->withPivot('watched');
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    public function watch(Lesson $lesson): void
    {
        Log::info('watch method called');
        $this->lessons()->sync([$lesson->id => ['watched' => true, 'created_at' => now(), 'updated_at' => now()]], false);
        // Trigger the LessonWatched event
        event(new LessonWatched($this, $lesson));
    }

    public function writeCommentOnLesson(Lesson $lesson, $body): void
    {
        Log::info('wroteComment method called');
        $comment = new Comment([
            'body' => $body,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $comment->user()->associate($this);
        $comment->lesson()->associate($lesson);

        $comment->save();
        // Trigger the CommentWritten event
        event(new CommentWritten($comment, $this));
    }

    /**
     * The achievements that belong to the user.
     */
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class);
    }

    /**
     * The badges that belong to the user.
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class);
    }

    public function hasAchievement(Achievement $achievement): bool
    {
        return $this->achievements->contains($achievement);
    }

    public function commentCount(): int
    {
        return $this->comments->count(); // You'll need a comments relationship in your User model
    }

    public function unlockAchievement(Achievement $achievement): void
    {
        $this->achievements()->attach($achievement->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

