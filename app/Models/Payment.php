<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    protected $table = "payment";

    protected $primaryKey = 'id';

    protected $fillable = [
        'client_id',
        'amount',
        'type',
        'note',
        'date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    
}
