<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinInvitation extends Model
{
    use HasFactory;


    protected $table = 'join_invitations'; // Adjust if your table name is different
    protected $primaryKey = 'id'; // Adjust if your primary key column is different
    public $timestamps = true;
    protected $fillable = [
        'invited_email',
        'invited_name',
        'token',
        'user_id',
        'company_id',
        'app_url',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');

    }



}
