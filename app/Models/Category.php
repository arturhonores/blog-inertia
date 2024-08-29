<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Category extends Model
{
    use HasFactory;

    //Relacion de uno a muchos con post
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
