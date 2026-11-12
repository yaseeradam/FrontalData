<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $fillable = [
        'admin_id',
        'title',
        'message',
        'action_url',
        'status',
        'date',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}