<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'service_id', 
        'plan_id', 
        'quantity', 
        'subcategory',
        'buying_price', 
        'customer_price', 
        'reseller_price', 
        'agent_price', 
        'validity'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}