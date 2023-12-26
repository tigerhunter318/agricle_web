<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = '_contact';

    protected $fillable = [
        'user_id',
        'type',
        'content',
        'name',
        'address',
    ];
}
