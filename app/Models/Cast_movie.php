<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Cast_movie extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'cast_movies';
    protected $fillable = ['name', 'cast_id', 'movie_id'];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    // Relasi ke model Cast
    public function cast()
    {
        return $this->belongsTo(Casts::class, 'cast_id');
    }
}
