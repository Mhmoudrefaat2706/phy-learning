<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'name',
        'slug',
        'image',
        'views',
        'content',
        'tags',
        'keywords',
        'meta_description',
    ];


    protected $casts = [
        'tags'     => 'array',
        'keywords' => 'array',
    ];

 public function category()
{
    return $this->belongsTo(BlogCategory::class,'blog_category_id');
}



      public function blogCategory()

    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
}
