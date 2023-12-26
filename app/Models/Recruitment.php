<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Recruitment extends Model
{
    use HasFactory;
    protected $table = '_recruitments';

    protected $fillable = [
        'title',
        'description',
        'producer_id',
        'post_number',
        'prefectures',
        'city',
        'workplace',
        'reward_type',
        'reward_cost',
        'work_date_start',
        'work_date_end',
        'work_time_start',
        'work_time_end',
        'break_time',
        'lunch_mode',
        'pay_mode',
        'traffic_type',
        'traffic_cost',
        'worker_amount',
        'rain_mode',
        'clothes',
        'toilet',
        'park',
        'insurance',
        'notice',
        'image',
        'status',
        'postscript',
        'comment',
        'approved'
    ];
}
