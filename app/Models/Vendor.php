<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'categories',
        'description',
        'email',
        'phone',
        'social_links',
        'logo',
        'menu',
        'tags',
        'dietary_restrictions',
        'restrictions'
    ];

    protected $casts = [
        'categories' => 'array',
        'social_links' => 'array',
        'tags' => 'array',
        'dietary_restrictions' => 'array',
        'restrictions' => 'array',
    ];
}