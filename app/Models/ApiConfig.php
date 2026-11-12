<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 
        'api_token', 
        'provider_id', 
        'status', 
        'optional'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function apiProvider()
    {
        return $this->belongsTo(ApiProvider::class, 'provider_id');
    }
}