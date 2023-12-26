<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;
    protected $table = '_applicants';

    protected $fillable = [
        'recruitment_id',
        'worker_id',
        'status',
        'worker_review',
        'worker_evaluation',
        'recruitment_review',
        'recruitment_evaluation',
        'apply_memo',
        'employ_memo',
    ];
}
