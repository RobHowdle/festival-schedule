<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages';

    protected $fillable = [
        'name',
        'location',
        'w3w_link',
        'capacity',
        'type',
    ];

    public $timestamps = true;
}