<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $table = "transactions";

    protected $primaryKey = 'id';

    protected $fillable = [
        'client_id', 'date', 'unit', 'price'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
