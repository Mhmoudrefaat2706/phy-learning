<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'blog_category_id',
        'name',
        'slug',
        'image',
        'views',
        'content',
        'tags',
        'keywords',
        'meta_description'
    ];
    protected $casts = [
        'keywords' => 'array',
        'tags' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }


      public function blogCategory()

    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
}
