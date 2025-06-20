<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $table = 'artists';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'genre',
        'image_url',
        'social_links',
    ];
    protected $casts = [
        'social_links' => 'array',
    ];
    public $timestamps = true;
}