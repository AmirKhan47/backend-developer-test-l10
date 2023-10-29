<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(LessonUser::class)
            ->withPivot('watched');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
