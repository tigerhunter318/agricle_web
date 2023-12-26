<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review_template extends Model
{
    use HasFactory;
    protected $table = '_review_templates';

    protected $fillable = [
        'user_id',
        'content',
    ];
}
