<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'temp_password',
        'company_id',
        'role',
        'invitation_token',
        'invited_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'invited_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'SuperAdmin';
    }

    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    public function isMember()
    {
        return $this->role === 'Member';
    }

    public function isSales()
    {
        return $this->role === 'Sales';
    }

    public function isManager()
    {
        return $this->role === 'Manager';
    }
}
