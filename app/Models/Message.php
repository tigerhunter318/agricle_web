<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = '_messages';

    protected $fillable = ['sender_id', 'recruitment_id', 'receiver_id', 'owner_id', 'message_id', 'read', 'message'];
}
