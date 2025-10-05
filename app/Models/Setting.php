<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'about_us',
        'lang',
        'google_analeteces',
        'type_description',
        'keywordes',
        'meta_description',
        'url',
        'maintenance_mode',
        'facebook_pixel',
        'logo',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
    ];
}
