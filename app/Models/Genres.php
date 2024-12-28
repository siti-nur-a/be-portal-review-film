<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Genres extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'genres';
    protected $fillable = ['name'];

    public function listMovie()
    {
        return $this->hasMany(Movie::class, 'genre_id');
    }
}
