<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'size',
    ];

    public function employees()
    {
        return $this->hasMany(User::class);
    }

    public function invitations()
    {
        return $this->hasMany(JoinInvitation::class,'company_id');
    }
}
