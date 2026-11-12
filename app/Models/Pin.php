<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    use HasFactory;

    protected $fillable = ['pin_code', 'transaction_id', 'user_id', 'session_id', 'service'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'session_id', 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
