<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'phone',
        'is_admin',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function wage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->HasOne(Wage::class, 'user_id');
    }

    public function getNameAttribute($value)
    {

        if (!$value) {
            $value = $this->phone;
        }
        return $value;
    }


    public function isAdmin(): bool
    {
        return $this->is_admin == 1;
    }
}
