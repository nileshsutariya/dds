<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Login extends Authenticatable
{
    // use HasFactory;

    use HasApiTokens, HasFactory;

    protected $table = 'logins';

    protected $primaryKey = 'id';

    protected $fillable = [
        'phone_no',
        'password',
        'name'
        // 'role'
    ];
    public function getAuthIdentifierName()
    {
        return 'phone_no';
    }

    public function getAuthIdentifier()
    {
        return $this->phone_no; 
    }
    protected $guard = 'admin';

    public function getFullNameAttribute()
    {
        return $this->name;
    }

}
