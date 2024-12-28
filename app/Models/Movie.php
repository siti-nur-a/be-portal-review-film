<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Movie extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'movies';
    protected $fillable = ['title', 'summary', 'poster', 'genre_id', 'year'];

    public function casts()
    {
        return $this->belongsToMany(Casts::class, 'cast_movies', 'movie_id', 'cast_id');
    }

    public function list_review()
    {
        return $this->hasMany(Review::class, 'movie_id');
    }

    public function genre()
    {
        return $this->belongsTo(Genres::class, 'genre_id');
    }
}
