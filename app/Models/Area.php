<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory;
    protected $table = "areas";

    protected $fillable = [
        'area_name', 'code'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
