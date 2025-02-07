<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    protected $table = "clients";

    protected $fillable = [
        'name',
        'phone_no',
        'password',
        'address',
        'area',
        'status'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    
}
