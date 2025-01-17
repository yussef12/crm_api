<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
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
        'role_id',
        'phone_number',
        'birth_date',
        'address',
        'company_id'
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
        'password' => 'hashed',
    ];

    public function scopeEmployees($query, $name = null, $sort = null, $company_id = null)
    {
        $query->where('role_id', 2);
        if ($sort) {
            $query->orderBy($sort);
        }
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        return $query;
    }

    public function scopeSuperAdmins($query, $name = null, $sort = null)
    {
        $query->where('role_id', 1);
        if ($sort) {
            $query->orderBy($sort);
        }
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        return $query;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invitations()
    {
        return $this->hasMany(JoinInvitation::class, 'user_id');
    }
}
