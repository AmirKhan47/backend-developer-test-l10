<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    const TYPE_LESSONS_WATCHED = 'lessons_watched';
    const TYPE_COMMENTS_WRITTEN = 'comments_written';
}
