<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentFavourite extends Model
{
    use HasFactory;

    protected $table = '_recruitment_favourites';

    protected $fillable = [
        'user_id',
        'recruitment_id',
    ];
}
