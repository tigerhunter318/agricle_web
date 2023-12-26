<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'family_name',
        'name',
        'nickname',
        'gender',
        'birthday',
        'avatar',
        'management_mode',
        'contact_address',
        'post_number',
        'prefectures',
        'city',
        'address',
        'agency_name',
        'agency_phone',
        'insurance',
        'other_insurance',
        'product_name',
        'appeal_point',
        'cell_phone',
        'emergency_phone',
        'emergency_relation',
        'job',
        'bio',
        'role',
        'email',
        'email_verified_at',
        'email_code',
        'password',
        'approved'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
    ];
}
