<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';
    protected $primaryKey = 'id';   
    protected $fillable = [
        'unit_name', 'unit_symbol', 'unit_type', 'status'
    ];

}
