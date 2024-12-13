<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categori extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = ['name', 'slug', 'description', 'image'];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_categories', 'category_id', 'article_id')
                    ->withTimestamps();
    }

    public function primaryArticles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
