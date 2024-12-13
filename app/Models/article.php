<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'author_id',
        'category_id',
        'status',
        'published_at'
    ];
    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

     /**
     * Relasi many-to-many dengan kategori.
     */
    public function categories()
    {
        return $this->belongsToMany(categori::class, 'article_categories', 'article_id', 'category_id')
                    ->withTimestamps();
    }

    /**
     * Relasi dengan kategori utama (primary category).
     */
    public function category()
    {
        return $this->belongsTo(categori::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
