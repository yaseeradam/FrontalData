<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'biller',
        'description',
        'type',
        'product',
        'session_id',
        'request_key',
        'amount',
        'number',
        'status',
        'old_balance',
        'new_balance',
        'profits',
        'quantity',
        'token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function pins()
    {
        return $this->hasMany(Pin::class, 'session_id', 'session_id');
    }
}
