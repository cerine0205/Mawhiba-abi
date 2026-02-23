<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submissions';   
    
    protected $fillable = [
        'full_name',
        'university_id',
        'phone',
        'answers',
    ];

    protected $casts = [
        'answers' => 'array',
    ];
}
