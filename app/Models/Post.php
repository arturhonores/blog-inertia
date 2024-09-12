<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Post extends Model
{
    use HasFactory;
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'image_post_url',
        'image_card_url',
        'post_html',
        'summary',
        'publish_date',
        'author_id',
        'category_id',
        'user_id',
    ];

    //relación uno a muchos inversa
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación uno a muchos inversa con Author
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    // Relación uno a muchos inversa con Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //relación muchos a muchos
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
