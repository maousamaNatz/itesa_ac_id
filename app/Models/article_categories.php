<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class article_categories extends Model
{
    use HasFactory;

    protected $table = 'article_categories';

    protected $fillable = [
        'article_id',
        'category_id'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function category()
    {
        return $this->belongsTo(categori::class);
    }

}
